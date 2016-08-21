<?php

namespace Clixy\Admin\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Clixy\Core\Models\Language;
use Clixy\Core\Models\Slide;
use Clixy\Core\Models\SlideLang;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        return view('clixy/admin::slider/slider', ['module' => 'slide']);
    }
    
    public function postList(Request $request)
    {
        $slide = new Slide();
        
        $list = $slide->orderBy('ord', 'asc')->get();
        if ($list) {
            foreach ($list as $v) {
                $v->lang = SlideLang::where('id', $v->id)->where('lang_id', 1)->first();
            }
        }
        
        return response()->json([
			'list' => [],
			'content' => view('clixy/admin::slider/sliderlist', ['list' => $list])->render(),
			'pagination' => '-na-',
			'token' => csrf_token()
		]);
    }
    
    public function postCreate(Request $request)
    {
        $lang = new Language();
        $lang_list = $lang->get();
        
        $slide = new Slide;
        $slide->save();
        
        foreach ($lang_list as $v) {
            $slideLang = new SlideLang();
            $slideLang->id = $slide->id;
            $slideLang->lang_id = $v->id;
            $slideLang->save();
        }
        
        $token = csrf_token();
        return response()->json([
			'id' => $slide->id,
			'token' => $token
		]);
    }
    
    public function postRemove(Request $request)
    {
        $id = $request->input('id');
        
        Slide::where('id', $id)->delete();
        
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
        
        $data = Slide::find($id);
        $slidelang = new SlideLang();
        
        if ($data) {
            $lang_data = [];
            foreach ($lang_list as $k => $v) {
                $lang_data[$v->id] = $slidelang->where('id', $id)->where('lang_id', $v->id)->first();
            }
        }
        
        $content = $content = view('clixy/admin::slider/sliderform', ['data' => $data, 'lang_data' => $lang_data, 'lang_list' => $lang_list])->render();
        
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
        
        $slide = Slide::find($id);
        $slide->ord = $request->input('ord');
        $slide->save();
        
        foreach ($lang_list as $v) {
            SlideLang::where('id', $id)
                ->where('lang_id', $v->id)
                ->update([
                    'title' => $request->input('title')[$v->id],
                    'content' => $request->input('content')[$v->id],
                    'uri' => $request->input('uri')[$v->id]
                ]);
        }
        
        return response()->json([
			'state' => true,
			'token' => csrf_token()
		]);
    }
}