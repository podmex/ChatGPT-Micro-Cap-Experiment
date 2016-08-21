<div class="row">
    <div class="large-12 columns">
        <?php echo 0 ? print_r($data, true) : ''; ?>
        <div class="row">
            <div class="large-6 columns">
                <h2>Новина / {{ $data->id }} /</h2>
                <form id="{{ $module }}-edit-form" data-abide>
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <div class="row">
                        <div class="large-12">
                            <label>Slug</label>
                            <input type="text" name="slug" value="{{ $data->slug }}" />
                        </div>
                    </div>
		    <div class="row">
                        <div class="large-12">
                            <label>Order</label>
                            <input type="text" name="ord" value="{{ $data->ord }}" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12">
                            <label>Parent</label>
                            <select name="parent_id">
                            <option value="0">Основна</option>
                            @foreach ($page_list as $v)
                            <option value="{{ $v->id }}"@if($data->parent_id == $v->id) selected="selected"@endif>{{ $v->lang->title }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <ul class="tabs" role="tablist" data-tab>
                            @foreach ($lang_list as $v)
                            <li class="tab-title @if($v->sname === 'bg') active @endif" role="presentation">
                                <a href="#mtitle{{ $v->id }}">
                                    <img src="http://img.webmax.bg/flags/{{ $v->sname }}.png" alt="" />
                                </a>
                            </li>
                            @endforeach
                            </ul>

                            <div class="tabs-content">
                                @foreach ($lang_list as $v)
                                <section role="tabpanel" class="content @if($v->sname === 'bg') active @endif" id="mtitle{{ $v->id }}">
                                    <div class="row">
                                        <div class="large-12">
                                            <label>URI</label>
                                            <input type="text" name="uri[{{ $v->id }}]" value="{{ $lang_data[$v->id]->uri }}" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="large-12">
                                            <label>Title</label>
                                            <input type="text" name="title[{{ $v->id }}]" value="{{ $lang_data[$v->id]->title }}" />
                                        </div>
                                    </div>
				    <div class="row">
                                        <div class="large-12">
                                            <label>Text</label>
                                            <input type="text" name="text[{{ $v->id }}]" value="{{ $lang_data[$v->id]->text }}" />
                                        </div>
                                    </div>
				    <div class="row">
                                        <div class="large-12">
                                            <label>Heading</label>
                                            <input type="text" name="heading[{{ $v->id }}]" value="{{ $lang_data[$v->id]->heading }}" />
                                        </div>
                                    </div>
				    <div class="row">
                                        <div class="large-12">
                                            <label>Content</label>
                                            <textarea class="ckeditor" name="content[{{ $v->id }}]">{{ $lang_data[$v->id]->content }}</textarea>
                                        </div>
                                    </div>
				    <hr />
				    <div class="row">
                                        <div class="large-12">
                                            <label>Title</label>
                                            <input type="text" name="meta_title[{{ $v->id }}]" value="{{ $lang_data[$v->id]->meta_title }}" />
                                        </div>
                                    </div>
				    <div class="row">
                                        <div class="large-12">
                                            <label>Description</label>
                                            <input type="text" name="meta_keywords[{{ $v->id }}]" value="{{ $lang_data[$v->id]->meta_description }}" />
                                        </div>
                                    </div>
				    <div class="row">
                                        <div class="large-12">
                                            <label>Keywords</label>
                                            <input type="text" name="meta_description[{{ $v->id }}]" value="{{ $lang_data[$v->id]->meta_keywords }}" />
                                        </div>
                                    </div>
                                </section>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <button type="submit" class="button tiny radius success">@lang('clixy/admin::common.btn.save')</button>
                            <button data-action="cancel" type="button" class="button tiny radius alert">@lang('clixy/admin::common.btn.cancel')</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="large-6 columns">
                <h2>Медия</h2>
		@include('clixy/admin::multimedia.media', ['cat_id' => 7, 'item_id' => $data->id])
            </div>
        </div>
    </div>
</div>