// state model
function StateModel()
{
    var self = this;
    var id = '', _log_;
    
    this.log = function (_id, msg, type) {
        var html;
        id = _id;
        if (typeof type !== "undefined") {
            html = "<div class='s_server_message_holder clearfix'>";
            html += "<div class='s_server_message_" + type + "'>";
            html += "<span class='d_top_left'></span>";
            html += "<span class='d_top_right'></span>";
            html += "<p>" + msg + "</p>";
            html += "<span class='d_bottom_right'></span>";
            html += "</div>";
            html += "</div>";
            $("#" + id).html(html);
        } else {
            $("#" + id).addClass("ui-state-highlight").html(msg);
        }
        if (typeof _log_ !== "undefined") {
            clearInterval(_log_);
        }
        _log_ = setTimeout(reset_log, 5000);
    };
    
    var reset_log = function () {
        $("#" + id).removeClass("ui-state-highlight").empty();
    };
}
var state_obj = new StateModel();