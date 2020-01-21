<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Session;
class artisanController extends Controller
{
    //function to get the state names during artisan registeration
    public function getstates()
    {
        $states=DB::table('states')
        ->get();
        $options="<option value=''>-select state-</option>";
        foreach ($states as $state ) {
            $options.="
                <option value='$state->id'>$state->state</option>
            ";
        }
        return $options;
    }

    //function to get the cities when the state has been selected
    public function getcities($state_id)
    {
        $cities=DB::table('cities')
        ->where('state_id',$state_id)
        ->get();
        $options="<option value=''>-select city-</option>";
        foreach ($cities as $city ) {
            $options.="
                <option value='$city->id'>$city->city</option>
            ";
        }
        return $options;
    }

    //function to signup
    public function signup()
    {
        $data= request()->validate([
            'firstname'=>'required',
            'lastname'=>'required_with:firstname',
            'bizname'=>'required|unique:artisans,Companyname',
            'email'=>'required|email:rfc,dns|unique:artisans,email',
            'phone'=>'required|unique:phone_nos,number',
            'password'=>'required',
            'repass'=>'required_with:password|same:password',
            'address'=>'required',
            'state'=>'required',
            'city'=>'required',
            'services'=>'required'
        ]);

        $time=strtotime('now');
        $firstname=request('firstname');
        $lastname=request('lastname');
        $bizname=request('bizname');
        $email=request('email');
        $phone=request('phone');
        $password=request('password');
        $password=Hash::make($password);
        $address=request('address');
        $city=request('city');
        $services=request('services');

        $artisan_id= DB::table('artisans')
        ->insertGetId([
            'companyname'=>$bizname,
            'firstname'=>$firstname,
            'lastname'=>$lastname,
            'email'=>$email,
            'password'=>$password,
            'address'=>$address,
            'city_id'=>$city,
            'registered'=>$time
        ]);

        foreach ($services as $service ) {
            DB::table('offered_by')
            ->insert([
                'artisan_id'=>$artisan_id,
                'service_id'=>$service
            ]);
        }
        

        DB::table('phone_nos')
        ->insert([
            'artisan_id'=>$artisan_id,
            'number'=>$phone
        ]);
        session()->flush();
        session()->put('artisan',$artisan_id);

        return redirect('artisan/dashboard');
    }

    //my test function
    public function tester()
    {
       
    }

    //funtion to get the registered services
    public function getservices()
    {
        $services=DB::table('services')
        ->get();
        $data="<div class='row'>";
        foreach ($services as $service ) {
            $data.="
                <div class='col-md-4 col-sm-6'>
                    <div class='form-check'>
                        <label class='form-check-label'>
                            <input type='checkbox' name='services[]' class='form-check-input' value='$service->id'>$service->service
                        </label>
                    </div>
                </div>
            ";
        }
        $data.="</div>";
        $data.="<div class='text-center'>Check the box(es) that best describe your business</div>";
        return $data;
    }

    //function to login
    public function login()
    {
        session()->flush();
        $data=request()->validate([
            'email'=>'required|email:rfc,dns',
            'lpassword'=>'required',
            
        ]);
        $email=request('email');
        $password=request('lpassword');
        $exists=DB::table('artisans')
        ->where('email',$email)
        ->exists();
        if ($exists) {
                $pass=DB::table('artisans')
            ->where('email',$email)
            ->value('password');
            
            $check=Hash::check($password,$pass);
            if ($check) {
                $artisan_id=DB::table('artisans')
                ->where('email',$email)
                ->pluck('id');

                session()->put('artisan',$artisan_id);
                
                return redirect('artisan/dashboard');
            }else{
                return redirect()->back()->with('pass_crash','Invalid email or password')->withInput();
            }
        }else {
            
        }return redirect()->back()->with('pass_crash','Invalid email or password')->withInput();
        
    }
    public function logout()
    {
        session()->flush();
        return redirect('/artisan/login');
    }
}
