(function ($) {
    "use strict";

    var HT = {};
    var ranges = [];

    // $.fn là cách để thêm một hàm mới vào đối tượng jQuery => thêm một hàm mới gọi là elExist.
    $.fn.elExist = function () {
        return this.length > 0;
    };

    HT.promotionNeverEnd = () => {
        $(document).on("change", "#neverEnd", function () {
            let _this = $(this);
            let isChecked = _this.prop("checked");
            if (isChecked) {
                $("input[name=endDate]").val("").attr("disabled", true);
            } else {
                let endDate = $("input[name=startDate]").val();
                $("input[name=endDate]").val(endDate).attr("disabled", false);
            }
        });
    };

    HT.promotionSource = () => {
        $(document).on("click", ".chooseSource", function () {
            let _this = $(this);
            let flag = _this.attr("id") == "allSource" ? true : false;
            if (flag) {
                _this.parents(".ibox-content").find(".source-wrapper").remove();
            } else {
                let sourceData = [
                    {
                        id: 1,
                        name: "Tiktok",
                    },
                    {
                        id: 2,
                        name: "Shoppe",
                    },
                ];
                let sourceHtml = HT.renderPromotionSource(sourceData);
                _this.parents(".ibox-content").append(sourceHtml);
                HT.promotionMultipleSelect2();
            }
        });
    };

    HT.renderPromotionSource = (sourceData) => {
        let wrapper = $("<div>").addClass("source-wrapper");
        if (sourceData.length) {
            let select = $("<select>")
                .addClass("multipleSelect2")
                .attr("name", "source")
                .attr("multiple", true);
            for (let i = 0; i < sourceData.length; i++) {
                let option = $("<option>")
                    .attr("value", sourceData[i].id)
                    .text(sourceData[i].name);
                select.append(option);
            }
            wrapper.append(select);
        }
        return wrapper;
    };

    HT.chooseCustomerCondition = () => {
        $(document).on("click", ".chooseApply", function () {
            let _this = $(this);
            let flag = _this.attr("id") == "allApply" ? true : false;
            if (flag) {
                _this.parents(".ibox-content").find(".apply-wrapper").remove();
            } else {
                let applyConditionData = [
                    {
                        id: "staff_take_care_customer",
                        name: staffTakeCareCustomer,
                    },
                    {
                        id: "customer_group",
                        name: customerGroup,
                    },
                    {
                        id: "customer_gender",
                        name: customerGender,
                    },
                    {
                        id: "customer_birthday",
                        name: customerBirthday,
                    },
                ];
                let applyHtml = HT.renderApplyCondition(applyConditionData);
                _this.parents(".ibox-content").append(applyHtml);
                HT.promotionMultipleSelect2();
            }
        });
    };

    HT.renderApplyCondition = (applyConditionData) => {
        let wrapper = $("<div>").addClass("apply-wrapper");
        let wrapperConditionItem = $("<div>").addClass("wrapper-condition");
        if (applyConditionData.length) {
            let select = $("<select>")
                .addClass("multipleSelect2 conditionItem")
                .attr("name", "applyObject")
                .attr("multiple", true);
            for (let i = 0; i < applyConditionData.length; i++) {
                let option = $("<option>")
                    .attr("value", applyConditionData[i].id)
                    .text(applyConditionData[i].name);
                select.append(option);
            }
            wrapper.append(select);
            wrapper.append(wrapperConditionItem);
        }
        return wrapper;
    };

    HT.chooseApplyItem = () => {
        $(document).on("change", ".conditionItem", function () {
            let _this = $(this);
            let condition = {
                // select multiple: value được trả về sẽ là một mảng chứa các value của những tùy chọn (option) đã được chọn.
                value: _this.val(),
                // trả về 1 mảng đối tượng, trong từng đối tượng có thuộc tính text
                label: _this.select2("data"),
            };

            $(".wrapperConditionItem").each(function () {
                let _item = $(this);
                let itemClass = _item.attr("class").split(" ")[2];
                if (condition.value.includes(itemClass) == false) {
                    _item.remove();
                }
            });

            for (let i = 0; i < condition.value.length; i++) {
                let value = condition.value[i];
                HT.createConditionItem(value, condition.label[i].text);
            }
        });
    };

    HT.createConditionItem = (value, label) => {
        let optionData = [
            {
                id: 1,
                name: "Khách VIP",
            },
            {
                id: 2,
                name: "Khách bán buôn",
            },
        ];
        let conditionItem = $("<div>").addClass(
            `wrapperConditionItem mt10 ${value}`
        );
        let select = $("<select>")
            .addClass("multipleSelect2 objectItem")
            .attr("name", "customerGroup")
            .attr("multiple", true);
        for (let i = 0; i < optionData.length; i++) {
            let option = $("<option>")
                .attr("value", optionData[i].id)
                .text(optionData[i].name);
            select.append(option);
        }
        let conditionLabel = HT.createConditionLabel(label, value);
        conditionItem.append(conditionLabel);
        conditionItem.append(select);
        if ($(".wrapper-condition").find(`.${value}`).elExist()) {
            return;
        }
        $(".wrapper-condition").append(conditionItem);
        HT.promotionMultipleSelect2();
    };

    HT.createConditionLabel = (label, value) => {
        // let deleteButton = $("<div>")
        //     .addClass("delete")
        //     .html(
        //         `
        // <svg data-icon="TrashSolidLarge" aria-hidden="true" focusable="false" width="15" height="16" viewBox="0 0 15 16" class="bem-svg" style="display: block">
        //         <path fill="currentColor" d="M2 14a1 1 0 001 1h9a1 1 0 001-1V6H2v8zM13 2h-3a1 1 0 01-1-1H6a1 1 0 01-1 1H1v2h13V2h-1z"></path>
        //     </svg>
        // `
        //     )
        //     .attr("data-condition-item", value);
        let conditionLabel = $("<div>").addClass("conditionLabel").text(label);
        let flex = $("<div>").addClass(
            "uk-flex uk-flex-middle uk-flex-space-between"
        );
        let wrapperBox = $("<div>").addClass("mb5");
        flex.append(conditionLabel);
        wrapperBox.append(flex);
        return wrapperBox;
    };

    // HT.deleteCondition = () => {
    //     $(document).on("click", ".wrapperConditionItem .delete", function () {
    //         let _this = $(this);
    //         let unSelectedValue = _this.attr("data-condition-item");
    //         $(".conditionItem").val(unSelectedValue).trigger("change");
    //     });
    // };

    HT.btnJs100 = () => {
        $(document).on("click", ".btn-js-100", function () {
            let trLastChild = $(".order_amount_range").find(
                "tbody tr:last-child"
            );
            // let newFrom = parseInt(
            //     trLastChild
            //         .find(".order_amount_range_from input")
            //         .val()
            //         .replace(/\./g, "")
            // );
            let newTo = parseInt(
                trLastChild
                    .find(".order_amount_range_to input")
                    .val()
                    .replace(/\./g, "")
            );
            // if (!HT.isValidRange(newFrom, newTo)) {
            //     alert(valueToGreaterThanValueFrom);
            //     return;
            // }
            // if (HT.checkBtnJs100ConflictRange(newFrom, newTo)) {
            //     trLastChild.addClass("errorLine");
            //     alert(conflictRange);
            //     return;
            // }
            // $(".order_amount_range").find("tr").removeClass("errorLine");
            // ranges.push({
            //     from: newFrom,
            //     to: newTo,
            // });
            let tr = $("<tr>");
            let tdList = [
                {
                    class: "order_amount_range_from td-range",
                    name: "amountFrom[]",
                    value: addCommas(parseFloat(newTo) + 1),
                },
                {
                    class: "order_amount_range_to td-range",
                    name: "amountTo[]",
                    value: 0,
                },
            ];
            for (let i = 0; i < tdList.length; i++) {
                let td = $("<td>", { class: tdList[i].class });
                let input = $("<input>")
                    .addClass("form-control int")
                    .attr("name", tdList[i].name)
                    .val(tdList[i].value);
                td.append(input);
                tr.append(td);
            }
            let discountTd = $("<td>").addClass("discountType");
            discountTd.append(
                // tạo một phần tử HTML mới với các thuộc tính cụ thể,
                $("<div>", { class: "uk-flex uk-flex-middle" })
                    .append(
                        $("<input>", {
                            type: "text",
                            name: "amountValue[]",
                            class: "form-control int",
                            placeholder: 0,
                            value: 0,
                        })
                    )
                    .append(
                        $("<select>", {
                            name: "amountType[]",
                            class: "multipleSelect2",
                        })
                            .append(
                                $("<option>", {
                                    value: "cash",
                                    text: cash,
                                })
                            )
                            .append(
                                $("<option>", {
                                    value: "percent",
                                    text: "%",
                                })
                            )
                    )
            );
            tr.append(discountTd);
            let deleteButton = $("<td>").append(
                $("<div>", {
                    class: "delete-some-item delete-order-amount-range-condition",
                }).append(`
                    <svg data-icon="TrashSolidLarge" aria-hidden="true"
                        focusable="false" width="15" height="16"
                        viewBox="0 0 15 16" class="bem-svg" style="display: block">
                        <path fill="currentColor"
                            d="M2 14a1 1 0 001 1h9a1 1 0 001-1V6H2v8zM13 2h-3a1 1 0 01-1-1H6a1 1 0 01-1 1H1v2h13V2h-1z">
                        </path>
                    </svg>
                `)
            );
            tr.append(deleteButton);
            $(".order_amount_range table tbody").append(tr);
            HT.promotionMultipleSelect2();
        });
    };

    // HT.isValidRange = (newFrom, newTo) => {
    //     if (newTo <= newFrom) {
    //         return false;
    //     }
    //     return true;
    // };

    // // ----------100-----------200-----------
    // HT.checkBtnJs100ConflictRange = (newFrom, newTo) => {
    //     for (let i = 0; i < ranges.length; i++) {
    //         let existRange = ranges[i];
    //         if (
    //             (newFrom >= existRange.from && newFrom <= existRange.to) ||
    //             (newFrom >= existRange.from && newTo <= existRange.to) ||
    //             (newFrom <= existRange.from && newTo >= existRange.from) ||
    //             (newFrom <= existRange.to && newTo >= existRange.to)
    //         ) {
    //             return true;
    //         }
    //     }
    //     return false;
    // };

    HT.deleteAmountRangeCondition = () => {
        $(document).on(
            "click",
            ".delete-order-amount-range-condition",
            function () {
                let _this = $(this);
                _this.parents("tr").remove();
            }
        );
    };

    HT.renderOrderRangeConditionContainer = () => {
        $(document).on("change", ".promotionMethod", function () {
            let _this = $(this);
            let option = _this.val();
            switch (option) {
                case "order_amount_range":
                    HT.renderOrderAmountRange();
                    break;
                case "product_and_quantity":
                    HT.renderProductAndQuantity();
                    break;
                case "product_quantity_range":
                    break;
                case "goods_discount_by_quantity":
                    break;
                default:
                    HT.removePromotionContainer();
                    break;
            }
        });
    };

    HT.renderOrderAmountRange = () => {
        let html = `
            <div class="order_amount_range">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-right">
                                ${valueFrom}
                            </th>
                            <th class="text-right">
                                ${valueTo}
                            </th>
                            <th class="text-right">
                                ${discount} (%)
                            </th>
                            <th class="text-right">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="order_amount_range_from td-range">
                                <input type="text" name="amountFrom[]" id=""
                                    class="form-control int" placeholder="0" value="0">
                            </td>
                            <td class="order_amount_range_to td-range">
                                <input type="text" name="amountTo[]" id=""
                                    class="form-control int" placeholder="0" value="0">
                            </td>
                            <td class="discountType">
                                <div class="uk-flex uk-flex-middle">
                                    <input type="text" name="amountValue[]" id=""
                                        class="form-control int" placeholder="0" value="0">
                                    <select name="amountType[]" class="multipleSelect2" id="">
                                        <option value="cash">${cash}</option>
                                        <option value="percent">%</option>
                                    </select>
                                </div>
                            </td>
                            <td>

                            </td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-success btn-custom btn-js-100" value=""
                    type="button">${addCondition}</button>
            </div>
        `;
        HT.renderPromotionContainer(html);
    };

    HT.removePromotionContainer = () => {
        $(".promotion-container").html("");
    };

    HT.setupAjaxSearch = () => {
        $(".ajaxSearch").each(function () {
            let _this = $(this);
            let option = {
                model: _this.attr("data-model"),
            };
            _this.select2({
                minimumInputLength: 2,
                placeholder: minimumInputLength,
                closeOnSelect: true,
                ajax: {
                    url: "ajax/dashboard/findPromotionObject",
                    type: "GET",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            // params.term dữ liệu người dùng nhập vào
                            search: params.term,
                            option: option,
                        };
                    },
                    processResults: function (data) {
                        return {
                            // select2 sẽ lấy thuộc tính text từ data.items để hiển thị
                            results: data.items,
                        };
                    },
                    cache: true,
                },
            });
        });
    };

    HT.renderProductAndQuantity = () => {
        let selectData = JSON.parse($(".input-product-and-quantity").val());
        let selectHtml = "";
        for (let key in selectData) {
            selectHtml += `<option value="${key}">${selectData[key]}</option>`;
        }
        let html = `
            <div class="product_and_quantity">
                <div class="choose-module mt20">
                    <div class="fix-label" style="color: blue">${applicableProduct}
                    </div>
                    <select name="" id=""
                        class="multipleSelect2 select-product-and-quantity">
                        ${selectHtml}
                    </select>
                </div>
                <table class="table table-striped mt20">
                    <thead>
                        <tr>
                            <th class="text-right" style="width: 400px">
                                ${purchasedProduct}
                            </th>
                            <th class="text-right" style="width: 80px">
                                ${minimumQuantity}
                            </th>
                            <th class="text-right">
                                ${promotionalLimit}
                            </th>
                            <th class="text-right">
                                ${discount} (%)
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="order_amount_range_from td-range">
                                <select name="amountFrom[]" id=""
                                    class="form-control multipleSelect2" data-model="Product"
                                    multiple></select>
                            </td>
                            <td class="order_amount_range_to td-range">
                                <input type="text" name="amountTo[]" id=""
                                    class="form-control int" value="1">
                            </td>
                            <td class="order_amount_range_to td-range">
                                <input type="text" name="amountTo[]" id=""
                                    class="form-control int" placeholder="0" value="0">
                            </td>
                            <td class="discountType">
                                <div class="uk-flex uk-flex-middle">
                                    <input type="text" name="amountValue[]" id=""
                                        class="form-control int" placeholder="0" value="0">
                                    <select name="amountType[]" class="multipleSelect2" id="">
                                        <option value="cash">${cash}</option>
                                        <option value="percent">%</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
        HT.renderPromotionContainer(html);
    };

    HT.renderPromotionContainer = (html) => {
        $(".promotion-container").html(html);
        HT.promotionMultipleSelect2();
    };

    HT.promotionMultipleSelect2 = () => {
        $(".multipleSelect2").select2({
            // minimumInputLength: 2,
            placeholder: clickToSelect,
            // ajax: {
            //     url: "ajax/attribute/getAttribute",
            //     type: "GET",
            //     dataType: "json",
            //     delay: 250,
            //     data: function (params) {
            //         return {
            //             // params.term dữ liệu người dùng nhập vào
            //             search: params.term,
            //             option: option,
            //         };
            //     },
            //     processResults: function (data) {
            //         return {
            //             // select2 sẽ lấy thuộc tính text từ data.items để hiển thị
            //             results: data.items,
            //         };
            //     },
            //     cache: true,
            // },
        });
    };

    $(document).ready(function () {
        HT.promotionNeverEnd();
        HT.promotionSource();
        HT.promotionMultipleSelect2();
        HT.chooseCustomerCondition();
        HT.chooseApplyItem();
        // HT.deleteCondition();
        HT.btnJs100();
        HT.deleteAmountRangeCondition();
        HT.renderOrderRangeConditionContainer();
        // HT.setupAjaxSearch();
    });
})(jQuery);
