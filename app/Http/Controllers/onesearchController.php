<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Session;

class onesearchController extends Controller
{
    public function randomartisan()
    {
        $artisans=DB::table('artisans')
        ->join('offered_by','offered_by.artisan_id','=','artisans.id')
        ->join('services','services.id','=','offered_by.service_id')
        ->join('cities','cities.id','=','artisans.city_id')
        ->join('states','states.id','=','cities.state_id')
        ->select('artisans.id as ID','artisans.*','states.*','cities.*', DB::raw("group_concat(DISTINCT services.service ORDER BY services.service DESC SEPARATOR ', ') as services"))
        ->groupBy('artisans.id') 
        ->inRandomOrder()
        ->limit(3)
        ->get();
        $result="";
        foreach ($artisans as $artisan ) {
            $result.="
            <div class='col-12 col-md-4 '>
                    <div class='row'>
                        <div class='col-1'></div>
                        <div class='col-10 mb-4 art-card card'>
                            <div class='row align-items-center'>
                                <div class='imgcover text-center col-5'>";
                                    if(null!=$artisan->displaypicture) {
                                        $result.="<img src='".asset($artisan->displaypicture)."' alt='profile picture' class='home-card-img fixedimg'>";
                                    }
                                     else {
                                        $result.="<img src='".asset('img/tempavt.png')."' alt='profile picture' class='home-card-img fixedimg'>";
                                     }
                                $result.="  </div>
                                <div class='col-7 infoside text-left'>
                                    <p class='infopar'>$artisan->companyname ($artisan->slog)</p>
                                    <p class='infopar'>$artisan->services</p>
                                    <p class='infopar'>$artisan->city, $artisan->state state.</p>
                                    <p class='infopar'>$artisan->address</p>
                                    <p class='infopar'>Tel: <a href='tel:$artisan->phone_no'>$artisan->phone_no</a></p>
                                </div>
                            </div>
                        </div>
                        <div class='col-1'></div>
                    </div>
                </div>";
        }

        return $result;
    }

    //artisans

    public function artisans()
    {
        $artisans=DB::table('artisans')
        ->join('offered_by','offered_by.artisan_id','=','artisans.id')
        ->join('services','services.id','=','offered_by.service_id')
        ->join('cities','cities.id','=','artisans.city_id')
        ->join('states','states.id','=','cities.state_id')
        ->select('artisans.id as ID','artisans.*','states.*','cities.*', DB::raw("group_concat(DISTINCT services.service ORDER BY services.service DESC SEPARATOR ', ') as services"))
        ->groupBy('artisans.id')
        ->orderBy(DB::raw('RAND(1234)'))
        ->simplePaginate(20);
        
        $result="";
        foreach ($artisans as $artisan ) {
        
            $result.="
            <div class='col-6 col-md-3 '>
                    <div class='row pr-2 pl-2'>
                        <div class='col-12 mb-4  card artpage-card'>
                            <div class='row align-items-center'>
                            <div class='imgcover text-center col-5'>";
                            if(null!=$artisan->displaypicture) {
                                $result.="<img src='".asset($artisan->displaypicture)."' alt='profile picture' class='home-card-img fixedimg'>";
                            }
                             else {
                                $result.="<img src='".asset('img/tempavt.png')."' alt='profile picture' class='home-card-img fixedimg'>";
                             }
                        $result.="  </div>
                                <div class='col-7 infoside text-left'>
                                    <p class='infopar'>$artisan->companyname ($artisan->slog)</p>
                                    <p class='infopar'>$artisan->services</p>
                                    <p class='infopar'>$artisan->city, $artisan->state state.</p>
                                    <p class='infopar'>$artisan->address</p>
                                    <p class='infopar'>Tel: <a href='tel:$artisan->phone_no'>$artisan->phone_no</a></p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>";
                
        }
        $result.="<div class='col-12 text-center col-md-12'>". $artisans->links()." </div>";
        return $result;
    }

    //search
    public function search($stuff)
    {
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
        ->whereRaw(DB::raw($query));
        $count=$data->count();
        if ($count>0) {
            $artisans=$data->simplepaginate(20);
            $result="";
            foreach ($artisans as $artisan ) {
                $result.="
                <div class='col-6 col-md-3 '>
                        <div class='row pr-2 pl-2'>
                            <div class='col-12 mb-4  card'>
                                <div class='row align-items-center'>
                                <div class='imgcover text-center col-5'>";
                                if(null!=$artisan->displaypicture) {
                                    $result.="<img src='".asset($artisan->displaypicture)."' alt='profile picture' class='home-card-img fixedimg'>";
                                }
                                 else {
                                    $result.="<img src='".asset('img/tempavt.png')."' alt='profile picture' class='home-card-img fixedimg'>";
                                 }
                            $result.="  </div>
                                    <div class='col-7 infoside text-left'>
                                        <p class='infopar'>$artisan->companyname ($artisan->slog)</p>
                                        <p class='infopar'>$artisan->services</p>
                                        <p class='infopar'>$artisan->city, $artisan->state state.</p>
                                        <p class='infopar'>$artisan->address</p>
                                        <p class='infopar'>Tel: <a href='tel:$artisan->phone_no'>$artisan->phone_no</a></p>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>";
                    
            }
            $result.="<div class='col-12'>". $artisans->links()." </div>";
            
        }else {
            $result="<div class='col-12 errors text-center'>No artisans found</div>";
        }
        return $result;
    }
}
