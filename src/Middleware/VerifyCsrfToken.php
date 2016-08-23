<?php

namespace Clixy\Admin\Middleware;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Encryption\Encrypter;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
	public function __construct(Application $app, Encrypter $encrypter) {

		parent::__construct($app, $encrypter);
		$prefix = config('clixy.admin.prefix');
		
		/**
		 * The URIs that should be excluded from CSRF verification.
		 *
		 * @var array
		 */
		$this->except = [
			"{$prefix}/media/upload"
		];

	}
}
