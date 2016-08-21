<span class="s_image_edit s_tooltip s_tooltip_up" style="width:320px">
    {assign var="lang_list" value=","|explode:"1"}
    <span class="button_dialog_close">
        <strong style="float:left" class="edit_title_hold_{$id}_title_1"></strong>
        <a id="close_title_button_{$id}" class="close_title">затвори прозореца</a>
    </span>
    <span class="d_top_left"></span>
    <span class="d_top_right"></span>
    <span class="d_bottom_right"></span>
    <form id="edit_title_{$id}_form" class="s_form s_form_2 clearfix">
        {include file="common/tabulation.tpl" prefix="media_content_`$id`"}
        {foreach from=$lang_list item=v}
        {assign var="lang_id" value=$v}
        <div id="media_content_{$id}_lang_{$lang_id}">
            <div class="s_form_row_1 clearfix">
                <label for="edit_title_hold_{$id}_title_{$lang_id}">{$lang.media.info.title}</label>
                <span class="s_textarea width_5">
                    <span class="t_top_right"></span>
                    <textarea class="s_textarea height_1" id="edit_title_hold_{$id}_title_{$lang_id}"></textarea>
                    <span class="t_bottom_left"></span>
                    <span class="t_bottom_right"></span>
                </span>
            </div>
        </div>
        {/foreach}
        <div class="s_data_submit_2 clearfix">
            {include file="common/button.tpl" type="submit" value=$lang.button.save}
            {include file="common/button.tpl" type="button" value=$lang.button.cancel id="edit_title_hold_cancel_`$id`" class="reset"}
        </div>
    </form>
</span>