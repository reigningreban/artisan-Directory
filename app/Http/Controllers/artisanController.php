<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ArtisanSignupEmail;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Session;
use Cookie;
use Geographical;
use App\Http\Requests;
use Image;
class artisanController extends Controller
{
    public function regmail($id)
    {

        $artisan=DB::table('artisans')
        ->where('id',$id)
        ->first();
        

    $data = ['artisan' => $artisan];

    \Mail::to($artisan->email)->send(new ArtisanSignupEmail($data));
    }

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
            'slog'=>'required',
            'email'=>'required|email|unique:artisans,email',
            'phone'=>'required|numeric|digits:11|unique:artisans,phone_no',
            'password'=>'required',
            'repass'=>'required_with:password|same:password',
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
        $email=request('email');
        $phone=request('phone');
        $password=request('password');
        $password=Hash::make($password);
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
                'email'=>$email,
                'password'=>$password,
                'address'=>$address,
                'city_id'=>$city,
                'registered'=>$time,
                'phone_no'=>$phone,
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

            
            session()->flush();
            session()->put('artisan',$artisan_id);
            $this->regmail($artisan_id);
            return redirect('artisan/dashboard');
        }else {
            return redirect()->back()->withInput()->with('fail','This slog has been taken');
        }
    }



    //seo strip slug

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
                'slog'=>'required',
                'email'=>'required|email|unique:artisans,email'.",$artisan->ID",
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
            $email=request('email');
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
                if(request('others')!=null){
                    $others=request('others');
                    DB::table('suggested_services')
                    ->insert([
                        'service'=>$others,
                        'artisan_id'=>$id
                    ]);
                }
                

                return redirect('artisan/dashboard')->with('success','Profile has been successfully updated');
            }else {
                return redirect()->with('fail','This slog is taken')->withInput();
            }
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


    public function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
          return 0;
        }
        else {
          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
          $unit = strtoupper($unit);
      
          if ($unit == "K") {
            return ($miles * 1.609344);
          } else if ($unit == "N") {
            return ($miles * 0.8684);
          } else {
            return $miles;
          }
        }
      }
    //my test function
    public function tester()
    {
        
        
$latitude=33.900002;
$longitude=-119.199997;
$geolocation = $latitude.','.$longitude;
$request = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false'; 
$file_contents = file_get_contents($request);
$json_decode = json_decode($file_contents);
if(isset($json_decode->results[0])) {
    $response = array();
    foreach($json_decode->results[0]->address_components as $addressComponet) {
        if(in_array('political', $addressComponet->types)) {
                $response[] = $addressComponet->long_name; 
        }
    }

    if(isset($response[0])){ $first  =  $response[0];  } else { $first  = 'null'; }
    if(isset($response[1])){ $second =  $response[1];  } else { $second = 'null'; } 
    if(isset($response[2])){ $third  =  $response[2];  } else { $third  = 'null'; }
    if(isset($response[3])){ $fourth =  $response[3];  } else { $fourth = 'null'; }
    if(isset($response[4])){ $fifth  =  $response[4];  } else { $fifth  = 'null'; }

    if( $first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth != 'null' ) {
        echo "<br/>Address:: ".$first;
        echo "<br/>City:: ".$second;
        echo "<br/>State:: ".$fourth;
        echo "<br/>Country:: ".$fifth;
    }
    else if ( $first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth == 'null'  ) {
        echo "<br/>Address:: ".$first;
        echo "<br/>City:: ".$second;
        echo "<br/>State:: ".$third;
        echo "<br/>Country:: ".$fourth;
    }
    else if ( $first != 'null' && $second != 'null' && $third != 'null' && $fourth == 'null' && $fifth == 'null' ) {
        echo "<br/>City:: ".$first;
        echo "<br/>State:: ".$second;
        echo "<br/>Country:: ".$third;
    }
    else if ( $first != 'null' && $second != 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null'  ) {
        echo "<br/>State:: ".$first;
        echo "<br/>Country:: ".$second;
    }
    else if ( $first != 'null' && $second == 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null'  ) {
        echo "<br/>Country:: ".$first;
    }
  }else {
      echo "wrong";
  }

        

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

    //function to login
    public function login()
    {
        session()->flush();
        $data=request()->validate([
            'email'=>'required|email',
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
                if (request('remember')!= null and (request('remember')=='remember')) {
                    $remember=request('remember');
                    Cookie::queue('email', $email,270000);
                }
                
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
            ->leftJoin('offered_by','artisans.id','=','offered_by.artisan_id','left outer')
            ->leftjoin('services','services.id','=','offered_by.service_id')
            ->join('cities','cities.id','=','artisans.city_id')
            ->join('states','states.id','=','cities.state_id')
            ->select('artisans.id as ID','artisans.*','states.*','cities.*', DB::raw("group_concat(DISTINCT services.service ORDER BY services.service DESC SEPARATOR ', ') as services"))
            ->groupBy('artisans.id')
            ->where('artisans.id',$id)
            ->first();
            if ($artisan->services == null) {
                DB::table('artisans')
                ->where('id',$id)
                ->update([
                    'enabled'=>0,
                ]);
            }
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


    //upload picture
    public function picupload(Request $request)
    {
        if (!$this->checker()) {
            return redirect('/artisan/login');
        }else {
            $artisan_id=session()->get('artisan');
            $data=request()->validate([
                'image'=>'bail|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            $image = $request->file('image');

            $name = $request->file('image')->getClientOriginalName();

            $image_name = $request->file('image')->getRealPath();;

            Cloudder::upload($image_name, null,[
                'folder'=>'1search/artisans',
                'public_id' =>$artisan_id
            ]);
            $info=Cloudder::getResult();
            $link=$info['secure_url'];
            
            
            
            DB::table('artisans')
            ->where('id',$artisan_id)
            ->update([
                'displaypicture'=>$link,
            ]);

            return redirect()->back()->with('success','Image has been successfully uploaded');
        }
    }
}
