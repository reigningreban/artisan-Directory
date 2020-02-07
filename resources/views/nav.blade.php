@extends('layout')
@section('pstyle')
<style>
 body{
          
      }
 </style>
@endsection
@section('content')
    <nav class="navbar navbar-expand-md mainnav" id="navbar">
        <a class="navbar-brand" href="/">
            <img src="{{asset('img/1app1.png')}}" width="50px" alt=""><span id="onesearch" class="text-white">Search</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon text-white">
                <i class="fas fa-bars"></i>
            </span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav ml-auto mr-5" style="font-size:small !important;">
                <li class="nav-item">
                    <a class="nav-link @yield('homeA')" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @yield('artA')" href="/artisans">Artisans</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rborder" href="/artisan/signup">Signup</a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link" href="/artisan/login">Login</a>
                </li>
                   
            </ul>
        </div>  
    </nav>
    <script>
                // When the user scrolls down 20px from the top of the document, slide down the navbar
            // When the user scrolls to the top of the page, slide up the navbar (50px out of the top view)
            window.onscroll = function() {
                scrollFunction();
            
            };
         
            function scrollFunction() {
            if (document.body.scrollTop > 60 || document.documentElement.scrollTop > 60) {
                $('body').attr('class','push');
                document.getElementById("navbar").setAttribute('class','navbar navbar-expand-md sticky');
               
            } else {
                $('body').attr('class','');
                document.getElementById("navbar").setAttribute('class','navbar navbar-expand-md mainnav');;
            }
            }
    </script>

    @yield('body')
       <footer class="footer myfooter text-center text-white py-3" style="font-size:small !important;">
            copyright©O'Bounce Tech 2020
       </footer>
    
@endsection