<?php

namespace Clixy\Admin\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Clixy\Core\Models\Language;
use Clixy\Core\Models\Item;
use Clixy\Core\Models\ItemLang;
use Clixy\Core\Models\ItemCategory;
use Clixy\Core\Models\Category;
use Clixy\Core\Models\ItemDate;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        return view('clixy/admin::item/item', ['module' => 'item']);
    }
    
    public function postList(Request $request)
    {
        $o = new Item();
        $ol = new ItemLang();
        
        $list = $o->orderBy('ord', 'asc')->get();
        if ($list) {
            foreach ($list as $v) {
                $v->lang = $ol->where('id', $v->id)->where('lang_id', 1)->first();
            }
        }
        
        return response()->json([
			'list' => [],
			'content' => view('clixy/admin::item/itemlist', ['module' => 'item', 'list' => $list])->render(),
			'pagination' => '-na-',
			'token' => csrf_token()
		]);
    }
    
    public function postCreate(Request $request)
    {
        $lang = new Language();
        $lang_list = $lang->get();
        
        $o = new Item();
        $o->save();
        
        foreach ($lang_list as $v) {
            $ol = new ItemLang();
            $ol->id = $o->id;
            $ol->lang_id = $v->id;
            $ol->save();
        }
        
        return response()->json([
			'id' => $o->id,
			'state' => true,
			'token' => csrf_token()
		]);
    }
    
    /**
     * 
     * @param Request $request
     * @return json
     */
    public function postDateCreate(Request $request)
    {
        $o = new ItemDate();
        $o->item_id = $request->input('id');
        $o->date_at = $request->input('date');
        $o->save();
        
        return response()->json([
			'id' => $o->id,
			'state' => true,
			'token' => csrf_token()
		]);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function postDateList(Request $request)
    {
        $o = new ItemDate();
        
        $list = $o->where('item_id', $request->input('id'))->orderBy('date_at', 'desc')->get();
        
        return response()->json([
			'list' => [],
			'content' => view('clixy/admin::item/itemdatelist', ['list' => $list])->render(),
			'token' => csrf_token()
		]);
    }
    
    public function postRemove(Request $request)
    {
        $id = $request->input('id');
        
        $o = new Item();
        
        $o->find($id)->delete();
        
        return response()->json([
			'state' => true,
			'msg' => 'done',
			'token' => csrf_token()
		]);
    }
    
    public function postDateRemove(Request $request)
    {
        $id = $request->input('id');
        
        $o = new ItemDate();
        
        $o->find($id)->delete();
        
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
        $o = new Item();
        $ol = new ItemLang();
        $oc = new ItemCategory();
        $cat = new Category();
        $od = new ItemDate();
        
        $lang_list = $language->get();
        $data = $o->find($id);
        if ($data) {
            $data_category_list = [];
            $rr = $oc->where('item_id', $data->id)->get();
            if ($rr) {
                foreach ($rr as $vv) {
                    $data_category_list[] = (int) $vv->category_id;
                }
            }
            $data->category_list = $data_category_list;
            // var_dump($data->category_list);
            
            $data_date_list = [];
            $rrr = $od->where('item_id', $data->id)->get();
            if ($rr) {
                foreach ($rrr as $vvv) {
                    $data_date_list[] = $vvv;
                }
            }
            $data->date_list = $data_date_list;
            // var_dump($data->category_list);
            
            // var_dump($data->category_list);
            $lang_data = [];
            foreach ($lang_list as $k => $v) {
                $lang_data[$v->id] = $ol->where('id', $id)->where('lang_id', $v->id)->first();
            }
        }
        
        $category_list = [];
        $r = $cat->where('active', 1)->orderBy('ord', 'asc')->get();
        if ($r) {
            foreach ($r as &$v) {
                $v->lang = $v->lang()->where('lang_id', 1)->first();
                $category_list[] = $v;
            }
        }
        
        $content = view(
            'clixy/admin::item/itemform',
            [
                'module' => 'item',
                'data' => $data,
                'lang_data' => $lang_data,
                'lang_list' => $lang_list,
                'category_list' => $category_list
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
        $lang = new Language();
        $lang_list = $lang->get();
        
        $id = $request->input('id');
        
        $о = Item::find($id);
        $о->slug = $request->input('slug');
        $о->ord = $request->input('ord');
        $о->active = $request->input('active');
        $о->days = $request->input('days');
        $о->min_price = $request->input('min_price');
        $о->max_price = $request->input('max_price');
        $о->port_from = $request->input('port_from');
        $о->port_to = $request->input('port_to');
        $о->save();
        
        foreach ($lang_list as $v) {
            ItemLang::where('id', $id)
                ->where('lang_id', $v->id)
                ->update([
                    'title' => $request->input('title')[$v->id],
                    'uri' => $request->input('uri')[$v->id],
                    'content' => $request->input('content')[$v->id]
                ]);
        }
        
        $assoc = new ItemCategory();
        // remove all
        $assoc->where('item_id', $id)->delete();
        
        // set all
        $list = $request->input('category');
        if ($list) {
            foreach ($list as $k => $v) {
                $as = new ItemCategory();
                $as->item_id = $id;
                $as->category_id = $k;
                $as->save();
            }
        }
        
        return response()->json([
			'state' => true,
			'token' => csrf_token()
		]);
    }
}