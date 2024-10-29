<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;


class PermissionController extends Controller
{

    public function __construct()
    {
        // Apply permissions with correct syntax
        $this->middleware(['permission:View Permissions'], ['only' => ['index']]);
        $this->middleware(['permission:Edit Permissions'], ['only' => ['edit']]);
        $this->middleware(['permission:	Create Permissions'], ['only' => ['create']]);
        $this->middleware(['permission:Delete Permissions'], ['only' => ['destroy']]);
    }

    public function index(){

        $permissions = Permission::orderby('created_at', 'DESC')->paginate(10);
        return view('permissions.list',[
            'permissions'=> $permissions

        ]);


    }

//this method will show the permission page
    public function create(){

        return view('permissions.create');

    }

//this method will incert a permission in db
    public function store(Request $request){

        $validator = validator::make($request->all(),[
            'name' => 'required|unique:permissions,name',
        ]);

        if($validator->passes()){
            Permission::create(['name'=> $request->name]);

            return redirect()->route('permissions.index')->with('done');


        }
        else{
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);
        }



    }

//this method will edit the permission page
    public function edit($id){

        $permission = Permission::findOrFail($id);
        return view('permissions.edit',[
            'permission'=> $permission
        ]);

    }

 //this method will update the permission page
    public function update($id,Request $request){
        $permission = Permission::findOrFail($id);

        $validator = validator::make($request->all(),[
            'name' => 'required|unique:permissions,name,'.$id.',id'
        ]);

        if($validator->passes()){
            //Permission::create(['name'=> $request->name]);
            $permission->name=$request->name;
            $permission->save();


            return redirect()->route('permissions.index')->with('Upades');


        }
        else{
            return redirect()->route('permissions.edit',$id)->withInput()->withErrors($validator);
        }


    }



//this method will delete the permission page

    public function destroy(Request $request){

$permission=Permission::findOrFail($request->id);
$permission->delete();
return redirect()->route('permissions.index')->with('delete');




    }
}
