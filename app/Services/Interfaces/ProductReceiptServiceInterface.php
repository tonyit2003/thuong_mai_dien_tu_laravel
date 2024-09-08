<?php

namespace App\Services\Interfaces;

/**
 * Interface ProductReceiptServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductReceiptServiceInterface
{
    public function paginate($request, $languageId);
    public function create($request, $languageId);
    public function update($id, $request, $languageId);
    public function delete($id);
    public function updateStatus($post = []);
    public function updateStatusAll($post = []);
}
