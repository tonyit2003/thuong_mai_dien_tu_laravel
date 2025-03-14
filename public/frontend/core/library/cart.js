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
                            HT.changeMinyCartQuantity(res.totalQuantity);
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
                let cartInfo = _this.siblings(".cart-info");
                let variant_uuid = cartInfo.find(".variant_uuid").val();
                let customer_id = cartInfo.find(".customer_id").val();
                let product_id = cartInfo.find(".product_id").val();
                HT.checkQuantity(qtyElement, variant_uuid, newQty, qty, () => {
                    let updateOption = {
                        quantity: newQty,
                        customer_id: customer_id,
                        product_id: product_id,
                        variant_uuid: variant_uuid,
                        _token: _token,
                    };
                    HT.handleUpdateCart(_this, updateOption);
                });
            });
        }
    };

    HT.changeQuantityInput = () => {
        if ($(".input-qty").length) {
            $(document).on("change", ".input-qty", function () {
                let _this = $(this);
                let quantity = _this.val() < 1 ? 1 : parseInt(_this.val());
                let cartInfo = _this.siblings(".cart-info");
                let variant_uuid = cartInfo.find(".variant_uuid").val();
                let customer_id = cartInfo.find(".customer_id").val();
                let product_id = cartInfo.find(".product_id").val();
                HT.checkQuantity(_this, variant_uuid, quantity, 1, () => {
                    let updateOption = {
                        quantity: quantity,
                        customer_id: customer_id,
                        product_id: product_id,
                        variant_uuid: variant_uuid,
                        _token: _token,
                    };
                    HT.handleUpdateCart(_this, updateOption);
                });
            });
        }
    };

    HT.checkQuantity = (
        quantityInput,
        variant_uuid,
        quantity,
        oldQuantity,
        callback
    ) => {
        let option = {
            variant_uuid: variant_uuid,
            quantity: quantity,
            _token: _token,
        };
        $.ajax({
            url: "ajax/cart/checkQuantity",
            type: "POST",
            data: option,
            dataType: "json",
            beforeSend: function () {},
            success: function (res) {
                if (res.code === 10) {
                    quantityInput.val(quantity);
                    if (typeof callback === "function") {
                        callback();
                    }
                } else {
                    toastr.clear();
                    quantityInput.val(oldQuantity);
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
                    HT.changeCartTotal(res.totalPrice, res.cartDiscount);
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

    HT.changeCartTotal = (totalPrice, cartDiscount) => {
        $(".cart-total").html(totalPrice);
        $(".discount-value").html("- " + cartDiscount);
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
                            HT.changeCartTotal(
                                res.totalPrice,
                                res.cartDiscount
                            );
                            HT.removeCartItemRow(_this);
                            if (res.totalQuantity == 0) {
                                HT.setCartEmpty();
                            }
                            toastr.success(res.messages, "SUCCESS");
                        } else {
                            toastr.error(res.messages, "ERROR");
                        }
                    },
                });
            });
        }
    };

    HT.setCartEmpty = () => {
        if ($(".cart-not-empty").length) {
            $(".cart-not-empty").addClass("uk-hidden");
        }
        if ($(".empty-cart").length) {
            $(".empty-cart").removeClass("uk-hidden");
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
