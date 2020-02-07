@extends('artisan/dashlay')
@section('title','dashboard')
@section('body')
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
                      <img src="@if(null!=$artisan->displaypicture) {{asset($artisan->displaypicture)}} @else {{asset('img/tempavt.png')}} @endif" alt="profile picture" class="profimg fixedimg" id="profimg">
                      <div class="text-right">
                        <span id="prfimg" class="btn purple-btn"><i class="fas fa-edit" id="load"></i></span>
                      </div>
                        
                      
                      
                    </div>
                    <form action="/artisan/picupload" method="post" id="imageform" enctype="multipart/form-data">
                        <input type="file" accept="image/*" name="image" id="upprfimg" class="invisible">
                        <div class="errors text-center">{{$errors->first('image')}}</div>
                        @csrf
                      </form>
                  </div>
                  <div class="col-md-1"></div>
                  <div class="col-md-6 col-12 mt-2">
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
              <div class="col-md-12 col-12" id="des">
                <div class="row">
                  <div class="col-md-6 col-12" id="passch">
                      <div class="table-responsive shad2">
                        <form action="/artisan/editdes" method="post">
                        <table class="table">
                          <tr>
                            <td>Service(s)</td>
                            <td>@if($artisan->services != null){{$artisan->services}} @else <span class="text-danger">No approved service added</span> @endif</td>
                          </tr>
                          <tr>
                            <td>Description:</td>
                            <td>
                              <textarea name="descrip" id="descrip" cols="30" rows="5" maxlength="150" placeholder="@if(($artisan->description)==null)Description not added @endif">@if($artisan->description!=null){{$artisan->description}}@endif</textarea>
                              <div class="text-right"><button class="btn purple-btn" onclick="this.innerHTML='Loading...'">Edit</button></div>
                              <div class="errors text-center">{{$errors->first('descrip')}} </div>
                            </td>
                          </tr>
                        </table>
                        @csrf
                      </form>
                      </div>
                    </div>
                  

                  <div class="col-md-5 col-12 shad2 nopad">
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

                </div>
                <!-- <div class="col-md-5 col-12" id="passch">
                    <div class="col-md-12 col-12 shad2">
                      <h4 class="text-center">Rating</h4>
                    </div>
                    <div class="col-md-12 col-12 shad2">
                      <h4 class="text-center">Reviews</h4>
                    </div>
                </div> -->
              
    
              <!-- endsecondrow -->
            </div>
            
 <!-- endcontainer -->
        </div>
         <!-- endcontainerfluid -->
      

  
  
    
            
   
@endsection