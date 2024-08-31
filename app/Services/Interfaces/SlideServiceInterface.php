<?php

namespace App\Services\Interfaces;

/**
 * Interface SlideServiceInterface
 * @package App\Services\Interfaces
 */
interface SlideServiceInterface
{
    public function paginate($request);
    public function create($request, $languageId);
    public function update($id, $request, $languageId);
    public function delete($id, $languageId);
    public function updateStatus($post = []);
    public function updateStatusAll($post = []);
}
