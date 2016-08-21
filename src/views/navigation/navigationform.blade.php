<div class="row">
    <div class="large-12 columns">
        @if(0)
            {{ print_r($data, true) }}
        @endif
        <div class="row">
            <div class="large-6 columns">
                <h2>{{ $data->id }}</h2>
                <form id="navigation-edit-form" data-abide>
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <div class="row">
                        <div class="large-12">
                            <label>Group</label>
                            <select name="group_id">
				@foreach($data->group_list as $k => $v)
				    @if($v->parent_id == 0)
				    <optgroup label="{{ $v->slug }}">
					@foreach($data->group_list as $kk => $vv)
					    @if($vv->parent_id == $v->id)
					    <option value="{{ $vv->id }}" @if($data->group_id == $vv->id)selected="selected"@endif>{{ $v->slug }} / {{ $vv->slug }}</option>
					    @endif
					@endforeach
				    </optgroup>
				    @endif
				@endforeach
			    </select>
                        </div>
                    </div>
		    <div class="row">
                        <div class="large-12">
                            <label>Page</label>
                            <select name="page_id">
                            <option value="0">Основна</option>
                            @foreach ($page_list as $v)
                            <option value="{{ $v->id }}"@if($data->page_id == $v->id) selected="selected"@endif>{{ $v->lang->title }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    @if($data->page_id != 0)
                    <div class="row">
                        <div class="large-12">
                            <label>Slug</label>
                            <input type="text" name="slug" value="{{ $data->slug }}" readonly />
                        </div>
                    </div>
		    <div class="row">
                        <div class="large-12">
                            <label>URI</label>
                            <input type="text" name="uri" value="{{ $data->uri }}" readonly />
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="large-12">
                            <label>Order</label>
                            <input type="text" name="ord" value="{{ $data->ord }}" />
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
                                @foreach ($lang_list as $k => $v)
                                <section role="tabpanel" class="content @if($v->sname === 'bg') active @endif" id="mtitle{{ $v->id }}">
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
                                            <label>Content</label>
                                            <input type="text" name="content[{{ $v->id }}]" value="{{ $lang_data[$v->id]->content }}" />
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
            <div class="large-6 columns" style="padding-left: 14px">
		@if(0)
                <h2>Медия</h2>
                <p>Главна снимка 245 x 196 px</p>
                <p>Малки под нея, 2 бр., 121 x 99 px</p>
                @include('clixy/admin::multimedia.media', ['cat_id' => 2, 'item_id' => $data->id])
		@endif
            </div>
        </div>
    </div>
</div>