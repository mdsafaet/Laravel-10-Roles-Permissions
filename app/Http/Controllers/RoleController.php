<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Contracts\Role as ContractsRole;
use Spatie\Permission\Models\role;
use Spatie\Permission\Models\Permission;



class RoleController extends Controller
{
    public function __construct()
    {
        // Apply permissions with correct syntax
        $this->middleware(['permission:View Roles'], ['only' => ['index']]);
        $this->middleware(['permission:Edit Roles'], ['only' => ['edit']]);
        $this->middleware(['permission:Create Roles'], ['only' => ['create']]);
        $this->middleware(['permission:Delete Roles'], ['only' => ['destroy']]);
    }
    public function index(){

        $roles = Role::orderby('created_at', 'DESC')->paginate(10);
        return view('roles.list',[
            'roles'=> $roles

        ]);
    }

    public function create(){
        $permissions = Permission::orderBy('created_at', 'ASC')->get();
        return view('roles.create', [
            'permissions' => $permissions
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name', // Fix the unique validation rule
        ]);

        if ($validator->passes()) {
            $role = Role::create(['name' => $request->name]);

            if (!empty($request->permission)) {
                foreach ($request->permission as $name) {
                    $role->givePermissionTo($name);
                }
            }

            // Flash a success message after creation
            return redirect()->route('roles.index')->with('status', 'Role created successfully!');
        } else {
            // Redirect back with input and errors
            return redirect()->route('roles.create')
                             ->withInput()
                             ->withErrors($validator)
                             ->with('status', 'Failed to create the role, please check the inputs.');
        }
    }

    public function edit( $id){
        $role = Role::findOrFail($id);
        $hasPermission=$role->permissions->pluck('name');
        $permissions = Permission::orderBy('created_at', 'ASC')->get();

        return view('roles.edit',[
        'hasPermission'=>$hasPermission,
        'role'=>$role,
        'permissions'=> $permissions
        ]);

    }
    public function update($id,Request $request){
        $role = Role::findOrFail($id);

        $validator = validator::make($request->all(),[
            'name' => 'required|unique:roles,name,'.$id.',id'
        ]);

        if($validator->passes()){
            //Permission::create(['name'=> $request->name]);
            $role->name=$request->name;
            $role->save();

            if (!empty($request->permission)) {
                  $role->syncPermissions($request->permission);
                }
              else{
                    $role->syncPermissions([]);
                }


            return redirect()->route('roles.index')->with('Updated');
            }
            else{
                return redirect()->route('roles.edit',$id)->withInput()->withErrors($validator);

            }
        }
        public function destroy(Request $request){

            $role=Role::findOrFail($request->id);
            $role->delete();
            return redirect()->route('roles.index')->with('delete');
    }
}

