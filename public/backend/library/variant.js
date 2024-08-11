(function ($) {
    "use strict";
    var HT = {};

    HT.setupProductVariant = () => {
        if ($(".variantInputCheckbox").length) {
            $(document).on("change", ".variantInputCheckbox", function () {
                let _this = $(this);
                let price = $("input[name=price]").val();
                let code = $("input[name=code]").val();
                if (price == "" || code == "") {
                    alert(
                        "Bạn phải nhập Mã sản phẩm và Giá sản phẩm để sử dụng chức năng này."
                    );
                    $(".variantInputCheckbox").prop("checked", false);
                    code == ""
                        ? $("input[name=code]").focus()
                        : $("input[name=price]").focus();
                    return false;
                }
                if (_this.prop("checked")) {
                    $(".variant-wrapper").removeClass("hidden");
                } else {
                    $(".variant-wrapper").addClass("hidden");
                }
            });
        }
    };

    HT.addVariant = () => {
        if ($(".add-variant").length) {
            $(document).on("click", ".add-variant", function () {
                let html = HT.renderVariantItem();
                $(".variant-body").append(html);
                $(".variantTable thead").html("");
                $(".variantTable tbody").html("");
                HT.checkMaxAttributeGroup();
                HT.disabledAttributeCatalogueChoose();
            });
        }
    };

    HT.renderVariantItem = () => {
        // ${} (khi dùng dấu ``): nhúng các biểu thức JavaScript bên trong một chuỗi mà không cần phải nối chuỗi bằng các toán tử +
        let options = "";
        for (let i = 0; i < attributeCatalogues.length; i++) {
            options += `<option value="${attributeCatalogues[i].id}">${attributeCatalogues[i].name}</option>`;
        }

        return `
    <div class="row mb20 variant-item">
        <div class="col-lg-3">
            <div class="attribute-catalogue">
                <select name="attributeCatalogue[]" id="" class="choose-attribute niceSelect">
                    <option value="0">${selectAttributeCatalogue}</option>
                    ${options}
                </select>
            </div>
        </div>
        <div class="col-lg-8">
            <input type="text" name="" disabled class="fake-variant form-control">
        </div>
        <div class="col-lg-1">
            <button type="button" class="remove-attribute btn btn-danger">
                <svg data-icon="TrashSolidLarge" aria-hidden="true" focusable="false" width="15" height="16" viewBox="0 0 15 16" class="bem-svg" style="display: block">
                    <path fill="currentColor" d="M2 14a1 1 0 001 1h9a1 1 0 001-1V6H2v8zM13 2h-3a1 1 0 01-1-1H6a1 1 0 01-1 1H1v2h13V2h-1z"></path>
                </svg>
            </button>
        </div>
    </div>`;
    };

    HT.chooseVariantGroup = () => {
        $(document).on("change", ".choose-attribute", function () {
            let _this = $(this);
            let attributeCatalogueId = _this.val();
            if (attributeCatalogueId != 0) {
                _this
                    .parents(".col-lg-3")
                    .siblings(".col-lg-8")
                    .html(HT.select2Variant(attributeCatalogueId));
                $(".selectVariant").each(function (key, index) {
                    HT.getSelect2($(this));
                });
            } else {
                _this
                    .parents(".col-lg-3")
                    .siblings(".col-lg-8")
                    .html(
                        `<input type="text" name="attribute[${attributeCatalogueId}][]" disabled class="fake-variant form-control">`
                    );
            }
            HT.disabledAttributeCatalogueChoose();
        });
    };

    HT.select2Variant = (attributeCatalogueId) => {
        return `<select class="selectVariant variant-${attributeCatalogueId} form-control" name="attribute[${attributeCatalogueId}][]" multiple data-catid="${attributeCatalogueId}"></select>`;
    };

    HT.getSelect2 = (object) => {
        let option = {
            attributeCatalogueId: object.attr("data-catid"),
        };
        $(object).select2({
            minimumInputLength: 2,
            placeholder: minimumInputLength,
            ajax: {
                url: "ajax/attribute/getAttribute",
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
    };

    HT.disabledAttributeCatalogueChoose = () => {
        let id = [];
        // find(): tìm các phần tử con thỏa điều kiện
        $(".choose-attribute").each(function () {
            let _this = $(this);
            let selected = _this.find("option:selected").val();
            if (selected != 0) {
                id.push(selected);
            }
        });
        $(".choose-attribute").find("option").removeAttr("disabled");
        for (let i = 0; i < id.length; i++) {
            $(".choose-attribute")
                .find("option[value=" + id[i] + "]")
                .prop("disabled", true);
        }
        HT.destroyNiceSelect();
        HT.niceSelect();
        $(".choose-attribute").find("option:selected").removeAttr("disabled");
    };

    HT.niceSelect = () => {
        $(".niceSelect").niceSelect();
    };

    HT.destroyNiceSelect = () => {
        if ($(".niceSelect").length) {
            $(".niceSelect").niceSelect("destroy");
        }
    };

    HT.checkMaxAttributeGroup = () => {
        let variantItem = $(".variant-item").length;
        if (variantItem >= attributeCatalogues.length) {
            $(".add-variant").remove();
        } else {
            $(".variant-foot").html(
                `<button type="button" class="add-variant">${btnAddVariant}</button>`
            );
        }
    };

    HT.removeAttribute = () => {
        $(document).on("click", ".remove-attribute", function () {
            let _this = $(this);
            _this.parents(".variant-item").remove();
            HT.checkMaxAttributeGroup();
            HT.createVariant();
        });
    };

    HT.createProductVariant = () => {
        $(document).on("change", ".selectVariant", function () {
            let _this = $(this);
            HT.createVariant();
        });
    };

    HT.createVariant = () => {
        let attributes = []; // khởi tạo 1 mảng
        let variants = [];
        let attributeTitle = [];

        $(".variant-item").each(function () {
            let _this = $(this);
            let attr = [];
            let attrVariant = [];
            let attributeCatalogueId = _this.find(".choose-attribute").val();
            let optionText = _this
                // tìm option:selected là con của .choose-attribute và .choose-attribute là con của _this
                .find(".choose-attribute option:selected")
                .text();
            // select2("data") => lấy dữ liệu được chọn: [{ id: "1", text: "Option 1" }, { id: "2", text: "Option 2" }]
            let attribute = $(`.variant-${attributeCatalogueId}`).select2(
                "data"
            );

            for (let i = 0; i < attribute.length; i++) {
                let item = {}; // khởi tạo 1 đối tượng
                let itemVariant = {};
                // item[optionText]: truy cập thuộc tính optionText của đối tượng item
                // a[b]: truy cập những thuộc tính có chứa khoảng trắng, dấu,....
                // a.b: truy cập những thuộc tính ko có khoảng trắng, ko dấu
                item[optionText] = attribute[i].text;
                itemVariant[attributeCatalogueId] = attribute[i].id;
                attr.push(item);
                attrVariant.push(itemVariant);
            }

            attributeTitle.push(optionText);
            attributes.push(attr);
            variants.push(attrVariant);
        });
        // reduce: lấy từng cặp mảng a và b và kết hợp chúng.
        attributes = attributes.reduce((a, b) =>
            // flatMap(): được sử dụng để làm phẳng mảng sau khi kết hợp.
            // map(): được sử dụng để tạo một mảng mới với các đối tượng kết hợp từ d và e
            // { ...d, ...e }: tạo ra một đối tượng mới bằng cách kết hợp tất cả các thuộc tính của d và e vào một đối tượng duy nhất
            a.flatMap((d) => b.map((e) => ({ ...d, ...e })))
        );
        variants = variants.reduce((a, b) =>
            a.flatMap((d) => b.map((e) => ({ ...d, ...e })))
        );
        HT.createTableHeader(attributeTitle);

        let trClass = [];
        // lặp qua các phần tử trong một mảng
        attributes.forEach((value, index) => {
            let row = HT.createVariantRow(value, variants[index]);
            let classModified = `tr-variant-${Object.values(variants[index])
                .join(", ")
                .replace(/, /g, "-")}`;
            trClass.push(classModified);
            if (!$("table.variantTable tbody tr").hasClass(classModified)) {
                $("table.variantTable tbody").append(row);
            }
        });

        $("table.variantTable tbody tr").each(function () {
            const row = $(this);
            const rowClasses = row.attr("class");
            if (rowClasses) {
                const rowClassArray = rowClasses.split(" ");
                let shouldRemove = false;
                rowClassArray.forEach((value, index) => {
                    if (value == "variant-row") {
                        return;
                        //  includes() => kiểm tra xem một chuỗi hoặc một mảng có chứa một giá trị cụ thể hay không
                    } else if (!trClass.includes(value)) {
                        shouldRemove = true;
                    }
                });
                if (shouldRemove) {
                    row.remove();
                }
            }
        });
    };

    HT.createVariantRow = (attributeItem, variantItem) => {
        // Object.values() => trả về một mảng chứa các giá trị của các thuộc tính có thể liệt kê của một đối tượng.
        let attributeString = Object.values(attributeItem).join(", ");
        let attributeIdString = Object.values(variantItem).join(", ");

        // /, /g => /, /: Mẫu tìm kiếm cho dấu phẩy và khoảng trắng tiếp theo, g => tìm kiếm tất cả các lần xuất hiện của mẫu trong chuỗi
        let classModified = attributeIdString.replace(/, /g, "-");
        let row = $("<tr>").addClass(`variant-row tr-variant-${classModified}`);

        let td;

        td = $("<td>").append(
            $("<span>")
                .addClass("image img-cover")
                .append(
                    $("<img>")
                        .addClass("imageSrc")
                        .attr("src", "backend/img/photo.jpg")
                )
        );
        row.append(td);

        Object.values(attributeItem).forEach((value) => {
            td = $("<td>").text(value);
            row.append(td);
        });

        td = $("<td>").addClass("hidden td-variant");
        let mainPrice = $("input[name=price]").val();
        let mainSku = $("input[name=code]").val();
        let inputHiddenFields = [
            { name: "variant[quantity][]", class: "variant_quantity" },
            {
                name: "variant[sku][]",
                class: "variant_sku",
                value: `${mainSku}-${classModified}`,
            },
            {
                name: "variant[price][]",
                class: "variant_price",
                value: mainPrice,
            },
            { name: "variant[barcode][]", class: "variant_barcode" },
            { name: "variant[file_name][]", class: "variant_filename" },
            { name: "variant[file_url][]", class: "variant_fileurl" },
            { name: "variant[album][]", class: "variant_album" },
            { name: "productVariant[name][]", value: attributeString },
            { name: "productVariant[id][]", value: attributeIdString },
        ];

        $.each(inputHiddenFields, function (index, value) {
            let input = $("<input>")
                .attr("type", "text")
                .attr("name", value.name)
                .addClass(value.class);
            if (value.value) {
                input.val(value.value);
            }
            td.append(input);
        });

        row.append($("<td>").addClass("td-quantity").text("-"));
        row.append($("<td>").addClass("td-price").text(mainPrice));
        row.append(
            $("<td>").addClass("td-sku").text(`${mainSku}-${classModified}`)
        );
        row.append(td);

        return row;
    };

    HT.createTableHeader = (attributeTitle) => {
        let thead = $("table.variantTable thead");
        // tạo một phần tử <tr> mới
        let row = $("<tr>");
        row.append($("<td>").text(tableImageTitle));
        for (let i = 0; i < attributeTitle.length; i++) {
            row.append($("<td>").text(attributeTitle[i]));
        }
        row.append($("<td>").text(tableQuantityTitle));
        row.append($("<td>").text(tablePriceTitle));
        row.append($("<td>").text(tableSkuTitle));
        thead.html(row);
    };

    HT.variantAlbum = () => {
        $(document).on("click", ".click-to-upload-variant", function (e) {
            HT.browseVariantServerAlbum();
            e.preventDefault();
        });
    };

    HT.browseVariantServerAlbum = () => {
        var type = "Images";
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data, allFiles) {
            let html = "";
            for (var i = 0; i < allFiles.length; i++) {
                var image = allFiles[i].url;
                html +=
                    '<li class="ui-state-default"><div class="thumb"><span class="span image image-scaledown"><img src="' +
                    image +
                    '" alt="' +
                    image +
                    '"><input type="hidden" name="variantAlbum[]" value="' +
                    image +
                    '"></span><button class="variant-delete-image"><i class="fa fa-trash"></i></button></div></li>';
            }
            $(".click-to-upload-variant").addClass("hidden");
            $("#sortable2").append(html);
            $(".upload-variant-list").removeClass("hidden");
        };
        finder.popup();
    };

    HT.deleteVariantAlbum = () => {
        $(document).on("click", ".variant-delete-image", function (e) {
            let _this = $(this);
            _this.parents(".ui-state-default").remove();
            if ($(".ui-state-default").length == 0) {
                $(".click-to-upload-variant").removeClass("hidden");
                $(".upload-variant-list").addClass("hidden");
            }
            e.preventDefault();
        });
    };

    HT.switchChange = () => {
        $(document).on("change", ".js-switch", function () {
            let _this = $(this);
            let isChecked = _this.prop("checked");
            if (isChecked == true) {
                _this
                    .parents(".col-lg-2")
                    .siblings(".col-lg-10")
                    .find(".disabled")
                    .removeAttr("disabled");
            } else {
                _this
                    .parents(".col-lg-2")
                    .siblings(".col-lg-10")
                    .find(".disabled")
                    .attr("disabled", true);
            }
        });
    };

    HT.updateVariant = () => {
        $(document).on("click", ".variant-row", function () {
            let _this = $(this);
            let variantData = {};
            _this
                // class^=variant_ => Chọn tất cả các phần tử có class bắt đầu bằng variant_
                .find(`.td-variant input[type=text][class^=variant_]`)
                .each(function () {
                    let className = $(this).attr("class");
                    variantData[className] = $(this).val();
                });
            let updateVariantBox = HT.updateVariantHtml(variantData);
            if ($(".updateVariantTr").length == 0) {
                // after(): chèn nội dung sau phần tử được chọn
                _this.after(updateVariantBox);
                HT.switchery();
            }
        });
    };

    HT.updateVariantHtml = (variantData) => {
        let variantAlbum =
            variantData.variant_album != ""
                ? variantData.variant_album.split(",")
                : [];
        let variantAlbumItem = HT.variantAlbumList(variantAlbum);
        return `
        <tr class="updateVariantTr">
            <td colspan="6">
                <div class="updateVariant ibox">
                    <div class="ibox-title">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h5>
                                ${updateInfoVariant}
                            </h5>
                            <div class="button-group">
                                <div class="uk-flex uk-flex-middle">
                                    <button type="button"
                                        class="cancelUpdate btn btn-danger mr10">${btnCancel}</button>
                                    <button type="button"
                                        class="saveUpdate btn btn-success mr10">${btnSave}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="click-to-upload-variant ${
                            variantAlbum.length > 0 ? "hidden" : ""
                        }">
                            <div class="icon">
                                <a type="button" class="upload-variant-picture">
                                    <svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80">
                                        <path
                                            d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                            <div class="small-text">${clickToAddImage}</div>
                        </div>
                        <ul class="upload-variant-list ${
                            variantAlbum.length == 0 ? "hidden" : ""
                        } sortui ui-sortable clearfix" id="sortable2">
                            ${variantAlbumItem}
                        </ul>
                        <div class="row mt20 uk-flex uk-flex-middle">
                            <div class="col-lg-2 uk-flex uk-flex-middle uk-flex-space-between">
                                <label for="" class="mr10">${inventoryManagement}</label>
                                <input type="checkbox" class="js-switch" data-target="variantQuantity" ${
                                    variantData.variant_quantity != ""
                                        ? "checked"
                                        : ""
                                }>
                            </div>
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="" class="control-label">${quantityTitle}</label>
                                        <input type="text" name="variant_quantity" value="${
                                            variantData.variant_quantity
                                        }"
                                            class="form-control disabled int" ${
                                                variantData.variant_quantity ==
                                                ""
                                                    ? "disabled"
                                                    : ""
                                            }>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="" class="control-label">${skuTitle}</label>
                                        <input type="text" name="variant_sku" value="${
                                            variantData.variant_sku
                                        }" class="form-control text-right">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="" class="control-label">${priceTitle}</label>
                                        <input type="text" name="variant_price" value="${HT.addCommas(
                                            variantData.variant_price
                                        )}" class="form-control int">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="" class="control-label">${barcodeTitle}</label>
                                        <input type="text" name="variant_barcode" value="${
                                            variantData.variant_barcode
                                        }" class="form-control text-right">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt20 uk-flex uk-flex-middle">
                            <div class="col-lg-2 uk-flex uk-flex-middle uk-flex-space-between">
                                <label for="" class="mr10">${fileManagement}</label>
                                <input type="checkbox" class="js-switch" data-target="disabled" ${
                                    variantData.variant_filename != "" ||
                                    variantData.variant_fileurl != ""
                                        ? "checked"
                                        : ""
                                }>
                            </div>
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="" class="control-label">${fileNameTitle}</label>
                                        <input type="text" name="variant_file_name" value="${
                                            variantData.variant_filename
                                        }"
                                            class="form-control disabled" ${
                                                variantData.variant_filename ==
                                                    "" &&
                                                variantData.variant_fileurl ==
                                                    ""
                                                    ? "disabled"
                                                    : ""
                                            }>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="" class="control-label">${pathTitle}</label>
                                        <input type="text" name="variant_file_url" value="${
                                            variantData.variant_fileurl
                                        }"
                                            class="form-control disabled" ${
                                                variantData.variant_filename ==
                                                    "" &&
                                                variantData.variant_fileurl ==
                                                    ""
                                                    ? "disabled"
                                                    : ""
                                            }>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>`;
    };

    HT.variantAlbumList = (album) => {
        let html = "";
        if (album.length > 0) {
            for (let i = 0; i < album.length; i++) {
                html += `
                    <li class="ui-state-default">
                        <div class="thumb">
                            <span class="span image image-scaledown">
                                <img src="${album[i]}" alt="${album[i]}">
                                <input type="hidden" name="variantAlbum[]" value="${album[i]}">
                            </span>
                            <button class="variant-delete-image">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </li>
            `;
            }
        }
        return html;
    };

    HT.switchery = () => {
        $(".js-switch").each(function () {
            var switchery = new Switchery(this, {
                color: "#1AB394",
                size: "small",
            });
        });
    };

    HT.saveUpdateVariant = () => {
        $(document).on("click", ".saveUpdate", function () {
            let variant = {
                quantity: $("input[name=variant_quantity]").val(),
                sku: $("input[name=variant_sku]").val(),
                price: $("input[name=variant_price]").val(),
                barcode: $("input[name=variant_barcode]").val(),
                filename: $("input[name=variant_file_name]").val(),
                fileurl: $("input[name=variant_file_url]").val(),
                // $("input[name='variantAlbum[]']").val() => chỉ lấy phần tử đầu tiên
                // dùng map().get() để chuyển input[name='variantAlbum[]'] thành 1 mảng
                album: $("input[name='variantAlbum[]']")
                    .map(function () {
                        return $(this).val();
                    })
                    .get(),
            };

            $.each(variant, function (index, value) {
                $(`.updateVariantTr`)
                    // prev() => chọn phần tử anh em liền trước của phần tử được chọn
                    .prev()
                    .find(`.variant_${index}`)
                    .val(value);
            });

            HT.previewVariantTr(variant);
            HT.closeUpdateVariantBox();
        });
    };

    HT.previewVariantTr = (variant) => {
        let option = {
            quantity: variant.quantity,
            price: variant.price,
            sku: variant.sku,
        };

        $.each(option, function (index, value) {
            $(`.updateVariantTr`).prev().find(`.td-${index}`).html(value);
        });

        $(`.updateVariantTr`)
            .prev()
            .find(`.imageSrc`)
            .attr("src", variant.album[0]);
    };

    HT.cancelUpdateVariant = () => {
        $(document).on("click", ".cancelUpdate", function () {
            HT.closeUpdateVariantBox();
        });
    };

    HT.closeUpdateVariantBox = () => {
        $(".updateVariantTr").remove();
    };

    HT.setupSelectMultiple = (callback) => {
        if ($(".selectVariant").length) {
            let count = $(".selectVariant").length;
            $(".selectVariant").each(function () {
                let _this = $(this);
                let attributeCatalogueId = _this.attr("data-catid");
                if (attribute != "") {
                    $.get(
                        "ajax/attribute/loadAttribute",
                        {
                            attribute: attribute,
                            attributeCatalogueId: attributeCatalogueId,
                        },
                        function (data) {
                            if (
                                data.items != "undefined" &&
                                data.items.length
                            ) {
                                for (let i = 0; i < data.items.length; i++) {
                                    var option = new Option(
                                        data.items[i].text,
                                        data.items[i].id,
                                        true,
                                        true
                                    );
                                    _this.append(option).trigger("change");
                                }
                            }
                            if (--count === 0 && callback) {
                                callback();
                            }
                        }
                    );
                }
                HT.getSelect2(_this);
            });
        }
    };

    HT.productVariant = () => {
        // atob() => Giải mã Base64
        // JSON.parse() => chuyển 1 chuỗi JSON (có dấu ngoặc kép bao quanh: '{}') thành 1 đối tượng (ko có dấu ngoặc kép bao quanh: {})
        variant = JSON.parse(atob(variant));
        $(".variant-row").each(function (index, value) {
            let _this = $(this);
            let inputHiddenFields = [
                {
                    name: "variant[quantity][]",
                    class: "variant_quantity",
                    value: variant.quantity[index],
                },
                {
                    name: "variant[sku][]",
                    class: "variant_sku",
                    value: variant.sku[index],
                },
                {
                    name: "variant[price][]",
                    class: "variant_price",
                    value: variant.price[index],
                },
                {
                    name: "variant[barcode][]",
                    class: "variant_barcode",
                    value: variant.barcode[index],
                },
                {
                    name: "variant[file_name][]",
                    class: "variant_filename",
                    value: variant.file_name[index],
                },
                {
                    name: "variant[file_url][]",
                    class: "variant_fileurl",
                    value: variant.file_url[index],
                },
                {
                    name: "variant[album][]",
                    class: "variant_album",
                    value: variant.album[index],
                },
            ];
            for (let i = 0; i < inputHiddenFields.length; i++) {
                _this
                    .find(`.${inputHiddenFields[i].class}`)
                    .val(inputHiddenFields[i].value);
            }

            let album = variant.album[index];
            let variantImage = album
                ? album.split(",")[0]
                : "backend/img/photo.jpg";
            _this
                .find(".td-quantity")
                .html(
                    variant.quantity[index]
                        ? HT.addCommas(variant.quantity[index])
                        : "-"
                );
            _this.find(".td-price").html(HT.addCommas(variant.price[index]));
            _this.find(".td-sku").html(variant.sku[index]);
            _this.find(".imageSrc").attr("src", variantImage);
        });
    };

    HT.addCommas = (nStr) => {
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

    $(document).ready(function () {
        HT.setupProductVariant();
        HT.addVariant();
        HT.niceSelect();
        HT.chooseVariantGroup();
        HT.removeAttribute();
        HT.createProductVariant();
        HT.variantAlbum();
        HT.deleteVariantAlbum();
        HT.switchChange();
        HT.updateVariant();
        HT.cancelUpdateVariant();
        HT.saveUpdateVariant();
        HT.setupSelectMultiple(() => {
            HT.productVariant();
        });
    });
})(jQuery);
