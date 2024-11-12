(function ($) {
    "use strict";
    var HT = {};
    var _token = $('meta[name="csrf-token"]').attr("content");

    HT.select2 = () => {
        if ($(".setupSelect2").length) {
            $(".setupSelect2").select2();
        }
    };

    HT.editOrder = () => {
        if ($(".edit-order").length) {
            $(document).on("click", ".edit-order", function () {
                let _this = $(this);
                let target = _this.attr("data-target");
                let html = "";
                let originalHtml = _this
                    .parents(".ibox")
                    .find(".ibox-content")
                    .html();
                if (target === "description") {
                    let value = _this
                        .parents(".ibox")
                        .find(".description")
                        .text()
                        .trim();
                    html = HT.renderDescriptionOrder(value);
                } else if (target == "customerInfo") {
                    html = HT.renderCustomerOrderInformation();
                    // HT.select2() chỉ chạy sau khi HT.renderCustomerOrderInformation() chạy xong, để tránh lỗi khi cố gắng áp dụng select2 vào các phần tử chưa tồn tại.
                    setTimeout(() => {
                        HT.select2();
                    }, 0);
                }
                _this.parents(".ibox").find(".ibox-content").html(html);
                HT.changeEditToCancel(_this, originalHtml);
            });
        }
    };

    HT.renderDescriptionOrder = (value) => {
        let html = `
            <input class="form-control ajax-edit" name="description" data-field="description" value="${value}">
            <div class="row mb15 mt15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <button class="btn btn-success updateDescription">${save}</button>
                    </div>
                </div>
            </div>
        `;
        return html;
    };

    HT.renderCustomerOrderInformation = () => {
        let data = {
            fullname: $(".fullname").text().trim(),
            email: $(".email").text().trim(),
            phone: $(".phone").text().trim(),
            address: $(".address").text().trim(),
            ward_id: $(".ward_id").val(),
            district_id: $(".district_id").val(),
            province_id: $(".province_id").val(),
        };
        let html = `
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="">${fullname}</label>
                        <input type="text" name="fullname" value="${
                            data.fullname
                        }" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="">${phone}</label>
                        <input type="text" name="phone" value="${
                            data.phone
                        }" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="">${email}</label>
                        <input type="text" name="email" value="${
                            data.email
                        }" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="">${address}</label>
                        <input type="text" name="address" value="${
                            data.address
                        }" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="">${province}</label>
                        <select name="province_id" class="setupSelect2 province location" data-target="district">
                            <option value="0">${selectProvince}</option>
                            ${HT.provinceList()}
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="">${district}</label>
                        <select name="district_id" class="setupSelect2 district location" data-target="ward">
                            <option value="0">${selectDistrict}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="">${ward}</label>
                        <select name="ward_id" class="setupSelect2 ward">
                            <option value="0">${selectWard}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <button class="btn btn-success saveCustomer">${save}</button>
                    </div>
                </div>
            </div>
        `;
        setTimeout(() => {
            HT.loadCity(data.province_id);
        }, 0);
        return html;
    };

    HT.loadCity = (province_id) => {
        if (province_id != "") {
            $(".province").val(province_id).trigger("change");
        }
    };

    HT.provinceList = () => {
        let html = "";
        for (let i = 0; i < provinces.length; i++) {
            html += `<option value="${provinces[i].id}">${provinces[i].name}</option>`;
        }
        return html;
    };

    HT.changeEditToCancel = (object, originalHtml) => {
        let encodeHtml = btoa(encodeURIComponent(originalHtml.trim()));
        object
            .html(cancel)
            .removeClass("edit-order")
            .addClass("cancel-edit")
            .attr("data-html", encodeHtml);
    };

    HT.cancelEdit = () => {
        $(document).on("click", ".cancel-edit", function (e) {
            let _this = $(this);
            let originalHtml = decodeURIComponent(
                atob(_this.attr("data-html"))
            );
            _this.html(edit).removeClass("cancel-edit").addClass("edit-order");
            _this.parents(".ibox").find(".ibox-content").html(originalHtml);
        });
    };

    HT.updateDescription = () => {
        $(document).on("click", ".updateDescription", function (e) {
            e.preventDefault();
            let inputValue = $("input[name=description].ajax-edit");
            let field = inputValue.attr("data-field");
            let value = inputValue.val();
            let option = {
                id: $(".orderId").val(),
                payload: {
                    [field]: value,
                },
                _token: _token,
            };
            HT.ajaxUpdateOrderInfo(option, inputValue);
        });
    };

    HT.saveCustomer = () => {
        $(document).on("click", ".saveCustomer", function (e) {
            e.preventDefault();
            let _this = $(this);
            let option = {
                id: $(".orderId").val(),
                payload: {
                    fullname: $("input[name=fullname]").val(),
                    phone: $("input[name=phone]").val(),
                    email: $("input[name=email]").val(),
                    address: $("input[name=address]").val(),
                    ward_id: $(".ward").val(),
                    district_id: $(".district").val(),
                    province_id: $(".province").val(),
                },
                _token: _token,
            };
            HT.ajaxUpdateOrderInfo(option, _this);
        });
    };

    HT.ajaxUpdateOrderInfo = (option, object) => {
        $.ajax({
            url: "ajax/order/update",
            type: "POST",
            dataType: "json",
            data: option,
            success: function (res) {
                toastr.clear();
                if (res.code == 10) {
                    if (
                        object
                            .parents(".ibox")
                            .find(".cancel-edit")
                            .attr("data-target") == "description"
                    ) {
                        HT.renderDescriptionHtml(
                            option.payload,
                            object.parents(".ibox")
                        );
                    } else if (
                        object
                            .parents(".ibox")
                            .find(".cancel-edit")
                            .attr("data-target") == "customerInfo"
                    ) {
                        HT.renderCustomerInfoHtml(res.order);
                    }
                    toastr.success(res.messages, "SUCCESS");
                } else {
                    toastr.error(res.messages, "ERROR");
                }
            },
        });
    };

    HT.renderDescriptionHtml = (payload, target) => {
        let html = `<div class="description">${payload.description}</div>`;
        target.find(".ibox-content").html(html);
        target
            .find(".cancel-edit")
            .removeClass("cancel-edit")
            .addClass("edit-order")
            .attr("data-html", "")
            .html(edit);
    };

    HT.renderCustomerInfoHtml = (order) => {
        let html = `
            <div class="customer-line">
                <strong>N:</strong>
                <span class="fullname">
                    ${order.fullname}
                </span>
            </div>
            <div class="customer-line">
                <strong>P:</strong>
                <span class="phone">
                    ${order.phone}
                </span>
            </div>
            <div class="customer-line">
                <strong>E:</strong>
                <span class="email">
                    ${order.email}
                </span>
            </div>
            <div class="customer-line">
                <strong>A:</strong>
                <span class="address">
                    ${order.address}
                </span>
            </div>
            <div class="customer-line">
                <strong>W:</strong> ${order.ward}
            </div>
            <div class="customer-line">
                <strong>D:</strong> ${order.district}
            </div>
            <div class="customer-line">
                <strong>P:</strong> ${order.province}
            </div>
        `;
        $(".order-customer-information").html(html);
        $(".ward_id").val(order.ward_id);
        $(".district_id").val(order.district_id);
        $(".province_id").val(order.province_id);
        $(".order-customer-information")
            .parents(".ibox")
            .find(".cancel-edit")
            .removeClass("cancel-edit")
            .addClass("edit-order")
            .attr("data-html", "")
            .html(edit);
    };

    HT.getLocation = () => {
        $(document).on("change", ".location", function () {
            let _this = $(this);
            let option = {
                data: {
                    location_id: _this.val(),
                },
                target: _this.attr("data-target"),
            };
            HT.sendDataToGetLocation(option);
        });
    };

    HT.sendDataToGetLocation = (option) => {
        let district_id = $(".district_id").val();
        let ward_id = $(".ward_id").val();
        $.ajax({
            url: "ajax/location/getLocation",
            type: "GET",
            data: option,
            dataType: "json",
            success: function (res) {
                $("." + option.target).html(res.html);

                if (district_id != "" && option.target == "district") {
                    $(".district").val(district_id).trigger("change");
                }

                if (ward_id != "" && option.target == "ward") {
                    $(".ward").val(ward_id).trigger("change");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Error: " + textStatus + " " + errorThrown);
            },
        });
    };

    HT.updateField = () => {
        $(document).on("click", ".updateField", function (e) {
            e.preventDefault();
            let _this = $(this);
            let option = {
                payload: {
                    [_this.attr("data-field")]: _this.attr("data-value"),
                },
                id: $(".orderId").val(),
                _token: _token,
            };
            $.ajax({
                url: "ajax/order/update",
                type: "POST",
                dataType: "json",
                data: option,
                success: function (res) {
                    toastr.clear();
                    if (res.code == 10) {
                        HT.createOrderConfirmSection(_this);
                        toastr.success(res.messages, "SUCCESS");
                    } else {
                        toastr.error(res.messages, "ERROR");
                    }
                },
            });
        });
    };

    HT.createOrderConfirmSection = (object) => {
        $(".isConfirm").html(object.attr("data-title"));

        if (object.attr("data-value") == "confirm") {
            let button = `<a href="#submitCancelOrder" rel="modal:open" class="btn btn-danger">${cancelOrder}</a>`;
            $(".cancel-block").html(button);

            let checkImage = "backend/img/check.png";
            $(".confirm-box").find("img").attr("src", checkImage);
            $(".confirm-block").html(confirmed).addClass("text-success");
        }

        if (object.attr("data-value") == "cancel") {
            let checkImage = "backend/img/remove.png";
            $(".confirm-box").find("img").attr("src", checkImage);
            $(".confirm-block").html(canceled).addClass("text-danger");
            $(".cancel-block").html("");
        }

        if (object.attr("data-value") == "processing") {
            let button = `<a class="btn btn-success" target="_blank" href="${invoiceUrl}">${invoiceTitle}</a> <a class="btn btn-primary confirm updateField" data-field="delivery" data-value="success" data-title="${invoiceButton}">${invoiceButton}</a>`;
            $(".invoice-block").html(button);

            let checkImage = "backend/img/sold.png";
            $(".confirm-box").find("img").attr("src", checkImage);
            $(".processing-block")
                .html(successfulExport)
                .addClass("text-success");

            $(".order-item-voucher").html("");
        }

        if (object.attr("data-value") == "success") {
            window.location.href = `${routeOutOfStock}`;
        }

        $.modal.close();
    };

    HT.updateBadge = () => {
        $(document).on("change", ".updateBadge", function () {
            let _this = $(this);
            let originalStatus = _this.siblings(".changeOrderStatus").val();
            let option = {
                payload: {
                    [_this.attr("data-field")]: _this.val(),
                },
                id: _this.parents("tr").find(".checkBoxItem").val(),
                _token: _token,
            };
            let confirmStatus = _this.parents("tr").find(".confirm").val();
            if (confirmStatus != "pending") {
                $.ajax({
                    url: "ajax/order/update",
                    type: "POST",
                    dataType: "json",
                    data: option,
                    success: function (res) {
                        toastr.clear();
                        if (res.code == 10) {
                            toastr.success(res.messages, "SUCCESS");
                        } else {
                            toastr.error(res.messages, "ERROR");
                        }
                    },
                });
            } else {
                toastr.error(mustConfirmOrder, "ERROR");
            }
        });
    };

    $(document).ready(function () {
        HT.editOrder();
        HT.updateDescription();
        HT.cancelEdit();
        HT.getLocation();
        HT.saveCustomer();
        HT.updateField();
        HT.updateBadge();
    });
})(jQuery);
