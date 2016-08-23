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
	
	/**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {

		$prefix = config('clixy.admin.prefix');
		$this->redirectTo = "/$prefix";
		$this->middleware($this->guestMiddleware(), ['except' => "/{$prefix}/logout"]);
    }
	
	/**
     * Get the guest middleware for the application.
     */
    public function guestMiddleware()
    {
        $guard = $this->getGuard();

        return $guard ? 'guest.admin:' . $guard : 'guest.admin';
    }

}
