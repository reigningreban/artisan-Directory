@extends('nav')
@section('artA','active')
@section('body')
<div class="container-fluid scroll py-5">
        <h3 class="text-center">
          Profile
        </h3>
        <div class="container">
            <div class="row">
                <div class="col-md-1 col-12"></div>
              <div class="col-12 col-md-10 shad2">
                <div class="row">
                  <div class="col-3 col-md-1"></div>
                  <div class="col-md-5 col-12">
                      <div class="row">
                          <div class="col-md-6 col-6">
                            <div class="imgcont text-center mb-3">
                                <img src="@if(null!=$artisan->displaypicture) {{asset($artisan->displaypicture)}} @else {{asset('img/tempavt.png')}} @endif" alt="profile picture" class="profimg fixedimg" id="profimg">
                                <p class="text-center">
                                  <h6 class="font-weight-bolder">{{$artisan->firstname}} {{$artisan->lastname}}</h6>
                                </p>
                                
                                <p class="text-left mt-3 font-weight-bold">
                                  {{$artisan->companyname}}<br>
                                  <i class="fa fa-map-marker-alt"></i>
                                <a target='_blank' href='https://www.google.com/maps/search/?api=1&query={{$artisan->latitude}},{{$artisan->longitude}}'>{{$artisan->address}}, {{$artisan->city}}, {{$artisan->state}} state.</a>
                                </p>
                            </div>
                          </div>
                          <div class="col-md-12">
                          <div class="row">
                  <div class="col-md-12 col-12" id="passch">
                      <div class="table-responsive">
                        <table class="table">
                          
                        </table>
                      </div>
                    </div>
                  </div>
                          </div>
                      </div>
                    
                  </div>
                  <div class="col-md-6 col-12 mt-2">
                  
                    <div class="table-responsive">
                      <table id="mytab" class="table ">
                        <tbody>
                        <tr>
                            <td>Service(s)</td>
                            <td>@if($artisan->services != null){{$artisan->services}} @else pending acceptance.@endif</td>
                          </tr>
                          <tr>
                            <td>Description:</td>
                            <td style="word-wrap: normal;word-break: break-all;">
                                @if($artisan->description!=null){{$artisan->description}} @else <span class="text-danger">No description added</span> @endif 
                            </td>
                          </tr>
                          
                          <tr>
                            <td>Slog:</td>
                            <td>{{$artisan->slog}}</td>
                          </tr>
              
                          <tr>
                            <td>Email:</td>
                            <td><a href="mailto:{{$artisan->email}}">{{$artisan->email}}</a></td>
                          </tr>
                          <tr>
                            <td>Phone:</td>
                            <td><a href="tel:{{$artisan->phone_no}}">{{$artisan->phone_no}}</a></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    
                  </div>
                </div>
              </div>
              <div class="col-md-1"></div>
              <!-- <div class="col-md-4 col-12" id="des">
                
                </div> -->
                
            </div>
            
</div>
</div>      

@endsection