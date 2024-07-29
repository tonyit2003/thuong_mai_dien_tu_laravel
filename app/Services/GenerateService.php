<?php

namespace App\Services;

use App\Repositories\GenerateRepository;
use App\Services\Interfaces\GenerateServiceInterface;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Class GenerateService
 * @package App\Services
 */
class GenerateService implements GenerateServiceInterface
{
    protected $generateRepository;

    public function __construct(GenerateRepository $generateRepository)
    {
        $this->generateRepository = $generateRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->generateRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'generate/index'], []);
    }

    public function create($request)
    {
        try {
            $this->makeDatabase($request);
            $this->makeController($request);
            $this->makeModel($request);
            $this->makeRepository($request);
            $this->makeService($request);
            $this->makeRequest($request);
            $this->makeView($request);
            if ($request->input('moduleType') == 'catalogue') {
                $this->makeRule($request);
            }
            $this->makeRoute($request);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send'); // lấy tất cả nhưng ngoại trừ... => trả về dạng mảng
            $this->generateRepository->update($id, $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->generateRepository->delete($id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function makeDatabase($request)
    {
        $payload = $request->only('schema', 'name', 'moduleType');
        $module = $this->convertModuleNameToTableName($payload['name']);
        $extractModule = explode('_', $module);
        $this->makeMainTable($module, $payload);
        if ($payload['moduleType'] !== 'other') {
            $this->makeLanguageTable($module);
            if (count($extractModule) == 1) {
                $this->makeRelationTable($module);
            }
        }
        // thực hiện file migration vừa tạo
        ARTISAN::call('migrate');
    }

    private function makeMainTable($module, $payload)
    {
        $tableName = $module . 's';
        $migrationFileName = date('Y_m_d_His') . '_create_' . $tableName . '_table.php';
        // database_path(): trả về đường dẫn tuyệt đối đến thư mục database
        $migrationPath = database_path('migrations\\' . $migrationFileName);
        $migrationTemplate = $this->createMigrationFile($payload['schema'], $tableName);
        // tạo file migration (2 tham số: đường dẫn và nội dung file)
        File::put($migrationPath, $migrationTemplate);
    }

    private function makeLanguageTable($module)
    {
        $pivotTableName = $module . '_language';
        $pivotSchema = $this->createPivotSchema($module);
        $migrationPivotTemplate = $this->createMigrationFile($pivotSchema, $pivotTableName);
        $migrationPivotFileName = date('Y_m_d_His', time() + 10) . '_create_' . $pivotTableName . '_table.php';
        $migrationPivotPath = database_path('migrations\\' . $migrationPivotFileName);
        File::put($migrationPivotPath, $migrationPivotTemplate);
    }

    private function createPivotSchema($module)
    {
        // \$ => dùng để laravel hiểu là 1 chuỗi
        return <<<SCHEMA
Schema::create('{$module}_language', function (Blueprint \$table) {
            \$table->unsignedBigInteger('{$module}_id');
            \$table->unsignedBigInteger('language_id');
            \$table->foreign('{$module}_id')->references('id')->on('{$module}s')->onDelete('cascade');
            \$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            \$table->string('name');
            \$table->text('description')->nullable();
            \$table->longText('content')->nullable();
            \$table->string('meta_title')->nullable();
            \$table->string('meta_keyword')->nullable();
            \$table->text('meta_description')->nullable();
            \$table->string('canonical')->nullable();
            \$table->timestamps();
        });
SCHEMA;
    }

    private function makeRelationTable($module)
    {
        $relationTableName = $module . '_catalogue_' . $module;
        $schema = $this->createRelationSchema($relationTableName, $module);
        $migrationRelationFile = $this->createMigrationFile($schema, $relationTableName);
        $migrationRelationFileName = date('Y_m_d_His', time() + 10) . '_create_' . $relationTableName . '_table.php';
        $migrationRelationPath = database_path('migrations\\' . $migrationRelationFileName);
        File::put($migrationRelationPath, $migrationRelationFile);
    }

    private function createRelationSchema($tableName, $module)
    {
        return <<<SCHEMA
Schema::create('{$tableName}', function (Blueprint \$table) {
            \$table->unsignedBigInteger('{$module}_catalogue_id');
            \$table->unsignedBigInteger('{$module}_id');
            \$table->foreign('{$module}_catalogue_id')->references('id')->on('{$module}_catalogues')->onDelete('cascade');
            \$table->foreign('{$module}_id')->references('id')->on('{$module}s')->onDelete('cascade');
        });
SCHEMA;
    }

    private function createMigrationFile($schema, $dropTable)
    {
        // cú pháp HEREDOC: cho phép khai báo một chuỗi nhiều dòng => định nghĩa một lớp ẩn danh mở rộng từ lớp Migration.
        return <<<MIGRATION
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        {$schema}
    }

    public function down(): void
    {
        Schema::dropIfExists('{$dropTable}');
    }
};

MIGRATION;
    }

    private function makeController($request)
    {
        $payload = $request->only('name', 'moduleType');
        switch ($payload['moduleType']) {
            case 'catalogue': {
                    $this->createTemplateController($payload['name'], 'PostCatalogueController');
                    break;
                }
            case 'detail': {
                    $this->createTemplateController($payload['name'], 'PostController');
                    break;
                }
            default: {
                    // $this->createSingleController();
                    break;
                }
        }
    }

    private function createTemplateController($name, $controllerFile)
    {
        $controllerName = $name . 'Controller.php';
        // base_path: trả về đường dẫn tuyệt đối đến thư mục gốc của dự án
        $templateControllerPath = base_path('app\\Templates\\controllers\\' . $controllerFile . '.txt');
        $module = $this->convertModuleNameToTableName($name);
        $extractModule = explode('_', $module);
        $controllerContent = file_get_contents($templateControllerPath);
        $replace = [
            '$class' => ucfirst(current($extractModule)), // Lấy giá trị của phần tử hiện tại mà con trỏ đang trỏ tới => [0]
            '$module' => lcfirst(current($extractModule)),
        ];
        $newContent = $this->replaceContent($controllerContent, $replace);
        $controllerPath = base_path('app\\Http\\Controllers\\Backend\\' . $controllerName);
        File::put($controllerPath, $newContent);
    }

    private function makeModel($request)
    {
        $moduleType = $request->input('moduleType');
        $name = $request->input('name');
        $modelName =  $name . '.php';
        switch ($moduleType) {
            case 'catalogue': {
                    $this->createCatalogueModel($request, $modelName);
                    break;
                }
            case 'detail': {
                    $this->createModel($request, $modelName);
                    break;
                }
            default: {
                    break;
                }
        }
    }

    private function createCatalogueModel($request, $modelName)
    {
        $templateModelPath = base_path('app\\Templates\\models\\PostCatalogue.txt');
        $modelContent = file_get_contents($templateModelPath);
        $module = $this->convertModuleNameToTableName($request->input('name'));
        $extractModule = explode('_', $module);
        $replace = [
            '$class' => ucfirst($extractModule[0]),
            '$module' => lcfirst($extractModule[0]),
        ];
        $newModelContent = $this->replaceContent($modelContent, $replace);
        $this->createModelFile($modelName, $newModelContent);
    }

    private function createModel($request, $modelName)
    {
        $templateModelPath = base_path('app\\Templates\\models\\Post.txt');
        $modelContent = file_get_contents($templateModelPath);
        $module = $this->convertModuleNameToTableName($request->input('name'));
        $replace = [
            '$class' => ucfirst($module),
            '$module' => lcfirst($module),
        ];
        $newModelContent = $this->replaceContent($modelContent, $replace);
        $this->createModelFile($modelName, $newModelContent);
    }

    private function createModelFile($modelName, $modelContent)
    {
        $modelPath = base_path('app\\Models\\' . $modelName);
        File::put($modelPath, $modelContent);
    }

    private function makeRepository($request)
    {
        $name = $request->input('name');
        $module = $this->convertModuleNameToTableName($name);
        $extractModule = explode('_', $module);
        $interfacePath = count($extractModule) == 1 ? base_path('app\\Templates\\repositories\\PostRepositoryInterface.txt') : base_path('app\\Templates\\repositories\\PostCatalogueRepositoryInterface.txt');
        $repositoryPath = count($extractModule) == 1 ? base_path('app\\Templates\\repositories\\PostRepository.txt') : base_path('app\\Templates\\repositories\\PostCatalogueRepository.txt');
        $path = [
            'interface' => $interfacePath,
            'repository' => $repositoryPath,
        ];
        $replace = [
            '$class' => ucfirst(current($extractModule)),
            '$module' => lcfirst(current($extractModule)),
        ];
        foreach ($path as $key => $val) {
            $content = file_get_contents($val);
            $newContent = $this->replaceContent($content, $replace);
            $contentPath = $key == 'interface' ? base_path('app\\Repositories\\Interfaces\\' . $name . 'RepositoryInterface.php') : base_path('app\\Repositories\\' . $name . 'Repository.php');
            if (!FILE::exists($contentPath)) {
                FILE::put($contentPath, $newContent);
            }
        }
    }

    private function makeService($request)
    {
        $name = $request->input('name');
        $module = $this->convertModuleNameToTableName($name);
        $extractModule = explode('_', $module);
        $interfacePath = count($extractModule) == 1 ? base_path('app\\Templates\\services\\PostServiceInterface.txt') : base_path('app\\Templates\\services\\PostCatalogueServiceInterface.txt');
        $servicePath = count($extractModule) == 1 ? base_path('app\\Templates\\services\\PostService.txt') : base_path('app\\Templates\\services\\PostCatalogueService.txt');
        $path = [
            'interface' => $interfacePath,
            'service' => $servicePath,
        ];
        $replace = [
            '$class' => ucfirst(current($extractModule)),
            '$module' => lcfirst(current($extractModule)),
        ];
        foreach ($path as $key => $val) {
            $content = file_get_contents($val);
            $newContent = $this->replaceContent($content, $replace);
            $contentPath = $key == 'interface' ? base_path('app\\Services\\Interfaces\\' . $name . 'ServiceInterface.php') : base_path('app\\Services\\' . $name . 'Service.php');
            if (!FILE::exists($contentPath)) {
                FILE::put($contentPath, $newContent);
            }
        }
    }

    private function replaceContent($content, $replace)
    {
        foreach ($replace as $key => $val) {
            $content = str_replace('{' . $key . '}', $val, $content);
        }
        return $content;
    }

    private function makeRequest($request)
    {
        $name = $request->input('name');
        $requestArray = ['Store' . $name . 'Request.php', 'Update' . $name . 'Request.php', 'Delete' . $name . 'Request.php'];
        $requestTemplate = ['RequestTemplateStore.txt', 'RequestTemplateUpdate.txt', 'RequestTemplateDelete.txt'];
        if ($request->input('moduleType') != 'catalogue') {
            // loại bỏ phần tử trong mảng $requestArray tại chỉ số 2
            unset($requestArray[2]);
            unset($requestTemplate[2]);
        }
        foreach ($requestTemplate as $key => $val) {
            $templateRequestPath = base_path('app\\Templates\\requests\\' . $val . '');
            $requestContent = file_get_contents($templateRequestPath);
            $requestContent = str_replace('{Module}', $name, $requestContent);
            $requestPath = base_path('app\\Http\\Requests\\' . $requestArray[$key]);
            FILE::put($requestPath, $requestContent);
        }
    }

    private function makeView($request)
    {
        $name = $request->input('name');
        $module = $this->convertModuleNameToTableName($name);
        $extractModule = explode('_', $module);
        // resource_path: trả về đường dẫn tuyệt đối đến thư mục resources
        $basePath = resource_path('views\\backend\\' . $extractModule[0]);
        $folderPath = count($extractModule) == 2 ? "$basePath\\$extractModule[1]" : "$basePath\\$extractModule[0]";
        $componentPath = "$folderPath\\component";

        $this->createDirectory($folderPath);
        $this->createDirectory($componentPath);

        $sourcePath = base_path('app\\Templates\\views\\' . (count($extractModule) == 2 ? 'catalogue' : 'post') . '\\');
        $viewPath = count($extractModule) == 2 ? "$extractModule[0].$extractModule[1]" : $extractModule[0];
        $replacement = [
            'view' => $viewPath,
            'message' => str_replace('.', '_', $viewPath),
            'module' => lcfirst($name),
            'Module' => $name,
        ];

        $fileArray = ['store.blade', 'index.blade', 'delete.blade'];
        $this->copyAndReplaceContent($fileArray, $sourcePath, $folderPath, $replacement);

        $componentFileArray = ['aside.blade', 'filter.blade', 'table.blade'];
        $this->copyAndReplaceContent($componentFileArray, $sourcePath . 'component\\', $componentPath, $replacement);
    }

    private function copyAndReplaceContent($fileArray, $sourcePath, $folderPath, $replacement)
    {
        foreach ($fileArray as $key => $val) {
            $templatePath = $sourcePath . $val . '.txt';
            $destination = "$folderPath\\$val.php";
            $content = file_get_contents($templatePath);
            foreach ($replacement as $keyReplace => $replace) {
                $content = str_replace('{' . $keyReplace . '}', $replace, $content);
            }
            if (!FILE::exists($destination)) {
                FILE::put($destination, $content);
            }
        }
    }

    private function createDirectory($path)
    {
        if (!FILE::exists($path)) {
            // tạo một thư mục mới, 0775: toàn quyền (đọc, ghi, thực thi)
            // true: cho phép tạo các thư mục cha chưa tồn tại
            FILE::makeDirectory($path, 0755, true);
        }
    }

    private function makeRule($request)
    {
        $name = $request->input('name');
        $destination = base_path('app\\Rules\\Check' . $name . 'ChildrenRule.php');
        $ruleTemplate = base_path('app\\Templates\\rules\\RuleTemplate.txt');
        $content = file_get_contents($ruleTemplate);
        $content = str_replace('{Module}', $name, $content);
        if (!FILE::exists($destination)) {
            FILE::put($destination, $content);
        }
    }

    private function makeRoute($request)
    {
        $name = $request->input('name');
        $module = $this->convertModuleNameToTableName($name);
        $extractModule = explode('_', $module);
        $routePath = base_path('routes\\web.php');
        $content = file_get_contents($routePath);
        $routeComment = strtoupper(str_replace('_', ' ', $module));
        $routeUrl = count($extractModule) == 2 ? "$extractModule[0]/$extractModule[1]" : $extractModule[0];
        $routeName = count($extractModule) == 2 ? "$extractModule[0].$extractModule[1]" : $extractModule[0];
        $routeGroup = $this->createRouteGroup($routeComment, $routeUrl, $name, $routeName);
        $useController = $this->createUseController($name);
        $content = str_replace('// @@new-module@@', $routeGroup, $content);
        $content = str_replace('// @@use-controller@@', $useController, $content);
        FILE::put($routePath, $content);
    }

    private function createRouteGroup($routeComment, $routeUrl, $name, $routeName)
    {
        return <<<ROUTE
// {$routeComment}
    Route::group(['prefix' => '{$routeUrl}'], function () {
        Route::get('index', [{$name}Controller::class, 'index'])->name('{$routeName}.index');
        Route::get('create', [{$name}Controller::class, 'create'])->name('{$routeName}.create');
        Route::post('store', [{$name}Controller::class, 'store'])->name('{$routeName}.store');
        Route::get('{id}/edit', [{$name}Controller::class, 'edit'])->name('{$routeName}.edit')->where(['id' => '[0-9]+']);
        Route::post('{id}/update', [{$name}Controller::class, 'update'])->name('{$routeName}.update')->where(['id' => '[0-9]+']);
        Route::get('{id}/delete', [{$name}Controller::class, 'delete'])->name('{$routeName}.delete')->where(['id' => '[0-9]+']);
        Route::post('{id}/destroy', [{$name}Controller::class, 'destroy'])->name('{$routeName}.destroy')->where(['id' => '[0-9]+']);
    });

    // @@new-module@@
ROUTE;
    }

    private function createUseController($name)
    {
        return <<<ROUTE
        use App\\Http\\Controllers\\Backend\\{$name}Controller;
        // @@use-controller@@
        ROUTE;
    }

    private function convertModuleNameToTableName($name)
    {
        // PostCatalogue => post_catalogues
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
    }

    private function paginateSelect()
    {
        return ['id', 'name', 'schema'];
    }
}
