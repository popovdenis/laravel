<?php
/**
 * User: Denis Popov
 * Date: 15.10.2017
 * Time: 14:08
 */

namespace App\Http\Controllers;

use App\Model\ImageLib;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    const AVATAR_DESTINATION = 'uploads/users';
    
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
        $usersOnPage = config('pagination.albums.items_per_page');
        
        $users = User::where('is_admin', 0)
            ->orderBy('firstname', 'ASC')
            ->orderBy('lastname', 'ASC')
            ->paginate($usersOnPage);
        $currentUser = Auth::getUser();
        $pageOwner = $currentUser;
        
        return view('user.index', compact('users', 'currentUser', 'pageOwner'))
            ->with('i', ($request->input('page', 1) - 1) * $usersOnPage);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
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
                'email' => 'required',
                'password' => 'required',
            ]
        );
        
        User::create($request->all());
        
        return redirect()->route('user.index')
            ->with('success', 'User has been created successfully');
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
        $albumsOnPage = config('pagination.albums.items_per_page');
        
        $user = User::find($id);
        $albums = $user->albums()->orderBy('id', 'DESC')->paginate($albumsOnPage);
        $currentUser = Auth::getUser();
        $pageOwner = $user;
        
        return view('user.show', compact('user', 'albums', 'currentUser', 'pageOwner'));
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
        $user = User::find($id);
        
        return view('user.edit', compact('user'));
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
        $this->validate($request, ['email' => 'required']);
    
        $user = User::find($id);
        
        $file = $request->file('file');
        
        if (!empty($file)) {
            $extension = $file->getClientOriginalExtension();
            $fileName = 'user_avatar_' . $user->getAuthIdentifier();
            $uploadedFile = $file->move(self::AVATAR_DESTINATION, $fileName . '.' . $extension);
            
            $config = [];
            $config['image_library'] = 'gd2';
            $config['source_image'] = $uploadedFile->getRealPath();
            $config['create_thumb'] = false;
            $config['maintain_ratio'] = TRUE;
            $config['width']         = 120;
            $config['height']       = 120;
    
            $imageLib = new ImageLib($config);
            $imageLib->resize();
    
            $request->merge(['avatar_path' => $uploadedFile->getPath() . '/' . $uploadedFile->getFilename()]);
        }
        $requestData = $request->all();
        if (!empty($requestData['password'])) {
            $requestData['password'] = Hash::make($requestData['password']);
        }
    
        $user->update($requestData);
        
        return redirect()->route('user.index')->with('success', __('user.updated.success'));
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
        User::find($id)->delete();
        
        return redirect()->route('User.index')->with('success', __('user.deleted.success'));
    }
}
