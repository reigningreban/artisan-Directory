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
                                Welcome to 
                                    <a href="/">
                                        <span class="font-weight-bold" style="color:#4a2d43;">1SEARCH</span>
                                    </a>
                                </h2>
                                <h4 style="color: #9e6161;" class="mb-3">Hi {{$artisan->firstname ?? ''}},</h4>
                                <p>Congratulations on taking the first step in promoting your craft to a larger audience.</p>
                                 <p>1Search is an online platform that helps to seamlessly connect potential clients with capable artisans all over Nigeria.</p>
                                <p>Your profile is now live. Be sure to upload a nice profile picture and add a short description to tell potential customers more about your business.</p>
                                <div class="text-center"><a href="https://1search.com.ng/artisan/login"><button class="purple-btn btn">Login to 1Search</button></a></div>
                                @include('emails/foot')
                            </div>
                        </div>
                        <div class="col-md-2 col-2"></div>
                    </div>
                </div>

@endsection



