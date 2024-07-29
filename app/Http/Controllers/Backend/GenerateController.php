<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenerateRequest;
use App\Http\Requests\UpdateGenerateRequest;
use App\Repositories\GenerateRepository;
use App\Services\GenerateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GenerateController extends Controller
{
    protected $generateService;
    protected $generateRepository;

    public function __construct(GenerateService $generateService, GenerateRepository $generateRepository)
    {
        $this->generateService = $generateService;
        $this->generateRepository = $generateRepository;
        parent::__construct();
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'generate.index');
        $generates = $this->generateService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Generate'
        ];
        $config['seo'] = __('generate');

        $template = 'backend.generate.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'generates'));
    }

    public function create()
    {
        Gate::authorize('modules', 'generate.create');
        $config = $this->configData();
        $config['seo'] = __('generate');
        $config['method'] = 'create';
        $template = 'backend.generate.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StoreGenerateRequest $storeGenerateRequest)
    {
        if ($this->generateService->create($storeGenerateRequest)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('generate.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('generate.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'generate.update');
        $config = $this->configData();
        $generate = $this->generateRepository->findById($id);
        $config['seo'] = __('generate');
        $config['method'] = 'edit';
        $template = 'backend.generate.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'generate'));
    }

    public function update($id, UpdateGenerateRequest $updateGenerateRequest)
    {
        if ($this->generateService->update($id, $updateGenerateRequest)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('generate.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('generate.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'generate.destroy');
        $generate = $this->generateRepository->findById($id);
        $config['seo'] = __('generate');
        $template = 'backend.generate.delete';
        return view('backend.dashboard.layout', compact('template', 'generate', 'config'));
    }

    public function destroy($id)
    {
        if ($this->generateService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('generate.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('generate.index');
    }

    private function configData()
    {
        return [
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ]
        ];
    }
}
