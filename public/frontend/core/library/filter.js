(function ($) {
    "use strict";
    var HT = {}; // Khai báo là 1 đối tượng
    var timer;
    var filter = $(".filtering");
    var minPriceValue = 0;
    var maxPriceValue = 100000000;

    HT.priceRange = () => {
        let isInitialized = false;
        $("#price-range").slider({
            step: 50000,
            range: true,
            min: minPriceValue,
            max: maxPriceValue,
            values: [minPriceValue, maxPriceValue],
            slide: function (event, ui) {
                $(".min-value").val(HT.addCommas(ui.values[0]));
                $(".max-value").val(HT.addCommas(ui.values[1]));
            },
            create: function (event, ui) {
                isInitialized = true;
            },
            change: function (event, ui) {
                if (isInitialized) {
                    //   let filterOption = HT.filterOption();
                    HT.sendDataToFilter();
                }
            },
        });
        $("#priceRange").val(
            $("#price-range").slider("values", 0) +
                " - " +
                $("#price-range").slider("values", 1)
        );
    };

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

    HT.filter = () => {
        $(document).on("change", ".filtering", function () {
            HT.sendDataToFilter();
        });
    };

    HT.sendDataToFilter = () => {
        let option = HT.filterOption();

        $.ajax({
            url: "ajax/product/filter",
            type: "GET",
            data: option,
            dataType: "json",
            beforeSend: function () {},
            success: function (res) {
                $(".product-catalogue .product-list").html(res.data);
            },
        });
    };

    HT.filterOption = () => {
        var filterOption = {
            perpage: $("select[name=perpage]").val(),
            sort: $("select[name=sort]").val(),
            // rate: $('input[name="rate[]"]:checked')
            //     .map(function () {
            //         return this.value;
            //     })
            //     .get(),
            price: {
                price_min: $(".min-value").val(),
                price_max: $(".max-value").val(),
            },
            productCatalogueId: $(".product_catalogue_id").val(),
            attributes: {},
        };

        $(".filterAttribute:checked").each(function () {
            let attributeId = $(this).val();
            let attributeGroup = $(this).attr("data-group");

            if (!filterOption.attributes.hasOwnProperty(attributeGroup)) {
                filterOption.attributes[attributeGroup] = [];
            }

            filterOption.attributes[attributeGroup].push(attributeId);
        });

        return filterOption;
    };

    HT.setMinMaxValue = () => {
        if ($(".min-value").length && $(".max-value").length) {
            $(".min-value").val(HT.addCommas(minPriceValue));
            $(".max-value").val(HT.addCommas(maxPriceValue));
        }
    };

    $(document).ready(function () {
        HT.setMinMaxValue();
        HT.priceRange();
        HT.filter();
    });
})(jQuery);
