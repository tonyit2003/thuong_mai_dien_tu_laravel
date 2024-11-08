(function ($) {
    "use strict";
    var HT = {}; // Khai báo là 1 đối tượng
    var timer;
    var _token = $('meta[name="csrf-token"]').attr("content");

    HT.review = () => {
        var modal = UIkit.modal("#review");

        $(document).on("click", ".btn-send-review", function () {
            let option = {
                score: $(".rate:checked").val(),
                content: $(".review-textarea").val(),
                variant_uuid: $(".variant_uuid").val(),
                _token: _token,
                parent_id: $(".review_parent_id").val(),
            };

            $.ajax({
                url: "ajax/review/create",
                type: "POST",
                data: option,
                dataType: "json",
                beforeSend: function () {},
                success: function (res) {
                    toastr.clear();
                    if (res.code === 10) {
                        toastr.success(res.messages, "SUCCESS");
                        modal.hide();
                        location.reload();
                    } else if (res.code == 11) {
                        toastr.error(res.messages, "ERROR");
                    } else if (res.code == 12) {
                        toastr.warning(res.messages, "WARNING");
                        modal.hide();
                    }
                },
            });
        });
    };

    $(document).ready(function () {
        HT.review();
    });
})(jQuery);
