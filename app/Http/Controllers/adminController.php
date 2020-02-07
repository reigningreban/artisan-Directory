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
                
                
                return redirect('admin/dashboard');
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
            return redirect('admin/login');
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

            return view('admin/dashboard',[
                'artcount'=>$artcount,
                'art'=>$art,
                'servecount'=>$servecount,
                'services'=>$services,
                'pendcount'=>$pendcount,
                'pending'=>$pending,
            ]);
        }
    }


    //logout

    public function logout()
    {
        session()->flush();
        return redirect('/admin/login');
    }


    //disable
    public function disable($id)
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('admin/login');
        }else {
            DB::table('artisans')
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
            return redirect('admin/login');
        }else {
            DB::table('artisans')
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
            return redirect('admin/login');
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



    //disapprove
    public function disapprove($id)
    {
        $logged=$this->checker();
        if (!$logged) {
            return redirect('admin/login');
        }else {
            DB::table('suggested_services')
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
            return redirect('admin/login');
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

            return view('admin/artisanprofile',[
                'artisan'=>$artisan,
            ]);
        }
    }
}
