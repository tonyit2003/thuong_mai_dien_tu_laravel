<?php

namespace App\Services\Interfaces;

/**
 * Interface MenuServiceInterface
 * @package App\Services\Interfaces
 */
interface MenuServiceInterface
{
    public function paginate($request, $languageId);
    public function create($request, $languageId);
    public function saveChildren($request, $languageId, $menu);
    public function update($id, $request, $languageId);
    public function delete($id, $languageId);
}
