<?php
/**
 * User: Denis Popov
 * Date: 15.10.2017
 * Time: 13:45
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Album;
use App\AlbumImage;
use Folklore\Image\Facades\Image;
use Chumper\Zipper\Zipper;
use Illuminate\Support\Facades\Auth;

class AlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $albums = Album::orderBy('id', 'DESC')->paginate(3);
        
        return view('album.index', compact('albums'))
            ->with('i', ($request->input('page', 1) - 1) * 3);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('album.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request, [
            'title' => 'required',
        ]);
        $request->merge(['user_id' => $this->getCurrentUser()->getAuthIdentifier()]);
    
        Album::create($request->all());
    
        return response()->json(array('message'=> __('album.created.successfully')), 200);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $album = Album::find($id);
        $photos = $album->images($album);
        $currentUser = $this->getCurrentUser();
    
        if ($album->owner()->id === $currentUser->getKey('id')) {
            $newComments = $album->comments(true);
            if ($newComments) {
                $album->owner()->decreaseNewComments($album->getCountComments($newComments));
                $album->markCommentsAsRead($newComments);
            }
        }
        
        return view('album.show', compact('album', 'photos', 'currentUser'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $album = Album::find($id);
        
        return view('Album.edit', compact('album'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request, [
            'title' => 'required',
        ]);
    
        Album::find($id)->update($request->all());
    
        return response()->json(array('message'=> 'updated.successfully'), 200);
    }
    
    public function download($id)
    {
        $album = Album::find($id);
        $zip = new Zipper;
    
        $images = [];
        foreach ($album->images($album) as $image) {
            $images[] = $image->path;
        }
        $zipName = time() . rand(1111, 9999);
        $archive = $zip->make(public_path('uploads/' . $zipName . '.zip'))->add($images);
        $zipPath = $archive->getFilePath();
        $archive->close();
    
        return response()->download($zipPath);
    }
    
    private function _removeAlbum($albumId)
    {
        $album = Album::find($albumId);
    
        foreach ($album->images($album) as $photo) {
            foreach ($photo->comments()->get()->all() as $comment) {
                $comment->delete();
            }
            $photo->delete();
        }
        $album->delete();
    }
    
    public function removeList(Request $request)
    {
        $albumsIds = $request->get('albumsIds', []);
        
        if (!empty($albumsIds)) {
            foreach ($albumsIds as $albumsId) {
                $this->_removeAlbum(intval($albumsId));
            }
        }
    
        return response()->json(['message'=> __('album.deleted.selected.success')], 200);
    }
    
    public function downloadList(Request $request)
    {
        $albumsIds = $request->get('albumsIds', []);
    
        $zip = new Zipper;
        $images = [];
        foreach ($albumsIds as $albumsId) {
            $album = Album::find($albumsId);
            foreach ($album->images($album) as $image) {
                $images[] = $image->path;
            }
        }

        $zipName = time() . rand(1111, 9999);
        $archive = $zip->make(public_path('uploads/' . $zipName . '.zip'))->add($images);
        $zipPath = $archive->getFilePath();
        $archive->close();

        return response()->download($zipPath);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->_removeAlbum($id);
        
        return redirect()->route('user.show', $this->getCurrentUser()->id)
            ->with('success', 'Album deleted successfully');
    }
}
