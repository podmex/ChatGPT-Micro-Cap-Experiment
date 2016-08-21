<div class="row">
    <div class="large-12 columns">
        <?php echo 0 ? print_r($data, true) : ''; ?>
        <div class="row">
            <div class="large-6 columns">
                <h2>Писмо / {{ $data->id }} /</h2>
                <form id="{{ $module }}-edit-form" data-abide>
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <div class="row">
                        <div class="large-12">
                            <label>Slug</label>
                            <input type="text" name="slug" value="{{ $data->slug }}" />
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
                                            <label>Subject</label>
                                            <input type="text" name="subject[{{ $v->id }}]" value="{{ $lang_data[$v->id]->subject }}" />
                                        </div>
                                    </div>
				    <div class="row">
                                        <div class="large-12">
                                            <label>Content</label>
                                            <textarea class="ckeditor" name="content[{{ $v->id }}]">{{ $lang_data[$v->id]->content }}</textarea>
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
		@include('clixy/admin::multimedia.media', ['cat_id' => 8, 'item_id' => $data->id])
            </div>
        </div>
    </div>
</div>