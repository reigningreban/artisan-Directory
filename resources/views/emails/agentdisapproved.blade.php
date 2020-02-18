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
                                    <a href="/">
                                        <span class="font-weight-bold" style="color:#4a2d43;">1SEARCH</span>
                                    </a>
                                </h2>
                                <h4 style="color: #9e6161;" class="mb-3">Hi {{$agent->firstname ?? ''}},</h4>
                                <p>We regret to inform you that your account has been disapproved. </p>
                                <p>We are not able to accept you at this time. We apologise and wish you well in your future endeavours.</p>
                                @include('emails/foot')
                            </div>
                        </div>
                        <div class="col-md-2 col-2"></div>
                    </div>
                </div>

@endsection



