<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    //
    public function store(Request $request){

        if (! session('company_id')) {
            return redirect()->back()->with('error', 'Please select an active company before adding a customer.');
        }
    
        $validatedData = $request->validate([
            'name'=>['required','string','max:255'],
            'contact_name'=>['required','string','max:255'],
            'contact_role'=>['required','string','max:255'],
            'address'=>['nullable','string','max:255'],
            'phone'=>['nullable','string','max:20'],
            'email'=>[
                'required',
                'email',
                Rule::unique('customers', 'email')->where(function ($query) {
                    return $query->where('company_id', session('company_id'));
                }),
            ],
            'tax_id'=>['nullable','string','max:255'],
        ]);
        //dd($validatedData);
        Customer::create([
            'company_id'=>session('company_id'),
            'company_name'=>$validatedData['name'],
            'contact_person'=>$validatedData['contact_name'],
            'contact_role'=>$validatedData['contact_role'],
            'address'=>$validatedData['address'],
            'phone'=>$validatedData['phone'],
            'email'=>$validatedData['email'],
            'tax_id'=>$validatedData['tax_id'],
        ]);
        return redirect()->back()->with('success', 'Customer created successfully.');
        
    }
    public function index(){
        $companyId = session('company_id');
        $customers = $companyId ? Customer::where('company_id', $companyId)->get() : collect();
        return view('customers', compact('customers'));
    }
}