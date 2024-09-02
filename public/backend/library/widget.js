(function ($) {
    "use strict";
    var HT = {};
    var _token = $('meta[name="csrf-token"]').attr("content");
    var typingTimer;
    const donTypingInterval = 300;

    HT.searchModel = () => {
        $(document).on("keyup", ".search-model", function (e) {
            e.preventDefault();
            let _this = $(this);
            if ($("input[type=radio]:checked").length === 0) {
                alert(chooseModuleMessage);
                _this.val("");
                return false;
            }
            let keyword = _this.val();
            let option = {
                model: $("input[type=radio]:checked").val(),
                keyword: keyword,
            };
            if (keyword.length > 0) {
                HT.sendAjax(option);
            } else {
                $(".ajax-search-result").html("").hide();
            }
        });
    };

    HT.chooseModel = () => {
        $(document).on("change", ".input-radio", function () {
            let _this = $(this);
            let keyword = $(".search-model").val();
            let option = {
                model: _this.val(),
                keyword: keyword,
            };
            $(".search-model-result").html("");
            if (keyword.length > 0) {
                HT.sendAjax(option);
            } else {
                $(".ajax-search-result").html("").hide();
            }
        });
    };

    HT.sendAjax = (option) => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            $.ajax({
                url: "ajax/dashboard/findModelObject",
                type: "GET",
                data: option,
                dataType: "json",
                success: function (res) {
                    let html = HT.renderSearchResult(res);
                    if (html.length) {
                        $(".ajax-search-result").html(html).show();
                    } else {
                        $(".ajax-search-result").hide();
                    }
                },
                beforeSend: function () {
                    $(".ajax-search-result").html("").hide();
                },
            });
        }, donTypingInterval);
    };

    HT.renderSearchResult = (data) => {
        let html = "";
        if (data.length) {
            for (let i = 0; i < data.length; i++) {
                let flag = $(`#model-${data[i].id}`).length ? 1 : 0;
                html += `
                <button class="ajax-search-item" data-flag="${flag}" data-canonical="${
                    data[i].languages[0].pivot.canonical
                }" data-id="${data[i].id}" data-image="${
                    data[i].image
                }" data-name="${data[i].languages[0].pivot.name}">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <span>${data[i].languages[0].pivot.name}</span>
                        <div class="auto-icon">
                            ${flag == 1 ? HT.setChecked() : ""}
                        </div>
                    </div>
                </button>
                `;
            }
        }
        return html;
    };

    HT.setChecked = () => {
        return `<img width="16px" src="backend/img/check.png">`;
    };

    HT.unfocusSearchBox = () => {
        $(document).on("click", "html", function (e) {
            if (
                !$(e.target).hasClass("search-model-result") &&
                !$(e.target).hasClass("search-model")
            ) {
                $(".ajax-search-result").html("");
            }
        });
        $(document).on("click", ".ajax-search-result", function (e) {
            //  sự kiện click sẽ chỉ được xử lý trong sự kiện click của .ajax-search-result mà không kích hoạt bất kỳ sự kiện click nào được đính kèm vào các phần tử cha của nó. => $(".ajax-search-result").html(""); sẽ không được gọi,
            e.stopPropagation();
        });
    };

    HT.addModel = () => {
        $(document).on("click", ".ajax-search-item", function (e) {
            e.preventDefault();
            let _this = $(this);
            // lấy tất cả dữ liệu có dạng data-* của phần tử => trả về một đối tượng chứa tất cả các cặp khóa-giá trị của dữ liệu.
            let data = _this.data();
            if (data.flag == 0) {
                _this.find(".auto-icon").html(HT.setChecked());
                _this.attr("data-flag", 1).data("flag", 1); // Cập nhật lại giá trị .data()
                $(".search-model-result").append(HT.modelTemplate(data)).show();
            } else {
                $(`#model-${data.id}`).remove();
                _this.find(".auto-icon").html("");
                _this.attr("data-flag", 0).data("flag", 0); // Cập nhật lại giá trị .data()
            }
        });
    };

    HT.modelTemplate = (data) => {
        return `
        <div class="search-result-item" id="model-${data.id}" data-modelId="${data.id}">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="uk-flex uk-flex-middle">
                    <span class="image img-cover">
                        <img src="${data.image}" alt="">
                    </span>
                    <span class="name">${data.name}</span>
                    <div class="hidden">
                        <input type="text" name="modelItem[id][]" value="${data.id}">
                        <input type="text" name="modelItem[name][]" value="${data.name}">
                        <input type="text" name="modelItem[image][]" value="${data.image}">
                    </div>
                </div>
                <div class="deleted">
                    <img src="backend/img/remove.png">
                </div>
            </div>
        </div>
        `;
    };

    HT.removeModel = () => {
        $(document).on("click", ".deleted", function () {
            let _this = $(this);
            _this.parents(".search-result-item").remove();
        });
    };

    $(document).ready(function () {
        HT.searchModel();
        HT.chooseModel();
        HT.unfocusSearchBox();
        HT.addModel();
        HT.removeModel();
    });
})(jQuery);
