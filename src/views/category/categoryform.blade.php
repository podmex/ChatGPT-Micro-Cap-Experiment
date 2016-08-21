<div class="row">
	<div class="large-12 columns">
		<div class="row">
			<div class="large-7 columns">
				<h2>{{ $lang_data[1]->title }} / {{ $data->id }}</h2>
				<form id="{{{ $module }}}-edit-form" data-abide>
					<input type="hidden" name="id" value="{{ $data->id }}" />
					<div class="row">
						<div class="large-12">
							<label>@lang('clixy/admin::category.slug')</label>
							<input type="text" name="slug" value="{{ $data->slug }}" />
						</div>
					</div>
					<div class="row">
						<div class="large-4 columns">
							<label>@lang('clixy/admin::category.order')</label>
							<input type="text" name="ord" value="{{ $data->ord }}" />
						</div>
						<div class="large-4 columns">
							<label>@lang('clixy/admin::category.active')</label>
							<input type="text" name="active" value="{{ $data->active }}" />
						</div>
						<div class="large-4 columns">
							<label>@lang('clixy/admin::category.homepage')</label>
							<input type="text" name="is_home" value="{{ $data->is_home }}" />
						</div>
					</div>
					<div class="row">
						<div class="large-12">
							<label>@lang('clixy/admin::category.parent')</label>
							<select name="parent_id">
								<option value="0">@lang('clixy/admin::category.main')</option>
								@foreach ($category_list as $v)
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
												<label>@lang('clixy/admin::category.uri')</label>
												<input type="text" name="uri[{{ $v->id }}]" value="{{ $lang_data[$v->id]->uri }}" />
											</div>
										</div>
										<div class="row">
											<div class="large-12">
												<label>@lang('clixy/admin::category.title')</label>
												<input type="text" name="title[{{ $v->id }}]" value="{{ $lang_data[$v->id]->title }}" />
											</div>
										</div>
										<div class="row">
											<div class="large-12">
												<label>@lang('clixy/admin::category.brief')</label>
												<input type="text" name="brief[{{ $v->id }}]" value="{{ $lang_data[$v->id]->brief }}" />
											</div>
										</div>
										<div class="row">
											<div class="large-12">
												<label>@lang('clixy/admin::category.content')</label>
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
			<div class="large-4 columns">
				<h2>Медия</h2>
				<p>
					Главна снимка 245 x 196 px<br />
					Малки под нея, 2 бр., 121 x 99 px
				</p>
				@include('clixy/admin::multimedia.media', ['cat_id' => 4, 'item_id' => $data->id])
			</div>
		</div>
	</div>
</div>