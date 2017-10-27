<?php
/**
 * User: Denis Popov
 * Date: 15.10.2017
 * Time: 14:08
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Album;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::orderBy('id', 'DESC')->paginate(30);
        
        return view('user.index', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * 30);
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
        $user = User::find($id);
        $albums = $user->getAlbums()->orderBy('id', 'DESC')->paginate(30);
        $currentUser = Auth::getUser();
        
        return view('user.show', compact('user', 'albums', 'currentUser'));
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
        $this->validate(
            $request, [
                'email' => 'required',
            ]
        );
        
        $file = $request->file('file');
        
        if (!empty($file)) {
//            foreach ($files as $file) {
                // Set the destination path
                $destinationPath = 'uploads';
                // Get the orginal filname or create the filename of your choice
                $filename = $file->getClientOriginalName();
                // Copy the file in our upload folder
//                $file->move($destinationPath, $filename);
                Storage::put($file->getClientOriginalName(), file_get_contents($file));
//            }
        }
        
        User::find($id)->update($request->all());
        
        return redirect()->route('user.index')
            ->with('success', 'User has been updated successfully');
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
        
        return redirect()->route('User.index')
            ->with('success', 'User has been deleted successfully');
    }
}
