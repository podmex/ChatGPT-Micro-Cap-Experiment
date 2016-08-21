<div class="row">
	<div class="large-12 columns">
		<?php echo 0 ? print_r($data, true) : ''; ?>
		<div class="row">
			<div class="large-6 columns">
				<h2>Предмет {{ $data->slug }}</h2>
				<form id="{{{ $module }}}-edit-form" data-abide>
					<input type="hidden" name="id" value="{{ $data->id }}" />
					<div class="row">
						<div class="large-12">
							<label>Slug</label>
							<input type="text" name="slug" value="{{ $data->slug }}" />
						</div>
					</div>
					<div class="row">
						<div class="large-6 columns">
							<label>@lang('clixy/admin::Order')</label>
							<input type="text" name="ord" value="{{ $data->ord }}" />
						</div>
						<div class="large-6 columns">
							<label>Active</label>
							<input type="text" name="active" value="{{ $data->active }}" />
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
						<div class="large-12">
							<h2>Категории</h2>
							@foreach($category_list as $v)
							@if($v->parent_id == 0)
							<input id="checkbox{{ $v->id}}" type="checkbox" name="category[{{ $v->id }}]" value="1" @if(in_array($v->id, $data->category_list))checked="checked"@endif />
								   <label for="checkbox{{ $v->id }}">{{ $v->lang->title }}</label><br />
							@foreach($category_list as $vv)
							@if($vv->parent_id == $v->id)
							<input style="margin-left: 16px" id="checkbox{{ $vv->id }}" type="checkbox" name="category[{{ $vv->id }}]" value="1" @if(in_array($vv->id, $data->category_list))checked="checked"@endif />
								   <label for="checkbox{{ $vv->id }}">{{ $vv->lang->title }}</label><br />
							@endif
							@endforeach
							@endif
							@endforeach
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
		<p>Главна снимка 245 x 196 px<br />
		Малки под нея, 2 бр., 121 x 99 px</p>
		@include('clixy/admin::multimedia.media', ['cat_id' => 5, 'item_id' => $data->id])
		</div>
		</div>
	</div>
</div>