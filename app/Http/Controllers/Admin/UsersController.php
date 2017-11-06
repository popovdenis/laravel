<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Model\ImageLib;

class UsersController extends Controller
{
    const AVATAR_DESTINATION = 'uploads/users';
    
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $usersOnPage = config('pagination.albums.items_per_page');
        
        $users = User::orderBy('id', 'DESC')->paginate($usersOnPage);
        
        return view('admin.user.index', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * $usersOnPage);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
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
    
        $data = $request->all();
        
        $data['password'] = Hash::make($request->password);
        
        User::create($data);
        
        return redirect()->route('admin.index')
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
        $user = User::find($id);
        
        return view('admin.user.show', compact('user'));
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
        
        return view('admin.user.edit', compact('user'));
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
        
        return redirect()->route('admin.index')->with('success', __('user.updated.success'));
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
        $user = User::find($id);
        if ($user->id === $this->getCurrentUser()->id) {
            return redirect()->route('admin.index')
                ->with('error', __('admin.delete.admin'));
        }
        
        $user->delete();
        
        return redirect()->route('admin.index')
            ->with('success', __('admin.user.deleted.success'));
    }
}
