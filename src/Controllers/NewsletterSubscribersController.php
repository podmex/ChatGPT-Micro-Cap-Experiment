<?php

namespace Clixy\Admin\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Clixy\Core\Models\Language;
use Clixy\Core\Models\Newsletter;
use Clixy\Core\Models\NewsletterLang;
use Clixy\Core\Models\NewsletterSubscribers;

class NewsletterSubscribersController extends Controller
{
    public function index(Request $request)
    {
        return view('clixy/admin::NewsletterSubscribers/NewsletterSubscribers', ['module' => 'NewsletterSubscribers']);
    }
    
    public function postList(Request $request)
    {
        $o = new NewsletterSubscribers();
        $list = $o->orderBy('id', 'desc')->get();
        if ($list) {
        }
        
        return response()->json([
			'list' => [],
			'content' => view('clixy/admin::NewsletterSubscribers/NewsletterSubscribersList', ['list' => $list])->render(),
			'pagination' => '-na-',
			'token' => csrf_token()
		]);
    }
    
    public function postCreate()
    {
        $o = new NewsletterSubscribers();
        $o->save();
        
        return response()->json([
			'id' => $o->id,
			'token' => csrf_token()
		]);
    }
    
    public function postRemove(Request $request)
    {
        $id = $request->input('id');
	
        $o = new NewsletterSubscribers();
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
        
        $o = new NewsletterSubscribers();
        
        $data = $o->find($id);
        // var_dump($data);
        
        $content = $content = view(
                'clixy/admin::NewsletterSubscribers/NewsletterSubscribersForm', 
                [
                    'module' => 'NewsletterSubscribers',
                    'data' => $data
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
        $id = $request->input('id');
        
	$o = new NewsletterSubscribers();
	
        $v = $o->find($id);
        $v->name = $request->input('name');
        $v->email = $request->input('email');
        $v->save();
        
        return response()->json([
			'state' => true,
			'token' => csrf_token()
		]);
    }
}