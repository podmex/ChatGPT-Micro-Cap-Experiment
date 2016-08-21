<?php

namespace Clixy\Admin\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Clixy\Core\Models\Language;
use Clixy\Core\Models\Conf;

class ConfController extends Controller
{
	public function index(Request $request)
	{
		return view('clixy/admin::conf.conf', ['module' => 'conf']);
	}

	public function postList()
	{
		$conf = new Conf();

		$list = $conf->orderBy('ord', 'asc')->get();
		if ($list) {
			foreach ($list as $v) {
				// var_dump(ColorLang::where('id', $v->id)->where('lang_id', 1)->first());
				// $v->lang = ColorLang::where('id', $v->id)->where('lang_id', 1)->first();
			}
		}

		return response()->json([
			'list' => [],
			'content' => view('clixy/admin::conf.conflist', ['list' => $list])->render(),
			'pagination' => '-na-',
			'token' => csrf_token()
		]);
	}

	public function postCreate()
	{
		$conf = new Conf;
		$conf->save();

		return response()->json([
			'id' => $conf->id,
			'token' => csrf_token()
		]);
	}

	public function postSave(Request $request)
	{
		$id = $request->input('id');

		$conf = Conf::find($id);
		$conf->slug = $request->input('slug');
		// $conf->ord = $request->input('ord');
		$conf->value = $request->input('value');
		$conf->save();

		return response()->json([
			'state' => true,
			'token' => csrf_token()
		]);
	}

	public function postGet(Request $request)
	{
		$id = $request->input('id');
		$lang_list = Language::get();

		$data = Conf::find($id);

		return response()->json([
			'id' => 0,
			'content' => view('clixy/admin::conf.confform', ['module' => 'conf', 'data' => $data])->render(),
			'token' => csrf_token()
		]);
	}

	public function postRemove(Request $request)
	{
		Conf::where('id', $request->input('id'))->delete();

		return response()->json([
			'state' => true,
			'msg' => 'done',
			'token' => csrf_token()
		]);
	}
}