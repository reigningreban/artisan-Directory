@extends('nav')
@section('pstyle')
    <style>
       
    </style>
@endsection
@section('homeA','active')
@section('body')
    
    <header class="myheader">
       
        <div class="row align-items-center title">
            <div class="col-md-2"></div>
            <div class="col-md-8 col-sm-12">
                <h1 class="font-weight-bolder text-white text-center">ARTISANS AT THE CLICK OF A BUTTON</h1>
            </div>
            <div class="col-md-2"></div>
        </div>
        <div class="row mb-5">
            <div class="col-md-1 col-1"></div>
            <div class="col-7 col-md-5 text-white ml-1">
                <h3>Want a tested artisan?</h3>
                <a href="/artisans"><button class="btn btn-primary purple-btn pr-4 pl-4">Get An Artisan</button></a>
            </div>
            
        </div>
        <div class="row text-right">
            <div class="col-3 col-md-6"></div>
            <div class="col-8 col-md-5 text-right text-white mr-1">
                <h3>Join our network of skilled artisans</h3>
                <a href="/artisan/signup"><button class="btn btn-primary purple-btn pr-4 pl-4">Join Us</button></a>
            </div>
            <div class="col-md-1"></div>
        </div>
    </header>


    <div class="container-fluid scroll pt-5 pb-5">
        
        <div class="container shad pt-3">
            <h4 class="text-center dpurple mb-4">Artisans</h4>
            <div class="row">
                <div class="col-12 mb-4 col-md-4">
                <a href="#" class="">
                    <div class="card art-card text-center">
                        <img class="card-imgtop" src="{{asset('img/tempavt.png')}}" alt="Card image" height="170px">
                        <div class="card-body text-center">
                            
                            <table class="table text-left table-sm art-tab">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>businessname</td>
                                    </tr>
                                    <tr>
                                        <th>Service(s)</th>
                                        <td>Tailor, carpenter</td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td>businesslocation</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>Addtresss, jsanicscms,dhshndsubyuhunhnhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh</td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            
                            <a href="tel:0202020202020" class="btn btn-primary purple-btn" >Contact</a>
                        </div>
                    </div>
                    </a>
                </div>
                
                <div class="col-12 mb-4 col-md-4">
                <a href="#" class="">
                    <div class="card art-card  text-center">
                        <img class="card-imgtop" src="{{asset('img/tempavt.png')}}" alt="Card image" height="170px">
                        <div class="card-body text-center">
                            
                            <table class="table text-left table-sm art-tab">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>businessname</td>
                                    </tr>
                                    <tr>
                                        <th>Service(s)</th>
                                        <td>Tailor, carpenter</td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td>businesslocation</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>Addtresss, jsanicscms,dhshndsubyuhunhnhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh</td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            
                            <a href="tel:0202020202020" class="btn btn-primary purple-btn" >Contact</a>
                        </div>
                    </div>
                    </a>
                </div>
            </div>
            <div class="text-center">
                <a href="/artisans" class="btn purple-btn">Go to artisans <i class="fas fa-angle-double-right"></i></a href="/artisans">
            </div>
        </div>
    </div>
@endsection