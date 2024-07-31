<?php

namespace App\Services\Interfaces;

/**
 * Interface ProductServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductServiceInterface
{
    public function paginate($request, $languageId);
    public function create($request, $languageId);
    public function update($id, $request, $languageId);
    public function delete($id);
    public function updateStatus($post = []);
    public function updateStatusAll($post = []);
}