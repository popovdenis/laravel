<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
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
        $users = User::orderBy('id', 'DESC')->paginate(3);
        
        return view('admin.user.index', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * 3);
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
    
        $request->merge(['password' => Hash::make($request->password)]);
        
        User::create($request->all());
        
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
        
        return redirect()->route('admin.index')
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
        
        return redirect()->route('admin.index')
            ->with('success', 'User has been deleted successfully');
    }
}
