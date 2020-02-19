@extends('layout')
@section('title','Login')
@section('pstyle')

@section('content')
    <header class="myheader">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-3 col-sm-12"></div>
                <div class="col-lg-4 col-md-6 col-sm-12 align-items-center">
                    <div class="shad mb-5 mt-5">
                        <div class="text-center topsignup">
                            <a href="/">
                                <h4 class="font-weight-bold align"><img src="{{asset('img/1app1.png')}}" alt="" class="oneappimg">SEARCH</h4>
                            </a>
                        </div>
                        <h3 class="text-center">Agent Login</h3>
                        <div class="errors">*All fields are required</div>

                        <form action="/agent/login" method="post">

                             @if(session()->exists('notval'))
                                    <div class="alert alert-danger prompt">
                                        {{session()->get('notval')}}
                                    </div>
                                 @endif

                            <div class="entry">
                                <label for="email">E-mail:</label>
                                <div class="input-group">
                                    <input type="email" class="form-control" id="email" name="email" value="@if(Cookie::get('agent_email') !== null){{Cookie::get('agent_email') }} @else{{old('email')}} @endif">
                                </div>
                                
                            </div>

                            
                            <div class="entry">
                                <label for="lpassword">Password:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="seepass">
                                            <i id="eyecon" class="fas fa-eye-slash"></i>
                                        </span>
                                    </div>
                                    <input type="password" class="form-control" id="lpassword" name="lpassword">
                                </div>
                                <div class="errors">{{$errors->first()}} </div>
                                <div class="errors">@if (\Session::has('pass_crash')) {!! \Session::get('pass_crash') !!}@endif</div>
                            </div>

                            <div class="entry">
                                <div class='form-check'>
                                    <label class='form-check-label'>
                                        <input type='checkbox' name='remember' class='form-check-input' value='remember'>Remember me
                                    </label>
                                </div>
                            </div>

                            
                            <div class="entry text-center mt-5 mb-5">
                                <button class="btn btn-primary btn-block purple-btn">Login</button>
                            </div>
                            @csrf
                        </form>
                        <p class="text-center">Forgot password? <a href="">click to recover</a></p>
                        <p class="text-center">Not yet an agent? <a href="/agent/apply">Apply here</a></p>
                        <p class="text-center">
                            <a href="/"><< <i class="fas fa-home"></i> Return to home</a>
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-3 col-sm-12"></div>
            </div>
        </div>
    </header>    
@endsection