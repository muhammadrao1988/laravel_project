<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
	protected $mainViewFolder = 'admin.users.manage-password.';

    public function index()
    {
        return view($this->mainViewFolder . 'form');
    }

    public function store(Request $request)
    {
    	$user_id = auth()->user()->id;
        $user = User::findOrFail($user_id);

        $rules = [
		    'current_password' => 'required',
		    'new_password' => 'required|different:current_password',
		    'confirm_new_password' => 'required|same:new_password',
		];

		$customMessages = [
			'required' => 'Your :attribute is required.',
        	'different' => 'Please choose a different :attribute from your current password.',
        	'same' => 'Your new password and :attribute must be the same.'
    	];

    	$this->validate($request, $rules, $customMessages);

		if (Hash::check($request->current_password, $user->password)) {

			User::find($user_id)->update(['password'=> $request->new_password]);

		    $request->session()->flash('success', 'Password changed!');
		    return redirect()->back();

		}
		else {
		    $request->session()->flash('error', 'Invalid current password!');
		    return redirect()->back();
		}
    }
}
