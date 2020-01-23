@extends('nav')
@section('pstyle')
    <style>
       
    </style>
@endsection
@section('homeA','active')
@section('body')
    
    <header class="myheader">
       
        <div class="row align-items-center title">
            <div class="col-md-12" >
                <h1 class="font-weight-bolder text-white text-center">ARTISANS AT THE CLICK OF A BUTTON</h1>
            </div>
        </div>
        <div class="container">
            <div class="row mb-1">
                <div class="col-7 col-md-5 text-white">
                    <h3>Need a tested artisan?</h3>
                    <a href="/artisans"><button class="btn btn-primary purple-btn pr-4 pl-4">Get An Artisan</button></a>
                </div>
                
            </div>
            <div class="row text-right">
                <div class="col-4 col-md-6"></div>
                <div class="col-8 col-md-5 text-right text-white">
                    <h3>Join our network of skilled artisans</h3>
                    <a href="/artisan/signup"><button class="btn btn-primary purple-btn pr-4 pl-4">Join Us</button></a>
                </div>
            </div>
        </div>
        
    </header>


    <div class="container-fluid scroll pt-5 pb-5">
        
        <div class="container shad pt-3">
            <h4 class="text-center dpurple mb-4">Suggested</h4>
            <div class="row text-center" id="randart">
                
            </div>
            <div class="text-center">
                <a href="/artisans" class="btn purple-btn">Go to artisans <i class="fas fa-angle-double-right"></i></a href="/artisans">
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $.get('/randartisan', function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            
           document.getElementById('randart').innerHTML =data;
       });
        });
    </script>
@endsection