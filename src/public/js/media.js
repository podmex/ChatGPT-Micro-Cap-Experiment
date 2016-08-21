// media
function MediaModel() {

    var handle = this;
    var self = this;
    var token = null;

    var admin = 0;
    var page = 0;
    var type = 0;
    var item_id = 0;
    
    var controller = 'multimedia';
    var list_hold = $('#media_list_hold');
    var detail_list_hold = $('#media_list_hold');
    var info_hold = $('#media_info');

    var media_id = false;
    var reorder = 0;
    var list_view = 'thumb';
    var gate = '/';

    var msg = {
        deleted: "Media item deleted",
        confirm: "Are you sure?",
        loading: "Loading..."
    };

    var state = state_obj || {};
    // public state handler
    var log = function (msg, type) {
        state.log(self.controller + "_out", msg, type);
    };

    var render = function (hold, content) {
        hold.html(content);
    };

    this.tooltip = function (state) {
        if (state)
            $("#category_tooltip").show();
        else
            $("#category_tooltip").hide();
    };
    this.tooltip_category = function (state) {
        if (state)
            $("#category_tooltip").show();
        else
            $("#category_tooltip").hide();
    };

    this.close_info = function (id) {
        if (id == undefined)
            return;
        $("#media_detail_hold_" + id).empty();
        $("#media_detail_hold_" + id).hide();
        self.media_id = 0;
    };

    this.getInf = function (id) {
        if (id == undefined || id < 0)
            return;
        if (self.media_id != 0) {
            self.close_info(self.media_id);
        }
        self.media_id = id;
        $("#media_detail_hold_" + id).show();
        $.post('/cms/', {m: 'media', a: 'info', type: 'media', id: id, hide_add: media.hide_add_to_item, hide_actions: media.hide_actions}, function (data) {
            handle.render($('#media_detail_hold_' + id), data);
            $("a#media_info_" + id).css("cursor", "pointer").click(function (e) {
                e.preventDefault();
                handle.close_info(id);
            });
        }
        );
        //this.render( $('#media_detail_hold_'+id) , this.msg.loading );
    };

    this.getRand = function () {
        return Math.random(99999999);
    };
    this.show_all = function (type) {
        this.type = type;
        this.all = 1;
        getMediaDetailList();
    };
    this.save_cancel = function (type) {
        $('#edit_title_hold').hide(this.speed);
    };

    this.admin_view = function (cat_id) {
        if (cat_id == undefined || cat_id < 0)
            return;
        this.init(0, cat_id);
    };

    this.get_page = function (page) {
        if (page == undefined || page < 0)
            return;
        this.page = page;
        this.getPagination();
    };
    
    var getPagination = function () {
        all = 0;
        var pagination_hold = $('.media_pagination');
        //this.render(pagination_hold, this.msg.loading);
        var obj = {
            page: page,
            type: type,
            item_id: item_id,
            _token: token
        };
        $.post(gate + 'media/getMediaPagination', obj, function (data) {
            token = data.token;
            render(pagination_hold, data.pagination);
            getMediaDetailList();
        }, "json");
    };

    this.init = function (page, p_item_id, category_id) {
        // console.log(page + ' ' + item_id + ' ' + category_id);
        token = Conf.token;
        $("#edit_title_hold").hide();
        if (typeof page !== "undefined") {
            this.page = page;
        }
        if (typeof category_id !== "undefined") {
            self.type = category_id;
            type = category_id;
        }
        if (typeof p_item_id !== "undefined") {
            self.item_id = p_item_id;
            item_id = p_item_id;
        }
        getPagination();
    };

    var list = function () {
        getMediaDetailList();
    };
    
    var getMediaDetailList = function () {
        var hold = media.detail_list_hold;
        var obj = {
            all: all,
            page: page,
            category_id: type,
            reorder: reorder,
            admin: admin,
            item_id: item_id,
            list_view: list_view,
            _token: token
        };
        $.post(gate + 'media/getMediaDetailList', obj, function (data) {
            token = data.token;
            render($('#media-list'), data.content);
            attach_detail_list_action();
        }, "json");
    };
    
    var attach_detail_list_action = function () {
        $('.media-listing a[data-action=edit]').on('click', function (e) {
            e.preventDefault();
        });
        
        $('.media-listing a[data-action=delete]').on('click', remove);
        
        if ($('.thumb_render').length > 0) {
            $.each($('.thumb_render'), function () {
                var id = $(this).attr("id").split('_')[1];
                $('img', this).unbind("click").bind("click", function () {
                    media_getInf(id);
                });
                $('ul > li.img_shown', this).unbind("click").bind("click", function () {
                    media_make_hidden(id);
                });
                $('ul > li.img_hidden', this).unbind("click").bind("click", function () {
                    media_make_visible(id);
                });
                $('ul > li.img_denied', this).unbind("click").bind("click", function () {
                    media_refuse(id);
                });
                $('ul > li.img_approved', this).unbind("click").bind("click", function () {
                    media_approve(id);
                });
                $('ul > li.img_edit', this).unbind("click").bind("click", function () {
                    media_edit_title(id);
                });
            }
            );
        }
        // reorder
        /*
        $("#sort_wrap").sortable({
            forcePlaceholderSize: true,
            stop: function (event, ui) {
                new_list = $(this).sortable('toArray');
                $.post(gate + '&action=reorder', {item_id: handle.item_id, category_id: handle.type, act: "reorder", reorder_listing: new_list.join(',').replace(/page_subitem_item_/gi, '')},
                function (o) {
                    if (o.state) {
                        //log(o.result_message, 2);
                    }
                }, "json");
            }
        });
        */
    };
    
    /**
     * delete an item
     * @todo make script check for clones and existense in documents
     * @param id
     * @return void
     */
    var remove = function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        if (confirm(msg.confirm)) {
            $.post(gate + 'media/remove', {id: id, _token: token}, function (data) {
                if (data.state) {
                    list();
                    
                    //$('#' + controller + '_out').show(speed).text(msg.deleted);
                    //$('#single_' + controller + '_hold').empty();
                    //$("#media_list_hold #item_" + id).remove();
                }
            }, "json");
        }
    };
    
    //sort update
    this.sortUpdate = function ()
    {
        if (this.item_id == 0) {
            return;
        }
        var dragEls = $('#media_list_hold > ul.sortable_list li');
        var items = [];
        var unique = {};
        jQuery.each(dragEls, function () {
            var id = $(this).attr('id').split('_')[1];
            if (unique[ id ] == undefined) {
                unique[ id ] = 1;
                items.push(id);
            }
        });
        items = items.join("_");
        alert(items);
        //$.post(gate+'&act=update_media_order', {item_id:this.item_id, items: items});
    };
    //edit title
    this.edit_title = function (id)
    {
        if (id == undefined)
            return;
        if (media_id != 0) {
            this.close_info(media_id);
        }

        image_id = id;
        $.post(this.gate + '&act=json_get_info', {id: id}, function (reply) {
            for (var i in o = conf.lang_list.split(",")) {
                $('#edit_title_hold_' + id + '_title_' + o[i]).val(reply.info.title[o[i]]);
            }
            $("#media_edit_title_hold_" + id).show();

            //submit
            $("#edit_title_" + id + "_form").submit(function (e) {
                e.preventDefault();
                handle.save_title();
            }
            );
            //cancel
            $("#edit_title_hold_cancel_" + id).bind("click", function (e) {
                e.preventDefault();
                $("#media_edit_title_hold_" + id).hide();
                //handle.reset_edit_title_form();
            }
            );
            $("#close_title_button_" + id).css("cursor", "pointer").click(function (e) {
                e.preventDefault();
                $("#media_edit_title_hold_" + id).hide();
            });
            //tabs
            Tabs.init('normal');
        }, "json");
    };
    //edit title
    this.save_title = function () {
        var obj = {id: image_id};
        for (var i in o = conf.lang_list.split(",")) {
            obj['title_' + o[i] ] = $("#edit_title_hold_" + image_id + "_title_" + o[i]).val();
        }
        $.post(gate + '&act=edit_title', obj, function (data) {
            if (data.status) {
                handle.reset_edit_title_form();
                handle.getPagination();
            }
            ;
            handle.reset_state();
        },
                "json"
                );
    };

    this.reset_state = function () {
        $('#' + this.controller + '_out').text('');
    };

    this.show_convert = function () {
        $('#convert_media').show(this.speed);
    };

    this.hide_convert = function () {
        $('#convert_media').hide(this.speed);
    };

    this.convert_media = function ()
    {
        var obj = {
            mode: this.convert_mode,
            item_id: this.media_id
        };
        switch (this.convert_mode) {
            case 1:
                obj.width = $('input#video_width').val();
                obj.height = $('input#video_height').val();
                obj.fps = $('input#video_fps').val();
                break;
            case 2:
                $.each(
                        $('input[name=custom_dim]'),
                        function ()
                        {
                            if ($(this).attr("checked")) {
                                obj.size = $(this).val();
                            }
                        }
                );
                obj.fps = $('input#video_fps_1').val();
                break;
            default:
                break;
        }
        ;
        $.post(
                conf.base_url + conf.lang + '/ajax/admin/' + this.controller + '/convert',
                obj,
                function (reply)
                {
                    //console.log(reply);
                },
                "json"
                );
    };
    /**
     * set the convert mode
     * @param mode string
     * @return
     */
    this.set_convert_mode = function (mode) {
        this.convert_mode = mode;
    };
    this.approve_current = function () {
        this.approve(this.image_id);
    }
    this.approve = function (id)
    {
        $.post(
                conf.base_url + conf.lang + '/ajax/admin/' + this.controller + '/approve',
                {id: id},
        function (reply)
        {
            if (Boolean(reply)) {
                $('#media_out').show(handle.speed).text(msg.approved);
                $('#single_media_hold').removeClass("media_refused").addClass("media_approved");
                handle.getMediaDetailList();
            }
        }
        );
    };
    this.refuse_current = function () {
        this.refuse(this.image_id);
    }
    this.refuse = function (id)
    {
        $.post(
                conf.base_url + conf.lang + '/ajax/admin/' + this.controller + '/refuse',
                {id: id},
        function (reply)
        {
            if (Boolean(reply)) {
                $('#media_out').show(handle.speed).text(msg.refused);
                $('#single_media_hold').removeClass("media_approved").addClass("media_refused");
                handle.getMediaDetailList();
            }
        }
        );
    };
    this.init_sortable = function ()
    {
        /*
         * 
         var sortable_conf = {
         update:function(ui, event)
         {
         handle.sortUpdate();
         },
         revert: true
         };
         if ($('.sortable_list').children().length() > 0 ) {
         $('.sortable_list').sortable(sortable_conf);
         }
         console.log( $('.sortable_list').children().length() );
         */
    };
    this.add_to_item = function (id)
    {
        if (this.item_id != 0) {
            $.post(
                    conf.base_url + conf.lang + '/admin/' + this.controller + '/add_to_item',
                    {id: id, item_id: this.item_id},
            function (reply)
            {
                if (Boolean(reply)) {
                    $('#media_out').text('');
                    $('#media_out').show(handle.speed).text(msg.attached);
                    //$('#single_media_hold').empty();
                    //media.getPagination();
                    //media.getList();
                }
            }
            );
        }
    };
    this.reset_view_panel = function () {
        $.each($('.list_view > div > a'), function () {
            $(this).removeClass("active");
        }
        );
    };

    this.set_active_class = function (view)
    {
        this.reset_view_panel();
        $('div.list_view > div.' + view + ' > a').addClass("active");
    };
    this.set_view = function (view)
    {
        this.set_active_class(view);
        this.list_view = view;
        this.getMediaDetailList();
    };
    this.set_visibility = function (id, visibility)
    {
        $.post(
                conf.full_url + 'admin/' + this.controller + '/set_visibility',
                {id: id, visibility: visibility},
        function ()
        {
            handle.getMediaDetailList();
        }
        );
    };
    this.attach_add = function (id) {
    };
    this.attach_remove = function (id) {
    };
    this.close_info_box = function ()
    {
        $('#media_info').hide();
    };

    this.getCategoryList = function () {
        $.post(
                conf.full_url + 'admin/multimedia/category/get_category_list',
                null,
                function (data) {
                    $('#category_list_hold').html(data.content);
                    Tabs.select(0);
                    Tabs.disable(1);
                    Tabs.disable(2);
                },
                "json"
                );
    };

    this.addCategory = function () {
        this.tooltip(0);
        Tabs.selectOnly(2);
        $.post(
                conf.full_url + 'admin/multimedia/category/get_category_add_form',
                null,
                function (data) {
                    $("#category_new_hold").html(data.content);
                    handle.assignCategoryAddAction();
                },
                "json"
                );
    }
    this.assignCategoryAddAction = function () {
        $("form#category_add_form")
                .submit(function (e) {
                    e.preventDefault();
                    handle.saveCategoryAdd();
                });

        $("button#category_add_cancel")
                .bind("click", function () {
                    $("form#category_add_form").unbind("submit");
                    $("button#category_add_cancel").unbind("click");
                    $("#category_new_hold").empty();
                    Tabs.selectOnly(0);
                    handle.tooltip(1);
                });
    }
    this.editCategory = function (id) {
        if (id == undefined || id < 0)
            return;
        this.tooltip(0);
        this.category_id = id;
        Tabs.selectOnly(1);
        $.post(
                conf.full_url + 'admin/multimedia/category/get',
                {id: id},
        function (data) {
            $('#category_edit_hold').html(data.content);
            SubTabs.init();
            handle.attachCategoryEditAction();
        },
                "json"
                );
    };

    this.removeCategory = function (id)
    {
        if (id == undefined || id < 0)
            return;
        if (confirm(msg.confirm)) {
            $.post(
                    conf.full_url + 'admin/multimedia/category/remove',
                    {id: id},
            function (o) {
                if (o.state) {
                    handle.log(msg.category.remove_success, 2);
                    handle.getCategoryList();
                } else {
                    handle.log(o.err.join("<br />"), 1);
                }
            },
                    "json"
                    );
        }
    }
    this.reset_category_add_form = function () {
        for (var i = 0; i < conf.lang_list.split(',').length; i++) {
            $('input[name=' + conf.lang_list.split(',')[i] + '_title]').val('');
        }
        $('input[name=directory]').val('');
    };

    this.saveCategoryEdit = function () {
        var obj = {
            id: this.category_id,
            directory: $("input[name=directory]").val()
        };
        for (var i = 0; i < conf.lang_list.split(',').length; i++) {
            var code = conf.lang_list.split(',')[i];
            obj['title_' + code] = $('input[name=title_' + code + ']').val();
        }

        $.post(
                conf.full_url + 'admin/multimedia/category/edit',
                obj,
                function (data) {
                    if (data.state) {
                        handle.log(msg.category.edit_success, 2);
                    } else {

                    }
                },
                "json"
                );
    }
    this.saveCategoryAdd = function () {
        var obj = {
            id: this.category_id,
            directory: $("input[name=directory]").val()
        };
        for (var i = 0; i < conf.lang_list.split(',').length; i++) {
            var code = conf.lang_list.split(',')[i];
            obj['title_' + code] = $('input[name=title_' + code + ']').val();
        }

        $.post(
                conf.full_url + 'admin/multimedia/category/add',
                obj,
                function (data) {
                    if (data.state) {
                        handle.log(msg.category.add_success, 2);
                        for (var i in data.err)
                            $("p#" + i + "_err").text('');
                    } else {
                        for (var i in data.err)
                            $("p#" + i + "_err").text(data.err[i]);
                    }
                },
                "json"
                );
    }
    this.attachCategoryEditAction = function () {
        $("form#category_edit_form").submit(function (e) {
            e.preventDefault();
            handle.saveCategoryEdit();
        }
        );
        $("button#category_edit_cancel")
                .unbind("click")
                .bind("click", function () {
                    handle.tooltip(1);
                    $("div#category_edit_hold").empty();
                    handle.category_id = 0;
                    Tabs.select(0);
                }
                );
    };

    this.attachActions = function () {
        $("a#category_reload, a#category_new_tab").css("cursor", "pointer");
        $("a#category_reload").bind("click", function () {
            handle.getCategoryList();
        });
        $("a#category_new_tab").bind("click", function () {
            handle.addCategory();
        }
        );
        /*
         $('button#media_category_edit_cancel')
         .unbind("click")
         .bind("click", function(){
         }
         );
         $('button#media_category_add_cancel')
         .unbind("click")
         .bind("click", function(){
         handle.reset_category_add_form();
         Tabs.select(0);
         }
         );
         */
    };
    //reset edit title form
    this.reset_edit_title_form = function () {
        $("#edit_title_form").unbind("submit");
        $("#media_edit_title_cancel").unbind("click");
        //new
        $('#edit_title_hold').hide(handle.speed);
        for (var i in o = conf.lang_list.split(",")) {
            $('#edit_title_hold_title_' + o[i]).val("");
        }
        $('#show_state').attr("checked", "");

    };
    this.category_init = function () {
        this.attachActions();
        this.getCategoryList();
    };
}
;
var media = new MediaModel();
MediaModel = null;

