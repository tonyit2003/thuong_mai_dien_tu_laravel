(function ($) {
    "use strict";
    var HT = {};
    var _token = $('meta[name="csrf-token"]').attr("content");
    var typingTimer;
    const donTypingInterval = 1000;

    HT.createMenuCatalogue = () => {
        $(document).on("submit", ".create-menu-catalogue", function (e) {
            e.preventDefault();
            let _form = $(this);
            let option = {
                name: _form.find("input[name=name]").val(),
                keyword: _form.find("input[name=keyword]").val(),
                _token: _token,
            };
            $.ajax({
                url: "ajax/menu/createCatalogue",
                type: "POST",
                data: option,
                dataType: "json",
                success: function (res) {
                    if (res.code == 0) {
                        $(".form-error")
                            .removeClass("text-danger")
                            .removeClass("hidden")
                            .addClass("text-success")
                            .addClass("mb10")
                            .html(res.message)
                            .show();
                        const menuCatalogueSelect = $(
                            "select[name=menu_catalogue_id]"
                        );
                        menuCatalogueSelect.append(
                            `<option value="${res.data.id}">${res.data.name}</option>`
                        );
                    } else {
                        $(".form-error")
                            .removeClass("text-success")
                            .removeClass("hidden")
                            .addClass("text-danger")
                            .addClass("mb10")
                            .html(res.message)
                            .show();
                    }
                },
                beforeSend: function () {
                    _form.find(".error").html("");
                    _form
                        .find(".form-error")
                        .addClass("hidden")
                        .removeClass("mb10");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 422) {
                        let errors = jqXHR.responseJSON.errors;
                        // for in dùng để lặp qua các thuộc tính trong đối tượng
                        for (let field in errors) {
                            let errorMessage = errors[field];
                            errorMessage.forEach(function (message) {
                                $(`.${field}`).html(message);
                            });
                        }
                    }
                },
            });
        });
    };

    HT.createMenuRow = () => {
        $(document).on("click", ".add-menu", function (e) {
            e.preventDefault();
            let _this = $(this);
            $(".menu-wrapper")
                .append(HT.menuRowHtml())
                .find(".notification")
                .hide();
        });
    };

    HT.deleteMenuRow = () => {
        $(document).on("click", ".delete-menu", function () {
            let _this = $(this);
            _this.parents(".menu-item").remove();
            HT.checkMenuItemLength();
        });
    };

    HT.getMenu = () => {
        $(document).on("click", ".menu-module", function () {
            let _this = $(this);
            let option = {
                model: _this.attr("data-model"),
            };
            let target = _this.parents(".panel-default").find(".menu-list");
            let menuRowClass = HT.checkMenuRowExist();
            HT.sendAjaxGetMenu(option, target, menuRowClass);
        });
    };

    HT.menuLinks = (links) => {
        let nav = $("<nav>");
        if (links.length > 3) {
            let paginationUl = $("<ul>").addClass("pagination");
            $.each(links, function (index, link) {
                let liClass = "page-item";
                if (link.active) {
                    liClass += " active disabled";
                } else if (!link.url) {
                    liClass += " disabled";
                }
                let li = $("<li>").addClass(liClass);
                if (link.label == "pagination.previous") {
                    let a = $("<a>")
                        .addClass("page-link")
                        .attr("aria-hidden", true)
                        .html("‹");
                    li.append(a);
                } else if (link.label == "pagination.next") {
                    let a = $("<a>")
                        .addClass("page-link")
                        .attr("aria-hidden", true)
                        .html("›");
                    li.append(a);
                } else if (link.url) {
                    let a = $("<a>")
                        .addClass("page-link")
                        .text(link.label)
                        .attr("href", link.url);
                    li.append(a);
                }
                paginationUl.append(li);
            });
            nav.append(paginationUl);
        }
        return nav;
    };

    HT.chooseMenu = () => {
        $(document).on("click", ".choose-menu", function () {
            let _this = $(this);
            let name = _this.siblings("label").text();
            let canonical = _this.val();
            let $row = HT.menuRowHtml({ name: name, canonical: canonical });
            let isChecked = _this.prop("checked");
            if (isChecked === true) {
                $(".menu-wrapper").append($row).find(".notification").hide();
            } else {
                $(".menu-wrapper").find(`.${canonical}`).remove();
                HT.checkMenuItemLength();
            }
        });
    };

    HT.menuRowHtml = (option) => {
        let $row = $("<div>").addClass(
            `row mb10 menu-item ${
                typeof option != "undefined" ? option.canonical : ""
            }`
        );

        const columns = [
            {
                class: "col-lg-4",
                name: "menu[name][]",
                value: typeof option != "undefined" ? option.name : "",
            },
            {
                class: "col-lg-4",
                name: "menu[canonical][]",
                value: typeof option != "undefined" ? option.canonical : "",
            },
            { class: "col-lg-2", name: "menu[order][]", value: 0 },
        ];

        columns.forEach((col) => {
            let $col = $("<div>").addClass(col.class);
            let $input = $("<input>")
                .attr("type", "text")
                .attr("value", col.value)
                .addClass(
                    `form-control ${
                        col.name == "menu[order][]" ? "int text-right" : ""
                    }`
                )
                .attr("name", col.name);
            $col.append($input);
            $row.append($col);
        });

        let $removeCol = $("<div>").addClass("col-lg-2");
        let $removeRow = $("<div>").addClass("form-row text-center");
        let $a = $("<a>").addClass("delete-menu");
        let $img = $("<img>").attr("src", "backend/img/remove.png");
        let $input = $("<input>")
            .addClass("hidden")
            .attr("name", "menu[id][]")
            .attr("value", 0);

        $a.append($img);
        $removeRow.append($a);
        $removeCol.append($removeRow);
        $removeCol.append($input);
        $row.append($removeCol);

        return $row;
    };

    HT.checkMenuItemLength = () => {
        if ($(".menu-item").length == 0) {
            $(".notification").show();
        }
    };

    HT.getPaginationMenu = () => {
        $(document).on("click", ".page-link", function (e) {
            e.preventDefault();
            let _this = $(this);
            let option = {
                model: _this.parents(".panel-collapse").attr("id"),
                page: _this.text(),
            };
            let target = _this.parents(".menu-list");
            let menuRowClass = HT.checkMenuRowExist();
            HT.sendAjaxGetMenu(option, target, menuRowClass);
        });
    };

    HT.checkMenuRowExist = () => {
        let menuRowClass = $(".menu-item")
            .map(function () {
                let allClasses = $(this)
                    .attr("class")
                    // .split(" "): Chia chuỗi các lớp thành một mảng
                    .split(" ")
                    // .slice(3): Cắt mảng, giữ lại tất cả các phần tử từ vị trí thứ 3 trở đi
                    .slice(3)
                    // .join(" "): Nối các phần tử trong mảng thành một chuỗi duy nhất, với mỗi phần tử được ngăn cách bởi khoảng trắng
                    .join(" ");
                return allClasses;
            })
            .get();
        return menuRowClass;
    };

    HT.renderModelMenu = (object, menuRowClass) => {
        return `
        <div class="m-item">
            <div class="uk-flex uk-flex-middle">
                <input type="checkbox" ${
                    menuRowClass.includes(object.canonical) ? "checked" : ""
                } class="m0 choose-menu" value="${object.canonical}" name=""
                    id="${object.canonical}">
                <label for="${object.canonical}">${object.name}</label>
            </div>
        </div>
        `;
    };

    HT.searchMenu = () => {
        $(document).on("keyup", ".search-menu", function (e) {
            let _this = $(this);
            let keyword = _this.val();
            let option = {
                model: _this.parents(".panel-collapse").attr("id"),
                keyword: keyword,
            };
            // Nếu người dùng gõ phím liên tục, clearTimeout() sẽ hủy bỏ bộ đếm thời gian trước đó, và một bộ đếm thời gian mới sẽ được khởi động lại.
            clearTimeout(typingTimer);
            // setTimeout() tạo ra một bộ đếm thời gian mới và lưu trữ ID của nó vào biến typingTimer. Khi thời gian chờ (donTypingInterval) kết thúc mà không có sự kiện keyup nào khác, hàm callback sẽ được thực hiện.
            typingTimer = setTimeout(() => {
                let menuRowClass = HT.checkMenuRowExist();
                let target = _this.siblings(".menu-list");
                HT.sendAjaxGetMenu(option, target, menuRowClass);
            }, donTypingInterval);
        });
    };

    HT.sendAjaxGetMenu = (option, target, menuRowClass) => {
        $.ajax({
            url: "ajax/dashboard/getMenu",
            type: "GET",
            data: option,
            dataType: "json",
            beforeSend: function () {
                target.html("");
            },
            success: function (res) {
                let html = "";
                for (let i = 0; i < res.data.length; i++) {
                    html += HT.renderModelMenu(res.data[i], menuRowClass);
                }
                // prop("outerHTML") => lấy toàn bộ mã HTML của phần tử, bao gồm cả thẻ mở và thẻ đóng của phần tử.
                html += HT.menuLinks(res.links).prop("outerHTML");
                target.html(html);
            },
            error: function (jqXHR, textStatus, errorThrown) {},
        });
    };

    HT.setupNestable = () => {
        if ($("#nestable2").length) {
            $("#nestable2")
                .nestable({
                    group: 1,
                })
                .on("change", HT.updateNestableOutput);
        }
    };

    HT.updateNestableOutput = (e) => {
        var list = $(e.currentTarget),
            output = $(list.data("output"));
        let json = window.JSON.stringify(list.nestable("serialize"));
        if (json.length) {
            let option = {
                json: json,
                menu_catalogue_id: $("#dataCatalogue").attr("data-catalogueId"),
                _token: _token,
            };
            $.ajax({
                url: "ajax/menu/drag",
                type: "POST",
                data: option,
                dataType: "json",
                success: function (res) {},
            });
        }
    };

    HT.runUpdateNestableOutput = () => {
        updateOutput($("#nestable2").data("output", $("#nestable2-output")));
    };

    HT.expandAndCollapse = () => {
        $("#nestable-menu").on("click", function (e) {
            var target = $(e.target),
                action = target.data("action");
            if (action === "expand-all") {
                $(".dd").nestable("expandAll");
            }
            if (action === "collapse-all") {
                $(".dd").nestable("collapseAll");
            }
        });
    };

    $(document).ready(function () {
        HT.createMenuCatalogue();
        HT.createMenuRow();
        HT.deleteMenuRow();
        HT.getMenu();
        HT.chooseMenu();
        HT.getPaginationMenu();
        HT.searchMenu();
        HT.setupNestable();
        HT.updateNestableOutput();
        HT.runUpdateNestableOutput();
        HT.expandAndCollapse();
    });
})(jQuery);
