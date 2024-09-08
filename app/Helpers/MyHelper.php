<?php

if (!function_exists('convert_price')) {
    function convert_price(string $price = '')
    {
        return str_replace('.', '', $price);
    }
}

if (!function_exists('convert_array')) {
    function convert_array($system = null, $keyword = '', $value = '')
    {
        $temp = [];
        if (is_array($system)) {
            foreach ($system as $key => $val) {
                $temp[$val[$keyword]] = $val[$value];
            }
        }
        if (is_object($system)) {
            foreach ($system as $key => $val) {
                $temp[$val->{$keyword}] = $val->{$value};
            }
        }
        return $temp;
    }
}

if (!function_exists('renderSystemInput')) {
    function renderSystemInput(string $name = '', $systems = null)
    {
        return '<input type="text" name="config[' . $name . ']" value="' . old($name, $systems[$name] ?? "") . '" class="form-control" placeholder="" autocomplete="off">';
    }
}

if (!function_exists('renderSystemImages')) {
    function renderSystemImages(string $name = '', $systems = null)
    {
        return '<div class="row">
            <div class="col-lg-6">
                <span class="image img-cover img-target img-avatar">
                    <img src="' . old($name, $systems[$name] ?? "backend/img/no-photo.png") . '"
                        alt="">
                </span>
                <input type="hidden" name="config[' . $name . ']"
                    value="' . old($name, $systems[$name] ?? "backend/img/no-photo.png") . '"
                    class="form-control upload-image" data-upload="Images">
            </div>
        </div>';
    }
}

if (!function_exists('renderSystemTextarea')) {
    function renderSystemTextarea(string $name = '', $systems = null)
    {
        return '<textarea name="config[' . $name . ']" class="form-control system-textarea">' . old($name, $systems[$name] ?? "") . '</textarea>';
    }
}

if (!function_exists('renderSystemEditor')) {
    function renderSystemEditor(string $name = '', $systems = null)
    {
        return '<textarea name="config[' . $name . ']" class="form-control system-textarea ck-editor" id="' . $name . '">' . old($name, $systems[$name] ?? "") . '</textarea>';
    }
}

if (!function_exists('renderSystemLink')) {
    function renderSystemLink(array $item = [])
    {
        return isset($item['link']) ? '<a target="' . $item['link']['target'] . '" class="system-link" href="' . $item['link']['href'] . '">' . $item['link']['text'] . '</a>' : '';
    }
}

if (!function_exists('renderSystemTitle')) {
    function renderSystemTitle(array $item = [])
    {
        return isset($item['title']) ? '<span class="system-title">' . $item['title'] . '</span>' : '';
    }
}

if (!function_exists('renderSystemSelect')) {
    function renderSystemSelect(array $item = [], string $name = '', $systems = null)
    {
        $options = '';
        foreach ($item['option'] as $key => $val) {
            $options .= '<option ' . (isset($systems[$name]) && $key == $systems[$name] ? "selected" : "") . ' value="' . $key . '">' . $val . '</option>';
        }
        return '
            <select name="config[' . $name . ']" class="form-control setupSelect2">
                ' . $options . '
            </select>
        ';
    }
}

if (!function_exists('recursive')) {
    function recursive($data = [], $parentId = 0)
    {
        $temp = [];
        if (!is_null($data) && count($data)) {
            foreach ($data as $key => $val) {
                if ($val->parent_id == $parentId) {
                    $temp[] = [
                        'item' => $val,
                        'children' => recursive($data, $val->id)
                    ];
                }
            }
        }
        return $temp;
    }
}

if (!function_exists('recursive_menu')) {
    function recursive_menu($data = [])
    {
        $html = "";
        if (count($data)) {
            foreach ($data as $key => $val) {
                $itemId = $val['item']->id;
                $itemName = $val['item']->languages->first()->pivot->name;
                $itemUrl = route('menu.children', $itemId);
                $title = __('form.submenu_management');

                $html .= "<li class='dd-item' data-id='$itemId'>";
                $html .= "<div class='dd-handle'>";
                $html .= "<span class='label label-info'><i class='fa fa-arrows'></i></span> $itemName";
                $html .= "</div>";
                $html .= "<a class='create-children-menu' href='$itemUrl'> $title </a>";
                if (count($val['children'])) {
                    $html .= "<ol class='dd-list'>";
                    $html .= recursive_menu($val['children']);
                    $html .= "</ol>";
                }
                $html .= "</li>";
            }
        }
        return $html;
    }
}

if (!function_exists('buildMenu')) {
    function buildMenu($menus = null, $parent_id = 0, $prefix = '')
    {
        $output = [];
        $count = 1;
        if (count($menus)) {
            foreach ($menus as $key => $val) {
                if ($val->parent_id == $parent_id) {
                    $val->position = $prefix . $count;
                    $output[] = $val;
                    $output = array_merge($output, buildMenu($menus, $val->id, $val->position . '.'));
                    $count++;
                }
            }
        }
        return $output;
    }
}

if (!function_exists('loadClass')) {
    function loadClass($model = '', $folder = 'Repositories', $interface = 'Repository')
    {
        $repositoryNamespace = '\App\\' . $folder . '\\' . ucfirst($model) . $interface;
        if (class_exists($repositoryNamespace)) {
            $repositoryInstance = app($repositoryNamespace);
            return $repositoryInstance;
        }
        return null;
    }
}

if (!function_exists('convertArrayByKey')) {
    function convertArrayByKey($object = null, $fields = [])
    {
        $temp = [];
        foreach ($object as $item) {
            foreach ($fields as $field) {
                if (is_array($object)) {
                    $temp[$field][] = $item[$field];
                } else {
                    $extract = explode('.', $field);
                    if (count($extract) == 2) {
                        $temp[$extract[0]][] = $item->{$extract[1]}->first()->pivot->{$extract[0]};
                    } else {
                        $temp[$field][] = $item->{$field};
                    }
                }
            }
        }
        return $temp;
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        return number_format($amount, 0, ',', '.') . ' VND';
    }
}
