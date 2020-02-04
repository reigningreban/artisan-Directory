@extends('admin/dashlay')

@section('body')
<div class="container-fluid scroll pushup">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-sm-6 py-2">
                <a href="#artisans">
                <div class="card bg-success text-white h-100">
                    <div class="card-body bg-success">
                        <div class="rotate">
                            <i class="fas fa-user fa-4x"></i>
                        </div>
                        <h6 class="text-uppercase">Artisans</h6>
                        <h5 class="display-6">{{$artcount}}</h5>
                </div>
                </div>
                </a>
            </div>
            
            <div class="col-xl-3 col-sm-6 py-2">
                <a href="#services">
                <div class="card bg-info text-white h-100">
                    <div class="card-body bg-info">
                        <div class="rotate">
                            <i class="fas fa-book fa-4x"></i>
                        </div>
                        <h6 class="text-uppercase">Services</h6>
                        <h5 class="display-6">{{$servecount}}</h5>
                </div>
                </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 py-2">
                <a href="#suggested">
                <div class="card bg-secondary text-white h-100">
                    <div class="card-body bg-secondary">
                        <div class="rotate">
                            <i class="fas fa-pause-circle fa-4x"></i>
                        </div>
                        <h6 class="text-uppercase">Pending Approvals</h6>
                        <h5 class="display-6">{{$pendcount}}</h5>
                </div>
                </div>
                </a>
            </div>
            
        </div>
        <div class="row padtop" id="artisans">
            <div class="col-md-2 col-12"></div>
            <div class="col-md-8 col-12">
                <div class="table-responsive">
                    <h4 class="text-center">Artisans</h4>
                    <table class="table table-bordered data">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Company name</th>
                                <th>Slug</th>
                                <th>view profile</th>
                                <th>Enabled</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($art as $artisan)
                                <tr>
                                    <td>{{$artisan->id}}</td>
                                    <td>{{$artisan->companyname}}</td>
                                    <td>{{$artisan->slog}}</td>
                                    <td><a href="/admin/profile/{{$artisan->id}}"><button class="btn pink-btn"><i class="fas fa-eye"></i></button></a></td>
                                    <td>
                                        @if($artisan->enabled==1)
                                            <button class="btn btn-success disable" id="{{$artisan->id}}">Enabled</button>
                                        @else
                                            <button class="btn btn-danger enable" id='{{$artisan->id}}'>Disabled</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-2 col-12"></div>
        </div>


        <div class="row padtop" id="services">
            <div class="col-md-2 col-12"></div>
            <div class="col-md-8 col-12">
                <div class="table-responsive">
                    <h4 class="text-center">Services</h4>
                    <table class="table table-bordered data">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Service</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                                <tr>
                                    <td>{{$service->id}}</td>
                                    <td>{{$service->service}}</td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-2 col-12"></div>
        </div>

        <div class="row padtop" id="suggested">
            <div class="col-md-2 col-12"></div>
            <div class="col-md-8 col-12">
                <div class="table-responsive">
                    <h4 class="text-center">Suggested services</h4>
                    <table class="table table-bordered data">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>service</th>
                                <th>Suggested by</th>
                                <th>Approve</th>
                                <th>Disapprove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pending as $pend)
                                <tr>
                                    <td>{{$pend->id}}</td>
                                    <td>{{$pend->service}}</td>
                                    <td>{{$pend->firstname}} {{$pend->lastname}}</td>
                                    <td>
                                       <a href="/admin/approve/{{$pend->ID}}"><button class="btn btn-success"><i class="fas fa-check"></i></button></a>
                                    </td>
                                    <td>
                                        <a href="/admin/disapprove/{{$pend->ID}}"><button class="btn btn-danger"><i class="fas fa-times"></i></button></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-2 col-12"></div>
        </div>
    </div>
</div>



@endsection