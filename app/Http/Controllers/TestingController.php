<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;

use App\Models\User;

class TestingController extends Controller
{
    public function index()
    {
        $users = '';

        // ---------------------------------- CREATE ----------------------------------
        // $user = new User();
        // $user->name = 'code name1';
        // $user->email = 'code@email.com';
        // $user->password = Hash::make('Default@123');
        // $user->save();

        // CREATE LOG
        // $users = User::findOrFail(1);
        // $users->logs()->create([
        //     'action' => 'logout',
        //     'details' => 'User logged out',
        // ]);

        // ---------------------------------- READ ----------------------------------
        // $users = User::all(); // get all users

        // $users = User::find(1); // get user with id 1

        // $users = User::find(1)->email; // get user with id 1 and get email

        // $users = User::pluck('email'); // get all email and store in array

        // $users = User::select('name', 'email')->get(); // get all data with name and email

        // $users = User::findOrFail(1); // get user with id 7 or throw error if not found (RECOMMENDED)
        // return new UserResource($users); // Format data to json response on how you serialize data (RECOMMENDED)

        // $users = User::where('email', '=', 'code@email.com')->first(); // get user with constraint data

        // $users = User::where('remember_token', '=', null)->get(); // get all users with null value in remember_token (RECOMMENDED)

        // $users = User::with('teacher')->get(); // example: get user with relationship teacher model (RECOMMENDED)

        // $users = User::unRemembered()->get(); // get scope data created in user model (RECOMMENDED)

        // $personal_information = PersonalInformation::where('email', $user_account)->orWhere('contact_number', $user_account)->first();

        // ---------------------------------- UPDATE ----------------------------------
        // $users = User::find(1);
        // $users->contact_no = '639937027083';
        // $users->email = 'code1@gmail.com';
        // $users->save(); //(RECOMMENDED)

        // $users = User::where('id', 1)->update(['name' => 'code name1']);

        // ---------------------------------- DELETE ----------------------------------
        // $users = User::find(8);
        // $users->delete();
        // User::destroy(1); (RECOMMENDED)

        // User::destroy([1, 2, 3]); // delete multiple users (RECOMMENDED)
        // User::where('active', 0)->delete(); // delete with condition

        // $users = User::destroy(1);

        // ---------------------------------- PAGINATE ----------------------------------
        // $users = User::paginate(4);
        // $paginate = UserResource::collection($users); //paginate with serialization (RECOMMENDED)




        return response()->json(['output' => $users], 200);
        // return $paginate;
    }
}
