<?php

namespace Clixy\Admin\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user() ? $request->user()->toArray() : ['id' => null];
        return view('clixy/admin::user.user', ['module' => 'user', 'user' => $user, 'token' => csrf_token()]);
    }
    
    public function postList()
    {
        $o = new User();
        
        $list = $o->orderBy('id', 'asc')->get();
        if ($list) {
            foreach ($list as $v) {
                // var_dump(ColorLang::where('id', $v->id)->where('lang_id', 1)->first());
                // $v->lang = ColorLang::where('id', $v->id)->where('lang_id', 1)->first();
            }
        }
        // var_dump($list);
        
        $content = view('clixy/admin::user.userlist', ['list' => $list])->render();
        
        return response()->json(['list' => [], 'content' => $content, 'pagination' => '-na-', 'token' => csrf_token()]);
    }
    
    public function postCreate()
    {
        $o = new User;
        $o->save();
        
        return response()->json(['id' => $o->id, 'token' => csrf_token()]);
    }
    
    public function postSave(Request $request)
    {
        $id = $request->input('id');
        
        $o = new User();
        
        $u = $o->find($id);
        $u->name = $request->input('name');
        $u->email = $request->input('email');
        if ($request->input('password')) {
            $u->password = bcrypt($request->input('password'));
        }
        $u->save();
        
        return response()->json(['state' => true, 'token' => csrf_token()]);
    }
    
    public function postGet(Request $request)
    {
        $id = $request->input('id');
        
        $o = new User;
        $data = $o->find($id);
        if ($data) {
            
        }
        $content = $content = view('clixy/admin::user.userform', ['module' => 'user', 'data' => $data])->render();
        return response()->json(['id' => 0, 'content' => $content, 'token' => csrf_token()]);
    }
    
    public function postRemove(Request $request)
    {
        User::where('id', $request->input('id'))->delete();
        return response()->json(['state' => true, 'msg' => 'done', 'token' => csrf_token()]);
    }
}