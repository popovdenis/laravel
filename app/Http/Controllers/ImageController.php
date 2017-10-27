<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Validator;
use Response;
use App\Photo;
use App\AlbumImage;
use App\Album;
use Illuminate\Http\Request;
use Folklore\Image\Facades\Image;
use App\Model\ImageLib;

/**
 * Class ImageController
 *
 * @package App\Http\Controllers
 */
class ImageController extends BaseController
{
    const IMAGE_DESTINATION = 'uploads';
    
    /**
     * Upload files.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function uploadFiles()
    {
        $input = Input::all();
        
        $rules = ['file' => 'image|max:50000'];
        $validation = Validator::make($input, $rules);
        
        if ($validation->fails()) {
            return Response::make($validation->errors()->first(), 400);
        }
        
        $extension = Input::file('file')->getClientOriginalExtension(); // getting file extension
        $fileName = time() . rand(1111, 9999);
        $uploadedFile = Input::file('file')->move(self::IMAGE_DESTINATION, $fileName . '.' . $extension);
    
        $config = [];
        $config['image_library'] = 'gd2';
        $config['source_image'] = public_path() . '/' . $uploadedFile;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = 120;
        $config['height']       = 120;
    
        $imageLib = new ImageLib($config);
        $imageLib->resize();
        
        if ($uploadedFile) {
            $response = [
                'result' => true,
                'filename' => $uploadedFile->getFilename()
            ];
            return Response::json($response, 200);
        } else {
            $response = ['result' => false];
            return Response::json($response, 400);
        }
    }
    
    /**
     * Remove photos
     */
    public function removePhotos(Request $request)
    {
        $albumId = $request->get('albumid');
        $photosIds = $request->get('photos');
        
        if (empty($albumId) || empty($photosIds)) {
            return Response::json('error', 400);
        }
    
        $album = Album::find($albumId);
        $photos = $album->images($album, $photosIds);
        
        if (!empty($photos)) {
            Photo::destroy($photosIds);
        }
    
        return Response::json('success', 200);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $albumId = $request->get('albumid');
        $images = $request->get('images');
        
        if (empty($albumId) || empty($images)) {
            return Response::json('error', 400);
        }
    
        $result = true;
        foreach ($images as $image) {
            $filename = explode('.', $image);
            $ext = array_pop($filename);
            
            $imageData = [
                'title' => $image,
                'path' => self::IMAGE_DESTINATION . '/' . $image,
                'path_thumb' => self::IMAGE_DESTINATION . '/' . $filename[0] . '_thumb.' . $ext
            ];
            $imageObj = new Photo($imageData);
            
            if($imageObj->save()) {
                $albumData = [
                    'album_id' => $albumId,
                    'image_id' => $imageObj->id
                ];
                $albumImageObj = new AlbumImage($albumData);
    
                if(!$albumImageObj->save()) {
                    $result = false;
                }
            }
        }
        
        if ($result) {
            $response = [
                'result' => true,
                'message' => __('album.You have uploaded images successfully')
            ];
            return Response::json($response, 200);
        } else {
            return Response::json(array('error' => true), 400);
        }
    }
}
