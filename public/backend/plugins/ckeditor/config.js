/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    (config.filebrowserBrowseUrl =
        BASE_URL + "backend/plugins/ckfinder_2/ckfinder.html"),
        (config.filebrowserImageBrowseUrl =
            BASE_URL + "backend/plugins/ckfinder_2/ckfinder.html?type=Images"),
        (config.filebrowserFlashBrowseUrl =
            BASE_URL + "backend/plugins/ckfinder_2/ckfinder.html?type=Flash");
    (config.filebrowserUploadUrl =
        BASE_URL +
        "backend/plugins/ckfinder_2/core/connector/php/connector.php?command=QuickUpload&type=Files"),
        (config.filebrowserImageUploadUrl =
            BASE_URL +
            "backend/plugins/ckfinder_2/core/connector/php/connector.php?command=QuickUpload&type=Images"),
        (config.filebrowserFlashUploadUrl =
            BASE_URL +
            "backend/plugins/ckfinder_2/core/connector/php/connector.php?command=QuickUpload&type=Flash");
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
};
