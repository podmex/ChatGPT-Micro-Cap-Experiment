@extends('clixy/admin::layouts.default')

@section('content')
<div class="row collapse">
    <div class="large-6 columns">
		<p>@lang('clixy/admin::auth.loginTitle')</p>
    </div>
    <div class="large-6 columns">
		{!! Form::open(['url' => 'login', 'method' => 'post']) !!}
            <div class="row">
                <label>@lang('clixy/admin::auth.username')</label>
				{!! Form::email('email', old('email'), [
					'placeholder' => Lang::trans('clixy/admin::auth.email')
				]) !!}
            </div>
            <div class="row">
                <label>@lang('clixy/admin::auth.password')</label>
				{!! Form::password('password', [
					'id' => 'password',
					'placeholder' => Lang::trans('clixy/admin::auth.password')
				]); !!}
            </div>
            <div class="row">
				{!! Form::button(Lang::trans('clixy/admin::common.btn.login'), [
					'type' => 'submit',
					'class' => 'button tiny success radius'
				]) !!}
            </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection