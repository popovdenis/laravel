<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Validator;
use Response;
use App\Photo;
use App\AlbumImage;
use App\Album;
use Illuminate\Http\Request;
use App\Model\ImageLib;
use Chumper\Zipper\Zipper;

/**
 * Class ImageController
 *
 * @package App\Http\Controllers
 */
class ImageController extends Controller
{
    const IMAGE_DESTINATION = 'uploads';
    
    const TEMP_DESTINATION = 'temp';
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
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
        $uploadedFile = Input::file('file')->move(self::TEMP_DESTINATION, $fileName . '.' . $extension);
    
        $config = [];
        $config['image_library'] = 'gd2';
        $config['source_image'] = public_path() . '/' . $uploadedFile;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = 200;
        $config['height']       = 200;
    
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
    
            // move original file
            \Storage::disk('public')->move(
                self::TEMP_DESTINATION . '/' . $image,
                self::IMAGE_DESTINATION . '/' . $image
            );
            // move thumbnail
            \Storage::disk('public')->move(
                self::TEMP_DESTINATION . '/' . $filename[0] . '_thumb.' . $ext,
                self::IMAGE_DESTINATION . '/' . $filename[0] . '_thumb.' . $ext
            );
            
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
    
    public function download(Request $request)
    {
        $albumId = $request->get('albumid', []);
        $photosIds = $request->get('photosIds', []);

        $album = Album::find($albumId);
        $photos = $album->images($album, $photosIds);

        $zip = new Zipper;
        $zipName = time() . rand(1111, 9999);
        $archive = $zip->make(public_path('uploads/' . $zipName . '.zip'));

        if (!empty($photos)) {
            foreach ($photos as $image) {
                $archive->add($image->path);
            }
        }

        $zipPath = $archive->getFilePath();
        $archive->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
    
    private function _removePhoto($photoId, $albumId)
    {
        $album = Album::find($albumId);
        if ($album->owner()->id === $this->getCurrentUser()->id) {
            $photos = $album->images($album, [$photoId]);
            foreach ($photos as $photo) {
                foreach ($photo->comments()->get()->all() as $comment) {
                    $comment->delete();
                }
                $photo->delete();
            }
            
            return true;
        }
        
        \Session::flash('error', $album->title . ' does not belong to the current user.');
        
        return false;
    }
    
    /**
     * Remove photos
     */
    public function remove(Request $request)
    {
        $albumId = (int) $request->get('albumid');
        $photosIds = $request->get('photosIds');
    
        $result = true;
        if (!empty($photosIds)) {
            foreach ($photosIds as $photoId) {
                if (!$this->_removePhoto(intval($photoId), intval($albumId))) {
                    $result = false;
                }
            }
        }
        $message = ($result) ? 'photo.deleted.selected.success' : 'photo.deleted.selected.failure';
    
        return redirect()->route('album.show', $albumId)->with('success', __($message));
    }
}