function media_getInf(id) {
    media.getInf(id);
}
;
function media_edit_title(id) {
    media.edit_title(id);
}
;
function media_save_title() {
    media.save_title();
}
;
function media_show_all(p) {
    media.show_all(p);
}
;
function media_save_cancel() {
    media.save_cancel();
}
;
function media_init() {
    if (arguments.length == 2) {
        media.init(arguments[0], arguments[1]);
    } else if (arguments.lenght == 1) {
        media.init(arguments[0]);
    } else {
        media.init();
    }
}
;
function media_refuse(id) {
    media.refuse(id);
}
function media_approve(id) {
    media.approve(id);
}
function media_show_convert() {
    media.show_convert();
}
;
function media_hide_convert() {
    media.hide_convert();
}
;
function media_convert_media() {
    media.convert_media();
}
;
function media_set_convert_mode(mode) {
    media.set_convert_mode(mode);
}
;
function media_init_sortable() {
    media.init_sortable();
}
;
function media_init_draggable() {
    media.init_draggable();
}
;
function media_add_to_item(id) {
    media.add_to_item(id);
}
;
function media_set_view(view) {
    media.set_view(view);
}
;
function media_approve_current() {
    media.approve_current();
}
;
function media_make_visible(id) {
    media.set_visibility(id, 0);
}
;
function media_close_info() {
    media.close_info_box();
}
;
function media_category_remove(id) {
    media.category_delete(id);
}
;
function media_category_add() {
    media.category_add();
}
;
function media_category_save() {
    media.category_save();
}
;
function media_category_edit(id) {
    media.category_edit(id);
}
;
/* MVC */

function media_make_hidden(id) {
    media.set_visibility(id, 1);
}
;
function media_attach_add(id) {
    media.attach_add(id);
}
;
function media_attach_remove(id) {
    media.attach_remove(id);
}
;