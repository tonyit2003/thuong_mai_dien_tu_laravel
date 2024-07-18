<?php

namespace App\Services\Interfaces;

/**
 * Interface PostCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface PostCatalogueServiceInterface
{
    public function paginate($request, $languageId);
    public function create($request, $languageId);
    public function update($id, $request, $languageId);
    public function delete($id);
    public function updateStatus($post = []);
    public function updateStatusAll($post = []);
}
