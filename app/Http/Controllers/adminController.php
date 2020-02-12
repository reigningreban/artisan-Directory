<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ArtisanSignupEmail;
use App\Mail\AgentApprovedEmail;
use App\Mail\AgentDisapprovedEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Session;
use Cookie;
use Geographical;
use App\Http\Requests;
use Image;
class adminController extends Controller
{
    //checker
    public function checker()
    {
        if (session()->exists('admin')) {
            return true;
        }else {
            return false;
        }
    }

    //login
    public function login()
    {
        session()->flush();
        $data=request()->validate([
            'username'=>'required',
            'lpassword'=>'required',
            
        ]);
        $username=request('username');
        $password=request('lpassword');
        $exists=DB::table('admin')
        ->where('username',$username)
        ->exists();
        if ($exists) {
                $pass=DB::table('admin')
            ->where('username',$username)
            ->value('password');
            
            $check=Hash::check($password,$pass);
            if ($check) {
                $admin_id=DB::table('admin')
                ->where('username',$username)
                ->value('id');

                session()->put('admin',$admin_id);
                
                
                return redirect('qzwf/dashboard');
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
            return redirect('qzwf/login');
        }else {
            $artisan=DB::table('artisans');
            $artcount=$artisan->count();
            $art=$artisan->orderBy('enabled')->get();

            $serve=DB::table('services');
            $servecount=$serve->count();
            $services=$serve->get();

            $pend=DB::table('suggested_services');
            $pendcount=$pend->count();
            $pending=$pend->join('artisans','artisans.id','=','suggested_services.artisan_id')
            ->select('suggested_services.id as ID','artisans.*','suggested_services.*')
            ->get();
            $agents=DB::table('agents')->where('validated',1)->get();
            foreach ($agents as $agent ) {
                $artisancount[$agent->id]=DB::table('artisans')->where('registered_by',$agent->id)->count();
            }
            $applicants=DB::table('agents')->where('validated',0)->get();
            $agecount=DB::table('agents')->where('validated',1)->count();
            $unvalcount=DB::table('agents')->whereRaw('validated = 0')->count();

            return view('qzwf/dashboard',[
                'artcount'=>$artcount,
                'art'=>$art,
                'servecount'=>$servecount,
                'services'=>$services,
                'pendcount'=>$pendcount,
                'pending'=>$pending,
                'agecount'=>$agecount,
                'unvalcount'=>$unvalcount,
                'agents'=>$agents,
                'applicants'=>$applicants,
                'artisancount'=>$artisancount,
            ]);
        }
    }


    //logout

    public function logout()
    {
        session()->flush();
        return redirect('/qzwf/login');
    }


    //disable
    public function disable($id)
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('qzwf/login');
        }else {
            DB::table('artisans')
            ->where('id',$id)
            ->update([
                'enabled'=>0
            ]);
        }
    }

    //disable
    public function agentdisable($id)
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('qzwf/login');
        }else {
            DB::table('agents')
            ->where('id',$id)
            ->update([
                'enabled'=>0
            ]);
        }
    }

    //enable
    public function enable($id)
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('qzwf/login');
        }else {
            DB::table('artisans')
            ->where('id',$id)
            ->update([
                'enabled'=>1
            ]);
        }
    }

    //enable
    public function agentenable($id)
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('qzwf/login');
        }else {
            DB::table('agents')
            ->where('id',$id)
            ->update([
                'enabled'=>1
            ]);
        }
    }


    //approve
    public function approve($id)
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('qzwf/login');
        }else {
            $service=DB::table('suggested_services')
            ->where('id',$id)
            ->first();

            $serve_id=DB::table('services')
            ->insertGetId([
                'service'=>$service->service,
            ]);

            DB::table('offered_by')
            ->insert([
                'artisan_id'=>$service->artisan_id,
                'service_id'=>$serve_id
            ]);

            DB::table('suggested_services')
            ->where('id',$id)
            ->delete();

            return redirect()->back();
        }
    }




    //approve
    public function agentapprove($id)
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('qzwf/login');
        }else {
            $service=DB::table('agents')
            ->where('id',$id)
            ->update([
                'validated'=>1
            ]);

            $this->appmail($id);

            return redirect()->back();
        }
    }



    //disapprove
    public function disapprove($id)
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('qzwf/login');
        }else {
            DB::table('suggested_services')
            ->where('id',$id)
            ->delete();

            return redirect()->back();
        }
    }


    //agent disapprove
    public function agentdisapprove($id)
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('qzwf/login');
        }else {

            $this->disappmail($id);
            DB::table('agents')
            ->where('id',$id)
            ->delete();

            return redirect()->back();
        }
    }

    //artisan profile from Admin
    public function artisan($id)
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('qzwf/login');
        }else {
            $artisan=DB::table('artisans')
            ->leftJoin('offered_by','artisans.id','=','offered_by.artisan_id','left outer')
            ->leftjoin('services','services.id','=','offered_by.service_id')
            ->join('cities','cities.id','=','artisans.city_id')
            ->join('states','states.id','=','cities.state_id')
            ->select('artisans.id as ID','artisans.*','states.*','cities.*', DB::raw("group_concat(DISTINCT services.service ORDER BY services.service DESC SEPARATOR ', ') as services"))
            ->groupBy('artisans.id')
            ->where('artisans.id',$id)
            ->first();

            return view('qzwf/artisanprofile',[
                'artisan'=>$artisan,
            ]);
        }
    }



    //agent profile from Admin
    public function agent($id)
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('qzwf/login');
        }else {
            $agent=DB::table('agents')
            ->join('cities','cities.id','=','agents.city_id')
            ->join('states','states.id','=','cities.state_id')
            ->where('agents.id',$id)
            ->first();

            return view('qzwf/agentprofile',[
                'agent'=>$agent,
            ]);
        }
    }
    public function test()
    {
        $id=1;
        $this->regmail($id);
    }

    public function regmail($id)
    {

        $artisan=DB::table('artisans')
        ->where('id',$id)
        ->first();
        

    $data = ['artisan' => $artisan];

    \Mail::to('francisokewale@gmail.com')->send(new ArtisanSignupEmail($data));
    }



    public function appmail($id)
    {

        $agent=DB::table('agent')
        ->where('id',$id)
        ->first();
        

    $data = ['agent' => $agent];

    \Mail::to($agent->email)->send(new AgentApprovedEmail($data));
    }


    public function disappmail($id)
    {

        $agent=DB::table('agent')
        ->where('id',$id)
        ->first();
        

    $data = ['agent' => $agent];

    \Mail::to($agent->email)->send(new AgentDisapprovedEmail($data));
    }

}
