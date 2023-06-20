<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function allPermission()
    {
        $permissions = Permission::all();
        return view('backend.pages.permission.all_permission', compact('permissions'));
    }

    public function addPermission()
    {
        return view('backend.pages.permission.add_permission');
    }

    public function storePermission(Request $request)
    {
        $role = Permission::create([
           'name' => $request->name,
           'group_name' => $request->group_name,
         ]);

        $notification = array(
            'message' => 'Permission Inserted Successfully',
            'alert-type' => 'success'

        );

        return redirect()->route('all.permission')->with($notification);
    }


    public function editPermission($id)
    {
        $permission = Permission::findOrFail($id);
        return view('backend.pages.permission.edit_permission', compact('permission'));
    }

    public function updatePermission(Request $request)
    {
        $per_id = $request->id;

        Permission::findOrFail($per_id)->update([
           'name' => $request->name,
           'group_name' => $request->group_name,
        ]);

        $notification = array(
            'message' => 'Permission Updated Successfully',
            'alert-type' => 'success'

        );
        return redirect()->route('all.permission')->with($notification);
    }

    public function deletePermission($id)
    {
        Permission::findOrFail($id)->delete();

        $notification = array(
         'message' => 'Permission Deleted Successfully',
         'alert-type' => 'success'

         );

        return redirect()->back()->with($notification);
    }

   // Roles

   public function allRoles()
   {
       $roles = Role::all();
       return view('backend.pages.roles.all_roles', compact('roles'));
   }


   public function addRoles()
   {
       return view('backend.pages.roles.add_roles');
   }

   public function storeRoles(Request $request)
   {

       $role = Role::create([
          'name' => $request->name,
      ]);

       $notification = array(
           'message' => 'Role Inserted Successfully',
           'alert-type' => 'success'

       );
       return redirect()->route('all.roles')->with($notification);
   }

   public function editRoles($id)
   {
       $roles = Role::findOrFail($id);
       return view('backend.pages.roles.edit_roles', compact('roles'));
   }


   public function updateRoles(Request $request)
   {
       $role_id = $request->id;

       Role::findOrFail($role_id)->update([
          'name' => $request->name,
     ]);

       $notification = array(
           'message' => 'Role Updated Successfully',
           'alert-type' => 'success'

       );
       return redirect()->route('all.roles')->with($notification);
   }

   public function deleteRoles($id)
   {
       Role::findOrFail($id)->delete();

       $notification = array(
           'message' => 'Role Deleted Successfully',
           'alert-type' => 'success'

       );
       return redirect()->back()->with($notification);
   }

   public function addRolesPermissio()
   {
       $roles = Role::all();
       $permissions = Permission::all();
       $permission_groups = User::getpermissionGroups();
       return view('backend.pages.roles.add_roles_permission', compact('roles', 'permissions', 'permission_groups'));
   }

   public function rolePermissionStore(Request $request)
   {
       $data = array();
       $permissions = $request->permission;

       foreach($permissions as $key => $item) {
           $data['role_id'] = $request->role_id;
           $data['permission_id'] = $item;

           DB::table('role_has_permissions')->insert($data);
       }

       $notification = array(
          'message' => 'Role Permission Added Successfully',
          'alert-type' => 'success'

        );
       return redirect()->route('all.roles.permission')->with($notification);
   }

   public function allRolesPermissio()
   {
       $roles = Role::all();
       return view('backend.pages.roles.all_roles_permission', compact('roles'));
   }

   public function adminEditRoles($id)
   {
       $role = Role::findOrFail($id);
       $permissions = Permission::all();
       $permission_groups = User::getpermissionGroups();
       return view('backend.pages.roles.role_permission_edit', compact('role', 'permissions', 'permission_groups'));
   }

   public function rolePermissionUpdate(Request $request, $id)
   {
       $role = Role::findOrFail($id);
       $permissions = $request->permission;

       if (!empty($permissions)) {
           $role->syncPermissions($permissions);
       }

       $notification = array(
           'message' => 'Role Permission Updated Successfully',
           'alert-type' => 'success'

       );
       return redirect()->route('all.roles.permission')->with($notification);
   }



   public function adminDeleteRoles($id)
   {
       $role = Role::findOrFail($id);
       if (!is_null($role)) {
           $role->delete();
       }

       $notification = array(
          'message' => 'Role Permission Deleted Successfully',
          'alert-type' => 'success'

      );
       return redirect()->back()->with($notification);
   }
}