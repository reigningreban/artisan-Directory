<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Session;
use Cookie;
use Geographical;
use App\Http\Requests;
use Image;

class AgentController extends Controller
{
    //checker
    public function checker()
    {
        if (session()->exists('agent')) {
            return true;
        }else {
            return false;
        }
    }


    public function apply()
    {
        $data=request()->validate([
            'firstname'=>'required',
            'lastname'=>'required_with:firstname',
            'email'=>'required|email|unique:agents,email',
            'state'=>'required',
            'city'=>'required',
            'address'=>'required',
            'phone'=>'required|numeric|digits:11|unique:agents,phone_no',
            'password'=>'required',
            'repass'=>'required_with:password|same:password',
        ]);

        $time=strtotime('now');
        $firstname=request('firstname');
        $lastname=request('lastname');
        $email=request('email');
        $password=request('password');
        $phone=request('phone');
        $address=request('address');
        $city=request('city');
        $pass=Hash::make($password);

        $id=DB::table('agents')
        ->insertGetId([
            'firstname'=>$firstname,
            'lastname'=>$lastname,
            'email'=>$email,
            'password'=>$pass,
            'registered'=>$time,
            'phone_no'=>$phone,
            'address'=>$address,
            'city_id'=>$city,
        ]);

        $this->regmail($id);

        return redirect()->back()->with('success','Your application has been sent successfully. ');

    }
    public function regmail($id)
    {

        $agent=DB::table('agent')
        ->where('id',$id)
        ->first();
        

    $data = ['agent' => $agent];

    \Mail::to($agent->email)->send(new AgentSignupEmail($data));
    }


    public function logout()
    {
        session()->flush();
        return redirect('/agent/login');
    }

    public function login()
    {
        session()->flush();
        $data=request()->validate([
            'email'=>'required|email',
            'lpassword'=>'required',
            
        ]);
        $email=request('email');
        $password=request('lpassword');
        $exists=DB::table('agents')
        ->where('email',$email)
        ->exists();
        if ($exists) {
                $pass=DB::table('agents')
            ->where('email',$email)
            ->value('password');
            
            $check=Hash::check($password,$pass);
            if ($check) {
                $validated=DB::table('agents')
                ->where('email',$email)
                ->value('validated');
                $enabled=DB::table('agents')
                ->where('email',$email)
                ->value('enabled');
                if ($validated==1 && $enabled==1) {
                        $agent_id=DB::table('agents')
                    ->where('email',$email)
                    ->value('id');

                    session()->put('agent',$agent_id);
                    if (request('remember')!= null and (request('remember')=='remember')) {
                        $remember=request('remember');
                        Cookie::queue('agent_email', $email,270000);
                    }
                    
                    return redirect('agent/dashboard');
                }else if($validated==0 && $enabled==1) {
                    return redirect()->back()->with('notval','Your account is still pending validation. Please be patient');
                }else{
                    return redirect()->back()->with('notval','Your have been disabled.');
                }
                
            }else{
                return redirect()->back()->with('pass_crash','Invalid email or password')->withInput();
            }
        }else {
            
        }return redirect()->back()->with('pass_crash','Invalid email or password')->withInput();
  
    }


    //dashboard

    public function dashboard()
    {
        $logged=$this->checker();

        if (!$logged) {
            return redirect('/agent/login');
        }else {
            $agent_id=session()->get('agent');
            $agent=DB::table('agents')
            ->join('cities','cities.id','=','agents.city_id')
            ->join('states','states.id','=','cities.state_id')
            
            ->where('agents.id',$agent_id)
            ->first();
            return view('/agent/dashboard',[
                'agent'=>$agent,
            ]);
        }
        
    }

    public function seoUrl($string) {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }

