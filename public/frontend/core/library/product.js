(function ($) {
    "use strict";
    var HT = {}; // Khai báo là 1 đối tượng
    var timer;

    HT.popupSwiperSlide = () => {
        document.querySelectorAll(".popup-gallery").forEach((popup) => {
            var swiper = new Swiper(popup.querySelector(".swiper-container"), {
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
                        el: popup.querySelector(".swiper-container-thumbs"),
                        slidesPerView: 4,
                        spaceBetween: 10,
                        slideToClickedSlide: true,
                    },
                },
            });
        });
    };

    HT.changeQuantity = () => {
        $(document).on("click", ".quantity-button", function () {
            let _this = $(this);
            let quantity = $(".quantity-text").val();
            console.log($(".quantity-text").val());

            let newQuantity = 0;
            if (_this.hasClass("minus")) {
                newQuantity = parseInt(quantity) - 1;
            } else {
                newQuantity = parseInt(quantity) + 1;
            }
            if (newQuantity < 1) {
                newQuantity = 1;
            }
            $(".quantity-text").val(newQuantity);
        });
    };

    HT.selectProductVariant = () => {
        if ($(".choose-attribute").length) {
            $(document).on("click", ".choose-attribute", function (e) {
                e.preventDefault();
                let _this = $(this);
                _this
                    .parents(".attribute-value")
                    .find(".choose-attribute")
                    .removeClass("active");
                _this.addClass("active");
                HT.handleAttribute();
            });
        }
    };

    HT.handleAttribute = () => {
        let attribute_id = [];
        let canonical = $("input[name=product_canonical]").val();
        $(".attribute-value .choose-attribute").each(function () {
            let _this = $(this);
            if (_this.hasClass("active")) {
                attribute_id.push(_this.attr("data-attributeid"));
            }
        });
        $.ajax({
            type: "GET",
            url: "ajax/product/loadVariant",
            data: {
                attribute_id: attribute_id,
                product_id: $("input[name=product_id]").val(),
                language_id: $("input[name=language_id]").val(),
            },
            dataType: "json",
            beforeSend: function () {},
            success: function (res) {
                if (res.variant.uuid) {
                    let variant_url =
                        BASE_URL +
                        `${canonical}/uuid=${res.variant.uuid}` +
                        SUFFIX;
                    window.location.href = variant_url;
                }
            },
        });
    };

    HT.showAttribute = () => {
        $(".attribute-value .choose-attribute").each(function () {
            let _this = $(this);
            if (_this.hasClass("active")) {
                let attribute_name = _this.text();
                _this
                    .parents(".attribute-item")
                    .find("span")
                    .html(attribute_name);
            }
        });
    };

    // HT.chooseReviewStar = () => {
    //     $(document).on("click", ".popup-rating label", function () {
    //         let _this = $(this);
    //         let title = _this.attr("title");
    //         $(".rate-text").removeClass("uk-hidden").html(title);
    //     });
    // };

    $(document).ready(function () {
        /* CORE JS */
        HT.changeQuantity();
        HT.popupSwiperSlide();
        HT.selectProductVariant();
        HT.showAttribute();
        // HT.chooseReviewStar();
    });
})(jQuery);
