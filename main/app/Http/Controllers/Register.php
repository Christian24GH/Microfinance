<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class Register extends Controller
{
    public function client_register_index(){
        return view('clientreg');
    }

    public function client_register_store(Request $request){
        $request->validate([
            'email' => 'required|email|min:10|unique:Accounts,email',
            'password' => 'required|string'
        ]);
        
        DB::beginTransaction();

        try {
            // Generate unique client_id
            do {
                $client_id = 'c250' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
                $exists = DB::table('client_info')->where('client_id', $client_id)->exists();
            } while ($exists);

            
            // Client Info
            DB::table('client_info')->insert([
                'client_id'       => $client_id,
                'first_name'      => $request->first_name,
                'middle_name'     => $request->middle_name,
                'last_name'       => $request->last_name,
                'sex'             => $request->sex,
                'civil_status'    => $request->civil_status,
                'email'           => $request->email,
                'birthdate'       => $request->birthdate,
                'contact_number'  => $request->contact_number,
                'address'         => $request->address,
                'barangay'        => $request->barangay,
                'city'            => $request->city,
                'province'        => $request->province,
            ]);

            // Accounts
            $user_id = DB::table('accounts')->insertGetId([
                'email'     => $request->email,
                'password'  => $request->password,
                'role_id'   => 1,
                'client_id' => $client_id,
            ]);

            // Employment
            DB::table('client_employment')->insert([
                'client_id'      => $client_id,
                'employer_name'  => $request->employer_name,
                'address'        => $request->employment_address,
                'position'       => $request->position,
            ]);

            // Contact References
            DB::table('client_references')->insert([
                'client_id'            => $client_id,
                'fr_first_name'        => $request->fr_first_name,
                'fr_last_name'         => $request->fr_last_name,
                'fr_relationship'      => $request->fr_relationship,
                'fr_contact_number'    => $request->fr_contact_number,
                'sr_first_name'        => $request->sr_first_name,
                'sr_last_name'         => $request->sr_last_name,
                'sr_relationship'      => $request->sr_relationship,
                'sr_contact_number'    => $request->sr_contact_number,
            ]);

            // Financial Info
            DB::table('client_financial_info')->insert([
                'client_id'       => $client_id,
                'source_of_funds' => $request->source_of_funds,
                'monthly_income'  => $request->monthly_income,
            ]);

            
            // ✅ Documents Upload
            $uploadPath = 'C:/xampp/htdocs/dashboard/Microfinance/core1/components/files/';

            $document_one = null;
            $document_two = null;

            if ($request->hasFile('document_one')) {
                $fileOne = $request->file('document_one');
                $fileOneName = time() . '_one_' . $fileOne->getClientOriginalName();
                $fileOne->move($uploadPath, $fileOneName);
                $document_one = $fileOneName;
            }

            if ($request->hasFile('document_two')) {
                $fileTwo = $request->file('document_two');
                $fileTwoName = time() . '_two_' . $fileTwo->getClientOriginalName();
                $fileTwo->move($uploadPath, $fileTwoName);
                $document_two = $fileTwoName;
            }

            DB::table('client_documents')->insert([
                'client_id'     => $client_id,
                'docu_type'     => $request->docu_type,
                'document_one'  => $document_one,
                'document_two'  => $document_two,
            ]);
            
            // Client Status
            DB::table('client_status')->insert([
                'client_id'  => $client_id,
                'c_status'   => $request->c_status,
                'l_status'   => $request->l_status,
                'r_status'   => $request->r_status,
            ]);

            DB::commit();
            
            $loginRequest = new \Illuminate\Http\Request();
            $loginRequest->replace([
                'email' => $request->email,
                'password' => $request->password,
            ]);

            
            return app(\App\Http\Controllers\Login::class)->login($loginRequest);
            //return back()->with('success', 'Client Registration Complete');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error occurred: ' . $e->getMessage());
        }

    }
}
