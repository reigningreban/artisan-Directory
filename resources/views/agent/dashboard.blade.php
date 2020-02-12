@extends('agent/dashlay')
@section('title','Dashboard')
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
                      <img src="@if(null!=$agent->picture) {{asset($agent->picture)}} @else {{asset('img/tempavt.png')}} @endif" alt="profile picture" class="profimg fixedimg" id="profimg">
                      <div class="text-right">
                        <span id="prfimg" class="btn purple-btn"><i class="fas fa-edit" id="load"></i></span>
                      </div>
                        
                      
                      
                    </div>
                    <form action="/agent/picupload" method="post" id="imageform" enctype="multipart/form-data">
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
                            <td>{{$agent->firstname}} {{$agent->lastname}}</td>
                          </tr>
                          
                          
                          <tr>
                            <td>Address:</td>
                            <td>{{$agent->address}}, {{$agent->city}}, {{$agent->state}} state.</td>
                          </tr>
                          <tr>
                            <td>Email:</td>
                            <td>{{$agent->email}}</td>
                          </tr>
                          <tr>
                            <td>Phone:</td>
                            <td>{{$agent->phone_no}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="text-center">
                      <a href="/agent/editprofile" class="btn btn-primary purple-btn mr-3"><i class="fas fa-user-edit"></i>Edit profile</a> 
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-8 col-md-2"></div>
              
            </div>



            
                  

                  

                </div>
               
            </div>
            
 <!-- endcontainer -->
        </div>
         <!-- endcontainerfluid -->
      
<script>

</script>
@endsection