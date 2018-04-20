<?php
/**
 * User: Denis Popov
 * Date: 15.10.2017
 * Time: 13:45
 */

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use App\Album;
use Chumper\Zipper\Zipper;

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
        $showOnPage = config('album.pagination.showonpage');
        
        $albums = Album::orderBy('id', 'DESC')->paginate($showOnPage);
        
        return view('album.index', compact('albums'))
            ->with('i', ($request->input('page', 1) - 1) * $showOnPage);
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
    
        if($request->ajax()){
            return response()->json(array('message'=> __('album.created.successfully')), 200);
        }
    
        return redirect()
            ->route('user.show', $this->getCurrentUser()->id)
            ->with('success', __('album.created.success'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $photoid = $request->get('photo', null);
        $commentid = $request->get('comment', null);

        $album = Album::find($id);
        $isRead = ($commentid) ? (Comment::find($commentid))->is_new : null;
        $photos = $album->images($album);
        $currentUser = $this->getCurrentUser();
        $pageOwner = $album->owner();
        
        return view('album.show', compact('photoid', 'commentid', 'isRead', 'album', 'photos', 'currentUser', 'pageOwner'));
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
    
    public function download(Request $request)
    {
        $albumsIds = $request->get('albumsIds', []);
        
        $zip = new Zipper;
        $zipName = time() . rand(1111, 9999);
        $archive = $zip->make(public_path('uploads/' . $zipName . '.zip'));
        
        foreach ($albumsIds as $albumId) {
            $album = Album::find($albumId);
            foreach ($album->images($album) as $image) {
                $archive->add($image->path);
            }
        }
        
        $zipPath = $archive->getFilePath();
        $archive->close();
    
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
    
    private function _removeAlbum($albumId)
    {
        $album = Album::find($albumId);
        if ($album->owner()->id === $this->getCurrentUser()->id) {
            foreach ($album->images($album) as $photo) {
                foreach ($photo->comments()->get()->all() as $comment) {
                    $comment->delete();
                }
                $photo->delete();
            }
            $album->delete();
            
            return true;
        }
    
        \Session::flash('error', $album->title . ' does not belong to the current user.');
        
        return false;
    }
    
    public function removeList(Request $request)
    {
        $albumsIds = $request->get('albumsIds', []);
        
        $result = true;
        if (!empty($albumsIds)) {
            foreach ($albumsIds as $albumsId) {
                if (!$this->_removeAlbum(intval($albumsId))) {
                    $result = false;
                }
            }
        }
        $message = ($result) ? 'album.deleted.selected.success' : 'album.deleted.selected.failure';
        
        return redirect()->route('user.show', $this->getCurrentUser()->id)->with('success', __($message));
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
