<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gallery;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use JD\Cloudder\Facades\Cloudder;

class GalleryController extends Controller
{
    public function listGalleries(){
        $result = Gallery::allRecordsOrderByUpdateAt()->with('images')->get();
        return $result;
    }

    public function getGalleryByID($id){
        $result = Gallery::with('images')->find($id);
        if($result === null){
            $status = ['errors' => ['message' => 'Gallery not found, please add the gallery record first.']];
            return response()->json($status,404);
        }else{
            return response()->json($result);
        }  
    }

    public function createGallery(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:galleries|max:255',
            'description' => 'max:1024',
            // 'File' => 'required|image'
        ]);

        if($validator->fails()){
            $status = [
                'success' => false,
                'message' => 'Gallery cannot be created',
                'errors' => $validator->errors()
            ];
            return response()->json($status);
        }else{   

            // $file = $request->file('File');
            // $cloudder = Cloudder::upload($file)->getResult();
            // $path = $cloudder['url'];
            // $public_id = $cloudder['public_id'];

            $name = $request->name;
            $published = $request->published;
            $description = $request->description;

            $gallery = new Gallery();

            $gallery->name = $name;
            $gallery->published = $published;
            $gallery->description = $description;
            // $gallery->thumbnail = $path;
            // $gallery->public_id = $public_id;
    
            if($gallery->save()){
                $status = [
                    'success' => true,
                    'message' => 'Gallery created',
                    'results' => Gallery::allRecordsOrderByUpdateAt()->get()
                ];
                return response()->json($status);
            }else{
                $status = [
                    'success' => false,
                    'message' => 'Gallery cannot be created',
                ];
                return response()->json($status);
            }
        }

    }

    public function deleteGalleryByID($id){
        $gallery = Gallery::with('images')->find($id);

        foreach($gallery->images as $image){
            $cloudder = Cloudder::destroy($image->public_id);
        }
        
        if($gallery->delete()){
            $status = [
                'success' => true,
                'message' => 'Gallery deleted',
                'results' => Gallery::allRecordsOrderByUpdateAt()->with('images')->get()
            ];
            return response()->json($status);
        }else{
            $status = [
                'success' => false,
                'message' => 'Gallery cannot be deleted',
            ];
            return response()->json($status);
        }
    }

    public function updateGalleryByID($id,Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:galleries,id,'.$id,
            'description' => 'max:1024',
        ]);

        if($validator->fails()){
            $status = [
                'success' => false,
                'message' => 'Gallery cannot be updated',
                'errors' => $validator->errors()
            ];
            return response()->json($status);
        }else{   

            $gallery = Gallery::find($id);

            $gallery->name =  $request->name;
            $gallery->published = $request->published;
            $gallery->description = $request->description;

            $gallery->save();

            $status = [
                'success' => true, 
                'message' => 'Gallery Updated',
                'results' => Gallery::allRecordsOrderByUpdateAt()->get()
            ];
            return response()->json($status);
        }
    }

    public function getGalleryWithImages($id){

            $result = Gallery::with('images')->find($id);
            if($result === null){
                $status = ['message' => 'Page not found', 'code' => 404];
                return response()->json($status);
            }else{
                $status = [
                    'images' => $result
                ];
                return response()->json($status);
            }  
    }

    public function getGalleryList(){
        $lists = Gallery::select('id','name')->get();
        return $lists;
    }

}
