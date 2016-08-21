function SlideModel()
{
    var token = Conf.token;
    var id;

    var lang = {
        validation: {
            required: "Required"
        },
        msg: {
            confirm: "Are you sure?"
        }
    };

    var controller = "slide";
    
	var gate = "/";
    this.speed = 500;
    this.current_item = '';
    this.edit_hold = $('#' + controller + '_edit_hold');
    this.page = 0;
    var id = 0;

    // public state handler
    var log = function (msg, s) {
        alert(msg);
        // state_obj.log(controller + "_out", msg, s);
    };

    var tooltip = function (state) {
        $('#page_tooltip').css('display', state ? 'block' : 'none');
    };

    var list = function () {
        $.post(gate + controller + '/list', {a: "list", m: "slide", _token: token}, function (data) {
            token = data.token;
            
            $('#' + controller + '-list').empty().html(data.content);
            $('#' + controller + '-pagination').empty().html(data.pagination);
            
            $('#' + controller + '-list a[data-action=edit]').on('click', edit);
            $('#' + controller + '-list a[data-action=remove]').on('click', remove);
            /*
            $("#slide-list .main_drag_container").unbind('click').sortable({
                        items: '.main_drag',
                        handle: '.s_button_move',
                        axis: 'y',
                        forcePlaceholderSize: true,
                        stop: function (event, ui) {
                            list = $(this).sortable('toArray');
                            $.post(gate, {m: "slide", a: "ord", list: list.join(',').replace(/slide_item_/gi, '')},
                            function (o) {
                                if (o.state) {
                                    // log(o.msg, 2);
                                }
                            }, "json");
                        }
            });*/
        }, "json");
    };
    
    var cancel = function (e) {
        $("#" + controller + "-wrap").empty();
        list();
    };

    var edit = function (e) {
        var id = Number(e.type === "click" ? $(this).attr('data-id') : e);
        $.post(gate + controller + '/get', {id: id, a: "get", m: "slide", _token: token}, function (data) {
            $("#" + controller + "-wrap").html(data.content).foundation();
            // Tabs.init('normal');
            $("#" + controller + "-edit-form").on('submit', save);
            $("#" + controller + "-edit-form button[data-action=cancel]").on('click', cancel);
            CKEDITOR.replaceAll('ckeditor');
        }, "json");
        $("#" + controller + "-menu-wrap").hide();
    };

    /**
     * 
     * @param {type} e
     * @returns {undefined}
     */
    var save = function (e) {
        e.preventDefault();
        updateCKEditor();
        var obj = {_token: token};
        var o = $(this).serializeArray();
        for (var i in o) {
            obj[o[i].name] = o[i].value;
        }
        $.post(gate + controller + "/save", obj, function () {
            list();
        }, "json");
    };

    var remove = function (e) {
        id = Number(e.type === "click" ? $(this).attr('data-id') : e);
        if (confirm(lang.msg.confirm)) {
            $.post(gate + controller + "/remove", {id: id, a: "remove", m: "slide", _token: token}, function (data) {
                token = data.token;
                if (data.state) {
                    log(data.msg, 2);
                    list();
                }
            }, "json");
        }
    };

    var activate = function (id) {
        $.post(gate + controller + "/activate", {a: "activate", m: "page", id: id}, function (o) {
            list();
        }, "json");
    };


    var deactivate = function (id) {
        if (typeof id === "undefined" || id < 0)
            return;
        $.post(gate, {a: "deactivate", m: "page", id: id}, function (o) {
            list();
        }, "json");
    };

    var add = function (o) {
        // cleanTinyMCE();
        // $(".s_module_settings_buttons").hide();
        $.post(gate + 'slide/create', {a: "create", m: "slide", _token: token}, function (data) {
            token = data.token;
            edit(data.id);
        }, "json");
    };

    var attachDefaultAction = function () {
        $('#page_reload').css('cursor', 'pointer').click(function () {
            list(this.key);
        });
    };

    /**
     * init language model, get index list, get list and attach actions
     * @return void
     */
    list();

    //live
    $("#slidelist").on("click", list);
    $("a[data-action='create']").on("click", add);
    
    $('#slide_add_cancel').on("click", function () {
        cleanTinyMCE();
        $("#slide-list").empty();
        list();
    }
    );
    $('#slide_edit_cancel, #slide_close').on("click", function () {
        cleanTinyMCE();
        $("#slide_wrap").empty();
        list();
        $("#slide-menu-wrap").show();
    });
    $("#slide_list .s_button_edit").on("click", edit);
}
var slide_obj = new SlideModel();