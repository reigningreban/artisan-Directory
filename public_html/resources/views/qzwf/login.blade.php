@extends('layout')
@section('title','admin-login')
@section('pstyle')

@section('content')
    <header class="myheader">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-lg-4 col-sm-12"></div>
                <div class="col-md-6 col-lg-4 col-sm-12 align-items-center">
                    <div class="shad mb-5 mt-5">
                        <div class="text-center topsignup">
                            <a href="/">
                                <h4 class="font-weight-bold align"><img src="{{asset('img/1app1.png')}}" alt="" class="oneappimg">SEARCH</h4>
                            </a>
                        </div>
                        <h3 class="text-center">Admin Login</h3>
                        <form action="/qzwf/login" method="post">

                            

                            <div class="entry">
                                <label for="username">Username:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="username" name="username" value="" autofocus>
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

                            
                            <div class="entry text-center mt-5 mb-5">
                                <button class="btn btn-primary btn-block purple-btn">Login</button>
                            </div>
                            @csrf
                        </form>
                        <p class="text-center">
                        </p>
                    </div>
                </div>
                <div class="col-md-3 col-lg-4 col-sm-12"></div>
            </div>
        </div>
    </header>    
@endsection