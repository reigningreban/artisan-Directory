<?php

namespace App\Http\Controllers;

use Request;
use App\Mail\ArtisanSignupEmail;
use JD\Cloudder\Facades\Cloudder;
use App\Mail\AgentSignupEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Session;
use Cookie;
use Geographical;
use App\Http\Requests;
use Image;

class ApiController extends Controller
{


    //Regisration mail for artisan
    public function regmail($id)
    {

        $artisan=DB::table('artisans')
        ->where('id',$id)
        ->first();
        

    $data = ['artisan' => $artisan];

    \Mail::to($artisan->email)->send(new ArtisanSignupEmail($data));
    }














    public function getservices()
    {
        $services['status']="success";
        $services['services']=DB::table('services')
        ->get();

        return response()->json($services);
    }







    public function getstates()
    {
        $states['status']="success";
        $states['states']=DB::table('states')
        ->get();

        return response()->json($states);
    }




    public function getcities()
    {
        if(null!=request('state_id')){
            $state_id=request('state_id');
            $exists=DB::table('cities')
            ->where('state_id',$state_id)
            ->exists();
            if ($exists) {
                $cities['status']="success";
                $cities['cities']=DB::table('cities')
                ->where('state_id',$state_id)
                ->get();

                return response()->json($cities);
            }else {
                    $error['status']='failure';
                    $error['message']='No such State';
                    return response()->json($error);
            }
            
        }else {
                $error['status']='failure';
                $error['message']='No data sent';
                return response()->json($error);
        }
       
    }














