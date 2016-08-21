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
                            <label>E-mail</label>
                            <input type="text" name="email" value="{{ $data->email }}" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-12">
                            <label>Password</label>
                            <input type="text" name="password" placeholder="парола, ако ще се сменя" />
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
        </div>
    </div>
</div>