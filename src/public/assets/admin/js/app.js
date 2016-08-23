var updateCKEditor = function () {
    try {
        for (var instanceName in CKEDITOR.instances) {
            CKEDITOR.instances[instanceName].updateElement();
        }
    } catch (e) {
        alert(e);
    }
};

$(document).foundation();