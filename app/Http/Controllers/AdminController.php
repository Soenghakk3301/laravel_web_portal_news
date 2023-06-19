<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function adminDashboard()
    {
        $id = Auth::user()->id;
        $adminData = User::findOrFail($id);
        return view('admin.index', compact('adminData'));
    }

    public function adminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
         'message' => 'Admin Logout Successfully',
         'alert-type' => 'info'

         );

        return redirect('/admin/logout/page')->with($notification);
    }

    public function adminLogin()
    {
        return view('admin.admin_login');
    }

    public function adminLogoutPage()
    {
        return view('admin.admin_logout');
    }


    public function adminProfile()
    {
        $id = Auth::user()->id;
        $adminData = User::findOrFail($id);
        return view('admin.admin_profile_view', compact('adminData'));
    }

    public function adminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->photo;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
         'message' => 'Admin Profile Updated Successfully',
         'alert-type' => 'success'

         );

        return redirect()->back()->with($notification);
    }

    public function adminChangePassword()
    {
        return view('admin.admin_change_password');
    }

    public function adminUpdatePassword(Request $request)
    {
        // Validation
        $request->validate([
           'old_password' => 'required',
           'new_password' => 'required|confirmed',
         ]);

        // Match The Old Password
        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with('error', "Old Password Doesn't Match!!");
        }
        // Update the new password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('status', "Password Change Successfully");
    }


    public function allAdmin()
    {
        $alladminuser = User::where('role', 'admin')->latest()->get();
        return view('backend.admin.all_admin', compact('alladminuser'));
    }

    public function addAdmin()
    {
        return view('backend.admin.add_admin');
    }


    public function storeAdmin(Request $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->status = 'inactive';
        $user->save();

        $notification = array(
           'message' => 'New Admin User Created Successfully',
           'alert-type' => 'success'

         );
        return redirect()->route('all.admin')->with($notification);
    }

    public function editAdmin($id)
    {
        $adminuser = User::findOrFail($id);
        return view('backend.admin.edit_admin', compact('adminuser'));
    }


    public function updateAdmin(Request $request)
    {
        $admin_id = $request->id;

        $user = User::findOrFail($admin_id);
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role = 'admin';
        $user->status = 'inactive';
        $user->save();

        $notification = array(
           'message' => 'Admin User Updated Successfully',
           'alert-type' => 'success'

      );
        return redirect()->route('all.admin')->with($notification);
    }

    public function deleteAdmin($id)
    {
        User::findOrFail($id)->delete();

        $notification = array(
           'message' => 'Admin User Deleted Successfully',
           'alert-type' => 'success'

        );

        return redirect()->back()->with($notification);
    }

    public function inactiveAdminUser($id)
    {
        User::findOrFail($id)->update(['status' => 'inactive']);

        $notification = array(
            'message' => 'Admin User Inactive',
            'alert-type' => 'success'

        );

        return redirect()->back()->with($notification);
    }

    public function activeAdminUser($id)
    {
        User::findOrFail($id)->update(['status' => 'active']);

        $notification = array(
            'message' => 'Admin User Active',
            'alert-type' => 'success'

        );

        return redirect()->back()->with($notification);
    }
}