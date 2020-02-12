@extends('agent/dashlay')
@section('title','Profile')
@section('myartA','active')
@section('body')
      <!-- end topnav -->

      <!-- contanerfluid -->
      <div class="container-fluid scroll py-5 mt-5">
        <h3 class="text-center">
          Artisan Profile
        </h3>
        <div class="container">

            <div class="row">
              <div class="col-md-2"></div>
              <div class="col-12 col-md-8 shad2">
                <div class="row">
                  <div class="col-3 col-md-1"></div>
                  <div class="col-md-12 col-12">
                  <div class="row">
                      <div class="col-md-4 col-3"></div>
                      <div class="col-md-4 col-6">
                         <div class="imgcont text-center">
                      <img src="@if(null!=$artisan->displaypicture) {{asset($artisan->displaypicture)}} @else {{asset('img/tempavt.png')}} @endif" alt="profile picture" class="profimg fixedimg" id="profimg">
                      <div class="text-right">
                        <span id="prfimg" class="btn purple-btn"><i class="fas fa-edit" id="load"></i></span>
                      </div>
                        
                      
                      
                    </div>
                      </div>
                      <div class="col-md-4"></div>
                  </div>
                   
                    <form action="/artisan/picupload" method="post" id="imageform" enctype="multipart/form-data">
                        <input type="file" accept="image/*" name="image" id="upprfimg" class="invisible">
                        <div class="errors text-center">{{$errors->first('image')}}</div>
                        @csrf
                      </form>
                  </div>
                  <div class="col-md-1"></div>
                  <div class="col-md-12 col-12 mt-2">
                  @if (\Session::has('success'))
                            <div class="alert alert-success prompt">
                                <div class="text-right text-danger"><i class="fas fa-times fa-2x" id="close"></i></div>
                                {!! \Session::get('success') !!}
                                
                                
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
                            <td>Service(s)</td>
                            <td>@if($artisan->services != null){{$artisan->services}} @else <span class="text-danger">No approved service added</span> @endif</td>
                          </tr>
                          <tr>
                            <td>Description:</td>
                            <td>
                              @if(($artisan->description)==null)Description not added @else {{$artisan->description}} @endif
                              <div class="errors text-center">{{$errors->first('descrip')}} </div>
                            </td>
                          </tr>
                          <tr>
                            <td>Phone:</td>
                            <td>{{$artisan->phone_no}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="text-center">
                      <a href="/agent/editartisan/{{$artisan->slog}}" class="btn btn-primary purple-btn mr-3"><i class="fas fa-user-edit"></i>Edit profile</a> 
                      <a href="/artisans/{{$artisan->slog}}" class="btn btn-primary purple-btn"><i class="fas fa-eye"></i>Public Profile</a> 
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-8 col-md-2"></div>
              
            </div>



            
                  

                  
</div>

                </div>
               
              
    
              <!-- endsecondrow -->
            </div>
            
 <!-- endcontainer -->
        </div>
         <!-- endcontainerfluid -->
      

  
  
    
            
   
@endsection