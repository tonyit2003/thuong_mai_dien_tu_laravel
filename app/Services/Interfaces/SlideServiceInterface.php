<?php

namespace App\Services\Interfaces;

/**
 * Interface SlideServiceInterface
 * @package App\Services\Interfaces
 */
interface SlideServiceInterface
{
    public function paginate($request);
    public function create($request);
    public function update($id, $request);
    public function delete($id);
    public function updateStatus($post = []);
    public function updateStatusAll($post = []);
}
