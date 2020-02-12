@extends('agent/dashlay')
@section('title','Add Artisan')
@section('addartA','active')
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
                        <h3 class="text-center">Add an artisan</h3>
                        <div class="errors">*All fields are required</div>
                        <form action="/agent/addartisan" method="post">
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
                                            <input type="text" name="firstname" id="firstname" class="form-control" value="{{old('firstname')}}" autofocus>
                                        </div>                                    
                                    </div>
                                    <div class="col">
                                        <label for="lastname" class="">Lastname:</label>
                                        <div class="input-group">
                                            <input type="text" name="lastname" id="lastname" class="form-control" value="{{old('lastname')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="errors text-center">{{$errors->first('firstname')}} </div>
                                <div class="errors text-center">{{$errors->first('lastname')}} </div>
                            </div>

                            <div class="entry">
                                <label for="bizname">Business Name:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="bizname" name="bizname" value="{{old('bizname')}}">
                                </div>
                                <div class="errors">{{$errors->first('bizname')}} </div>
                            </div>

                            <div class="entry">
                                <label for="slog">Slug:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="slog" name="slog" value="{{old('slog')}}" placeholder="a short unique name for your company...">
                                </div>
                                <div class="errors">
                                    {{$errors->first('slog')}} 
                                    @if(session()->exists('fail')) {{session()->get('fail')}} @endif
                                </div>
                            </div>

                            

                            <div class="entry">
                                <label for="phone">Phone Number:</label>
                                <div class="input-group">
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{old('phone')}}">
                                </div>
                                <div class="errors">{{$errors->first('phone')}} </div>
                            </div>


                            <div class="entry">
                                <label for="address">Address:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="address" name="address" value="{{old('address')}}">
                                    <input type="hidden" name="longitude" id="longitude"><input type="hidden" name="latitude" id="latitude">
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
                            <!-- <div class="g-recaptcha" data-sitekey="6LfZedcUAAAAAENQoSnUPoUV2rxZbUud4aEGhoLx"></div> -->
                            @if(env('GOOGLE_RECAPTCHA_KEY'))
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                    <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                                    </div>
                                </div>
                            @endif
                            <div class="entry text-center mt-5 mb-5">
                                <button class="btn btn-primary btn-block purple-btn">Add Artisan</button>
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
            $.get('/artisan/states', function(data, status){
                let myresult = ("Data: " + data + "\nStatus: " + status);
                document.getElementById('state').innerHTML =data;
            });
            $.get('/artisan/services', function(data, status){
                    let myresult = ("Data: " + data + "\nStatus: " + status);
                    document.getElementById('services').innerHTML =data;
            });
            // $('#services').hide();
            getLocation();
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

        // $('#addserve').click(function () {
        //     var slide=$('#adserveicon').attr("class");
        //     if (slide=="fas fa-chevron-right") {
        //         $('#services').show(500); 
        //         $('#adserveicon').attr("class","fas fa-chevron-down");
        //     }else{
        //         $('#services').hide(500); 
        //         $('#adserveicon').attr("class","fas fa-chevron-right");
        //     }
            
        // });
        function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
            alert("Geolocation is not supported by this browser.");
        }
        }

        function showPosition(position) {
            $('#latitude').val(position.coords.latitude);
            $('#longitude').val(position.coords.longitude);
        }
    </script>
@endsection