    //add artisan
    public function addartisan()
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('/agent/login');
        }else {
            $agent_id=session()->get('agent');
            $data= request()->validate([
                'firstname'=>'required',
                'lastname'=>'required_with:firstname',
                'bizname'=>'required',
                'slog'=>'required',
                'phone'=>'required|numeric|digits:11|unique:artisans,phone_no',
                'address'=>'required',
                'state'=>'required',
                'city'=>'required',
                'services'=>'required_without:others',
                'others'=>'',
                'longitude'=>'numeric',
                'latitude'=>'numeric',
            ]);

            $time=strtotime('now');
            $firstname=request('firstname');
            $lastname=request('lastname');
            $bizname=request('bizname');
            $phone=request('phone');
            $address=request('address');
            $city=request('city');
            $services=request('services');
            $slog=$this->seoUrl(request('slog')) ;
            
            $unique=DB::table('artisans')
            ->where('slog',$slog)
            ->doesntExist();
            

            if ($unique) {
            
                $artisan_id= DB::table('artisans')
                ->insertGetId([
                    'companyname'=>$bizname,
                    'slog'=>$slog,
                    'firstname'=>$firstname,
                    'lastname'=>$lastname,
                    'address'=>$address,
                    'city_id'=>$city,
                    'registered'=>$time,
                    'registered_by'=>$agent_id,
                    'phone_no'=>$phone
                ]);
                
                if ((request('longitude')!=null)&&(request('latitude')!=null)) {
                    $longitude=request('longitude');
                    $latitude=request('latitude');
                    DB::table('artisans')
                    ->where('id',$artisan_id)
                    ->update([
                        'longitude'=>$longitude,
                        'latitude'=>$latitude
                    ]);
                }
                if(request('others')!=null){
                    $others=request('others');
                    DB::table('suggested_services')
                    ->insert([
                        'service'=>$others,
                        'artisan_id'=>$artisan_id
                    ]);
                }
                if($services!=null){
                foreach ($services as $service ) {
                    DB::table('offered_by')
                    ->insert([
                        'artisan_id'=>$artisan_id,
                        'service_id'=>$service
                    ]);
                }
            }


                return redirect()->back()->with('success','Artisan has been successfully added');
            }else {
                return redirect()->back()->withInput()->with('fail','This slog has been taken');
            }
        }
            
    }


    //my artisans

    public function myartisans()
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('/agent/login');
        }else {
            $agent_id=session()->get('agent');
            $myartisans=DB::table('artisans')
            ->where('registered_by',$agent_id)
            ->get();

            return view('agent/myartisans',[
                'myartisans'=>$myartisans,
            ]);
        }
    }

    public function artisanprofile($slog)
    {   
        $logged=$this->checker();
        if (!$logged) {
            return redirect('/agent/login');
        }else {
            $agent_id=session()->get('agent');
            $agent=DB::table('artisans')
            ->where('slog',$slog)
            ->value('registered_by');

            if ($agent==$agent_id) {
                $artisan=DB::table('artisans')
                ->leftJoin('offered_by','artisans.id','=','offered_by.artisan_id','left outer')
                ->leftjoin('services','services.id','=','offered_by.service_id')
                ->join('cities','cities.id','=','artisans.city_id')
                ->join('states','states.id','=','cities.state_id')
                ->select('artisans.id as ID','artisans.*','states.*','cities.*', DB::raw("group_concat(DISTINCT services.service ORDER BY services.service DESC SEPARATOR ', ') as services"))
                ->groupBy('artisans.id')
                ->where('artisans.slog',$slog)
                ->first();

                return view('agent/artisanprofile',[
                'artisan'=>$artisan,
            ]);
            }else {
                return redirect()->back();
            }

        }
    }


    public function showartisan($slog)
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('/agent/login');
        }else {
            $agent_id=session()->get('agent');
            $agent=DB::table('artisans')
            ->where('slog',$slog)
            ->value('registered_by');

            if ($agent==$agent_id) {
                    $artisan=DB::table('artisans')
                    ->join('cities','cities.id','=','artisans.city_id')
                    ->where('artisans.slog',$slog)
                    ->select('artisans.id as ID','artisans.*','cities.*')
                    ->first();

                    $states=DB::table('states')
                    ->get();
                    $cities=DB::table('cities')
                    ->get();
                    return view('/agent/editartisan',[
                        'artisan'=>$artisan,
                        'states'=>$states,
                        'cities'=>$cities,
                    ]);
            }else {
                return redirect()->back();
            }

        }
        
    }

    public function statesedit($slog)
    {
        if ($this->checker()) {
            $artisan=$this->artisan($slog);
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



    public function citiesedit($slog)
    {
        if ($this->checker()) {
            $artisan=$this->artisan($slog);
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




    public function servicesedit($slog)
    {
        if ($this->checker()) {
            $artisan=$this->artisan($slog);
            $offered=DB::table('offered_by')
            ->where('artisan_id',$artisan->ID)
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
            $data.="
            <div class='col-md-4 col-6'>
                <div class='form-check'>
                    <label class='form-check-label'>
                        <input type='checkbox' name='' class='form-check-input' id='othercheck'>others
                    </label>
                </div>
            </div>
        ";
            $data.="</div>";
            $data.="<div class='text-center'>Check the box(es) that best describe your business</div>";
            return $data;
        }
    }

    public function artisan($slog)
    {
        if ($this->checker()) {
            $artisan=DB::table('artisans')
            ->join('cities','cities.id','=','artisans.city_id')
            ->where('artisans.slog',$slog)
            ->select('artisans.id as ID','artisans.*','cities.*')
            ->first();

            return $artisan;
        }
    }

    public function editartisan($slog)
    {
        if ($this->checker()) {
            $agent_id=session()->get('agent');
            $artisan=$this->artisan($slog);
            $id=$artisan->ID;
            if ($agent_id==$artisan->registered_by) {
                $data= request()->validate([
                    'firstname'=>'required',
                    'lastname'=>'required_with:firstname',
                    'bizname'=>'required',
                    'slog'=>'required',
                    'phone'=>'required|numeric|digits:11|unique:artisans,phone_no'.",$artisan->ID",
                    'address'=>'required',
                    'state'=>'required',
                    'city'=>'required',
                    'services'=>'required_without:others',
                    'others'=>'',
                ]);


            $firstname=request('firstname');
            $lastname=request('lastname');
            $bizname=request('bizname');
            $phone=request('phone');
            $address=request('address');
            $city=request('city');
            $services=request('services');
            $slog=$this->seoUrl(request('slog')) ;
            

            $unique=DB::table('artisans')
            ->where('slog',$slog)
            ->doesntExist();
        
            $ontrack=true;
            if (!$unique) {
                $instance=DB::table('artisans')
                ->where('slog',$slog)
                ->first();
                if ($instance->id==$id) {
                    $ontrack=true;
                }else {
                    $ontrack=false;
                }
            }else {
                $ontrack=true;
            }
            if ($ontrack) {
                DB::table('artisans')
                ->where('id',$id)
                ->update([
                    'companyname'=>$bizname,
                    'firstname'=>$firstname,
                    'lastname'=>$lastname,
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
                if(request('others')!=null){
                    $others=request('others');
                    DB::table('suggested_services')
                    ->insert([
                        'service'=>$others,
                        'artisan_id'=>$id
                    ]);
                }
                
                $slog=DB::table('artisans')->where('id',$id)->value('slog');
                return redirect("/agent/editartisan/$slog")->with('success','Artisan has been successfully updated');
            }else {
                return redirect()->back()->with('fail','This slog is taken')->withInput();
            }
            }else {
                return redirect()->back();
            }

        }else {
            return redirect('/agent/login');
        }
    }


    public function picupload(Request $request)
    {
        if (!$this->checker()) {
            return redirect('/agent/login');
        }else {
            $agent_id=session()->get('agent');
            $data=request()->validate([
                'image'=>'bail|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            $image=request('image');

            $name = time().'.'.$image->extension(); 
        
            $destinationPath = public_path('/pics/thumbs');
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$name);
    
            $destinationPath = public_path('pics/agent/display/');
            $image->move($destinationPath, $name);
    
   
            $thumblink="/pics/thumbs".$name;
            $link="pics/agent/display/".$name;

            
            
            DB::table('agents')
            ->where('id',$agent_id)
            ->update([
                'picture'=>$link,
            ]);

            return redirect()->back()->with('success','Image has been successfully uploaded');
        }
    }
}
