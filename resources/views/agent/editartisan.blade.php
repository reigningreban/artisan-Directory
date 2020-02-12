@extends('agent/dashlay')
@section('title','Add Artisan')
@section('myartA','active')
@section('body')

<header class="myheader scroll">
        <div class="container pt-5">
            <div class="row">
                <div class="col-md-3 col-sm-12"></div>
                <div class="col-md-6 col-sm-12">
                    <div class="shad mb-5">
                        <div class="text-center topsignup">
                            <a href="/">
                                <h4 class="font-weight-bold align"><img src="{{asset('img/1app1.png')}}" alt="" class="oneappimg">SEARCH</h4>
                            </a>
                        </div>
                        <h3 class="text-center">Edit artisan</h3>
                        <div class="errors">*All fields are required</div>
                        <form action="/agent/editartisan/{{$artisan->slog}}" method="post">
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
                                            <input type="text" name="firstname" id="firstname" class="form-control" value="@if(null!=old('firstname')){{old('firstname')}} @else {{$artisan->firstname}} @endif">
                                        </div>                                    
                                    </div>
                                    <div class="col">
                                        <label for="lastname" class="">Lastname:</label>
                                        <div class="input-group">
                                            <input type="text" name="lastname" id="lastname" class="form-control" value="@if(null!=old('lastname')){{old('lastname')}} @else {{$artisan->lastname}} @endif">
                                        </div>
                                    </div>
                                </div>
                                <div class="errors text-center">{{$errors->first('firstname')}} </div>
                                <div class="errors text-center">{{$errors->first('lastname')}} </div>
                            </div>

                            <div class="entry">
                                <label for="bizname">Business Name:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="bizname" name="bizname" value="@if(null!=old('bizname')){{old('bizname')}} @else {{$artisan->companyname}} @endif">
                                </div>
                                <div class="errors">{{$errors->first('bizname')}} </div>
                            </div>

                            <div class="entry">
                                <label for="slog">Slug:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="slog" name="slog" value="@if(null!=old('slog')){{old('slog')}} @else {{$artisan->slog}} @endif" placeholder="a short unique name for your company...">
                                </div>
                                <div class="errors">
                                    {{$errors->first('slog')}} 
                                    @if(session()->exists('fail')) {{session()->get('fail')}} @endif
                                </div>
                            </div>

                            

                            <div class="entry">
                                <label for="phone">Phone Number:</label>
                                <div class="input-group">
                                    <input type="tel" class="form-control" id="phone" name="phone" value="@if(null!=old('phone')){{old('phone')}} @else {{$artisan->phone_no}} @endif">
                                </div>
                                <div class="errors">{{$errors->first('phone')}} </div>
                            </div>


                            <div class="entry">
                                <label for="address">Address:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="address" name="address" value="@if(null!=old('address')){{old('address')}} @else {{$artisan->address}} @endif">
                                    <!-- <input type="hidden" name="longitude" id="longitude"><input type="hidden" name="latitude" id="latitude"> -->
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
                                <label for="" id="addserve">Add Services <i class="fas fa-chevron-down" id="adserveicon"></i></label><br>
                                <span class="text-danger">*select at least one</span>
                            </div>
                            <div class="input-group" id="services">
                                
                            </div>
                            <div class="input-group" id="otherserve">
                                <input type="text" name="others" placeholder="suggest a new service..." id="othertext">
                            </div>
                            <div class="errors">{{$errors->first('services')}} </div>
                            <div class="errors">{{$errors->first('others')}} </div>
                            
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
        $(document).ready(function() {
            $('#otherserve').hide();
            
            $.get(window.location.pathname+'/statesedit', function(data, status){
                let myresult = ("Data: " + data + "\nStatus: " + status);
                document.getElementById('state').innerHTML =data;
            });
            $.get(window.location.pathname+'/citiesedit', function(data, status){
                let myresult = ("Data: " + data + "\nStatus: " + status);
                document.getElementById('city').innerHTML =data;
            });
            $.get(window.location.pathname+'/servicesedit', function(data, status){
                    let myresult = ("Data: " + data + "\nStatus: " + status);
                    document.getElementById('services').innerHTML =data;
            });
           
            // $('#services').hide();
        });

        $("body").on("change","#state", function(){
            var state=$("#state").val();
            var link="/artisan/cities/"+state;
                $.get(link, function(data, status){
                document.getElementById('city').innerHTML =data;
            });
        });
        $("body").on("change","#othercheck", function(){
           if(this.checked){
               $('#otherserve').show(500);
               $('#othertext').focus();
           }else{
            $('#otherserve').hide(500);
            $('#othertext').val("");
           }
        });

        
        
    </script>
@endsection