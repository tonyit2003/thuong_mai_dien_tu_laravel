(function ($) {
    "use strict";
    var HT = {};
    var _token = $('meta[name="csrf-token"]').attr("content");

    HT.switchery = () => {
        $(".js-switch").each(function () {
            var switchery = new Switchery(this, {
                color: "#1AB394",
                size: "small",
            });
        });
    };

    HT.select2 = () => {
        if ($(".setupSelect2").length) {
            $(".setupSelect2").select2();
        }
    };

    HT.sortui = () => {
        $("#sortable").sortable();
        $("#sortable").disableSelection();
    };

    HT.changeStatus = () => {
        // kiểm tra xem có tồn tại các phần tử có class là "status" hay không?
        if ($(".status").length) {
            // bắt sự kiện change của tất cả các phần tử có class là status
            $(document).on("change", ".status", function (e) {
                let _this = $(this);
                let option = {
                    value: $(this).val(), // lấy giá trị phần tử
                    modelId: $(this).attr("data-modelId"), // lấy giá trị thuộc tính data-modelId
                    model: $(this).attr("data-model"),
                    field: $(this).attr("data-field"),
                    _token: _token,
                };

                $.ajax({
                    url: "ajax/dashboard/changeStatus",
                    type: "POST",
                    data: option,
                    dataType: "json",
                    success: function (res) {
                        let inputValue = option.value == 1 ? 0 : 1;
                        if (res.flag == true) {
                            _this.val(inputValue); // thiết lập giá trị value cho phần tử
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

    HT.checkAll = () => {
        if ($("#checkAll").length) {
            // lấy phần tử có id là checkAll
            $(document).on("click", "#checkAll", function () {
                let isChecked = $(this).prop("checked"); // prop("checked"): lấy giá trị checked
                $(".checkBoxItem").prop("checked", isChecked); // prop("checked", isChecked): thiết lập giá trị checked
                $(".checkBoxItem").each(function () {
                    HT.changeBackground($(this));
                });
            });
        }
    };

    HT.checkBoxItem = () => {
        if ($(".checkBoxItem").length) {
            // bắt sự kiện click của tất cả các phần tử có class là checkBoxItem
            $(document).on("click", ".checkBoxItem", function () {
                HT.changeBackground($(this));
                HT.allCheck();
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

    HT.allCheck = () => {
        let allChecked =
            $(".checkBoxItem:checked").length === $(".checkBoxItem").length;
        $("#checkAll").prop("checked", allChecked);
    };

    HT.int = () => {
        // gán các sự kiện cho các trường nhập liệu có class .int.
        $(document).on("change keyup blur", ".int", function () {
            let _this = $(this);
            let value = _this.val();
            if (value === "") {
                $(this).val("0");
            }
            // Loại bỏ tất cả các dấu chấm trong giá trị hiện tại
            value = value.replace(/\./gi, "");
            // Định dạng lại giá trị với dấu chấm để phân cách hàng nghìn
            _this.val(HT.addCommas(value));
            // Kiểm tra nếu giá trị không phải là số (isNaN), đặt lại giá trị là 0
            if (isNaN(value)) {
                _this.val("0");
            }
        });

        $(document).on("keydown", ".int", function (e) {
            let _this = $(this);
            let data = _this.val();
            // Nếu giá trị hiện tại là 0, kiểm tra phím nhấn:
            if (data == 0) {
                let unicode = e.keyCode || e.which;
                // Nếu phím nhấn không phải là phím dấu chấm (keycode 190), đặt lại giá trị trường nhập liệu là rỗng
                if (unicode != 190) {
                    _this.val("");
                }
            }
        });
    };

    // định dạng một chuỗi số với dấu chấm để phân cách hàng nghìn.
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

    $(document).ready(function () {
        HT.switchery();
        HT.select2();
        HT.changeStatus();
        HT.checkAll();
        HT.checkBoxItem();
        HT.changeStatusAll();
        HT.sortui();
        HT.int();
    });
})(jQuery);
