<?php

namespace App\Services\Interfaces;

/**
 * Interface AttributeCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface AttributeCatalogueServiceInterface
{
    public function paginate($request, $languageId);
    public function create($request, $languageId);
    public function update($id, $request, $languageId);
    public function delete($id, $languageId);
    public function updateStatus($post = []);
    public function updateStatusAll($post = []);
}
