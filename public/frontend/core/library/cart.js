(function ($) {
    "use strict";
    var HT = {};
    var timer = null;
    var _token = $('meta[name="csrf-token"]').attr("content");

    var $window = $(window),
        $document = $(document);

    $.fn.elExists = function () {
        return this.length > 0;
    };

    HT.addCart = () => {
        if ($(".addToCart").length) {
            $(document).on("click", ".addToCart", function (e) {
                e.preventDefault();
                let _this = $(this);
                let product_id = _this.attr("data-productid");
                let variant_uuid = _this.attr("data-variantuuid");
                let quantity = $(".quantity-text").val();
                if (typeof quantity === "undefined") {
                    quantity = 1;
                }

                let attribute_id = [];
                $(".attribute-value .choose-attribute").each(function () {
                    let _this = $(this);
                    if (_this.hasClass("active")) {
                        attribute_id.push(_this.attr("data-attributeid"));
                    }
                });

                let option = {
                    product_id: product_id,
                    variant_uuid: variant_uuid,
                    quantity: quantity,
                    attribute_id: attribute_id,
                    _token: _token,
                };

                $.ajax({
                    url: "ajax/cart/create",
                    type: "POST",
                    data: option,
                    dataType: "json",
                    beforeSend: function () {},
                    success: function (res) {
                        toastr.clear();
                        if (res.code === 10) {
                            toastr.success(res.messages, "SUCCESS");
                        } else {
                            toastr.error(res.messages, "ERROR");
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 401) {
                            var response = xhr.responseJSON;
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        }
                    },
                });
            });
        }
    };

    HT.changeQuantity = () => {
        if ($(".btn-qty").length) {
            $(document).on("click", ".btn-qty", function () {
                let _this = $(this);
                let qtyElement = _this.siblings(".input-qty");
                let qty = qtyElement.val();
                let newQty = _this.hasClass("minus")
                    ? parseInt(qty) - 1
                    : parseInt(qty) + 1;
                newQty = newQty < 1 ? 1 : newQty;
                qtyElement.val(newQty);

                let option = {
                    quantity: newQty,
                    customer_id: _this
                        .siblings(".cart-info")
                        .find(".customer_id")
                        .val(),
                    product_id: _this
                        .siblings(".cart-info")
                        .find(".product_id")
                        .val(),
                    variant_uuid: _this
                        .siblings(".cart-info")
                        .find(".variant_uuid")
                        .val(),
                    _token: _token,
                };

                HT.handleUpdateCart(_this, option);
            });
        }
    };

    HT.changeQuantityInput = () => {
        if ($(".input-qty").length) {
            $(document).on("change", ".input-qty", function () {
                let _this = $(this);
                let option = {
                    quantity:
                        parseInt(_this.val()) == 0 ? 1 : parseInt(_this.val()),
                    customer_id: _this
                        .siblings(".cart-info")
                        .find(".customer_id")
                        .val(),
                    product_id: _this
                        .siblings(".cart-info")
                        .find(".product_id")
                        .val(),
                    variant_uuid: _this
                        .siblings(".cart-info")
                        .find(".variant_uuid")
                        .val(),
                    _token: _token,
                };
                _this.val(option.quantity);
                HT.handleUpdateCart(_this, option);
            });
        }
    };

    HT.handleUpdateCart = (_this, option) => {
        $.ajax({
            url: "ajax/cart/update",
            type: "POST",
            data: option,
            dataType: "json",
            beforeSend: function () {},
            success: function (res) {
                toastr.clear();
                if (res.code === 10) {
                    HT.changeMinyCartQuantity(res.totalQuantity);
                    HT.changeMinyQuantityItem(_this, option.quantity);
                    HT.changeCartItemSubTotal(_this, res.totalItem);
                    HT.changeCartTotal(res.totalPrice);
                    toastr.success(res.messages, "SUCCESS");
                } else {
                    toastr.error(res.messages, "ERROR");
                }
            },
        });
    };

    HT.changeMinyQuantityItem = (item, quantity) => {
        item.parents(".cart-item").find(".cart-item-number").html(quantity);
    };

    HT.changeCartItemSubTotal = (item, totalItem) => {
        item.parents(".cart-item-info")
            .find(".cart-price-sale")
            .html(totalItem);
    };

    HT.changeMinyCartQuantity = (quantity) => {
        $("#cartTotalItem").html(quantity);
    };

    HT.changeCartTotal = (totalPrice) => {
        $(".cart-total").html(totalPrice);
        // $(".discount-value").html(
        //     "-" + addCommas(res.response.cartDiscount) + "đ"
        // );
    };

    HT.removeCartItem = () => {
        if ($(".cart-item-remove").length) {
            $(document).on("click", ".cart-item-remove", function () {
                let _this = $(this);
                let option = {
                    customer_id: _this.attr("data-customer-id"),
                    product_id: _this.attr("data-product-id"),
                    variant_uuid: _this.attr("data-variant-uuid"),
                    _token: _token,
                };
                $.ajax({
                    url: "ajax/cart/delete",
                    type: "POST",
                    data: option,
                    dataType: "json",
                    beforeSend: function () {},
                    success: function (res) {
                        toastr.clear();
                        if (res.code === 10) {
                            HT.changeMinyCartQuantity(res.totalQuantity);
                            HT.changeCartTotal(res.totalPrice);
                            HT.removeCartItemRow(_this);
                            toastr.success(res.messages, "SUCCESS");
                        } else {
                            toastr.error(res.messages, "ERROR");
                        }
                    },
                });
            });
        }
    };

    HT.removeCartItemRow = (_this) => {
        _this.parents(".cart-item").remove();
    };

    HT.setupSelect2 = () => {
        if ($(".setupSelect2").length) {
            $(".setupSelect2").select2();
        }
    };

    $document.ready(function () {
        HT.addCart();
        HT.setupSelect2();
        HT.changeQuantity();
        HT.changeQuantityInput();
        HT.removeCartItem();
    });
})(jQuery);
