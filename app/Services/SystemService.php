<?php

namespace App\Services;

use App\Repositories\SystemRepository;
use App\Services\Interfaces\SystemServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class SystemService
 * @package App\Services
 */
class SystemService implements SystemServiceInterface
{
    protected $systemRepository;

    public function __construct(SystemRepository $systemRepository)
    {
        $this->systemRepository = $systemRepository;
    }

    public function save($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $config = $request->input('config');
            $payload = [];
            if (count($config)) {
                foreach ($config as $key => $val) {
                    $payload = [
                        'keyword' => $key,
                        'content' => $val,
                        'language_id' => $languageId,
                        'user_id' => Auth::id()
                    ];
                    $condition = ['keyword' => $key, 'language_id' => $languageId];
                    $this->systemRepository->updateOrInsert($payload, $condition);
                }
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
