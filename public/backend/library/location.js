(function ($) {
    "use strict";
    var HT = {};

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
        $.ajax({
            url: "ajax/location/getLocation",
            type: "GET",
            data: option,
            dataType: "json",
            success: function (res) {
                // thay thế nội dung HTML của các phần tử được chọn bằng nội dung được truyền vào
                $("." + option.target).html(res.html);

                if (district_id != "" && option.target == "district") {
                    // tìm class district và gán giá trị thành district_id và bắt sự kiện onChange
                    $(".district").val(district_id).trigger("change");
                }

                if (ward_id != "" && option.target == "ward") {
                    // tìm class ward và gán giá trị thành ward_id và bắt sự kiện onChange
                    $(".ward").val(ward_id).trigger("change");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Lỗi: " + textStatus + " " + errorThrown);
            },
        });
    };

    HT.loadCity = () => {
        if (province_id != "") {
            // tìm class province và gán giá trị thành province_id và bắt sự kiện onChange
            $(".province").val(province_id).trigger("change");
        }
    };

    $(document).ready(function () {
        HT.getLocation();
        HT.loadCity();
    });
})(jQuery);