    public function random()
    {
        if (request('num')!=null) {
            $num=request('num');
            $count=DB::table('artisans')->count();
            if ($num<=$count) {
                $artisans['status']="success";
                $artisans['artisans']=DB::table('artisans')
                ->join('offered_by','offered_by.artisan_id','=','artisans.id')
                ->join('services','services.id','=','offered_by.service_id')
                ->join('cities','cities.id','=','artisans.city_id')
                ->join('states','states.id','=','cities.state_id')
                ->select('artisans.id as ID','artisans.*','states.*','cities.*', DB::raw("group_concat(DISTINCT services.service ORDER BY services.service DESC SEPARATOR ', ') as services"))
                ->where('artisans.enabled',1)
                ->groupBy('artisans.id') 
                ->inRandomOrder()
                ->limit($num)
                ->get();
                return response()->json($artisans);
            }else {
                    $error['status']='failure';
                    $error['message']="Limit Exceeds total number of artisans";
                    return response()->json($error);
            }
        }else {
            $error['status']='failure';
            $error['message']="No limit sent";
            return response()->json($error);
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















    public function getartisans()
    {
        $artisans['status']="success";
        $artisans['artisans']=DB::table('artisans')
        ->join('offered_by','offered_by.artisan_id','=','artisans.id')
        ->join('services','services.id','=','offered_by.service_id')
        ->join('cities','cities.id','=','artisans.city_id')
        ->join('states','states.id','=','cities.state_id')
        ->select('artisans.id as ID','artisans.*','states.*','cities.*', DB::raw("group_concat(DISTINCT services.service ORDER BY services.service DESC SEPARATOR ', ') as services"))
        ->where('artisans.enabled',1)
        ->groupBy('artisans.id')
        ->orderBy(DB::raw('RAND(1234)'))
        ->get();

        return response()->json($artisans);
    }

















    public function artisan()
    {
        $data=request()->validate([
            'email'=>'required',
        ]);

        $email=request('email');
        $artisan['status']="success";
        $artisan['artisan']=DB::table('artisans')
        ->join('offered_by','offered_by.artisan_id','=','artisans.id')
        ->join('services','services.id','=','offered_by.service_id')
        ->join('cities','cities.id','=','artisans.city_id')
        ->join('states','states.id','=','cities.state_id')
        ->select('artisans.id as ID','artisans.*','states.*','cities.*', DB::raw("group_concat(DISTINCT services.service ORDER BY services.service DESC SEPARATOR ', ') as services"))
        ->whereRaw('artisans.email =?',[$email])
        ->groupBy('artisans.id')
        ->first();

        return response()->json($artisan);
    }










    //search
    public function search()
    {
        if (null!=request('data')) {

            $stuff=request('data');
            $words=explode(" ",$stuff);
            $head="CONCAT_WS( ' ',states.state, cities.city, artisans.address, artisans.companyname, services.service, artisans.slog)";
            $query="$head LIKE ";
            foreach ($words as $word ) {
                $query.="'%$word%' and $head LIKE ";
            }
            $query.="'% %'";
            
            $data=DB::table('artisans')
            ->join('offered_by','offered_by.artisan_id','=','artisans.id')
            ->join('services','services.id','=','offered_by.service_id')
            ->join('cities','cities.id','=','artisans.city_id')
            ->join('states','states.id','=','cities.state_id')
            ->select('artisans.id as ID','artisans.*','states.*','cities.*', DB::raw("group_concat(DISTINCT services.service ORDER BY services.service DESC SEPARATOR ', ') as services"))
            ->groupBy('artisans.id')
            ->orderBy(DB::raw('RAND(1234)'))
            ->whereRaw(DB::raw($query)."and artisans.enabled =?",[1] );
            $count=$data->count();
            $artisans['status']='success';
            $artisans['search']=$stuff;
            if ($count>0) {
                $artisans['artisans']=$data->get();
            }else {
            $artisans['artisans']="No artisans found";
            }
            return response()->json($artisans);
        }else {
            $error['status']='failure';
            $error['message']="No data sent";
            return response()->json($error);
        }
        
    }
















    public function artisanlogin()
    {
        $validation = Validator::make(Request::all(),[ 
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validation->fails()){
            $errors['status']="failure";
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
            $email=request('email');
            $password=request('password');
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

                    DB::table('artisan_sessions')
                    ->whereRaw('artisan_id=? and active=1',[$artisan_id])
                    ->update([
                        'active'=>0
                    ]);

                    $id=DB::table('artisan_sessions')
                    ->insertGetId([
                        'artisan_id'=>$artisan_id,
                        'sesscode'=>str::random(20),
                        'active'=>1
                    ]);
                    $artisan;
                    $artisan['status']='success';
                    $artisan['sesscode']=DB::table('artisan_sessions')
                    ->where('id',$id)
                    ->value('sesscode');
                    $artisan['email']=$email;
                    return response()->json($artisan);
                }else{
                    
                    $error['status']='failure';
                    $error['message']='Invalid Email or Password';
                    return response()->json($error);
                }
            }else {
                    $error['status']='failure';
                    $error['message']='Invalid Email or Password';
                    return response()->json($error);
            }
        }
        
        
    }











    //function to signup
    public function artisansignup()
    {

        $validation = Validator::make(Request::all(),[ 
            'firstname'=>'required',
            'lastname'=>'required_with:firstname',
            'bizname'=>'required',
            'slog'=>'required',
            'email'=>'required|email|unique:artisans,email',
            'phone'=>'required|numeric|digits:11|unique:artisans,phone_no',
            'password'=>'required',
            'repass'=>'required_with:password|same:password',
            'address'=>'required',
            'city'=>'required',
            'services'=>'required_without:others',
            'others'=>'',
            'longitude'=>'numeric',
            'latitude'=>'numeric',
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
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

            $id=DB::table('artisan_sessions')
            ->insertGetId([
                'artisan_id'=>$artisan_id,
                'sesscode'=>str::random(20),
                'active'=>1
            ]);
            $artisan;
            $artisan['sesscode']=DB::table('artisan_sessions')
            ->where('id',$id)
            ->value('sesscode');
            $artisan['email']=$email;
            

                $this->regmail($artisan_id);
                return response()->json($artisan);
            }else {
                $error['status']='failure';
                $error['errors']['slog']=["This slog is taken"];
                return response()->json($error);
            }
        }
    }



    

    //function to edit profile
    public function profedit()
    {
    
        $validation = Validator::make(Request::all(),[ 
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
            'description'=>'',
            'sesscode'=>'required',
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
            $email=request('email');
            $id=DB::table('artisans')
            ->where('email',$email)
            ->value('id');
            $sesscode=request('sesscode');
            $logged=DB::table('artisan_sessions')
            ->whereRaw('sesscode=? and artisan_id=? and active=?',[$sesscode,$id,1])
            ->exists();
                
            if ($logged) {
                
            

                $firstname=request('firstname');
                $lastname=request('lastname');
                $bizname=request('bizname');
                
                $phone=request('phone');
                $address=request('address');
                $city=request('city');
                $services=request('services');
                $description=request('description');
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
                    $response['status']='success';
                    $response['message']='Profile has been successfully updated';

                    return response()->json($response);
                }else {
                    $error['status']='failure';
                    $error['errors']['slog']=["This slog is taken"];
                    return response()->json($error);
                }
            }else {
                    $error['status']='failure';
                    $error['message']="Not Autheticated";
                    return response()->json($error);
            }
        }
        
    }









    //function to change password
    public function changepass()
    {
        $validation = Validator::make(Request::all(),[ 
            'oldpass'=>'required',
            'newpass'=>'required',
            'repass'=>'required',
            'sesscode'=>'required',
            'email'=>'required|email',
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
            $email=request('email');
            $id=DB::table('artisans')
            ->where('email',$email)
            ->value('id');
            $sesscode=request('sesscode');
            $logged=DB::table('artisan_sessions')
            ->whereRaw('sesscode=? and artisan_id=? and active=?',[$sesscode,$id,1])
            ->exists();
                
            if ($logged) {

                $oldpass=request('oldpass');
                $newpass=request('newpass');
                $password=DB::table('artisans')
                ->where('email',$email)
                ->value('password');
                $check=Hash::check($oldpass,$password);
                if ($check) {
                    $pass=Hash::make($newpass);
                    DB::table('artisans')
                    ->where('id',$artisan->ID)
                    ->update([
                        'password'=>$pass,
                    ]);
                    $response['status']='success';
                    $response['message']="Password has been successfully changed";
                    return response()->json($response);
                }else {
                    $error['status']='failure';
                    $error['message']="Incorrect Password";
                    return response()->json($error);
                }
            }else {
                $error['status']='failure';
                $error['message']="Not Autheticated";
                return response()->json($error);
            }
        }
    }



    //artisan picupload
    public function artisanpicupload(Request $request)
    {
        $validation = Validator::make(Request::all(),[ 
            'image'=>'bail|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sesscode'=>'required',
            'email'=>'required|email',
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
            $email=request('email');
            $id=DB::table('artisans')
            ->where('email',$email)
            ->value('id');
            $sesscode=request('sesscode');
            $logged=DB::table('artisan_sessions')
            ->whereRaw('sesscode=? and artisan_id=? and active=?',[$sesscode,$id,1])
            ->exists();
                
            if ($logged) {
                $image = $request->file('image');

                $name = $request->file('image')->getClientOriginalName();

                $image_name = $request->file('image')->getRealPath();;

                Cloudder::upload($image_name, null,[
                    'folder'=>'1search/artisans',
                    'public_id' =>$id
                ]);
                $info=Cloudder::getResult();
                $link=$info['secure_url'];
                
                
                
                DB::table('artisans')
                ->where('id',$id)
                ->update([
                    'displaypicture'=>$link,
                ]);

                $response['status']='success';
                $response['message']="Image has been successfully uploaded";
                return response()->json($response);

            }else {
                $error['status']='failure';
                $error['message']="Not Autheticated";
                return response()->json($error);
            }
        }
    }

    public function agentapply()
    {
        $validation = Validator::make(Request::all(),[ 
            'firstname'=>'required',
            'lastname'=>'required_with:firstname',
            'email'=>'required|email|unique:agents,email',
            'city'=>'required',
            'address'=>'required',
            'phone'=>'required|numeric|digits:11|unique:agents,phone_no',
            'password'=>'required',
            'repass'=>'required_with:password|same:password',
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
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

            $this->appmail($id);

            $response['status']='success';
            $response['message'] = "Application has been sent successfully.";
            return response()->json($response);
        }

    }



    public function appmail($id)
    {

        $agent=DB::table('agents')
        ->where('id',$id)
        ->first();
        

    $data = ['agent' => $agent];

    \Mail::to($agent->email)->send(new AgentSignupEmail($data));
    }


    public function agentlogin()
    {
        $validation = Validator::make(Request::all(),[ 
            'email'=>'required|email',
            'lpassword'=>'required',
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
        
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

                        DB::table('agent_sessions')
                        ->whereRaw('agent_id=? and active=1',[$agent_id])
                        ->update([
                            'active'=>0
                        ]);

                        $id=DB::table('agent_sessions')
                        ->insertGetId([
                            'agent_id'=>$agent_id,
                            'sesscode'=>str::random(20),
                            'active'=>1
                        ]);
                        $agent;
                        $agent['status']='success';
                        $agent['sesscode']=DB::table('agent_sessions')
                        ->where('id',$id)
                        ->value('sesscode');
                        $agent['email']=$email;
                        return response()->json($agent);                        
                    }else if($validated==0 && $enabled==1) {
                        $errors['status']='failure';
                        $errors['errors'] = 'Your account is still pending validation. Please be patient';
                        return response()->json($errors);
                    }else{
                        $errors['status']='failure';
                        $errors['errors'] = 'Your have been disabled.';
                        return response()->json($errors);
                    }
                    
                }else{
                    $errors['status']='failure';
                    $errors['errors'] = 'Invalid email or password';
                    return response()->json($errors);
                }
            }else {
                $errors['status']='failure';
                $errors['errors'] = 'Invalid email or password';
                return response()->json($errors);
            }
        }
    }


    public function agent()
    {
        $validation = Validator::make(Request::all(),[ 
            'email'=>'required|email',
            'sesscode'=>'required'
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {   
            $email=request('email');
            $id=DB::table('agents')
            ->where('email',$email)
            ->value('id');
            $sesscode=request('sesscode');
            $logged=DB::table('agent_sessions')
            ->whereRaw('sesscode=? and agent_id=? and active=?',[$sesscode,$id,1])
            ->exists();
                
            if ($logged) {
            $agent=DB::table('agents')
            ->join('cities','cities.id','=','agents.city_id')
            ->join('states','states.id','=','cities.state_id')
            
            ->where('agents.email',$email)
            ->first();
            $response['status']='success';
            $response['agent'] = $agent;
            return response()->json($response);
            }else {
                $response['status']='failure';
                $response['message'] = 'Not Authenticated';
                return response()->json($response);
            }
        }
    }




    public function addartisan()
    {
        $validation = Validator::make(Request::all(),[ 
            'sesscode'=>'required',
            'email'=>'required',
            'firstname'=>'required',
            'lastname'=>'required_with:firstname',
            'bizname'=>'required',
            'slog'=>'required',
            'phone'=>'required|numeric|digits:11|unique:artisans,phone_no',
            'address'=>'required',
            'city'=>'required',
            'services'=>'required_without:others',
            'others'=>'',
            'longitude'=>'numeric',
            'latitude'=>'numeric',
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {   
            $email=request('email');
            $id=DB::table('agents')
            ->where('email',$email)
            ->value('id');
            $sesscode=request('sesscode');
            $logged=DB::table('agent_sessions')
            ->whereRaw('sesscode=? and agent_id=? and active=?',[$sesscode,$id,1])
            ->exists();
                
            if ($logged) {
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

                    $response['status']="success";
                    $response['message']='Artisan has been successfully added';
                    return response()->json($response);
                }else {
                    $error['status']="failure";
                    $error['errors']['slog']=['This slog has been taken'];
                    return response()->json($error);
                }
            }else {
                $error['status']="failure";
                $error['message']='Not Autenticated';
                return response()->json($error);
            }
        }
            
    }

    public function myartisans()
    {
        $validation = Validator::make(Request::all(),[ 
            'email'=>'required|email',
            'sesscode'=>'required',
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
            $email=request('email');
            $id=DB::table('agents')
            ->where('email'.$email)
            ->value('id');
            $sesscode=request('sesscode');
            $logged=DB::table('agent_sessions')
            ->whereRaw('sesscode=? and agent_id=? and active=?',[$sesscode,$id,1])
            ->exists();
                
            if ($logged) {
                $myartisans=DB::table('artisans')
                ->where('registered_by',$id)
                ->get();
                
                $response['status']="success";
                $response['artisans']=$myartisans;
                return reponse()->json($response);
            }else {
                $error['status']="failure";
                $error['message']='Not Autenticated';
                return response()->json($error);
            }
        }
    }




    public function editartisan()
    {
        $validation = Validator::make(Request::all(),[ 
            'email'=>'required|email',
            'sesscode'=>'required',
            'slog'=>'required',
            'firstname'=>'required',
            'lastname'=>'required_with:firstname',
            'bizname'=>'required',
            'phone'=>'required|numeric|digits:11|unique:artisans,phone_no'.",$artisan->ID",
            'address'=>'required',
            'city'=>'required',
            'services'=>'required_without:others',
            'others'=>'',
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
            $email=request('email');
            $id=DB::table('agents')
            ->where('email'.$email)
            ->value('id');
            $sesscode=request('sesscode');
            $logged=DB::table('agent_sessions')
            ->whereRaw('sesscode=? and agent_id=? and active=?',[$sesscode,$id,1])
            ->exists();
                
            if ($logged) {
                $slog=request('slog');
                $artisan=DB::table('artisans')
                ->where('slog'.$slog)
                ->first();
                
                if ($id==$artisan->registered_by) {
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
                        ->where('id',$artisan->id)
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
                        ->where('artisan_id',$artisan->id)
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
                                'artisan_id'=>$artisan->id
                            ]);
                        }
                        $response['status']="success";
                        $response['message']="Artisan has been successfully updated";
                        
                        return response()->json($response);
                    }else {
                        $error['status']="failure";
                        $error['message']='This slog is taken';
                        return response()->json($error);
                    }
                }else {
                    $error['status']="failure";
                    $error['message']='Agent does not have access to this artisan';
                    return response()->json($error);
                }

            }else {
                $error['status']="failure";
                $error['message']='Not Autenticated';
                return response()->json($error);
            }
        }
    }



    public function agentpicupload(Request $request)
    {
        $validation = Validator::make(Request::all(),[ 
            'email'=>'required|email',
            'sesscode'=>'required',
            'image'=>'bail|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
            $email=request('email');
            $id=DB::table('agents')
            ->where('email'.$email)
            ->value('id');
            $sesscode=request('sesscode');
            $logged=DB::table('agent_sessions')
            ->whereRaw('sesscode=? and agent_id=? and active=?',[$sesscode,$id,1])
            ->exists();
                
            if ($logged) {
               
                $image = $request->file('image');

                $name = $request->file('image')->getClientOriginalName();

                $image_name = $request->file('image')->getRealPath();;

                Cloudder::upload($image_name, null,[
                    'folder'=>'1search/agents',
                    'public_id' =>$id
                ]);
                $info=Cloudder::getResult();
                $link=$info['secure_url'];
                DB::table('agents')
                ->where('id',$id)
                ->update([
                    'picture'=>$link,
                ]);
                $response['status']="success";
                $response['message']='Image has been successfully uploaded';
                return response()->json($response);
            }else {
                $error['status']="failure";
                $error['message']='Not Autenticated';
                return response()->json($error);
            }
        }
    }



    public function myartisanpicupload(Request $request)
    {
        $validation = Validator::make(Request::all(),[ 
            'email'=>'required|email',
            'sesscode'=>'required',
            'image'=>'bail|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'slog'=>'required'
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
            $slog=request('slog');
            $email=request('email');
            $artisan=DB::table('artisans')
            ->where('slog',$slog)
            ->first();
            $id=DB::table('agents')
            ->where('email'.$email)
            ->value('id');
            $sesscode=request('sesscode');
            $logged=DB::table('agent_sessions')
            ->whereRaw('sesscode=? and agent_id=? and active=?',[$sesscode,$id,1])
            ->exists();
                
            if ($logged) {
                if ($id==$artisan->registered_by) {
                    $image = $request->file('image');

                    $name = $request->file('image')->getClientOriginalName();

                    $image_name = $request->file('image')->getRealPath();;

                    Cloudder::upload($image_name, null,[
                        'folder'=>'1search/artisans',
                        'public_id' =>$artisan->id
                    ]);
                    $info=Cloudder::getResult();
                    $link=$info['secure_url'];
                    DB::table('artisans')
                    ->where('id',$artisan->id)
                    ->update([
                        'displaypicture'=>$link,
                    ]);
                    $response['status']="success";
                    $response['message']='Image has been successfully uploaded';
                    return response()->json($response);
                }else {
                    $error['status']="failure";
                    $error['message']='Not Autorized';
                    return response()->json($error);
                }
            }else {
                $error['status']="failure";
                $error['message']='Not Autenticated';
                return response()->json($error);
            }
        }
    }


    public function editmyprofile()
    {
        $validation = Validator::make(Request::all(),[
            'sesscode'=>'required',
            'email'=>'required',
            'firstname'=>'required',
            'lastname'=>'required_with:firstname',
            'city'=>'required',
            'address'=>'required',
            'phone'=>'required|numeric|digits:11|unique:agents,phone_no'.",$id",
            
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
            $slog=request('slog');
            $email=request('email');
            $id=DB::table('agents')
            ->where('email'.$email)
            ->value('id');
            $sesscode=request('sesscode');
            $logged=DB::table('agent_sessions')
            ->whereRaw('sesscode=? and agent_id=? and active=?',[$sesscode,$id,1])
            ->exists();
                
            if ($logged) {
                $firstname=request('firstname');
                $lastname=request('lastname');
                $phone=request('phone');
                $address=request('address');
                $city=request('city');

                $id=DB::table('agents')
                ->where('id',$id)
                ->update([
                    'firstname'=>$firstname,
                    'lastname'=>$lastname,
                    'phone_no'=>$phone,
                    'address'=>$address,
                    'city_id'=>$city,
                ]);
                $response['status']="success";
                $response['message']='Your profile has been successfully uploaded';
                return response()->json($response);            }
       }
    }
    public function agentlogout()
    {
        $validation = Validator::make(Request::all(),[
            'sesscode'=>'required',
            'email'=>'required',
            'logout'=>'required'
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
            $logout=request('logout');
            $email=request('email');
            $id=DB::table('agents')
            ->where('email'.$email)
            ->value('id');
            $sesscode=request('sesscode');
            $logged=DB::table('agent_sessions')
            ->whereRaw('sesscode=? and agent_id=? and active=?',[$sesscode,$id,1])
            ->exists();
                
            if ($logged && $logout) {
                DB::table('agent_sessions')
                ->whereRaw('agent_id=? and active=1 and sesscode=?',[$id,$sesscode])
                ->update([
                    'active'=>0
                ]);
                $response['status']="success";
                $response['message']='User has been successfully logged out';
                return response()->json($response);  
            }
        }
    }


    public function artisanlogout()
    {
        $validation = Validator::make(Request::all(),[
            'sesscode'=>'required',
            'email'=>'required',
            'logout'=>'required'
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
            $logout=request('logout');
            $email=request('email');
            $id=DB::table('artisans')
            ->where('email'.$email)
            ->value('id');
            $sesscode=request('sesscode');
            $logged=DB::table('artisan_sessions')
            ->whereRaw('sesscode=? and artisan_id=? and active=?',[$sesscode,$id,1])
            ->exists();
                
            if ($logged && $logout) {
                DB::table('artisan_sessions')
                ->whereRaw('artisan_id=? and active=1 and sesscode=?',[$id,$sesscode])
                ->update([
                    'active'=>0
                ]);
                $response['status']="success";
                $response['message']='User has been successfully logged out';
                return response()->json($response);  
            }
        }
    }









    public function closeartisans()
    {
        $validation = Validator::make(Request::all(),[
            'latitude'=>'required',
            'longitude'=>'required',
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
            $lat=request('latitude');
            $lon=request('longitude');
            $data=DB::table('artisans')
            ->join('offered_by','offered_by.artisan_id','=','artisans.id')
            ->join('services','services.id','=','offered_by.service_id')
            ->join('cities','cities.id','=','artisans.city_id')
            ->join('states','states.id','=','cities.state_id')
            ->select('artisans.id as ID',
            'artisans.*','states.*','cities.*',
            DB::raw("group_concat(DISTINCT services.service ORDER BY services.service DESC SEPARATOR ', ') as services"),
            DB::raw("((degrees(acos(sin(radians($lat)) * sin(radians(artisans.latitude)) +  cos(radians($lat)) * cos(radians(artisans.latitude)) * cos(radians($lon-artisans.longitude)))))*60*1.515*1.609344) as distance"))
            ->whereraw("((degrees(acos(sin(radians($lat)) * sin(radians(artisans.latitude)) +  cos(radians($lat)) * cos(radians(artisans.latitude)) * cos(radians($lon-artisans.longitude)))))*60*1.515*1.609344)<2 and artisans.enabled=?",[1])
            ->groupBy('artisans.id')
            ->orderBy(DB::raw("((degrees(acos(sin(radians($lat)) * sin(radians(artisans.latitude)) +  cos(radians($lat)) * cos(radians(artisans.latitude)) * cos(radians($lon-artisans.longitude)))))*60*1.515*1.609344)"));
            
            // ->get();
            $count=$data->count();
                
            if ($count>0) {
                $artisans=$data->get();
                $response['status']="success";
                $response['artisans']=$artisans;
                return response()->json($response);  
            }else {
                $artisans=$data->get();
                $response['status']="success";
                $response['message']='No nearby artisans';
                return response()->json($response);  
            }
        
        }
    }


    public function closesearch()
    {
        $validation = Validator::make(Request::all(),[
            'latitude'=>'required',
            'longitude'=>'required',
            'data'=>'required'
        ]);

        if($validation->fails()){
            $errors['status']='failure';
            $errors['errors'] = $validation->errors();
            return response()->json($errors);
        }else {
            $words=explode(" ",$stuff);
            $head="CONCAT_WS( ' ',states.state, cities.city, artisans.address, artisans.companyname, services.service, artisans.slog)";
            $query="$head LIKE ";
            foreach ($words as $word ) {
                $query.="'%$word%' and $head LIKE ";
            }
            $query.="'% %'";
            
            $data=DB::table('artisans')
            ->join('offered_by','offered_by.artisan_id','=','artisans.id')
            ->join('services','services.id','=','offered_by.service_id')
            ->join('cities','cities.id','=','artisans.city_id')
            ->join('states','states.id','=','cities.state_id')
            ->select('artisans.id as ID',
            'artisans.*','states.*','cities.*',
            DB::raw("group_concat(DISTINCT services.service ORDER BY services.service DESC SEPARATOR ', ') as services"),
            DB::raw("((degrees(acos(sin(radians($lat)) * sin(radians(artisans.latitude)) +  cos(radians($lat)) * cos(radians(artisans.latitude)) * cos(radians($lon-artisans.longitude)))))*60*1.515*1.609344) as distance"))        
            ->groupBy('artisans.id')
            ->whereRaw(DB::raw($query)." and ((degrees(acos(sin(radians($lat)) * sin(radians(artisans.latitude)) +  cos(radians($lat)) * cos(radians(artisans.latitude)) * cos(radians($lon-artisans.longitude)))))*60*1.515*1.609344)<2 and artisans.enabled=1")
            ->orderBy(DB::raw("((degrees(acos(sin(radians($lat)) * sin(radians(artisans.latitude)) +  cos(radians($lat)) * cos(radians(artisans.latitude)) * cos(radians($lon-artisans.longitude)))))*60*1.515*1.609344)"));
            $count=$data->count();
            if ($count>0) {
                $response['status']="success";
                $response['artisans']=$artisans;
                return response()->json($response);  
            }else {
                $artisans=$data->get();
                $response['status']="success";
                $response['message']='No artisans found';
                return response()->json($response); 
            }
        }
    }


}
