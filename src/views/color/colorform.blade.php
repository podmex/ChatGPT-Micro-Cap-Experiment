<div class="row">
    <div class="large-12 columns">
        <?php echo 0 ? print_r($data, true) : ''; ?>
        <div class="row">
            <div class="large-6 columns">
                <h2>{{ $data->id }}</h2>
                <form id="{{ $module }}-edit-form" data-abide>
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <div class="row">
                        <div class="large-12">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ $data->name }}" />
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
                            <label>Code</label>
                            <input type="text" name="code" value="{{ $data->code }}" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12 columns">
                            <ul class="tabs" role="tablist" data-tab>
                            @foreach ($lang_list as $v)
                            <li class="tab-title@if($v->sname === 'bg') active@endif" role="presentation">
                                <a href="#mtitle{{ $v->id }}">{{ $v->sname }}</a>
                            </li>
                            @endforeach
                            </ul>

                            <div class="tabs-content">
                                @foreach ($lang_list as $k => $v)
                                <section role="tabpanel" class="content@if($v->sname === 'bg') active@endif" id="mtitle{{ $v->id }}">
                                    <div class="row">
                                        <div class="large-12">
                                            <label>Title</label>
                                            <input type="text" name="title[{{ $v->id }}]" value="{{ $lang_data[$v->id]->title }}" />
                                        </div>
                                    </div>
                                    @if(0)
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
                                    @endif
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
                <h2>Медия</h2>
                <p>Главна снимка 245 x 196 px</p>
                <p>Малки под нея, 2 бр., 121 x 99 px</p>
                @include('clixy/admin::multimedia.media', ['cat_id' => 3, 'item_id' => $data->id]);
            </div>
        </div>
    </div>
</div>