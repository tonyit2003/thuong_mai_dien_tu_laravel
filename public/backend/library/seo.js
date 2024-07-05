(function ($) {
    "use strict";
    var HT = {};

    HT.seoPreview = () => {
        // bắt sự kiện nhấn phím của thẻ input có name = "name"
        $("input[name=meta_title]").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            //  thiết lập nội dung HTML bằng giá trị của biến value.
            $(".meta-title").html(value);
        });

        $("input[name=canonical").css({
            // outerWidth: lấy chiều rộng
            "padding-left": parseInt($(".baseUrl").outerWidth()) + 10,
        });

        $("input[name=canonical").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            //  thiết lập nội dung HTML bằng giá trị của biến value.
            $(".canonical").html(BASE_URL + value + SUFFIX); // BASE_URL, SUFFIX: biến được khai báo mở thẻ script trong file header
        });

        $("textarea[name=meta_description").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            //  thiết lập nội dung HTML bằng giá trị của biến value.
            $(".meta-description").html(value);
        });
    };

    $(document).ready(function () {
        HT.seoPreview();
    });
})(jQuery);
