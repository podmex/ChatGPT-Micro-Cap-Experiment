<?php

namespace Clixy\Admin\Controllers;

use App\Http\Controllers\Controller;

use Clixy\Core\Models\Language;

use Clixy\Core\Models\Page;
use Clixy\Core\Models\PageLang;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        return view('clixy/admin::page/page', ['module' => 'page']);
    }
    
    public function postList(Request $request)
    {
        $o = new Page();
        $ol = new PageLang();
        
        $list = $o->orderBy('ord', 'asc')->get();
        if ($list) {
            foreach ($list as $v) {
                $v->lang = $ol->where('id', $v->id)->where('lang_id', 1)->first();
            }
        }

        return response()->json([
			'list' => [],
			'content' => view('clixy/admin::page/pagelist', ['list' => $list])->render(),
			'pagination' => '-na-',
			'token' => csrf_token()
		]);
    }
    
    public function postCreate()
    {
        $lang = new Language();
        $lang_list = $lang->get();
        
        $o = new Page();
        $o->save();
        
        foreach ($lang_list as $v) {
            $ol = new PageLang();
            $ol->id = $o->id;
            $ol->lang_id = $v->id;
            $ol->save();
        }
        
        return response()->json([
			'id' => $o->id,
			'token' => csrf_token()
		]);
    }
    
    public function postRemove(Request $request)
    {
        $id = $request->input('id');
	
        $o = new Page();
	$o->where('id', $id)->delete();
        
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
        
        $o = new Page();
        $ol = new PageLang();
	
	$data = $o->find($id);
        // var_dump($data);
        
        if ($data) {
            $lang_data = [];
            foreach ($lang_list as $k => $v) {
                $lang_data[$v->id] = $ol->where('id', $id)->where('lang_id', $v->id)->first();
            }
        }
        
        $page_list = [];
        $r = $o->where('parent_id', 0)->orderBy('ord', 'asc')->get();
        if ($r) {
            foreach ($r as &$v) {
                // echo $v->id;
                $v->lang = $ol->where('id', $v->id)->where('lang_id', 1)->first();
                $page_list[] = $v;
            }
        }
        
        $content = $content = view(
                'clixy/admin::page/pageform', 
                [
                    'module' => 'page',
                    'data' => $data,
                    'lang_data' => $lang_data,
                    'lang_list' => $lang_list,
                    'page_list' => $page_list
                ]
            )->render();
        
        return response()->json([
			'id' => 0,
			'content' => $content,
			'token' => csrf_token()
		]);
    }
    
    public function postSave(Request $request)
    {
        $l = new Language();
        $lang_list = $l->get();
        
        $id = $request->input('id');
        
	$o = new Page();
	$ol = new PageLang();
	
        $v = $o->find($id);
        $v->ord = $request->input('ord');
        $v->slug = $request->input('slug');
        $v->parent_id = $request->input('parent_id');
        $v->save();
        
        foreach ($lang_list as $v) {
            $ol->where('id', $id)
                ->where('lang_id', $v->id)
                ->update([
                    'title' => $request->input('title')[$v->id],
                    'uri' => $request->input('uri')[$v->id],
                    'text' => $request->input('text')[$v->id],
                    'heading' => $request->input('heading')[$v->id],
                    'content' => $request->input('content')[$v->id],
                    'meta_title' => $request->input('meta_title')[$v->id],
                    'meta_keywords' => $request->input('meta_keywords')[$v->id],
                    'meta_description' => $request->input('meta_description')[$v->id]
                ]);
        }
        
        return response()->json([
			'state' => true,
			'token' => csrf_token()
		]);
    }
}
