<?php

namespace Clixy\Admin\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Clixy\Core\Models\Language;
use Clixy\Core\Models\Slide;
use Clixy\Core\Models\SlideLang;
use Clixy\Core\Models\Media;
use Clixy\Core\Models\MediaCategory;

class MediaController extends Controller
{
    public function postGetMediaPagination(Request $request)
    {
        $token = csrf_token();
        
        $media = new Media();
        
        $page = $request->input('page');
        $category_id = $request->input('type');
        $item_id = $request->input('item_id');
        
        $total = $media
                    ->where('category_id', $category_id)
                    ->where('item_id', $item_id)
                        ->count();
        
        $steps = ceil($total / 12);
        $pagination = view('clixy/admin::common.ajaxpagination', [
            "category_id" => $category_id,
            "pages" => $steps,
            "page" => $page,
            "obj" => "media",
            "act" => "get_page",
            "range" => 4
        ])->render();
        return response()->json([
			'pagination' => $pagination,
			'token' => $token
		]);
    }
    
    public function postGetMediaDetailList(Request $request)
    {
        $media = new Media();
        $mc = new MediaCategory();
        
        $category_id = $request->input('category_id');
        $item_id = $request->input('item_id');
        
        $obj = '';
        switch ($category_id) {
            case 1:
                $obj = 'slide';
            break;
        
            case 2:
                $obj = 'navigation';
            break;
        }
        
        $list = $media
                ->where('category_id', $category_id)
                ->where('item_id', $item_id)
                    ->get();
        if ($list) {
            foreach ($list as $v) {
                $v->category = $mc->find($v->category_id);
                $v->class = "jpg";
                $v->template = "JPG";
            }
        }
        // echo '<pre>' . print_r($list, true);
        
        $content = view('clixy/admin::multimedia.media_detail_list_ajax', [
            'obj' => $obj,
            'media_conf' => array(),
            'list' => $list
        ])->render();
        
        return response()->json([
			'content' => $content,
			'token' => csrf_token()
		]);
    }
    
    public function postRemove(Request $request)
    {
        $media = new Media();
        
        $id = $request->input('id');
        
        $media->find($id)->delete();
        
        return response()->json([
			'state' => true,
			'token' => csrf_token()
		]);
    }
    
    public function postUpload(Request $request)
    {
        $category_id = (int) $request->input('cat_id', 0);
        $item_id = (int) $request->input('item_id', 0);
        $file = $request->file('file');
        
		$state = true;
		
		
        $mc = new MediaCategory();

		$directory = rtrim(config('clixy.admin.upload_dir'), DIRECTORY_SEPARATOR);
        $directory .= DIRECTORY_SEPARATOR;
		$directory .= ltrim($mc->find($category_id)->directory, DIRECTORY_SEPARATOR);
        
        if ($file->isValid()) {
            $file->move($directory, $file->getClientOriginalName());
        }
        
        if ($state === true) {
            $media = new Media();
            $media->ord = 0;
            $media->item_id = $item_id;
            $media->category_id = $category_id;
            $media->type_id = 2;// JPG
            $media->file = $file->getClientOriginalName();
            $media->location = "";
            $media->width = 0;
            $media->height = 0;
            $media->fps = 0;
            $media->size = $file->getClientSize();
            $media->duration = 0;
            $media->mime = '-na-';//$file->getMimeType();
            $media->save();
        }
        
        return response()->json([
            'state' => $state,
            'directory' => $directory,
            'category_id' => $category_id,
            'item_id' => $item_id,
            'name' => $file->getClientOriginalName(), 
            'token' => csrf_token()
        ]);
    }
}