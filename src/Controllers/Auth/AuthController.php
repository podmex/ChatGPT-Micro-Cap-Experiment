<?php

namespace Clixy\Admin\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController as LaravelAuthController;

class AuthController extends LaravelAuthController
{
	
    protected $loginView = 'clixy/admin::auth/login';
    //protected $registerView = 'clixy/admin::auth/';
    //protected $linkRequestView = 'clixy/admin::auth/';
    //protected $resetView = 'clixy/admin::auth/';

}
