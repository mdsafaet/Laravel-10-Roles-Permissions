<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct()
    {
        // Apply permissions with correct syntax
        $this->middleware(['permission:View Users'], ['only' => ['index']]);
        $this->middleware(['permission:Edit Users'], ['only' => ['edit']]);
        $this->middleware(['permission:	Create Users'], ['only' => ['create']]);
        $this->middleware(['permission:Delete Users'], ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $users=User::latest()->paginate(10);
    return view('users.list',[
    'users'=>$users,
    ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $roles = Role::orderBy('name', 'ASC')->get();

        return view('users.create',[

        'roles'=>$roles,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validator=Validator::make($request->all(), [
        'name'=>'required|min:3',
        'email'=>"required|email|unique:users,email",
        "password"=> "required|min:3|same:confirm_password",
        'confirm_password'=>'required'

        ]);

if($validator->fails()){

return redirect()->route('users.create')->withInput()->withErrors($validator);
}
$user= new User();
$user->name=$request->name;
$user->email=$request->email;
$user->password=Hash::make($request->password) ;
$user->save();

$user->syncRoles($request->role);

return redirect()->route('users.index')->with('USer Created');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user=User::findOrFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();
        $hasRoles=$user->roles->pluck('id');

        return view('users.edit',[
        'user'=>$user,
        'roles'=>$roles,
         'hasRoles'=> $hasRoles

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user=User::findOrFail($id);

        $validator=Validator::make($request->all(), [
        'name'=>'required|min:3',
        'email'=>"required|email|unique:users,email,{$id},id"
        ]);

if($validator->fails()){

return redirect()->route('users.edit',$id)->withInput()->withErrors($validator);
}

$user->name=$request->name;
$user->email=$request->email;
$user->save();

$user->syncRoles($request->role);

return redirect()->route('users.index')->with('Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {


        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Article deleted successfully.');
    }
}
