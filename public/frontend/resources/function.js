(function ($) {
    "use strict";
    var HT = {};
    var timer;

    HT.swiper = () => {
        if ($(".panel-slide").length) {
            let setting = JSON.parse($(".panel-slide").attr("data-setting"));
            let option = HT.swiperOption(setting);
            var swiper = new Swiper(".panel-slide .swiper-container", option);
        }
    };

    HT.swiperOption = (setting) => {
        let option = {};
        if (setting.animation.length) {
            option.effect = setting.animation;
        }
        if (setting.arrow === "accept") {
            option.navigation = {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            };
        }
        if (setting.autoplay === "accept") {
            option.autoplay = {
                delay: 2000,
                disableOnInteraction: false,
            };
        }
        if (setting.navigate === "dots") {
            option.pagination = {
                el: ".swiper-pagination",
            };
        }
        return option;
    };

    HT.swiperCategory = () => {
        var swiper = new Swiper(".panel-category .swiper-container", {
            loop: false,
            pagination: {
                el: ".swiper-pagination",
            },
            spaceBetween: 20,
            slidesPerView: 3,
            breakpoints: {
                415: {
                    slidesPerView: 3,
                },
                500: {
                    slidesPerView: 3,
                },
                768: {
                    slidesPerView: 6,
                },
                1280: {
                    slidesPerView: 10,
                },
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    };

    // Bestseller Swiper
    HT.swiperBestSeller = () => {
        var swiper = new Swiper(".bestseller-container", {
            loop: false,
            pagination: {
                el: ".swiper-pagination",
            },
            spaceBetween: 20,
            slidesPerView: 2,
            breakpoints: {
                415: {
                    slidesPerView: 1,
                },
                500: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1280: {
                    slidesPerView: 4,
                },
            },
            navigation: {
                nextEl: ".bestseller-next",
                prevEl: ".bestseller-prev",
            },
        });
    };

    HT.swiperFeaturedNews = () => {
        var swiper = new Swiper(".deal-container", {
            loop: false,
            pagination: {
                el: ".swiper-pagination",
            },
            spaceBetween: 20,
            slidesPerView: 2,
            breakpoints: {
                415: {
                    slidesPerView: 1,
                },
                500: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1280: {
                    slidesPerView: 4,
                },
            },
            navigation: {
                nextEl: ".swiper-button-next-post",
                prevEl: ".swiper-button-prev-post",
            },
            on: {
                init: function () {
                    const swiper = this;
                    checkNavigationState(swiper);
                },
                slideChange: function () {
                    const swiper = this;
                    checkNavigationState(swiper);
                },
            },
        });

        function checkNavigationState(swiper) {
            const prevButton = document.querySelector(
                ".swiper-button-prev-post"
            );
            const nextButton = document.querySelector(
                ".swiper-button-next-post"
            );

            // Ẩn nút "prev" nếu ở trang đầu
            if (swiper.isBeginning) {
                prevButton.classList.add("disabled");
            } else {
                prevButton.classList.remove("disabled");
            }

            // Ẩn nút "next" nếu ở trang cuối
            if (swiper.isEnd) {
                nextButton.classList.add("disabled");
            } else {
                nextButton.classList.remove("disabled");
            }
        }
    };

    HT.wow = () => {
        var wow = new WOW({
            boxClass: "wow",
            animateClass: "animated",
            offset: 0,
            mobile: true,
            live: true,
            callback: function (box) {},
            scrollContainer: null,
            resetAnimation: true,
        });
        wow.init();
    };

    HT.niceSelect = () => {
        if ($(".nice-select").length) {
            $(".nice-select").niceSelect();
        }
    };

    HT.int = () => {
        if ($(".int").length) {
            // gán các sự kiện cho các trường nhập liệu có class .int.
            $(document).on("change keyup blur", ".int", function () {
                let _this = $(this);
                let value = _this.val();
                if (value === "") {
                    $(this).val("0");
                }
                // Loại bỏ tất cả các dấu chấm trong giá trị hiện tại
                value = value.replace(/\./gi, "");
                // Định dạng lại giá trị với dấu chấm để phân cách hàng nghìn
                _this.val(HT.addCommas(value));
                // Kiểm tra nếu giá trị không phải là số (isNaN), đặt lại giá trị là 0
                if (isNaN(value)) {
                    _this.val("0");
                }
                if (value < 0) {
                    _this.val(value * -1);
                }
            });

            $(document).on("keydown", ".int", function (e) {
                let _this = $(this);
                let data = _this.val();
                // Nếu giá trị hiện tại là 0, kiểm tra phím nhấn:
                if (data == 0) {
                    let unicode = e.keyCode || e.which;
                    // Nếu phím nhấn không phải là phím dấu chấm (keycode 190), đặt lại giá trị trường nhập liệu là rỗng
                    if (unicode != 190) {
                        _this.val("");
                    }
                }
            });
        }
    };

    // định dạng một chuỗi số với dấu chấm để phân cách hàng nghìn.
    HT.addCommas = (nStr) => {
        // Chuyển đổi giá trị đầu vào thành chuỗi
        nStr = String(nStr);
        // loại bỏ các dấu chấm hiện có
        nStr = nStr.replace(/\./gi, "");
        let str = "";
        // Duyệt qua chuỗi số từ phải sang trái, chèn dấu chấm sau mỗi ba chữ số
        for (let i = nStr.length; i > 0; i -= 3) {
            let a = i - 3 < 0 ? 0 : i - 3;
            // slice: trích xuất một phần của mảng or chuỗi
            str = nStr.slice(a, i) + "." + str;
        }
        // Loại bỏ dấu chấm cuối cùng và trả về chuỗi đã định dạng
        str = str.slice(0, str.length - 1);
        return str;
    };

    $(document).ready(function () {
        HT.wow();
        HT.swiperCategory();
        HT.swiperBestSeller();
        HT.swiperFeaturedNews();
        HT.swiper();
        HT.niceSelect();
        HT.int();
    });
})(jQuery);
