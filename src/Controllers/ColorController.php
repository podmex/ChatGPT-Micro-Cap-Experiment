<?php

namespace Clixy\Admin\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Clixy\Core\Models\Language;
use Clixy\Core\Models\Color;
use Clixy\Core\Models\ColorLang;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user() ? $request->user()->toArray() : ['id' => null];
        return view('clixy/admin::color.color', ['module' => 'color', 'user' => $user, 'token' => csrf_token()]);
    }
    
    public function postList()
    {
        $token = csrf_token();
        $col = new Color();
        
        $list = $col->orderBy('ord', 'asc')->get();
        if ($list) {
            foreach ($list as $v) {
                $v->lang = ColorLang::where('id', $v->id)->where('lang_id', 1)->first();
            }
        }
        $content = view('clixy/admin::color.colorlist', ['list' => $list])->render();
        
        return response()->json(['list' => [], 'content' => $content, 'pagination' => '-na-', 'token' => $token]);
    }
    
    public function postCreate()
    {
        $lang = new Language();
        $lang_list = $lang->get();
        
        $col = new Color;
        $col->save();
        
        foreach ($lang_list as $v) {
            $collang = new ColorLang();
            $collang->id = $col->id;
            $collang->lang_id = $v->id;
            $collang->save();
        }
        
        return response()->json(['id' => $col->id, 'token' => csrf_token()]);
    }
    
    public function postSave(Request $request)
    {
        $lang = new Language();
        $lang_list = $lang->get();
        
        $id = $request->input('id');
        
        $col = Color::find($id);
        $col->name = $request->input('name');
        $col->ord = $request->input('ord');
        $col->code = $request->input('code');
        $col->save();
        
        foreach ($lang_list as $v) {
            ColorLang::where('id', $id)
                ->where('lang_id', $v->id)
                ->update([
                    'title' => $request->input('title')[$v->id]
                ]);
        }
        
        return response()->json(['state' => true, 'token' => csrf_token()]);
    }
    
    public function postGet(Request $request)
    {
        $id = $request->input('id');
        $lang_list = Language::get();
        
        $data = Color::find($id);
        $collang = new ColorLang();
        
        if ($data) {
            $lang_data = [];
            foreach ($lang_list as $v) {
                $lang_data[$v->id] = $collang->where('id', $id)->where('lang_id', $v->id)->first();
            }
        }
        
        $content = $content = view('clixy/admin::color.colorform', ['module' => 'color', 'data' => $data, 'lang_data' => $lang_data, 'lang_list' => $lang_list])->render();
        
        return response()->json(['id' => 0, 'content' => $content, 'token' => csrf_token()]);
    }
    
    public function postRemove(Request $request)
    {
        Color::where('id', $request->input('id'))->delete();
        
        return response()->json(['state' => true, 'msg' => 'done', 'token' => csrf_token()]);
    }
}