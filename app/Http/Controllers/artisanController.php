<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Session;
class artisanController extends Controller
{

    //checker
    public function checker()
    {
        if (session()->exists('artisan')) {
            return true;
        }else {
            return false;
        }
    }

    //function to get an artisans details
    public function artisan($id)
    {
        if ($this->checker()) {
            $artisan=DB::table('artisans')
            ->join('cities','cities.id','=','artisans.city_id')
            ->where('artisans.id',$id)
            ->select('artisans.id as ID','artisans.*','cities.*')
            ->first();

            return $artisan;
        }
    }
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

    //function to get the state names during artisan edit profile
    public function getstatesedit()
    {
        if ($this->checker()) {
            $artisan_id=session()->get('artisan');
            $artisan=$this->artisan($artisan_id);
            $states=DB::table('states')
            ->get();
            $options="<option value=''>-select state-</option>";
            foreach ($states as $state ) {
                if ($state->id == $artisan->state_id) {
                    $options.="
                        <option value='$state->id' selected>$state->state</option>
                    ";
                }else {
                    $options.="
                        <option value='$state->id'>$state->state</option>
                    ";
                }
                
            }
            return $options;
        }
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


     //function to get the cities for user edit
    public function getcitiesedit()
    {
        if ($this->checker()) {
            $artisan_id=session()->get('artisan');
            $artisan=$this->artisan($artisan_id);
            $cities=DB::table('cities')
            ->where('state_id',$artisan->state_id)
            ->get();
            $options="<option value=''>-select city-</option>";
            foreach ($cities as $city ) {
                if ($city->id == $artisan->city_id) {
                    $options.="
                        <option value='$city->id' selected>$city->city</option>
                    ";
                }else {
                        $options.="
                        <option value='$city->id'>$city->city</option>
                    ";
                }
                
            }
            return $options;
        }
        
    }

    //function to signup
    public function signup()
    {
        $data= request()->validate([
            'firstname'=>'required',
            'lastname'=>'required_with:firstname',
            'bizname'=>'required',
            'slog'=>'required|unique:artisans,slog',
            'email'=>'required|email|unique:artisans,email',
            'phone'=>'required|unique:artisans,phone_no',
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
        $slog=request('slog');

        $artisan_id= DB::table('artisans')
        ->insertGetId([
            'companyname'=>$bizname,
            'slog'=>$slog,
            'firstname'=>$firstname,
            'lastname'=>$lastname,
            'email'=>$email,
            'password'=>$password,
            'address'=>$address,
            'city_id'=>$city,
            'registered'=>$time,
            'phone_no'=>$phone
        ]);

        foreach ($services as $service ) {
            DB::table('offered_by')
            ->insert([
                'artisan_id'=>$artisan_id,
                'service_id'=>$service
            ]);
        }
        

        
        session()->flush();
        session()->put('artisan',$artisan_id);

        return redirect('artisan/dashboard');
    }



    //function to edit profile
    public function editprofile()
    {
        if (!$this->checker()) {
            return redirect('/artisan/login');
        }else {
            $id=session()->get('artisan');
            $artisan=$this->artisan($id);
            
            $data= request()->validate([
                'firstname'=>'required',
                'lastname'=>'required_with:firstname',
                'bizname'=>'required',
                'slog'=>'required|unique:artisans,slog'.",$artisan->ID",
                'email'=>'required|email|unique:artisans,email'.",$artisan->ID",
                'phone'=>'required|unique:artisans,phone_no'.",$artisan->ID",
                'address'=>'required',
                'state'=>'required',
                'city'=>'required',
                'services'=>'required'
            ]);

            $firstname=request('firstname');
            $lastname=request('lastname');
            $bizname=request('bizname');
            $email=request('email');
            $phone=request('phone');
            $address=request('address');
            $city=request('city');
            $services=request('services');
            $slog=request('slog');

            DB::table('artisans')
            ->where('id',$id)
            ->update([
                'companyname'=>$bizname,
                'firstname'=>$firstname,
                'lastname'=>$lastname,
                'email'=>$email,
                'address'=>$address,
                'city_id'=>$city,
                'phone_no'=>$phone,
                'slog'=>$slog,
            ]);
            $myoffered=DB::table('offered_by')
            ->where('artisan_id',$id)
            ->pluck('service_id')
            ->toArray();
            foreach ($services as $service ) {
                if (!in_array($service,$myoffered)) {
                    DB::table('offered_by')
                ->insert([
                    'artisan_id'=>$artisan->ID,
                    'service_id'=>$service
                ]);
                }
            }
            foreach ($myoffered as $job) {
                if (!in_array($job,$services)) {
                    DB::table('offered_by')
                    ->whereRaw('artisan_id=? and service_id=?',[$artisan->ID,$job])
                    ->delete();
                }
            }
            

            return redirect('artisan/dashboard')->with('success','Profile has been successfully edited');
        }
    }


    //function to change password
    public function changepass()
    {
        if (!$this->checker()) {
            return redirect('/artisan/login');
        }else {
            $id=session()->get('artisan');
            $artisan=$this->artisan($id);
            $data=request()->validate([
                'oldpass'=>'required',
                'newpass'=>'required',
                'repass'=>'required_with:newpass|same:newpass',
            ]);

            $oldpass=request('oldpass');
            $newpass=request('newpass');

            $check=Hash::check($oldpass,$artisan->password);
            if ($check) {
                $pass=Hash::make($newpass);
                DB::table('artisans')
                ->where('id',$artisan->ID)
                ->update([
                    'password'=>$pass,
                ]);
                return redirect()->back()->with('success','Password has been successfully changed');
            }else {
                return redirect()->back()->with('failure','Incorrect Password');
            }
        }
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
                <div class='col-md-4 col-6'>
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


    //funtion to get the registered services
    public function getservicesedit()
    {
        if ($this->checker()) {
            $artisan_id=session()->get('artisan');
            $offered=DB::table('offered_by')
            ->where('artisan_id',$artisan_id)
            ->pluck('service_id')
            ->toArray();
            $services=DB::table('services')
            ->get();
            $data="<div class='row'>";
            foreach ($services as $service ) {
                if (in_array($service->id,$offered)) {
                    $data.="
                        <div class='col-md-4 col-6'>
                            <div class='form-check'>
                                <label class='form-check-label'>
                                    <input type='checkbox' name='services[]' class='form-check-input' value='$service->id' checked>$service->service
                                </label>
                            </div>
                        </div>
                    ";
                }else {
                    $data.="
                        <div class='col-md-4 col-6'>
                            <div class='form-check'>
                                <label class='form-check-label'>
                                    <input type='checkbox' name='services[]' class='form-check-input' value='$service->id'>$service->service
                                </label>
                            </div>
                        </div>
                    ";
                }
                
            }
            $data.="</div>";
            $data.="<div class='text-center'>Check the box(es) that best describe your business</div>";
            return $data;
        }
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
                ->value('id');

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

    //dashboard
    public function dashboard()
    {
        if (!$this->checker()) {
            return redirect('/artisan/login');
        }else{
            $id=session()->get('artisan');
            $artisan=DB::table('artisans')
            ->join('offered_by','offered_by.artisan_id','=','artisans.id')
            ->join('services','services.id','=','offered_by.service_id')
            ->join('cities','cities.id','=','artisans.city_id')
            ->join('states','states.id','=','cities.state_id')
            ->select('artisans.id as ID','artisans.*','states.*','cities.*', DB::raw("group_concat(DISTINCT services.service ORDER BY services.service DESC SEPARATOR ', ') as services"))
            ->groupBy('artisans.id')
            ->where('artisans.id',$id)
            ->first();
            return view('/artisan/dashboard',[
                'artisan'=>$artisan,
            ]);
        }
    }

    //edit profile
    public function getprofile()
    {
        if (!$this->checker()) {
            return redirect('/artisan/login');
        }else {
            $artisan_id=session()->get('artisan');
            $artisan=$this->artisan($artisan_id);
            $states=DB::table('states')
            ->get();
            $cities=DB::table('cities')
            ->get();
            return view('/artisan/editprofile',[
                'artisan'=>$artisan,
                'states'=>$states,
                'cities'=>$cities,
            ]);
        }
    }

    //edit descrription
    public function editdescription()
    {
       if (!$this->checker()) {
           return redirect('/artisan/login');
       }else {
            $artisan_id=session()->get('artisan');
            $data=request()->validate([
                'descrip'=>'required',
            ]);

            $decription=request('descrip');

            DB::table('artisans')
            ->where('id',$artisan_id)
            ->update([
                'description'=>$decription,
            ]);
            
            return redirect()->back()->with('success','Description has been added successfully');
       }


    }
}
