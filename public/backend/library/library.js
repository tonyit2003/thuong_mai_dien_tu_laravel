(function ($) {
    "use strict";
    var HT = {};
    var document = $(document);

    HT.switchery = () => {
        $(".js-switch").each(function () {
            var switchery = new Switchery(this, {
                color: "#1AB394",
            });
        });
    };

    document.ready(function () {
        HT.switchery();
    });
})(jQuery);
