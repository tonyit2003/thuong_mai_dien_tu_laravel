<?php

use App\Enums\PromotionEnum;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use Carbon\Carbon;
use CurrencyApi\CurrencyApi\CurrencyApiClient;

if (!function_exists('convert_price')) {
    function convert_price($price = '', $flag = false)
    {
        if ($price == null) {
            return 0;
        }
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
    function formatCurrency($amount, $currency = 'VND')
    {
        $locale = app()->getLocale();
        $currency = determineCurrency($locale);

        // Nếu là VND, không cần quy đổi
        if ($currency == 'VND') {
            return number_format($amount, 0, ',', '.') . ' ₫';
        }

        // Gọi API để lấy tỷ giá quy đổi
        $exchangeRate = getExchangeRate($currency);

        // Nếu không lấy được tỷ giá, trả về số tiền gốc
        if ($exchangeRate === null) {
            return number_format($amount, 0, ',', '.') . ' ₫';
        }

        // Tính toán số tiền theo tỷ giá quy đổi
        $convertedAmount = $amount * $exchangeRate;

        // Định dạng theo ngôn ngữ
        switch ($locale) {
            case 'en':
                return currencySymbol($currency) . number_format($convertedAmount, 2, '.', ',');
            case 'cn':
                return currencySymbol($currency) . number_format($convertedAmount, 2, '.', ',');
            default: // Ngôn ngữ khác (ví dụ: tiếng Việt)
                return number_format($convertedAmount, 2, ',', '.') . ' ' . $currency;
        }
    }

    // Xác định loại tiền tệ dựa trên ngôn ngữ
    function determineCurrency($locale)
    {
        switch ($locale) {
            case "vn":
                return "VND";
            case "en":
                return "USD";
            case "cn":
                return "CNY";
            default:
                return "VND"; // Mặc định
        }
    }

    // Hàm gọi API để lấy tỷ giá
    function getExchangeRate($currency)
    {
        $cacheFile = 'exchange_rates.json';
        $cacheDuration = 3600; // Thời gian cache (1 giờ)
        // Kiểm tra xem cache có tồn tại và còn hiệu lực không
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheDuration)) {
            $data = json_decode(file_get_contents($cacheFile), true);
        } else {
            // Lấy dữ liệu mới từ API
            $apiKey = 'c4c172f9dcdc3d6a975c04c6'; // Thay bằng API Key của bạn
            $url = "https://v6.exchangerate-api.com/v6/$apiKey/latest/VND";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response === false) {
                return null; // Xử lý lỗi
            }

            $data = json_decode($response, true);

            // Cache phản hồi
            file_put_contents($cacheFile, json_encode($data));
        }

        return isset($data['conversion_rates'][$currency]) ? $data['conversion_rates'][$currency] : null;
    }

    // Hàm trả về ký hiệu tiền tệ tương ứng
    function currencySymbol($currency)
    {
        switch ($currency) {
            case 'USD':
                return '$';
            case 'EUR':
                return '€';
            case 'GBP':
                return '£';
            case 'CNY':
                return '¥';
            case 'VND':
                return '₫';
            default:
                return '';
        }
    }
}

