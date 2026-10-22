<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;

class SignupController extends Controller
{
    public function create()
    {
        return view('signup');
    }

    public function store(Request $request)
    {
        //dd($request->all());
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:users,email',
        //     'password' => 'required|string|min:6',
        // ]);

        // User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => $data['password'],
        // ]);

        // return redirect('/')->with('success', 'Account created.');

        $validator = Validator::make($request->all(), [
            'first_name'=>['required','string','max:255'],
            'last_name'=>['required','string','max:255'],
            'company_name'=>['required','string','max:255'],
            'phone'=>['required','string','max:20'],
            'email'=>['required','email','unique:users,email'],
            'password'=>['required','string','min:6','confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('signup.create')
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors($validator);
        }

        $validatedData = $validator->validated();

        $dublicateEmailCheck=User::where('email', $validatedData['email'])->first();
        if($dublicateEmailCheck){
            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['email'=>'Email already exists.']);
        }
        if(User::where('role_id', 1)->exists()){

            $roled_id = 2;
        }
        else{
            $roled_id = 1;
        }


        DB::transaction(function () use ($validatedData, $roled_id): void {
            $baseSlug = Str::slug($validatedData['company_name']);
            $slug = $baseSlug;
            $suffix = 2;
            while (Company::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$suffix++;
            }
            $company = Company::create([
                'name' => $validatedData['company_name'],
                'slug' => $slug,
                'is_active' => true,
            ]);
            $user = User::create([
                'company_id'=>$company->id,
                'first_name'=>$validatedData['first_name'],
                'last_name'=>$validatedData['last_name'],
                'phone'=>$validatedData['phone'],
                'role_id'=>$roled_id,
                'email'=>$validatedData['email'],
                'password'=>bcrypt($validatedData['password']),
            ]);
            $user->companies()->attach($company->id, [
                'role_id' => $roled_id,
                'status' => 'active',
                'joined_at' => now(),
            ]);
        });

        //Auth::login($user);
        return redirect('/')->with('success', 'Account created.');
        
    }
}
