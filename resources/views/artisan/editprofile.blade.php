@extends('layout')
@section('title','dashboard')
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
        
      }
    </style>
@endsection

@section('content')

  <div class="wrapper">
      <!-- Sidebar  -->
      <nav id="sidebar" class="active">
          <div class="sidebar-header">
              <div class="imgcont text-center">
                <img src="{{asset('img/tempavt.png')}}" alt="my img" class="profnav">
              </div>
              <p class="text-center">{{$artisan->companyname}}</p>
          </div>

          <ul class="list-unstyled components">
              
              <li>
                  <a href="/artisan/dashboard" class="active">My Profile </a>
              </li>  
              <li>
                  <a href="#" class="">My Reviews </a>
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
      <!-- end topnav -->

      <!-- contanerfluid -->
      <div class="container-fluid scroll py-5 mt-5">
      <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-12"></div>
                <div class="col-md-6 col-sm-12">
                    <div class="shad2 mb-5">
                        
                        <h3 class="text-center">Edit Profile</h3>
                        <div class="errors">*All fields are required</div>
                        <form action="/artisan/editprofile" method="post">

                            <div class="entry">
                                <div class="row">
                                    <div class="col">
                                        <label for="firstname" class="">Firstname:</label>
                                        <div class="input-group">
                                            <input type="text" name="firstname" id="firstname" class="form-control" value="@if(null!==old('firstname')) {{old('firstname')}} @else {{$artisan->firstname}} @endif">
                                        </div>                                    
                                    </div>
                                    <div class="col">
                                        <label for="lastname" class="">Lastname:</label>
                                        <div class="input-group">
                                            <input type="text" name="lastname" id="lastname" class="form-control" value="@if(null!==old('lastname')) {{old('lastname')}} @else {{$artisan->lastname}} @endif">
                                        </div>
                                    </div>
                                </div>
                                <div class="errors text-center">{{$errors->first('firstname')}} </div>
                                <div class="errors text-center">{{$errors->first('lastname')}} </div>
                            </div>

                            <div class="entry">
                                <label for="slog">Business Name:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="bizname" name="bizname" value="@if(null!==old('bizname')) {{old('bizname')}} @else {{$artisan->companyname}} @endif">
                                </div>
                                <div class="errors">{{$errors->first('bizname')}} </div>
                            </div>

                            <div class="entry">
                                <label for="slog">Slog:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="slog" name="slog" value="@if(null!==old('slog')) {{old('slog')}} @else {{$artisan->slog}} @endif" placeholder="a short unique name for your company...">
                                </div>
                                <div class="errors">{{$errors->first('slog')}} </div>
                            </div>

                            <div class="entry">
                                <label for="email">Email:</label>
                                <div class="input-group">
                                    <input type="email" class="form-control" id="email" name="email" value="@if(null!==old('email')) {{old('email')}} @else {{$artisan->email}} @endif">
                                </div>
                                <div class="errors">{{$errors->first('email')}} </div>
                            </div>

                            <div class="entry">
                                <label for="phone">Phone Number:</label>
                                <div class="input-group">
                                    <input type="tel" class="form-control" id="phone" name="phone" value="@if(null!==old('phone')) {{old('phone')}} @else {{$artisan->phone_no}} @endif">
                                </div>
                                <div class="errors">{{$errors->first('phone')}} </div>
                            </div>

                            

                            <div class="entry">
                                <label for="address">Address:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="address" name="address" value="@if(null!==old('address')) {{old('address')}} @else {{$artisan->address}} @endif">
                                </div>
                                <div class="errors">{{$errors->first('address')}} </div>
                            </div>

                            <div class="entry">
                                <label for="state">State:</label>
                                <div class="input-group">
                                    <select name="state" id="state" class="form-control">
                                        <option value="">-Select state-</option>
                                    </select>
                                </div>
                                <div class="errors">{{$errors->first('state')}} </div>
                            </div>

                            <div class="entry">
                                <label for="city">City:</label>
                                <div class="input-group">
                                    <select name="city" id="city" class="form-control">
                                        <option value="">-Select city-</option>
                                    </select>
                                </div>
                                <div class="errors">{{$errors->first('city')}} </div>
                            </div>

                            <div class="entry text-center mt-5">
                                <label for="" id="addserve">Add Services</label><br>
                                <span class="text-primary">*You can add more services</span>
                            </div>
                            <div class="input-group" id="services">
                                
                            </div>
                            <div class="errors">{{$errors->first('services')}} </div>

                            <div class="entry text-center mt-5 mb-5">
                                <button class="btn btn-primary btn-block purple-btn"><i class="fas fa-user-edit"></i> Edit</button>
                            </div>
                            @csrf
                        </form>
                        
                        
                        
                    </div>
                </div>
                <div class="col-md-3 col-sm-12"></div>
            </div>
        </div>
      </div>
     <!-- endwrapper -->
  </div>

  
  
    <footer class="footer dashfoot text-center text-white py-3">
            copyright©O'bounce Tech 2020
       </footer>
            
    <script type="text/javascript">
        $(document).ready(function () {
            $("#sidebar").mCustomScrollbar({
                theme: "minimal"
            });

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar, #content, #dashnav').toggleClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');
            });

            $.get('/artisan/statesedit', function(data, status){
                let myresult = ("Data: " + data + "\nStatus: " + status);
                document.getElementById('state').innerHTML =data;
            });
            $.get('/artisan/citiesedit', function(data, status){
                let myresult = ("Data: " + data + "\nStatus: " + status);
                document.getElementById('city').innerHTML =data;
            });
            $.get('/artisan/servicesedit', function(data, status){
                    let myresult = ("Data: " + data + "\nStatus: " + status);
                    document.getElementById('services').innerHTML =data;
            });
                     
        });

        $("body").on("change","#state", function(){
            var state=$("#state").val();
            var link="/artisan/cities/"+state;
                $.get(link, function(data, status){
                document.getElementById('city').innerHTML =data;
            });
        });

        
              
        
    </script>
@endsection