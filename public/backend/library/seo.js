(function ($) {
    "use strict";
    var HT = {};

    HT.seoPreview = () => {
        // bắt sự kiện nhấn phím của thẻ input có name = "name"
        $("input[name=meta_title]").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            if (value == "") {
                $(".meta-title").html("Bạn chưa có tiêu đề SEO");
            } else {
                //  thiết lập nội dung HTML bằng giá trị của biến value.
                $(".meta-title").html(value);
            }
        });

        $(".seo_canonical").each(function () {
            let _this = $(this);
            _this.css({
                // outerWidth: lấy chiều rộng
                "padding-left": parseInt($(".baseUrl").outerWidth()) + 10,
            });
        });

        $("input[name=canonical").on("keyup", function () {
            let input = $(this);
            let value = HT.removeUtf8(input.val());
            if (value == "") {
                $(".canonical").html(BASE_URL + "duong-dan-cua-ban" + SUFFIX);
            } else {
                //  thiết lập nội dung HTML bằng giá trị của biến value.
                $(".canonical").html(BASE_URL + value + SUFFIX); // BASE_URL, SUFFIX: biến được khai báo mở thẻ script trong file header
            }
        });

        $("textarea[name=meta_description").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            if (value == "") {
                $(".meta-description").html("Bạn chưa có mô tả SEO");
            } else {
                //  thiết lập nội dung HTML bằng giá trị của biến value.
                $(".meta-description").html(value);
            }
        });
    };

    HT.removeUtf8 = (str) => {
        str = str.toLowerCase(); // chuyen ve ki tu biet thuong
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        str = str.replace(
            /!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|,|\.|\:|\;|\'|\–| |\"|\&|\#|\[|\]|\\|\/|~|$|_/g,
            "-"
        );
        str = str.replace(/-+-/g, "-");
        str = str.replace(/^\-+|\-+$/g, "");
        return str;
    };

    $(document).ready(function () {
        HT.seoPreview();
    });
})(jQuery);
