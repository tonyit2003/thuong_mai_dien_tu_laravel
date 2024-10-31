(function ($) {
    "use strict";
    var HT = {};
    var _token = $('meta[name="csrf-token"]').attr("content");

    HT.changeStatusAll = () => {
        if ($(".changeStatusAll").length) {
            $(document).on("click", ".changeStatusAll", function (e) {
                let id = [];
                $(".checkBoxItem").each(function () {
                    let checkBox = $(this);
                    if (checkBox.prop("checked")) {
                        id.push(checkBox.val());
                    }
                });
                let _this = $(this);
                let option = {
                    value: $(this).attr("data-value"),
                    model: $(this).attr("data-model"),
                    field: $(this).attr("data-field"),
                    id: id,
                    _token: _token,
                };

                $.ajax({
                    url: "ajax/dashboard/changeStatusAll",
                    type: "POST",
                    data: option,
                    dataType: "json",
                    success: function (res) {
                        // lấy dữ liệu kiểu json trong js
                        if (res.flag == true) {
                            let cssActive1 =
                                "box-shadow: rgb(26, 179, 148) 0px 0px 0px 11px inset; border-color: rgb(26, 179, 148); background-color: rgb(26, 179, 148); transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s, background-color 1.2s ease 0s;";
                            let cssActive2 =
                                "left: 13px; transition: background-color 0.4s ease 0s, left 0.2s ease 0s; background-color: rgb(255, 255, 255);";
                            let cssUnActive1 =
                                "box-shadow: rgb(223, 223, 223) 0px 0px 0px 0px inset; border-color: rgb(223, 223, 223); background-color: rgb(255, 255, 255); transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s;";
                            let cssUnActive2 =
                                "left: 0px; transition: background-color 0.4s ease 0s, left 0.2s ease 0s;";
                            for (let i = 0; i < id.length; i++) {
                                $(".js-switch-" + id[i])
                                    .find("input.status")
                                    .val(option.value);
                                // lấy dữ liệu từ đối tượng option
                                if (option.value == 1) {
                                    $(".js-switch-" + id[i])
                                        //tìm các phần tử con là thẻ span có class switchery
                                        .find("span.switchery")
                                        .attr("style", cssActive1) // thiết lập thuộc tính style
                                        .find("small")
                                        .attr("style", cssActive2); // thiết lập thuộc tính style
                                } else if (option.value == 0) {
                                    $(".js-switch-" + id[i])
                                        //tìm các phần tử con là thẻ span có class switchery
                                        .find("span.switchery")
                                        .attr("style", cssUnActive1) // thiết lập thuộc tính style
                                        .find("small")
                                        .attr("style", cssUnActive2); // thiết lập thuộc tính style
                                }
                            }
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log("Lỗi: " + textStatus + " " + errorThrown);
                    },
                });

                e.preventDefault(); // ngăn sự kiện hoạt động (vd: ngăn sư kiện chuyển trang của thẻ a)
            });
        }
    };

    HT.changeBackground = (object) => {
        let isChecked = object.prop("checked");
        if (isChecked) {
            object.closest("tr").addClass("active-bg"); // closest("tr"): tìm phần tử cha gần nhất là thẻ tr
        } else {
            object.closest("tr").removeClass("active-bg");
        }
    };

    HT.checkBoxItemReceipt = () => {
        if ($(".checkBoxItemReceipt").length) {
            // bắt sự kiện click của tất cả các phần tử có class là checkBoxItem
            $(document).on("click", ".checkBoxItemReceipt", function () {
                HT.changeBackground($(this));
                HT.addDataToReceipt($(this));
                HT.allCheckReceipt();
            });
        }
    };

    HT.checkAllReceipt = () => {
        if ($("#checkAllReceipt").length) {
            // lấy phần tử có id là checkAll
            $(document).on("click", "#checkAllReceipt", function () {
                let isChecked = $(this).prop("checked"); // prop("checked"): lấy giá trị checked
                $(".checkBoxItemReceipt").prop("checked", isChecked); // prop("checked", isChecked): thiết lập giá trị checked
                $(".checkBoxItemReceipt").each(function () {
                    HT.changeBackground($(this));
                    HT.addDataToReceipt($(this));
                });
            });
        }
    };

    HT.getProductReceipt = () => {
        $(document).on(
            "change",
            "#keywordInput input, #keywordInput select",
            function () {
                const keyword = $("#keywordInput input[name='keyword']").val();
                const productId = $("#productSelect").val();
                const quantity = $("#quantityInput").val();
                $.ajax({
                    url: "ajax/product/getProduct",
                    type: "GET",
                    data: { keyword, product_id: productId, quantity },
                    success: (response) => {
                        const $tableBody = $("#productTableBody").empty();
                        const uniqueProducts = {};
                        if (response.data.length) {
                            response.data.forEach((product) => {
                                const productName = product.name || "N/A";
                                const productVariants =
                                    product.product_variants || [];
                                const productVariantLanguage =
                                    product.product_variant_language || [];
                                for (
                                    let i = 0;
                                    i <
                                    Math.min(
                                        productVariants.length,
                                        productVariantLanguage.length
                                    );
                                    i++
                                ) {
                                    const variant = productVariants[i];
                                    const variantLanguage =
                                        productVariantLanguage[i];
                                    const variantName =
                                        variantLanguage.name || "N/A";
                                    const variantQuantity =
                                        variant.quantity || 0;
                                    const uniqueKey = `${product.id}-${variant.id}-${variantLanguage.id}`;

                                    if (!uniqueProducts[uniqueKey]) {
                                        uniqueProducts[uniqueKey] = true;

                                        // Kiểm tra xem sản phẩm này có trong danh sách đã chọn không
                                        const isChecked = HT.selectedProducts[
                                            `${product.id}-${variant.id}`
                                        ]
                                            ? "checked"
                                            : "";
                                        const row = `
                                        <tr>
                                            <td class="text-center">
                                                <input id="product-${product.id}-${variant.id}" value="${product.id}" type="checkbox" class="input-checkbox checkBoxItemReceipt" ${isChecked} />
                                            </td>
                                            <td>${productName}</td>
                                            <td>${variantName}</td>
                                            <td class="text-center">${variantQuantity}</td>
                                        </tr>`;

                                        $tableBody.append(row);
                                    }
                                }
                            });
                        } else {
                            $tableBody.append(
                                '<tr><td colspan="5" class="text-center">' +
                                    no_product +
                                    "</td></tr>"
                            );
                        }

                        HT.checkBoxItemReceipt();
                        HT.changeStatusAll();
                        HT.removeRowReceipt();
                    },
                    error: (xhr) => console.error("Error:", xhr.responseText),
                });
            }
        );
    };

    HT.getProductCatalogueBySupplierId = () => {
        // Hàm dùng chung để lấy danh mục sản phẩm dựa trên supplier_id
        const fetchProductCatalogue = (supplierId) => {
            if (supplierId) {
                // Gửi yêu cầu AJAX để lấy danh mục sản phẩm dựa trên supplier_id
                $.ajax({
                    url:
                        "ajax/" + supplierId + "/getProductCatalogueBySupplier",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        const $productSelect = $('select[name="product_id"]');
                        $productSelect.empty(); // Xóa các tùy chọn hiện tại
                        $productSelect.append(
                            `<option value="">${productname}</option>`
                        ); // Thêm tùy chọn mặc định

                        if (response.data.length > 0) {
                            // Duyệt qua các sản phẩm và thêm vào dropdown
                            $.each(response.data, function (index, product) {
                                $productSelect.append(
                                    '<option value="' +
                                        product.product_id +
                                        '">' +
                                        product.product_name +
                                        "</option>"
                                );
                            });
                        } else {
                            $productSelect.append(
                                '<option value="">No products available</option>'
                            );
                        }

                        // Khởi tạo lại Select2 nếu đang sử dụng
                        if ($(".setupSelect2").length) {
                            $(".setupSelect2").select2();
                        }
                    },
                    error: function (xhr) {
                        console.error("Error:", xhr.responseText);
                    },
                });
            } else {
                // Xóa các tùy chọn sản phẩm nếu không có nhà cung cấp nào được chọn
                $('select[name="product_id"]')
                    .empty()
                    .append(`<option value="">${productname}</option>`);
            }
        };

        // Sự kiện khi thay đổi nhà cung cấp trong dropdown
        $('select[name="supplier_id"]').on("change", function () {
            var supplierId = $(this).val(); // Lấy ID của nhà cung cấp đã chọn
            fetchProductCatalogue(supplierId); // Gọi hàm lấy danh mục sản phẩm
        });

        // Tự động thực hiện AJAX nếu đang ở chế độ edit
        const isEditMode =
            $('input[name="supplier_id"]').length > 0 &&
            $('input[name="supplier_id"]').val();
        if (isEditMode) {
            const supplierId = $('input[name="supplier_id"]').val(); // Lấy ID của nhà cung cấp từ input hidden
            fetchProductCatalogue(supplierId); // Gọi hàm lấy danh mục sản phẩm
        }
    };

    HT.addDataToReceipt = (checkbox) => {
        let row = checkbox.closest("tr");
        let productName = row.find("td").eq(1).text();
        let productVariant = row.find("td").eq(2).text();
        let uniqueId = checkbox.attr("id");
        let productId = uniqueId.split("-")[1];
        let variantId = uniqueId.split("-")[2];

        if (checkbox.prop("checked")) {
            if ($("#" + uniqueId + "-receipt").length === 0) {
                let newRow = `
                    <tr id="${uniqueId}-receipt">
                        <td>
                            <input type="hidden" name="productId[]" value="${productId}">
                            ${productName}
                        </td>
                        <td>
                            <input type="hidden" name="productVariantId[]" value="${variantId}">
                            ${productVariant}
                        </td>
                        <td><input type="text" name="quantityReceipt[]" class="form-control mr10 int" placeholder="" value="0"></td>
                        <td><input type="text" name="price[]" class="form-control mr10 int" placeholder=""></td>
                        <td class="text-center">
                            <a href="#" class="btn btn-danger delete-btn" data-row-id="${uniqueId}-receipt">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>`;
                $("#productTableBodyRereipt").append(newRow);

                // Khóa chọn nhà cung cấp và lưu giá trị vào hidden input
                let selectedSupplier = $("#supplierField").val(); // Lấy giá trị của supplier đã chọn
                $("#hiddenSupplierId").val(selectedSupplier); // Cập nhật hidden input với giá trị đã chọn
                $("#supplierField").prop("disabled", true); // Disable trường select
            }
        } else {
            HT.removeRow(null, uniqueId + "-receipt");

            // Nếu không có sản phẩm nào được chọn, mở khóa nhà cung cấp
            if ($(".checkBoxItemReceipt:checked").length === 0) {
                $("#supplierField").prop("disabled", false);
            }
        }
    };

    HT.removeRow = (event, rowId) => {
        if (event) {
            event.preventDefault();
        }
        $("#" + rowId).remove();

        // Nếu không có sản phẩm nào được chọn, mở khóa nhà cung cấp
        if ($(".checkBoxItemReceipt:checked").length === 0) {
            $("#supplierField").prop("disabled", false);
        }
    };

    HT.removeRowReceipt = () => {
        $(document).on("click", ".delete-btn", function (event) {
            event.preventDefault();
            let rowId = $(this).data("row-id");
            let checkboxId = rowId.replace("-receipt", "");
            $("#" + checkboxId).prop("checked", false);

            HT.changeBackground($("#" + checkboxId));
            HT.removeRow(event, rowId);

            if ($(".checkBoxItemReceipt:checked").length === 0) {
                $("#checkAllReceipt").prop("checked", false);
            }
        });
    };

    HT.requestReceipt = () => {
        $(document).ready(function () {
            $("#yourFormId").on("submit", function (e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr("action"),
                    method: $(this).attr("method"),
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message, "Success", {
                                timeOut: 3000,
                            });
                            window.location.href = response.redirect_url;
                        } else {
                            toastr.error(response.message, "Error", {
                                timeOut: 3000,
                            });
                        }
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            toastr.error(value, "Error", {
                                timeOut: 3000,
                            });
                        });
                    },
                });
            });
        });
    };

    HT.allCheckReceipt = () => {
        let allChecked =
            $(".checkBoxItemReceipt:checked").length ===
            $(".checkBoxItemReceipt").length;
        $("#checkAllReceipt").prop("checked", allChecked);
    };

    HT.selectedProducts = {};

    HT.getDataUpdateProductReceipt = () => {
        $(document).ready(function () {
            const receiptId = productReceiptId;
            if (receiptId == 0) {
                return;
            }
            $.ajax({
                url: "ajax/" + receiptId + "/product",
                type: "GET",
                success: function (response) {
                    const receiptData = response.data;
                    const $productTableBodyRereipt = $(
                        "#productTableBodyRereipt"
                    ).empty();

                    receiptData.forEach((item) => {
                        // Lưu trữ sản phẩm đã chọn trong HT.selectedProducts
                        HT.selectedProducts[
                            `${item.product_id}-${item.variant_id}`
                        ] = true;

                        let newRow = `
                        <tr id="product-${item.product_id}-${
                            item.variant_id
                        }-receipt">
                            <td>
                                <input type="hidden" name="productId[]" value="${
                                    item.product_id
                                }">
                                ${item.product_name}
                            </td>
                            <td>
                                <input type="hidden" name="productVariantId[]" value="${
                                    item.variant_id
                                }">
                                ${item.variant_name}
                            </td>
                            <td><input type="text" name="quantityReceipt[]" class="form-control mr10 int" placeholder="" value="${
                                item.quantity
                            }"></td>
                            <td><input type="text" name="price[]" class="form-control mr10 int" placeholder="" value="${number_format(
                                item.price,
                                0,
                                ",",
                                "."
                            )}"></td>
                            <td class="text-center">
                                <a href="#" class="btn btn-danger delete-btn" data-row-id="product-${
                                    item.product_id
                                }-${item.variant_id}-receipt">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>`;
                        $productTableBodyRereipt.append(newRow);
                    });
                },
                error: function (xhr) {
                    console.error(
                        "Error fetching receipt data:",
                        xhr.responseText
                    );
                },
            });
        });
    };

    function number_format(number, decimals, dec_point, thousands_sep) {
        // Đảm bảo rằng number là số
        number = parseFloat(number);
        if (isNaN(number)) {
            return "0";
        }

        // Cài đặt mặc định cho dấu phân cách hàng nghìn và dấu thập phân
        dec_point = dec_point || ",";
        thousands_sep = thousands_sep || ".";

        // Làm tròn số thập phân
        number = number.toFixed(decimals);

        // Tách phần thập phân và phần nguyên
        let parts = number.split(".");
        let integerPart = parts[0];
        let decimalPart = parts.length > 1 ? parts[1] : "";

        // Thêm dấu phân cách hàng nghìn
        integerPart = integerPart.replace(
            /\B(?=(\d{3})+(?!\d))/g,
            thousands_sep
        );

        // Ghép phần nguyên và phần thập phân lại
        return integerPart + (decimalPart ? dec_point + decimalPart : "");
    }

    function formatCurrency(amount) {
        return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " VND"; // Định dạng số
    }

    HT.total = () => {
        $(document).ready(function () {
            // Hàm tính tổng tiền
            function calculateTotal() {
                let total = 0;

                // Duyệt qua từng dòng trong bảng để tính tổng
                $("#productTable tbody tr").each(function () {
                    const quantityInput = $(this).find(
                        'input[name="actualQuantity[]"]'
                    );
                    const priceInput = $(this).find('input[name="price[]"]');

                    const quantity = parseFloat(quantityInput.val()) || 0; // Giá trị số lượng thực nhập
                    const price = parseFloat(priceInput.val()) || 0; // Giá trị giá

                    // Tính tổng cho dòng hiện tại và cộng dồn vào tổng
                    total += quantity * price;
                });

                // Cập nhật tổng tiền hiển thị
                $(".total").text(formatCurrency(total)); // Làm tròn và cập nhật
            }

            // Lắng nghe sự kiện input trên trường actualQuantity
            $('input[name="actualQuantity[]"]').on("input", function () {
                calculateTotal(); // Gọi hàm tính tổng khi có thay đổi
            });

            // Gọi tính tổng khi trang được tải để cập nhật số ban đầu
            calculateTotal();
        });
    };

    HT.setupDatepickerApproved = () => {
        if ($(".datepickerApproved").length) {
            const today = new Date();
            $(".datepickerApproved").datetimepicker({
                timepicker: true, // Tắt chọn giờ
                format: "d/m/Y H:i",
                minDate: today, // Giới hạn ngày bắt đầu từ hôm nay
                maxDate: today, // Giới hạn ngày kết thúc là hôm nay
            });
        }
    };

    $(document).ready(function () {
        HT.checkAllReceipt();
        HT.checkBoxItemReceipt();
        HT.getProductReceipt();
        HT.requestReceipt();
        HT.removeRowReceipt();
        HT.getDataUpdateProductReceipt();
        HT.getProductCatalogueBySupplierId();
        HT.total();
        HT.setupDatepickerApproved();
    });
})(jQuery);
