<?php

namespace Clixy\Admin\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Clixy\Core\Models\Language;
use Clixy\Core\Models\Category;
use Clixy\Core\Models\CategoryLang;

class CategoryController extends Controller
{
	public function index(Request $request)
	{
		return view('clixy/admin::category/category', ['module' => 'category']);
	}

	public function postList(Request $request)
	{
		$category = new Category();

		$list = $category->orderBy('ord', 'asc')->get();
		if ($list) {
			foreach ($list as $v) {
				$v->lang = CategoryLang::where('id', $v->id)->where('lang_id', 1)->first();
			}
		}

		return response()->json([
			'list' => [],
			'content' => view('clixy/admin::category/categorylist', ['list' => $list])->render(),
			'pagination' => '-na-',
			'token' => csrf_token()
		]);
	}

	public function postCreate(Request $request)
	{
		$lang = new Language();
		$lang_list = $lang->get();

		$cat = new Category;
		$cat->save();

		foreach ($lang_list as $v) {
			$catLang = new CategoryLang();
			$catLang->id = $cat->id;
			$catLang->lang_id = $v->id;
			$catLang->save();
		}

		return response()->json([
			'id' => $cat->id,
			'token' => csrf_token()
		]);
	}

	public function postRemove(Request $request)
	{
		$id = $request->input('id');

		Category::where('id', $id)->delete();

		return response()->json([
			'state' => true,
			'msg' => 'done',
			'token' => csrf_token()
		]);
	}

	public function postGet(Request $request)
	{
		$id = $request->input('id');

		$language = new Language();
		$lang_list = $language->get();

		$o = new Category();
		$ol = new CategoryLang();

		$data = $o->find($id);
		// var_dump($data);
		if ($data) {
			$lang_data = [];
			foreach ($lang_list as $k => $v) {
				$lang_data[$v->id] = $ol->where('id', $id)->where('lang_id', $v->id)->first();
			}
		}
		$category_list = [];
		$r = $o->where('parent_id', 0)->orderBy('ord', 'asc')->get();
		if ($r) {
			foreach ($r as &$v) {
				// echo $v->id;
				$v->lang = $ol->where('id', $v->id)->where('lang_id', 1)->first();
				$category_list[] = $v;
			}
		}

		$content = view('clixy/admin::category/categoryform', [
			'module' => 'category',
			'data' => $data,
			'lang_data' => $lang_data,
			'lang_list' => $lang_list,
			'category_list' => $category_list
		])->render();

		return response()->json([
			'id' => 0,
			'content' => $content,
			'token' => csrf_token()
		]);
	}

	public function postSave(Request $request)
	{
		$lang = new Language();
		$lang_list = $lang->get();

		$id = $request->input('id');

		$o = new Category();
		$ol = new CategoryLang();

		$cat = $o->find($id);
		$cat->slug = $request->input('slug');
		$cat->ord = $request->input('ord');
		$cat->active = $request->input('active');
		$cat->parent_id = $request->input('parent_id');
		$cat->is_home = $request->input('is_home');
		$cat->save();

		foreach ($lang_list as $v) {
			$ol->where('id', $id)
				->where('lang_id', $v->id)
				->update([
					'title' => $request->input('title')[$v->id],
					'brief' => $request->input('brief')[$v->id],
					'uri' => $request->input('uri')[$v->id],
					'content' => $request->input('content')[$v->id]
				]);
		}

		return response()->json([
			'state' => true,
			'token' => csrf_token()
		]);
	}
}