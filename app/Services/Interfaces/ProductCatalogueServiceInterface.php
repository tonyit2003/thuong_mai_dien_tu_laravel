<?php

namespace App\Services\Interfaces;

/**
 * Interface ProductCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductCatalogueServiceInterface
{
    public function paginate($request, $languageId);
    public function create($request, $languageId);
    public function update($id, $request, $languageId);
    public function delete($id, $languageId);
    public function updateStatus($post = []);
    public function updateStatusAll($post = []);
}
