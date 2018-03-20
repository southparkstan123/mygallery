<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function createImages(Request $request){

        $validator = Validator::make($request->all(), [
            'File' => 'required|image'
        ]);

        if($validator->fails()){
            $status = [
                'success' => false,
                'message' => 'Image cannot be created',
                'errors' => $validator->errors()
            ];
            return response()->json($status);
        }else{
            $file = $request->file('File');
            $cloudder = Cloudder::upload($file)->getResult();

            //Create a new record to DB
            $image = new Image();
            
            $image->image = $cloudder['url'];
            $image->public_id = $cloudder['public_id'];
            $image->gallery_id = $request->get('gallery_id');
            $image->published = false;
            $image->order = Image::count()+1;
            $image->width = $cloudder['width'];
            $image->height = $cloudder['height'];
            $image->bytes = $cloudder['bytes'];
            $image->format = $cloudder['format'];

            $image->save();

            $status = [
                'success' => true, 
                'message' => 'Image Created',
                'results' => Image::where('gallery_id','=',$request->get('gallery_id'))->get()
            ];
            return response()->json($status);
        }
    }

    public function deleteImageByID($id){
        $image = Image::findOrFail($id);

        $gallery_id = $image->gallery_id;

        if($image->delete()){
            $cloudder = Cloudder::destroy($image->public_id);
            $success = [
                'success' => true, 
                'message' => 'Image Deleted',
                'results' => Image::where('gallery_id','=',$gallery_id)->get()
            ];
            return response()->json($success);
        }else{
            $success = [
                'success' => false, 
                'message' => 'Image cannot deleted'
            ];
            return response()->json($success);
        }
    }

    public function updateImageByID($id,Request $request){

        $image = Image::findOrFail($id);

        $gallery_id = $image->gallery_id;
        $published = $image->published;

        //Update an selected record to DB
        $image->gallery_id = $request->gallery_id;
        $image->published = ($request->published === "1" || $request->published === "true");
        $image->description = $request->description;

        if($image->save()){
            $success = [
                'success' => true, 
                'message' => 'Image Updated'
        ];
            return response()->json($success);
        }else{
            $error = ['success' => false, 'message' => 'Image cannot updated'];
            return response()->json($error);
        }
    }

    public function publishAllImages(Request $request){

        $images = Image::where('gallery_id','=', $request->get('gallery_id'))
        ->update(['published' => true]);

        if($images){
            $success = [
                'success' => true, 
                'message' => 'Images Published',
                'results' => Image::where('gallery_id','=',$request->get('gallery_id'))->get()
        ];
            return response()->json($success);
        }else{
            $error = ['success' => false, 'message' => 'Image cannot updated'];
            return response()->json($error);
        }
    }

    // public function increaseOrder(){
    //     $imageOrderToBeSwapped = Image::findOrFail($id);
    //     $adjacentImage = Image::findOrFail($id+1);

    //     $imageOrderToBeSwapped->order = $imageOrderToBeSwapped->order + 1;
    //     $adjacentImage = $adjacentImage - 1;

    //     $imageOrderToBeSwapped->save();
    //     $adjacentImage->save();

    //     $success = ['success' => true, 'message' => 'Image order modified!'];
    //     return response()->json($success);
    // }

    // public function decreaseOrder(){
    //     $imageOrderToBeSwapped = Image::findOrFail($id);
    //     $adjacentImage = Image::findOrFail($id - 1);

    //     if(){
    //         $imageOrderToBeSwapped->order = $imageOrderToBeSwapped->order - 1;
    //         $adjacentImage = $adjacentImage + 1;
    //     }
        

    //     $imageOrderToBeSwapped->save();
    //     $adjacentImage->save();

    //     $success = ['success' => true, 'message' => 'Image order modified!'];
    //     return response()->json($success);
    // }

    // public function getImagesByGalleryID($gallery_id){
    //     $images = Image::where('gallery_id','=',$gallery_id)->with('gallery')->get();
    //     return $images;
    // }
}
