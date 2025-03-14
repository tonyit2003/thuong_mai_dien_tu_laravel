(function ($) {
    "use strict";
    var HT = {};
    var counter = 1;

    HT.addSlide = (type) => {
        $(document).on("click", ".addSlide", function (e) {
            e.preventDefault();
            if (typeof type == "undefined") {
                type = "Images";
            }
            var finder = new CKFinder();
            finder.resourceType = type;
            finder.selectActionFunction = function (fileUrl, data, allFiles) {
                let html = "";
                for (var i = 0; i < allFiles.length; i++) {
                    var image = allFiles[i].url;
                    html += HT.renderSlideItemHtml(image);
                }
                $(".slide-list").append(html);
                HT.checkSlideNotification();
            };
            finder.popup();
        });
    };

    HT.renderSlideItemHtml = (image) => {
        let tab_1 = "tab_" + counter;
        let tab_2 = "tab_" + (counter + 1);
        counter += 2;
        return `
        <div class="col-lg-12 ui-state-default">
            <div class="slide-item mb20">
                <div class="row custom-row">
                    <div class="col-lg-3 mb10">
                        <span class="slide-image img-cover">
                            <img src="${image}" alt="">
                            <div class="change-img text-center">
                                ${changeImage}
                            </div>
                            <input type="hidden" name="slide[image][]" value="${image}">
                            <button class="deleteSlide"><i class="fa fa-trash"></i></button>
                        </span>
                    </div>
                    <div class="col-lg-9">
                        <div class="tabs-container">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#${tab_1}" aria-expanded="true">
                                        ${generalInfo}</a>
                                </li>
                                <li class=""><a data-toggle="tab" href="#${tab_2}"
                                        aria-expanded="false">${seo}</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="${tab_1}" class="tab-pane active">
                                    <div class="panel-body">
                                        <div class="label-text mb5"> ${description}
                                        </div>
                                        <div class="form-row mb10">
                                            <textarea name="slide[description][]" class="form-control"></textarea>
                                        </div>
                                        <div class="form-row form-row-url">
                                            <input type="text" name="slide[canonical][]" class="form-control"
                                                placeholder="${url}">
                                            <div class="overlay">
                                                <div class="uk-flex uk-flex-middle">
                                                    <label for="input_${tab_1}">${openInNewTab}</label>
                                                    <input type="hidden" name="slide[window][]"
                                                        value="none">
                                                    <input type="checkbox" class="slide-window"
                                                        id="input_${tab_1}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="${tab_2}" class="tab-pane">
                                    <div class="panel-body">
                                        <div class="label-text mb5">${imageTitle}
                                        </div>
                                        <div class="form-row form-row-url slide-seo-tab">
                                            <input type="text" name="slide[name][]" class="form-control"
                                                placeholder="${imageTitle}">
                                        </div>
                                        <div class="label-text mb5 mt12">
                                            ${imageDescription}
                                        </div>
                                        <div class="form-row form-row-url slide-seo-tab">
                                            <input type="text" name="slide[alt][]" class="form-control"
                                                placeholder="${imageDescription}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </div>
        `;
    };

    HT.changeSlideImage = () => {
        $(document).on("click", ".change-img", function () {
            let input = $(this);
            let type = "Images";
            HT.browseServerSlideImage(input, type);
        });
    };

    HT.browseServerSlideImage = (object, type) => {
        if (typeof type == "undefined") {
            type = "Images";
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data) {
            object.siblings("img").attr("src", fileUrl);
            object.siblings("input[type=hidden]").val(fileUrl);
        };
        finder.popup();
    };

    HT.deleteSlide = () => {
        $(document).on("click", ".deleteSlide", function () {
            let _this = $(this);
            _this.parents(".ui-state-default").remove();
            HT.checkSlideNotification();
        });
    };

    HT.checkSlideNotification = () => {
        let slideItem = $(".slide-item");
        if (slideItem.length) {
            $(".slide-notification").hide();
        } else {
            $(".slide-notification").show();
        }
    };

    HT.checkWindow = () => {
        $(document).on("click", ".slide-window", function () {
            let _this = $(this);
            if (_this.is(":checked")) {
                _this.siblings('input[name="slide[window][]"]').val("_blank");
            } else {
                _this.siblings('input[name="slide[window][]"]').val("none");
            }
        });
    };

    $(document).ready(function () {
        HT.addSlide();
        HT.deleteSlide();
        HT.checkSlideNotification();
        HT.changeSlideImage();
        HT.checkWindow();
    });
})(jQuery);
