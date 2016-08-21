<?php

namespace Clixy\Admin\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Clixy\Core\Models\Language;
use Clixy\Core\Models\Newsletter;
use Clixy\Core\Models\NewsletterLang;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        return view('clixy/admin::newsletter/newsletter', ['module' => 'newsletter']);
    }
    
    public function postList(Request $request)
    {
        $o = new Newsletter();
        $ol = new NewsletterLang();
        
        $list = $o->orderBy('ord', 'asc')->get();
        if ($list) {
            foreach ($list as $v) {
                $v->lang = $ol->where('id', $v->id)->where('lang_id', 1)->first();
            }
        }
        
        return response()->json([
			'list' => [],
			'content' => view('clixy/admin::newsletter/newsletterlist', ['list' => $list])->render(),
			'pagination' => '-na-',
			'token' => csrf_token()
		]);
    }
    
    public function postCreate()
    {
        $lang = new Language();
        $lang_list = $lang->get();
        
        $o = new Newsletter();
        $o->save();
        
        foreach ($lang_list as $v) {
            $ol = new NewsletterLang();
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
	
        $o = new Newsletter();
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
        
        $o = new Newsletter();
        $ol = new NewsletterLang();
	
	$data = $o->find($id);
        // var_dump($data);
        
        
        if ($data) {
            $lang_data = [];
            foreach ($lang_list as $k => $v) {
                $lang_data[$v->id] = $ol->where('id', $id)->where('lang_id', $v->id)->first();
            }
        }
        
        $content = $content = view(
                'clixy/admin::newsletter/newsletterform', 
                [
                    'module' => 'newsletter',
                    'data' => $data,
                    'lang_data' => $lang_data,
                    'lang_list' => $lang_list
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
        
	$o = new Newsletter();
	$ol = new NewsletterLang();
	
        $v = $o->find($id);
        $v->slug = $request->input('slug');
        $v->save();
        
        foreach ($lang_list as $v) {
            $ol->where('id', $id)
                ->where('lang_id', $v->id)
                ->update([
                    'subject' => $request->input('subject')[$v->id],
                    'content' => $request->input('content')[$v->id]
                ]);
        }
        
        return response()->json([
			'state' => true,
			'token' => csrf_token()
		]);
    }
}