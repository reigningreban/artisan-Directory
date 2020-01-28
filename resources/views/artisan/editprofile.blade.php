@extends('artisan/dashlay')
@section('title','Edit Profile')
@section('perstyle')
<style>
      body{
        font-size:small !important;
      }
      
      @media (max-width: 768px) {
        
      }
    </style>
@endsection
@section('body')
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
                                    <div class="col-12 col-md-6">
                                        <label for="firstname" class="">Firstname:</label>
                                        <div class="input-group">
                                            <input type="text" name="firstname" id="firstname" class="form-control" value="@if(null!==old('firstname')) {{old('firstname')}} @else {{$artisan->firstname}} @endif">
                                        </div>                                    
                                    </div>
                                    <div class="col-12 col-md-6">
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
                                <label for="slog">Slug:</label>
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
                                <button class="btn btn-primary btn-block purple-btn" onclick="this.innerHTML='Loading...'"><i class="fas fa-user-edit"></i> Update</button>
                            </div>
                            @csrf
                        </form>
                        
                        
                        
                    </div>
                </div>
                <div class="col-md-3 col-sm-12"></div>
            </div>
        </div>

        </div>
      

  
  
    
            
        <script type="text/javascript">
        $(document).ready(function () {
            
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