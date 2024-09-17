<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Repositories\SourceRepository;
use Exception;

class SourceController extends Controller
{
    protected $sourceRepository;

    public function __construct(SourceRepository $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
    }

    public function getAllSource()
    {
        try {
            $sources = $this->sourceRepository->all();
            return response()->json([
                'data' => $sources,
                'error' => false
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'messages' => $e->getMessage()
            ]);
        }
    }
}
