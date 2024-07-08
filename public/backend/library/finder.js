(function ($) {
    "use strict";
    var HT = {};

    HT.setupCkeditor = () => {
        if ($(".ck-editor")) {
            $(".ck-editor").each(function () {
                let editor = $(this);
                let elementId = editor.attr("id");
                let elementHeight = editor.attr("data-height");
                HT.ckeditor4(elementId, elementHeight);
            });
        }
    };

    HT.uploadAlbum = () => {
        $(document).on("click", ".upload-picture", function (e) {
            HT.browseServerAlbum();
            e.preventDefault();
        });
    };

    HT.multipleUploadImageCkeditor = () => {
        $(document).on("click", ".multipleUploadImageCkeditor", function (e) {
            let object = $(this);
            let target = object.attr("data-target");
            HT.browseServerCkeditor(object, "Images", target);
            e.preventDefault();
        });
    };

    HT.ckeditor4 = (elementId, elementHeight) => {
        if (typeof elementHeight == "undefined") {
            elementHeight = 500;
        }
        CKEDITOR.replace(elementId, {
            height: elementHeight,
            removeButtons: "",
            entities: true,
            allowedContent: true,
            toolbarGroups: [
                { name: "clipboard", groups: ["clipboard", "undo"] },
                {
                    name: "editing",
                    groups: ["find", "selection", "spellchecker"],
                },
                { name: "links" },
                { name: "insert" },
                { name: "forms" },
                { name: "tools" },
                { name: "document", groups: ["mode", "document", "doctools"] },
                { name: "colors" },
                { name: "others" },
                "/",
                { name: "basicstyles", groups: ["basicstyles", "cleanup"] },
                {
                    name: "paragraph",
                    groups: ["list", "indent", "blocks", "align", "bidi"],
                },
                { name: "styles" },
            ],
        });
    };

    HT.uploadImageToInput = () => {
        $(".upload-image").click(function () {
            let input = $(this);
            let type = input.attr("data-type");
            HT.setupCkFinder2(input, type);
        });
    };

    // thiết lập CKFinder
    HT.setupCkFinder2 = (object, type) => {
        if (typeof type == "undefined") {
            type = "Images";
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data) {
            object.val(fileUrl);
        };
        finder.popup();
    };

    HT.uploadImageAvatar = () => {
        // Chỉ áp dụng sự kiện click cho các phần tử có mặt trong DOM tại thời điểm sự kiện được gán.
        $(".img-target").click(function () {
            let input = $(this);
            let type = "Images";
            HT.browseServerAvatar(input, type);
        });
    };

    HT.browseServerAvatar = (object, type) => {
        if (typeof type == "undefined") {
            type = "Images";
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data) {
            object.find("img").attr("src", fileUrl);
            // tìm tất cả các phần tử "anh chị em" (nằm cùng cấp) của object mà là các phần tử <input>
            object.siblings("input").val(fileUrl);
        };
        finder.popup();
    };

    HT.browseServerCkeditor = (object, type, target) => {
        if (typeof type == "undefined") {
            type = "Images";
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        // upload nhiều hình ảnh vào CkEditor
        finder.selectActionFunction = function (fileUrl, data, allFiles) {
            let html = "";
            for (var i = 0; i < allFiles.length; i++) {
                var image = allFiles[i].url;

                html +=
                    '<div class="image-content"><figure><img src="' +
                    image +
                    '" alt="' +
                    image +
                    '"><figcaption>Nhập vào mô tả cho ảnh</figcaption></figure></div>';
            }
            // chèn nội dung HTML vào vị trí hiện tại của con trỏ (cursor) trong trình soạn thảo.
            CKEDITOR.instances[target].insertHtml(html);
        };
        finder.popup();
    };

    HT.browseServerAlbum = () => {
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
                    '"><input type="hidden" name="album[]" value="' +
                    image +
                    '"></span><button class="delete-image"><i class="fa fa-trash"></i></button></div></li>';
            }
            $(".click-to-upload").addClass("hidden");
            $("#sortable").append(html); // append() => chèn nội dung của biến html vào cuối (bên trong) phần tử có id là sortable.
            $(".upload-list").removeClass("hidden");
        };
        finder.popup();
    };

    HT.deletePicture = () => {
        $(document).on("click", ".delete-image", function (e) {
            let _this = $(this);
            // parents() => tìm phần tử cha gần nhất có lớp ui-state-default của phần tử được nhấp vào (phần tử có lớp delete-image)
            // remove() => xóa phần tử cha này (ui-state-default) khỏi DOM.
            _this.parents(".ui-state-default").remove();
            if ($(".ui-state-default").length == 0) {
                $(".click-to-upload").removeClass("hidden");
                $(".upload-list").addClass("hidden");
            }
            e.preventDefault();
        });
    };

    $(document).ready(function () {
        HT.uploadImageToInput();
        HT.setupCkeditor();
        HT.uploadImageAvatar();
        HT.multipleUploadImageCkeditor();
        HT.uploadAlbum();
        HT.deletePicture();
    });
})(jQuery);
