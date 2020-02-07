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
                            <div class='row'>
                                <div class='imgcover text-center col-5'>";
                                    if(null!=$artisan->displaypicture) {
                                        $result.="<img src='".asset($artisan->displaypicture)."' alt='profile picture' class='home-card-img fixedimg'>";
                                    }
                                     else {
                                        $result.="<img src='".asset('img/tempavt.png')."' alt='profile picture' class='home-card-img fixedimg'>";
                                     }
                                $result.="  
                                    <div class='mb-1'>
                                    <a  href='#' data-toggle='popover' title='Address' data-content='$artisan->address' data-trigger='manual' class='mr-3 stoplink text-white'><i class='fas fa-map-marker-alt fa-lg'></i></a>
                                    <a href='#' title='Phone' data-toggle='popover' data-trigger='manual' data-content='<a href=&quot;tel:$artisan->phone_no&quot;>$artisan->phone_no</a>' class='stoplink text-white'><i class='fas fa-phone fa-lg'></i></a>
                                    </div>
                                </div>
                                <div class='col-7 infoside text-left align-items-center'>
                                    <p class='infopar compname'>$artisan->companyname ($artisan->slog)</p>
                                    <p class='infopar'>$artisan->city, $artisan->state state.</p>
                                    <p class='infopar'>$artisan->services</p>
                                    <p class='infopar text-right'><a href='/artisans/$artisan->slog' class=' text-white tobe '><i class='fas fa-expand-alt'></i></a></p>
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
                        <div class='row'>
                        <div class='imgcover text-center col-5'>";
                            if(null!=$artisan->displaypicture) {
                                $result.="<img src='".asset($artisan->displaypicture)."' alt='profile picture' class='home-card-img fixedimg'>";
                            }
                             else {
                                $result.="<img src='".asset('img/tempavt.png')."' alt='profile picture' class='home-card-img fixedimg'>";
                             }
                        $result.="  
                            <div class='mb-1'>
                            <a  href='#' data-toggle='popover' title='Address' data-content='$artisan->address' data-html='true' data-trigger='manual' class='mr-3 stoplink text-white'><i class='fas fa-map-marker-alt fa-lg'></i></a>
                            <a href='#' title='Phone' data-toggle='popover' data-trigger='manual' data-html='true' data-content='<a href=&quot;tel:$artisan->phone_no&quot;>$artisan->phone_no</a>' class='stoplink text-white'><i class='fas fa-phone fa-lg'></i></a>
                            </div>
                        </div>
                        <div class='col-7 infoside text-left align-items-center'>
                            <p class='infopar compname'>$artisan->companyname ($artisan->slog)</p>
                            <p class='infopar'>$artisan->city, $artisan->state state.</p>
                            <p class='infopar'>$artisan->services</p>
                            <p class='infopar text-right'><a href='/artisans/$artisan->slog' class=' text-white tobe '><i class='fas fa-expand-alt'></i></a></p>
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
                        <div class='row'>
                        <div class='imgcover text-center col-5'>";
                        if(null!=$artisan->displaypicture) {
                            $result.="<img src='".asset($artisan->displaypicture)."' alt='profile picture' class='home-card-img fixedimg'>";
                        }
                         else {
                            $result.="<img src='".asset('img/tempavt.png')."' alt='profile picture' class='home-card-img fixedimg'>";
                         }
                    $result.="  
                    <div class='mb-1'>
                            <a  href='#' data-toggle='popover' title='Address' data-content='<a target=&quot;_blank&quot; href=&quot;https://www.google.com/maps/search/?api=1&query=$artisan->latitude,$artisan->longitude&quot;> $artisan->address</a>' data-html='true' data-trigger='manual' class='mr-3 stoplink text-white'><i class='fas fa-map-marker-alt fa-lg'></i></a>
                            <a href='#' title='Phone' data-toggle='popover' data-trigger='manual' data-html='true' data-content='<a href=&quot;tel:$artisan->phone_no&quot;>$artisan->phone_no</a>' class='stoplink text-white'><i class='fas fa-phone fa-lg'></i></a>
                            </div></div>
                            <div class='col-7 infoside text-left align-items-center'>
                                <p class='infopar compname'>$artisan->companyname ($artisan->slog)</p>
                                <p class='infopar'>$artisan->city, $artisan->state state.</p>
                                <p class='infopar'>$artisan->services</p>
                                <p class='infopar'>"; 
                                    if(($artisan->distance)<1){
                                        $result.="less than 1KM away";
                                    }else{
                                        $result.=round($artisan->distance,2)."KM away";
                                    } 

                               $result.= "</p>
                                <p class='infopar text-right'><a href='/artisans/$artisan->slog' class=' text-white tobe '><i class='fas fa-expand-alt'></i></a></p>
                            </div>
                        </div>
                        </div>
                        
                    </div>
                </div>";
                
        }
        $result.="<div class='col-12 text-center col-md-12'>". $artisans->links()." </div>";
    }else {
        $result.="<div class='col-12 errors text-center'>No artisans nearby</div>";
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
                            <div class='row'>
                            <div class='imgcover text-center col-5'>";
                                if(null!=$artisan->displaypicture) {
                                    $result.="<img src='".asset($artisan->displaypicture)."' alt='profile picture' class='home-card-img fixedimg'>";
                                }
                                 else {
                                    $result.="<img src='".asset('img/tempavt.png')."' alt='profile picture' class='home-card-img fixedimg'>";
                                 }
                            $result.="  
                                <div class='mb-1'>
                                <a  href='#' data-toggle='popover' title='Address' data-content='$artisan->address' data-html='true' data-trigger='manual' class='mr-3 stoplink text-white'><i class='fas fa-map-marker-alt fa-lg'></i></a>
                                <a href='#' title='Phone' data-toggle='popover' data-trigger='manual' data-html='true' data-content='<a href=&quot;tel:$artisan->phone_no&quot;>$artisan->phone_no</a>' class='stoplink text-white'><i class='fas fa-phone fa-lg'></i></a>
                                </div>
                            </div>
                            <div class='col-7 infoside text-left align-items-center'>
                                <p class='infopar compname'>$artisan->companyname ($artisan->slog)</p>
                                <p class='infopar'>$artisan->city, $artisan->state state.</p>
                                <p class='infopar'>$artisan->services</p>
                                <p class='infopar text-right'><a href='/artisans/$artisan->slog' class=' text-white tobe '><i class='fas fa-expand-alt'></i></a></p>
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
                            <div class='row'>
                            <div class='imgcover text-center col-5'>";
                            if(null!=$artisan->displaypicture) {
                                $result.="<img src='".asset($artisan->displaypicture)."' alt='profile picture' class='home-card-img fixedimg'>";
                            }
                             else {
                                $result.="<img src='".asset('img/tempavt.png')."' alt='profile picture' class='home-card-img fixedimg'>";
                             }
                        $result.="  
                        <div class='mb-1'>
                                <a  href='#' data-toggle='popover' title='Address' data-content='<a target=&quot;_blank&quot; href=&quot;https://www.google.com/maps/search/?api=1&query=$artisan->latitude,$artisan->longitude&quot;> $artisan->address</a>' data-html='true' data-trigger='manual' class='mr-3 stoplink text-white'><i class='fas fa-map-marker-alt fa-lg'></i></a>
                                <a href='#' title='Phone' data-toggle='popover' data-trigger='manual' data-html='true' data-content='<a href=&quot;tel:$artisan->phone_no&quot;>$artisan->phone_no</a>' class='stoplink text-white'><i class='fas fa-phone fa-lg'></i></a>
                                </div></div>
                                <div class='col-7 infoside text-left align-items-center'>
                                    <p class='infopar compname'>$artisan->companyname ($artisan->slog)</p>
                                    <p class='infopar'>$artisan->city, $artisan->state state.</p>
                                    <p class='infopar'>$artisan->services</p>
                                    <p class='infopar'>"; 
                                        if(($artisan->distance)<1){
                                            $result.="less than 1KM away";
                                        }else{
                                            $result.=round($artisan->distance,2)."KM away";
                                        } 

                                   $result.= "</p>
                                    <p class='infopar text-right'><a href='/artisans/$artisan->slog' class=' text-white tobe '><i class='fas fa-expand-alt'></i></a></p>
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
        ->limit(10)
        ->get();
        $result="";
        $i='l';
        $j='r';
        foreach ($services as $service ) {
            $result.="
                <a href='/search/$service->service' class='btn pink-btn text-center ninety my-2'>$service->service</a>  
            ";
            // if ($i=='l') {
            //     $i="r";
            //     $j='l';
            // }else {
            //     $i='l';
            //     $j='r';
            // }
        }
        $result.="<a href='/services' class='btn btn-light text-center my-2'>More</a>";

        return $result;

    }

    //services
    public function services()
    {
        $services=DB::table('services')
        ->get();
        
        return view('services',[
            'services'=>$services,
        ]);

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

    public function artprofile($slug)
    {
        
        $artisan=DB::table('artisans')
        ->leftJoin('offered_by','artisans.id','=','offered_by.artisan_id','left outer')
        ->leftjoin('services','services.id','=','offered_by.service_id')
        ->join('cities','cities.id','=','artisans.city_id')
        ->join('states','states.id','=','cities.state_id')
        ->select('artisans.id as ID','artisans.*','states.*','cities.*', DB::raw("group_concat(DISTINCT services.service ORDER BY services.service DESC SEPARATOR ', ') as services"))
        ->groupBy('artisans.id')
        ->where('artisans.slog',$slug)
        ->first();

        return view('/artisanprofile',[
            'artisan'=>$artisan,
        ]);
       
    }
}
