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
        body{
          font-size:X-small !important;
        }
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
        <h3 class="text-center">
          My Profile
        </h3>
        <div class="container">

            <div class="row">
              <div class="col-md-2"></div>
              <div class="col-12 col-md-8 shad2">
                <div class="row">
                  <div class="col-3 col-md-1"></div>
                  <div class="col-md-4 col-6">
                    <div class="imgcont text-center">
                      <img src="{{asset('img/tempavt.png')}}" alt="" class="profimg">
                    </div>
                  </div>
                  <div class="col-md-1"></div>
                  <div class="col-md-6 col-12">
                  @if (\Session::has('success'))
                            <div class="alert alert-success prompt">
                                <div class="text-right text-danger"><i class="fas fa-times fa-lg" id="close"></i></div>
                                <ul>
                                    <li>{!! \Session::get('success') !!}</li>
                                </ul>
                                
                            </div>
                        @endif
                    <div class="table-responsive">
                      <table id="mytab" class="table ">
                        <tbody>
                          <tr>
                            <td>Name:</td>
                            <td>{{$artisan->firstname}} {{$artisan->lastname}}</td>
                          </tr>
                          <tr>
                            <td>Company:</td>
                            <td>{{$artisan->companyname}}</td>
                          </tr>
                          <tr>
                            <td>Slog:</td>
                            <td>{{$artisan->slog}}</td>
                          </tr>
                          <tr>
                            <td>Address:</td>
                            <td>{{$artisan->address}}, {{$artisan->city}}, {{$artisan->state}} state.</td>
                          </tr>
                          <tr>
                            <td>Email:</td>
                            <td>{{$artisan->email}}</td>
                          </tr>
                          <tr>
                            <td>Phone:</td>
                            <td>{{$artisan->phone_no}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="text-center">
                      <a href="/artisan/editprofile" class="btn btn-primary purple-btn"><i class="fas fa-user-edit"></i>Edit profile</a> 
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-8 col-md-2"></div>
              
            </div>



            <div class="row">
              <div class="col-md-6 col-12" id="des">
                <div class="row">
                  <div class="col-md-12" id="passch">
                      <div class="table-responsive shad2">
                        <form action="/artisan/editdes" method="post">
                        <table class="table">
                          <tr>
                            <td>Service(s)</td>
                            <td>{{$artisan->services}}</td>
                          </tr>
                          <tr>
                            <td>Description:</td>
                            <td>
                              <textarea name="descrip" id="descrip" cols="30" rows="5" maxlength="150" placeholder="@if(($artisan->description)==null)Description not added @endif">@if($artisan->description!=null){{$artisan->description}}@endif</textarea>
                              <div class="text-right"><button class="btn purple-btn">Edit</button></div>
                              <div class="errors text-center">{{$errors->first('descrip')}} </div>
                            </td>
                          </tr>
                        </table>
                        @csrf
                      </form>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12 col-12 shad2 nopad">
                <h3 class="text-center">Change Password</h3>
                <form action="/artisan/changepass" method="post">
                  <div class="entry">
                    <label for="oldpass">Old Password:</label>
                    <div class="form-group">
                      <input type="password" class="form-control" name="oldpass" id="oldpass" value="{{old('oldpass')}}">
                    </div>
                    <div class="errors">{{$errors->first('oldpass')}} 
                    @if (\Session::has('failure')){!! \Session::get('failure') !!}@endif
                    </div>
                  </div>
                  <div class="entry">
                    <label for="newpass">New Password</label>
                    <div class="form-group">
                      <input type="password" class="form-control" name="newpass" id="newpass" value="{{old('newpass')}}">
                    </div>
                    <div class="errors">{{$errors->first('newpass')}} </div>
                  </div>
                  <div class="entry">
                    <label for="repass">Repeat New Password</label>
                    <div class="form-group">
                      <input type="password" class="form-control" name="repass" id="repass" value="{{old('repass')}}">
                    </div>
                    <div class="errors">{{$errors->first('repass')}} </div>
                  </div>
                  
                  <div class="entry text-center">
                    <button class="btn purple-btn">Change</button>
                  </div>
                  @csrf
                </form>
              </div>
                  

                </div>
                <div class="col-md-5 col-12" id="passch">
                    <div class="col-md-12 col-12 shad2">
                      <h4 class="text-center">Rating</h4>
                    </div>
                    <div class="col-md-12 col-12 shad2">
                      <h4 class="text-center">Reviews</h4>
                    </div>
                </div>
              
    
              <!-- endsecondrow -->
            </div>
            
 <!-- endcontainer -->
        </div>
         <!-- endcontainerfluid -->
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