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
        ->where('artisans.enabled',1)
        ->groupBy('artisans.id') 
        ->inRandomOrder()
        ->limit(9)
        ->get();
        $result="";
        foreach ($artisans as $artisan ) {
            $result.="
            <div class='col-12 col-md-4 '>
                    <div class='row'>
                        <div class='col-md-11 col-12 mb-4 art-card card'>
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
                                    <p class='infopar address'><i class='fas fa-map-marker-alt'></i> $artisan->address</p>
                                    <p class='infopar'>Tel: <a href='tel:$artisan->phone_no'>$artisan->phone_no</a></p>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-1'></div>
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
        ->where('artisans.enabled',1)
        ->groupBy('artisans.id')
        ->orderBy(DB::raw('RAND(1234)'))
        ->simplePaginate(20);
        
        $result="";
        foreach ($artisans as $artisan ) {
        
            $result.="
            <div class='col-12 col-md-3 '>
                    <div class='row pr-4 pl-4'>
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
                                    <p class='infopar address'><i class='fas fa-map-marker-alt'></i> $artisan->address</p>
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

    //calc distance

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


    //near artisans

    public function nearartisans($lat,$lon)
    {
       
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
        $result="";
        if ($count>0) {
            
        
        $artisans=$data->simplePaginate(20);
        foreach ($artisans as $artisan ) {
        
            $result.="
            <div class='col-12 col-md-3 '>
                    <div class='row pr-4 pl-4'>
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
                                    <p class='infopar address'><a target='_blank' href='https://www.google.com/maps/search/?api=1&query=$artisan->latitude,$artisan->longitude'><i class='fas fa-map-marker-alt'></i> $artisan->address</a></p>
                                    <p class='infopar'>Tel: <a href='tel:$artisan->phone_no'>$artisan->phone_no</a></p>
                                    <p class='infopar'>"; 
                                        if(($artisan->distance)<1){
                                            $result.="less than 1KM away";
                                        }else{
                                            $result.=round($artisan->distance,2)."KM away";
                                        } 

                                   $result.= "</p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>";
                
        }
        $result.="<div class='col-12 text-center col-md-12'>". $artisans->links()." </div>";
    }else {
        $result.="<div class='errors'>No artisans nearby</div>";
    }
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
        ->orderBy(DB::raw('RAND(1234)'))
        ->whereRaw(DB::raw($query)."and artisans.enabled =?",[1] );
        $count=$data->count();
        if ($count>0) {
            $artisans=$data->simplepaginate(20);
            $result="";
            foreach ($artisans as $artisan ) {
                $result.="
                <div class='col-12 col-md-3 '>
                        <div class='row pr-4 pl-4'>
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
                                        <p class='infopar address'><i class='fas fa-map-marker-alt'></i> $artisan->address</p>
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


    public function closesearch($stuff,$lat,$lon)
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
        ->select('artisans.id as ID',
        'artisans.*','states.*','cities.*',
         DB::raw("group_concat(DISTINCT services.service ORDER BY services.service DESC SEPARATOR ', ') as services"),
         DB::raw("((degrees(acos(sin(radians($lat)) * sin(radians(artisans.latitude)) +  cos(radians($lat)) * cos(radians(artisans.latitude)) * cos(radians($lon-artisans.longitude)))))*60*1.515*1.609344) as distance"))        
        ->groupBy('artisans.id')
        ->whereRaw(DB::raw($query)." and ((degrees(acos(sin(radians($lat)) * sin(radians(artisans.latitude)) +  cos(radians($lat)) * cos(radians(artisans.latitude)) * cos(radians($lon-artisans.longitude)))))*60*1.515*1.609344)<2 and artisans.enabled=1")
        ->orderBy(DB::raw("((degrees(acos(sin(radians($lat)) * sin(radians(artisans.latitude)) +  cos(radians($lat)) * cos(radians(artisans.latitude)) * cos(radians($lon-artisans.longitude)))))*60*1.515*1.609344)"))
        ;
        


        $count=$data->count();
        if ($count>0) {
            $artisans=$data->simplepaginate(20);
            $result="";
            foreach ($artisans as $artisan ) {
                $result.="
                <div class='col-12 col-md-3 '>
                        <div class='row pr-4 pl-4'>
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
                                        <p class='infopar address'><a target='_blank' href='https://www.google.com/maps/search/?api=1&query=$artisan->latitude,$artisan->longitude'><i class='fas fa-map-marker-alt'></i> $artisan->address</a></p>
                                        <p class='infopar'>Tel: <a href='tel:$artisan->phone_no'>$artisan->phone_no</a></p>
                                        <p class='infopar'>"; 
                                        if(($artisan->distance)<1){
                                            $result.="less than 1KM away";
                                        }else{
                                            $result.=round($artisan->distance,2)."KM away";
                                        } 

                                   $result.= "</p>
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



    //get services
    public function getservices()
    {
        $services=DB::table('services')
        ->get();
        $result="";
        $i='l';
        $j='r';
        foreach ($services as $service ) {
            $result.="
                <div class='col-md-12 col-12  ' >
                    <div class='row'>
                        <div class='col-md-12 col-12 nopad'>
                            <a href='/search/$service->service'>
                                <div class='servecard text-center py-1'>
                                    <span class='servetext'>$service->service</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            ";
            if ($i=='l') {
                $i="r";
                $j='l';
            }else {
                $i='l';
                $j='r';
            }
        }

        return $result;

    }


    public function searchtoartisans()
    {
        $data=request()->validate([
            'search'=>'required'
        ]);
        $search=request('search');
        return redirect('artisans')->with('search',$search);
    }

    public function clicktoartisans($stuff)
    {
        
        $search=$stuff;
        return redirect('artisans')->with('search',$search);
    }
}
