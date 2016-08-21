<?php

namespace Clixy\Admin\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Clixy\Core\Models\Language;
use Clixy\Core\Models\Navigation;
use Clixy\Core\Models\NavigationLang;
use Clixy\Core\Models\NavigationGroup;
use Clixy\Core\Models\Page;
use Clixy\Core\Models\PageLang;

class NavigationController extends Controller
{
    public function index(Request $request)
    {
        return view('clixy/admin::navigation.navigation', ['module' => 'navigation']);
    }
    
    public function postList(Request $request)
    {
        $o = new Navigation();
        $ol = new NavigationLang();
		$ng = new NavigationGroup();
        
        $list = $o->orderBy('ord', 'asc')->get();
        if ($list) {
            foreach ($list as $v) {
                $v->lang = $ol->where('id', $v->id)->where('lang_id', 1)->first();
            }
		}
		$group_list = $ng->where('active', 1)->orderBy('ord', 'ASC')->get();
        
        return response()->json([
			'list' => [],
			'content' => view('clixy/admin::navigation.navigationlist', ['group_list' => $group_list, 'list' => $list])->render(),
			'pagination' => '-na-',
			'token' => csrf_token()
		]);
    }
    
    public function postCreate(Request $request)
    {
        $lang = new Language();
        $lang_list = $lang->get();
        
        $o = new Navigation();
        $o->save();
        
        foreach ($lang_list as $v) {
            $ol = new NavigationLang();
            $ol->id = $o->id;
            $ol->lang_id = $v->id;
            $ol->save();
        }
        
        return response()->json([
			'id' => $o->id,
			'token' => csrf_token()
		]);
    }
    
    public function postSave(Request $request)
    {
        $lang = new Language();
        $lang_list = $lang->get();
        
        $id = $request->input('id');
        
        $o = new Navigation();
        $ol = new NavigationLang();
        
        $page = new Page();
        $pagelang = new PageLang();
        
        $nav = $o->find($id);
        $nav->slug = $request->input('slug');
        $nav->ord = $request->input('ord');
        $nav->uri = $request->input('uri');
        $nav->page_id = $request->input('page_id');
        
        // get slug and uri from page
        $slug = $page->find($request->input('page_id'));
        if (!empty($slug) && $slug->count() > 0) {
            // dump($slug);
            $nav->slug = $slug->slug; 
            $nav->uri = $slug->slug; 
            //echo $request->input('page_id');
            //echo 'slug:' . $slug->slug;
            //echo $slug->id;
        }
        
        // $nav->header = $request->input('header');
        // $nav->footer = $request->input('footer');
        // $nav->child = $request->input('child');
	$nav->group_id = $request->input('group_id');
        $nav->save();
        
        foreach ($lang_list as $v) {
            $ol->where('id', $id)
                ->where('lang_id', $v->id)
                ->update([
                    'title' => $request->input('title')[$v->id],
                    'text' => $request->input('text')[$v->id],
                    'content' => $request->input('content')[$v->id]
                ]);
        }
        
        return response()->json([
			'state' => true,
			'token' => csrf_token()
		]);
    }
    
    public function postGet(Request $request)
    {
        $id = $request->input('id');
        $l = new Language();
        $lang_list = $l->get();
        
        $data = Navigation::find($id);
        $navlang = new NavigationLang();
	$ng = new NavigationGroup();
        $page = new Page();
        $pagelang = new PageLang();
        
        
        if ($data) {
            $lang_data = [];
            foreach ($lang_list as $v) {
                $lang_data[$v->id] = $navlang->where('id', $id)->where('lang_id', $v->id)->first();
            }
	    $data['group_list'] = $ng->where('active', 1)->orderBy('ord', 'ASC')->get();
        }
        
        $page_list = [];
        $r = $page->orderBy('ord', 'asc')->get();
        if ($r) {
            foreach ($r as &$v) {
                // echo $v->id;
                $v->lang = $pagelang->where('id', $v->id)->where('lang_id', 1)->first();
                $page_list[] = $v;
            }
        }
        
        $content = $content = view('clixy/admin::navigation.navigationform', ['data' => $data, 'page_list' => $page_list, 'lang_data' => $lang_data, 'lang_list' => $lang_list])->render();
        
        return response()->json([
			'id' => 0,
			'content' => $content,
			'token' => csrf_token()
		]);
    }
    
    public function postRemove(Request $request)
    {
        $o = new Navigation();
        $o->where('id', $request->input('id'))->delete();
        
        return response()->json([
			'state' => true,
			'msg' => 'done',
			'token' => csrf_token()
		]);
    }
}