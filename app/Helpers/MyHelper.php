<?php

use App\Enums\PromotionEnum;
use Carbon\Carbon;

if (!function_exists('convert_price')) {
    function convert_price($price = '', $flag = false)
    {
        return ($flag === false) ? str_replace('.', '', $price) : number_format((float)$price, 0, ',', '.');
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

if (!function_exists('convertDateTime')) {
    function convertDateTime($date = '', $format = 'd/m/Y H:i')
    {
        // tạo một đối tượng ngày từ định dạng Y-m-d H:i:s.
        $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $date);
        // Trả về chuỗi ngày tháng đã chuyển đổi sang định dạng mong muốn.
        return $carbonDate->format($format);
    }
}

if (!function_exists('renderDiscountInformation')) {
    function renderDiscountInformation($promotion)
    {
        if ($promotion->method === PromotionEnum::PRODUCT_AND_QUANTITY) {
            $discountValue = $promotion->discountInformation['info']['discountValue'];
            $discountType = $promotion->discountInformation['info']['discountType'] == 'percent' ? '%' : __('form.cash');
            return '<span class="label label-primary">' . $discountValue . ' ' . $discountType . '</span>';
        }
        return '<div><a href="' . route('promotion.edit', $promotion->id) . '">' . __('table.view') . '</a></div>';
    }
}

if (!function_exists('frontend_recursive_menu')) {
    function frontend_recursive_menu($data = [], $parentId = 0, $count = 1, $type = 'html')
    {
        if (isset($data) && count($data)) {
            if ($type === 'html') {
                $html = '';
                foreach ($data as $key => $val) {
                    $name = $val['item']->languages->first()->pivot->name;
                    $canonical = write_url($val['item']->languages->first()->pivot->canonical, true, true);
                    $ulClass = $count >= 1 ? 'menu-level__' . ($count + 1) : '';
                    $html .= '<li class=' . ($count == 1 ? "children" : "") . '>';
                    $html .= "<a href='$canonical' title='$name'>$name</a>";
                    if (count($val['children'])) {
                        $html .= '<div class="dropdown-menu">';
                        $html .= "<ul class='uk-clearfix uk-list $ulClass menu-style'>";
                        $html .= frontend_recursive_menu($val['children'], $val['item']->parent_id, $count + 1, $type);
                        $html .= '</ul>';
                        $html .= '</div>';
                    }
                    $html .= '</li>';
                }
                return $html;
            }
        }
        return $data;
    }
}

if (!function_exists('write_url')) {
    function write_url($canonical = '', $fullDomain = true, $suffix = false)
    {
        $canonical = is_null($canonical) ? '' : $canonical;
        if (strpos($canonical, 'http') !== false) {
            return $canonical;
        }
        $fullUrl = ($fullDomain === true ? config('app.url') : '') . $canonical . ($suffix === true ? config('apps.general.suffix') : '');
        return $fullUrl;
    }
}

if (!function_exists('image')) {
    function image($image = '')
    {
        return $image;
    }
}

if (!function_exists('getPrice')) {
    function getPrice($product = null)
    {
        $result = [
            'price' => $product->price,
            'priceSale' => isset($product->promotions->discount) ? $product->price - $product->promotions->discount : 0,
            'percent' => (isset($product->promotions->discount) && $product->price > 0) ? round($product->promotions->discount * 100 / $product->price) : 0,
            'html' => ''
        ];
        $result['html'] .= '<div class="price uk-flex uk-flex-bottom">';
        $result['html'] .= '<div class="price-sale">' . (isset($product->promotions->discount) ? convert_price($result['priceSale'], true) : convert_price($result['price'], true)) . __('form.cash') . '</div>';
        if (isset($product->promotions->discount)) {
            $result['html'] .= '<div class="price-old">' . convert_price($result['price'], true) . __('form.cash') . '</div>';
        }
        $result['html'] .= '</div>';
        return $result;
    }
}


if (!function_exists('getReview')) {
    function getReview($product)
    {
        return [
            'star' => rand(1, 5),
            'count' => rand(1, 100)
        ];
    }
}

if (!function_exists('renderQuickBuy')) {
    function renderQuickBuy($product, $canonical = '', $name = '')
    {
        $class = 'btn-addCart';
        $openModal = '';
        if (isset($product->product_variants) && count($product->product_variants)) {
            $class = '';
            $canonical = '#popup';
            $openModal = 'data-uk-modal';
        }
        $html = '
            <a href="' . $canonical . '" ' . $openModal . ' title="' . $name . '" class="' . $class . '">
                <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path
                            d="M24.4941 3.36652H4.73614L4.69414 3.01552C4.60819 2.28593 4.25753 1.61325 3.70863 1.12499C3.15974 0.636739 2.45077 0.366858 1.71614 0.366516L0.494141 0.366516V2.36652H1.71614C1.96107 2.36655 2.19748 2.45647 2.38051 2.61923C2.56355 2.78199 2.68048 3.00626 2.70914 3.24952L4.29414 16.7175C4.38009 17.4471 4.73076 18.1198 5.27965 18.608C5.82855 19.0963 6.53751 19.3662 7.27214 19.3665H20.4941V17.3665H7.27214C7.02705 17.3665 6.79052 17.2764 6.60747 17.1134C6.42441 16.9505 6.30757 16.7259 6.27914 16.4825L6.14814 15.3665H22.3301L24.4941 3.36652ZM20.6581 13.3665H5.91314L4.97214 5.36652H22.1011L20.6581 13.3665Z"
                            fill="#253D4E"></path>
                        <path
                            d="M7.49414 24.3665C8.59871 24.3665 9.49414 23.4711 9.49414 22.3665C9.49414 21.2619 8.59871 20.3665 7.49414 20.3665C6.38957 20.3665 5.49414 21.2619 5.49414 22.3665C5.49414 23.4711 6.38957 24.3665 7.49414 24.3665Z"
                            fill="#253D4E"></path>
                        <path
                            d="M17.4941 24.3665C18.5987 24.3665 19.4941 23.4711 19.4941 22.3665C19.4941 21.2619 18.5987 20.3665 17.4941 20.3665C16.3896 20.3665 15.4941 21.2619 15.4941 22.3665C15.4941 23.4711 16.3896 24.3665 17.4941 24.3665Z"
                            fill="#253D4E"></path>
                    </g>
                    <defs>
                        <clipPath>
                            <rect width="24" height="24" fill="white"
                                transform="translate(0.494141 0.366516)"></rect>
                        </clipPath>
                    </defs>
                </svg>
            </a>
        ';
        return $html;
    }
}
