@extends('agent/dashlay')
@section('title','Edit Profile')
@section('body')
      <!-- end topnav -->

      <!-- contanerfluid -->
      <header class="myheader">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-12"></div>
                <div class="col-md-6 col-sm-12">
                    <div class="shad mb-5">
                        <div class="text-center topsignup">
                            <a href="/">
                                <h4 class="font-weight-bold align"><img src="{{asset('img/1app1.png')}}" alt="" class="oneappimg">SEARCH</h4>
                            </a>
                        </div>
                        <h3 class="text-center">Edit Profile</h3>
                        <div class="errors">*All fields are required</div>
                        <form action="/agent/editprofile" method="post">
                            
                                @if(session()->exists('success'))
                                    <div class="alert alert-success prompt">
                                        {{session()->get('success')}}
                                    </div>
                                 @endif
                            
                            <div class="entry">
                                <div class="row">
                                    <div class="col">
                                        <label for="firstname" class="">Firstname:</label>
                                        <div class="input-group">
                                            <input type="text" name="firstname" id="firstname" class="form-control" value="@if(null!=old('firstname')){{old('firstname')}} @else{{$agent->firstname}} @endif">
                                        </div>                                    
                                    </div>
                                    <div class="col">
                                        <label for="lastname" class="">Lastname:</label>
                                        <div class="input-group">
                                            <input type="text" name="lastname" id="lastname" class="form-control" value="@if(null!=old('lastname')){{old('lastname')}} @else{{$agent->lastname}} @endif">
                                        </div>
                                    </div>
                                </div>
                                <div class="errors text-center">{{$errors->first('firstname')}} </div>
                                <div class="errors text-center">{{$errors->first('lastname')}} </div>
                            </div>

                            

                            

                            <div class="entry">
                                <label for="phone">Phone Number:</label>
                                <div class="input-group">
                                    <input type="tel" class="form-control" id="phone" name="phone" value="@if(null!=old('phone')){{old('phone')}} @else{{$agent->phone_no}} @endif">
                                </div>
                                <div class="errors">{{$errors->first('phone')}} </div>
                            </div>

                            <div class="entry">
                                <label for="address">Address:</label>
                                <div class="input-group">
                                    <input type="tel" class="form-control" id="address" name="address" value="@if(null!=old('address')){{old('address')}} @else{{$agent->address}} @endif">
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


                            

                            <div class="entry text-center mt-5 mb-5">
                                <button class="btn btn-primary btn-block purple-btn">Update</button>
                            </div>
                            @csrf
                        </form>
                    
                    </div>
                </div>
                <div class="col-md-3 col-sm-12"></div>
            </div>
        </div>
    </header>
    <script>
        $('document').ready(function () {
            $.get('/agent/statesedit', function(data, status){
                let myresult = ("Data: " + data + "\nStatus: " + status);
                document.getElementById('state').innerHTML =data;
            });
            $.get('/agent/citiesedit', function(data, status){
                let myresult = ("Data: " + data + "\nStatus: " + status);
                document.getElementById('city').innerHTML =data;
            });

            $("body").on("change","#state", function(){
            var state=$("#state").val();
            if (state!="") {
                var link="/artisan/cities/"+state;
                $.get(link, function(data, status){
                document.getElementById('city').innerHTML =data;
            });
            }else{
                
            }
            
        });
        })
    </script>
@endsection