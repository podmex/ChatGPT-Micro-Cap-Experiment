function ItemModel()
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

    var controller = "item";

    this.speed = 500;
    this.current_item = '';
    this.edit_hold = $('#' + controller + '_edit_hold');
    this.page = 0;
    var gate = "/";
    
    var log = function (msg, s) {
        alert(msg);
        // state_obj.log(controller + "_out", msg, s);
    };

    var tooltip = function (state) {
        $('#page_tooltip').css('display', state ? 'block' : 'none');
    };

    var list = function () {
        $.post(gate + controller + '/list', {_token: token}, function (data) {
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
    
    var listDate = function () {
        $.post(gate + controller + '/date/list', {id: id, _token: token}, function (data) {
            token = data.token;
            $("#" + controller + "-edit-form div[role=datelist]").empty().html(data.content);
            $("#" + controller + "-edit-form div[role=datelist] button[data-action=date-remove]").on('click', dateRemove);
            $("#" + controller + "-edit-form div[role=datelist] button[data-action=date-add]").on('click', dateCreate);
        }, "json");
    };

    var cancel = function (e) {
        $("#" + controller + "-wrap").empty();
        list();
    };

    var edit = function (e) {
        var id = Number(e.type === "click" ? $(this).attr('data-id') : e);
        $.post(gate + controller + '/get', {id: id, _token: token}, function (data) {
            token = data.token;
            $("#" + controller + "-wrap").html(data.content).foundation();
		    CKEDITOR.replaceAll('ckeditor');
            $("#" + controller + "-edit-form").on('submit', save);
            $("#" + controller + "-edit-form button[data-action=cancel]").on('click', cancel);
            $("#" + controller + "-edit-form button[data-action=date-remove]").on('click', dateRemove);
            $("#" + controller + "-edit-form button[data-action=date-add]").on('click', dateCreate);
            
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
            $.post(gate + controller + "/remove", {id: id, _token: token}, function (data) {
                token = data.token;
                if (data.state) {
                    // log(data.msg, 2);
                    list();
                }
            }, "json");
        }
    };
    
    var dateRemove = function (e) {
        id = Number(e.type === "click" ? $(this).attr('data-id') : e);
        if (confirm(lang.msg.confirm)) {
            $.post(gate + controller + "/date/remove", {id: id, _token: token}, function (data) {
                token = data.token;
                if (data.state) {
                    $('div[data-date-id=' + id + ']').remove();
                    // log(data.msg, 2);
                    list();
                    // listDate();
                }
            }, "json");
        }
    };
    
    var dateCreate = function (e) {
        id = Number(e.type === "click" ? $(this).attr('data-id') : e);
        var date = $('input[name=date-add]').val();
        if (true) {
            $.post(gate + controller + "/date/create", {id: id, date: date, _token: token}, function (data) {
                token = data.token;
                if (data.state) {
                    // log(data.msg, 2);
                    list();
                    listDate();
                }
            }, "json");
        }
    };

    var activate = function (id) {
        $.post(gate + controller + "/activate", {id: id}, function (o) {
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
        $.post(gate + controller + '/create', {_token: token}, function (data) {
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

    $("#" + controller + "list").on("click", list);
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
var item_obj = new ItemModel();