(function ($) {
    "use strict";
    var HT = {};

    HT.seoPreview = () => {
        // bắt sự kiện nhấn phím của thẻ input có name = "name"
        $("input[name=meta_title]").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            if (value == "") {
                $(".meta-title").html(doNotSeoTitle);
            } else {
                //  thiết lập nội dung HTML bằng giá trị của biến value.
                $(".meta-title").html(value);
            }
        });

        $("input[name=translate_meta_title]").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            if (value == "") {
                $(".translate-meta-title").html(doNotSeoTitle);
            } else {
                //  thiết lập nội dung HTML bằng giá trị của biến value.
                $(".translate-meta-title").html(value);
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
                $(".canonical").html(BASE_URL + yourPathUrl + SUFFIX);
            } else {
                //  thiết lập nội dung HTML bằng giá trị của biến value.
                $(".canonical").html(BASE_URL + value + SUFFIX); // BASE_URL, SUFFIX: biến được khai báo mở thẻ script trong file header
            }
        });

        $("input[name=translate_canonical").on("keyup", function () {
            let input = $(this);
            let value = HT.removeUtf8(input.val());
            if (value == "") {
                $(".translate-canonical").html(BASE_URL + yourPathUrl + SUFFIX);
            } else {
                //  thiết lập nội dung HTML bằng giá trị của biến value.
                $(".translate-canonical").html(BASE_URL + value + SUFFIX); // BASE_URL, SUFFIX: biến được khai báo mở thẻ script trong file header
            }
        });

        $("textarea[name=meta_description").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            if (value == "") {
                $(".meta-description").html(doNotSeoDescription);
            } else {
                //  thiết lập nội dung HTML bằng giá trị của biến value.
                $(".meta-description").html(value);
            }
        });

        $("textarea[name=translate_meta_description").on("keyup", function () {
            let input = $(this);
            let value = input.val();
            if (value == "") {
                $(".translate-meta-description").html(doNotSeoDescription);
            } else {
                //  thiết lập nội dung HTML bằng giá trị của biến value.
                $(".translate-meta-description").html(value);
            }
        });

        $(".count-keyword").on("keyup", function () {
            let input = $(this);
            HT.countKeyword(input);
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

    HT.countKeywordInput = () => {
        if ($(".count-keyword").length) {
            $(".count-keyword").each(function () {
                let input = $(this);
                HT.countKeyword(input);
            });
        }
    };

    HT.countKeyword = (input) => {
        let value = input.val();
        let length = value.length;
        input
            .prev()
            .find("span[class^=count_meta]")
            .text(length + " " + unitCharacter);
    };

    $(document).ready(function () {
        HT.seoPreview();
        HT.countKeywordInput();
    });
})(jQuery);
