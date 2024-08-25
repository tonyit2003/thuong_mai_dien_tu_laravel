<?php

namespace App\Services\Interfaces;

/**
 * Interface MenuCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface MenuCatalogueServiceInterface
{
    public function paginate($request);
    public function create($request);
    public function update($id, $request);
    public function updateStatus($post = []);
    public function updateStatusAll($post = []);
    public function delete($id);
}
