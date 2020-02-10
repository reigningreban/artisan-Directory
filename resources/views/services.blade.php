@extends('nav')
@section('title','1search - Services')
@section('serva','active')
@section('body')
    <div class="container-fluid scroll">
        <div class="container">
            <div class="row py-5">
                @foreach($services as $service)
                    <div class="col-xl-3 col-sm-6 py-2">
                        <a href="/search/{{$service->service}}">
                        <div class="card bg-info text-white h-100">
                            <div class="card-body bg-info">
                                <div class="rotate">
                                    <i class="fas fa-book fa-4x"></i>
                                </div>
                                <h6 class="text-uppercase"></h6>
                                <h5 class="display-6">{{$service->service}}</h5>
                        </div>
                        </div>
                        </a>
                    </div>
                @endforeach

                

            </div>
        </div>
    </div>
@endsection