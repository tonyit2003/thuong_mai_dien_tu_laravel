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

    HT.popupSwiperSlide = () => {
        var swiper = new Swiper(".popup-gallery .swiper-container", {
            loop: true,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            thumbs: {
                swiper: {
                    el: ".swiper-container-thumbs",
                    slidesPerView: 4,
                    spaceBetween: 10,
                    slideToClickedSlide: true,
                },
            },
        });
    };

    $(document).ready(function () {
        HT.wow();
        HT.swiperCategory();
        HT.swiperBestSeller();
        HT.swiperFeaturedNews();
        HT.swiper();
        HT.niceSelect();
        HT.popupSwiperSlide();
    });
})(jQuery);

addCommas = (nStr) => {
    nStr = String(nStr);
    nStr = nStr.replace(/\./gi, "");
    let str = "";
    for (let i = nStr.length; i > 0; i -= 3) {
        let a = i - 3 < 0 ? 0 : i - 3;
        str = nStr.slice(a, i) + "." + str;
    }
    str = str.slice(0, str.length - 1);
    return str;
};
