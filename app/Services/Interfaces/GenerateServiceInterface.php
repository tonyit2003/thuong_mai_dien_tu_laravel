<?php

namespace App\Services\Interfaces;

/**
 * Interface GenerateServiceInterface
 * @package App\Services\Interfaces
 */
interface GenerateServiceInterface
{
    public function paginate($request);
    public function create($request);
    public function update($id, $request);
    public function delete($id);
}
