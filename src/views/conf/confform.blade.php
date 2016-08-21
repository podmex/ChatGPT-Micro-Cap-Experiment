<div class="row">
	<div class="large-12 columns">
		<div class="row">
			<div class="large-6 columns">
				<h2>Настройка / {{ $data->id }}</h2>
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
							<label>Value</label>
							<input type="text" name="value" value="{{ $data->value }}" />
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
			</div>
		</div>
	</div>
</div>