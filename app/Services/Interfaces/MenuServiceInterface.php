<?php

namespace App\Services\Interfaces;

/**
 * Interface MenuServiceInterface
 * @package App\Services\Interfaces
 */
interface MenuServiceInterface
{
    public function getAndConvertMenu($menu = null, $languageId = 1);
    public function findMenuItemTranslate($menus, $currentLanguage, $languageId);
    public function save($request, $languageId);
    public function saveChildren($request, $languageId, $menu);
    public function saveTranslateMenu($request, $languageId);
    public function dragUpdate($json = [], $menuCatalogueId = 0, $languageId = 1, $parentId = 0);
    public function delete($id);
}