if (!function_exists('convertDateTime')) {
    function convertDateTime($date = '', $format = 'd/m/Y H:i', $inputDateFormat = 'Y-m-d H:i:s')
    {
        // tạo một đối tượng ngày từ định dạng Y-m-d H:i:s.
        $carbonDate = Carbon::createFromFormat($inputDateFormat, $date);
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
        $result['html'] .= '<div class="price-sale">' . (isset($product->promotions->discount) ? formatCurrency($result['priceSale']) : formatCurrency($result['price'])) . '</div>';
        if (isset($product->promotions->discount)) {
            $result['html'] .= '<div class="price-old">' . formatCurrency($result['price']) . '</div>';
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

if (!function_exists('cut_string_and_decode')) {
    function cut_string_and_decode($str = null, $n = 200)
    {
        // Chuyển đổi các ký tự HTML entities thành các ký tự tương ứng (&lt => '>', ...)
        $str = html_entity_decode($str);
        // Loại bỏ toàn bộ các thẻ HTML khỏi chuỗi.
        $str = strip_tags($str);
        $str = cutnchar($str, $n);
        return $str;
    }
}

if (!function_exists('cutnchar')) {
    function cutnchar($str = null, $n = 200)
    {
        if (strlen($str) < $n) return $str;
        $html = substr($str, 0, $n);
        // Tìm vị trí khoảng trắng cuối cùng trong chuỗi => cắt => đảm bảo không cắt giữa chừng một từ.
        $html = substr($html, 0, strrpos($html, ' '));
        return $html . '...';
    }
}

if (!function_exists('seo')) {
    function seo($model = null, $page = 1)
    {
        $canonical = $page > 1 ? write_url($model->canonical, true, false) . '/page-' . $page . config('apps.general.suffix') : write_url($model->canonical, true, true);
        return [
            'meta_title' => isset($model->meta_title) ? $model->meta_title : $model->name,
            'meta_keyword' => isset($model->meta_keyword) ? $model->meta_keyword : '',
            'meta_description' => isset($model->meta_description) ? $model->meta_description : cut_string_and_decode($model->description, 168),
            'meta_image' => $model->image,
            'canonical' => $canonical,
        ];
    }
}

if (!function_exists('sortString')) {
    function sortString($string = '')
    {
        $extract = explode(',', $string);
        // Loại bỏ khoảng trắng ở đầu và cuối của mỗi phần tử trong mảng $extract.
        $extract = array_map('trim', $extract);
        // Sắp xếp các phần tử trong mảng $extract theo giá trị số học
        sort($extract, SORT_NUMERIC);
        return implode(',', $extract);
    }
}

if (!function_exists('sortAttributeId')) {
    function sortAttributeId($attributeId = [])
    {
        sort($attributeId, SORT_NUMERIC);
        $attributeId = implode(',', $attributeId);
        return $attributeId;
    }
}

if (!function_exists('getAddress')) {
    function getAddress($province_id, $district_id, $ward_id, $address)
    {
        $province = Province::where('code', $province_id)->value('full_name');
        $district = District::where('code', $district_id)->value('full_name');
        $ward = Ward::where('code', $ward_id)->value('full_name');
        $result = (isset($address) ? $address . ', ' : '') . (isset($ward) ? $ward . ', ' : '') . (isset($district) ? $district . ', ' : '') . (isset($province) ? $province . ', ' : '');
        return rtrim($result, ', ');
    }
}

if (!function_exists('vnPayConfig')) {
    function vnPayConfig()
    {
        return [
            'vnp_Url' => "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html",
            'vnp_Returnurl' => write_url('return/vnpay', true, true),
            'vnp_TmnCode' => "X0YGLFY1",
            'vnp_HashSecret' => "KJ0QJ6S3ODXYGT6C0HWZ0PBQKG59228Z",
            'vnp_apiUrl' => "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html",
            'apiUrl' => "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction"
        ];
    }
}

if (!function_exists('moMoConfig')) {
    function moMoConfig()
    {
        return [
            "partnerCode" => "MOMOBKUN20180529",
            "accessKey" => "klm05TvNBzhg7h7j",
            "secretKey" => "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa",
            "returnUrl" => write_url('return/momo', true, true),
            "notifyurl" => write_url('return/momo_ipn', true, true),
            "secretKey" => 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa',
        ];
    }
}

if (!function_exists('execPostRequest')) {
    function execPostRequest($url, $data)
    {
        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\n" .
                    "Content-Length: " . strlen($data) . "\r\n",
                'method' => 'POST',
                'content' => $data,
                'timeout' => 5,
            ],
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            return false;
        }

        return $result;
    }
}

if (!function_exists('convertVNDToUSD')) {
    function convertVNDToUSD($amountInVND)
    {
        $apiKey = 'cur_live_9BzWyCeHf1VAFWB4L9on1XSFipRrcS1sCxVoylcN';
        $currencyapi = new CurrencyApiClient($apiKey);

        $response = $currencyapi->latest([
            'base_currency' => 'VND',
            'currencies' => 'USD',
        ]);

        if (isset($response['data']['USD']['value'])) {
            $exchangeRate = $response['data']['USD']['value'];
            return number_format($amountInVND * $exchangeRate, 2, '.', '');
        }

        return 0;
    }
}
