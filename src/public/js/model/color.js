function ColorModel()
{
    var handle = this;
    var self = this;
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

    var controller = "color";
    
    this.speed = 500;
    this.current_item = '';
    this.edit_hold = $('#' + controller + '_edit_hold');
    this.page = 0;
    var gate = "/";

    // public state handler
    var log = function (msg, s) {
        alert(msg);
        // state_obj.log(controller + "_out", msg, s);
    };
    
    var cancel = function (e) {
        $("#" + controller + "-wrap").empty();
        list();
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
        }, "json");
    };

    var edit = function (e) {
        var id = Number(e.type === "click" ? $(this).attr('data-id') : e);
        $.post(gate + controller + '/get', {id: id, _token: token}, function (data) {
            $("#" + controller + "-wrap").html(data.content).foundation();
            $("#" + controller + "-edit-form").on('submit', save);
            $("#" + controller + "-edit-form button[data-action=cancel]").on('click', cancel);
        }, "json");
        $("#" + controller + "-menu-wrap").hide();
    };

    /**
     * 
     * @param {type} e
     * @returns {undefined}
     */
    var save = function (e)
    {
        e.preventDefault();
        var obj = {_token: token};
        var o = $(this).serializeArray();
        for (var i in o) {
            obj[o[i].name] = o[i].value;
        }
        $.post(gate + controller + "/save", obj, function (o) {
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
        $.post(gate + controller + '/create', {a: "create", m: "slide", _token: token}, function (data) {
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

    $("a[data-action='create']").on("click", add);
}
var color_obj = new ColorModel();