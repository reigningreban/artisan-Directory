@extends('layout')
@section('pstyle')
        <style>
            body{
                font-family:open sans !important;
            }
        </style>
@endsection

@section('content')
                <div class="container">
                    <div class="row" style="background-color:#ccc;">
                        <div class="col-md-2 col-2"></div>
                        <div class="col-md-8 col-10">
                            <div class="text-center ">
                                
                            </div>
                            <div class="card px-4" style="background-color:#fff;">
                                <h2 class="text-secondary text-center">
                                Congratulations on joining
                                    <a href="/">
                                        <span class="font-weight-bold" style="color:#4a2d43;"><img src="{{asset('img/1app1.png')}}" alt="" class="oneappimg">SEARCH</span>
                                    </a>
                                </h2>
                                <h4 style="color: #DAC2C2FC;" class="mb-3">Hi $agent->firstname,</h4>
                                <p>Congratulations, you have been approved as an agent on 1search.</p>
                                <p>You can now login to your account using your email and password.</p>
                            </div>
                        </div>
                        <div class="col-md-2 col-2"></div>
                    </div>
                </div>

@endsection



