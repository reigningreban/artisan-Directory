@extends('layout')
@section('title','Dashboard')
@section('pstyle')
    <link rel="stylesheet" href="{{asset('css/style2.css')}}">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <style>
      body{
        font-size:smaller !important;
      }
      
      @media (max-width: 768px) {
        body{
          font-size:X-small !important;
        }
        .prompt{
          font-size:small !important;
        }
      }
    </style>
    @yield('perstyle')
@endsection

@section('content')

  <div class="wrapper">
      <!-- Sidebar  -->
      <nav id="sidebar" class="active">
          <div class="sidebar-header">
              <div class="imgcont text-center">
                <img src="@if(null!=$artisan->displaypicture) {{$artisan->displaypicture}} @else {{asset('img/tempavt.png')}} @endif" alt="profile picture" class="profnav fixedimg">
              </div>
              <p class="text-center">{{$artisan->companyname}}</p>
          </div>

          <ul class="list-unstyled components">
              
              <li>
                  <a href="/artisan/dashboard" class="active"><i class="fas fa-user"></i> My Profile </a>
              </li>  
              <li>
                  <a href="#" class=""><i class="fa fa-star"></i> My Reviews </a>
              </li>   
              <li>
                  <a href="/" class=""><i class="fas fa-home"></i> Home</a>
              </li>             
          </ul>
      
      </nav>
      <!-- End sidebar -->

      <!-- Page Content  -->
      <!-- top nav -->
      <nav id="dashnav">
        <span id="content">
        <button type="button" id="sidebarCollapse" class="btn btn-info purple-btn">
              <i class="fas fa-bars"></i>
              <span>Menu</span>
            </button>
            <a href="/artisan/logout" id="logout" class="btn btn-info purple-btn">
              <i class="fas fa-power-off"></i>
              <span>Logout</span>
            </a>
      </span>

      
      </nav>
    @yield('body')
     <!-- endwrapper -->
  </div>

  
  
    <footer class="footer dashfoot text-center text-white py-3">
            ©O'Bounce Technologies 2020
       </footer>
            
    <script type="text/javascript">
        $(document).ready(function () {
            $("#sidebar").mCustomScrollbar({
                theme: "minimal"
            });

            $('#upprfimg').change(function () {
              $('#load').attr('class','fas fa-circle-notch fa-spin');
              $('#imageform').submit();
            })

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar, #content, #dashnav').toggleClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');
            });
              
            
              if($(window).width() < 800) {
                $('#mytab').addClass('table-sm');
                $('#mytab').addClass('mt-4');
              }else{
                $('#des').addClass('mr-3');
                $('#passch').addClass('ml-3');
              }
        });
        $("body").on("click","#close", function(){
              $('.prompt').hide();
            })
    </script>
@endsection