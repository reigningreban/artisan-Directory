@extends('layout')
@section('title','Admin-Dashboard')

@section('content')
    <nav class="navbar navbar-expand-md adminnav" id="navbar">
        <a class="navbar-brand" href="/">
            <img src="{{asset('img/1app1.png')}}" width="50px" alt=""><span id="onesearch" class="text-white">Search</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon text-white">
                <i class="fas fa-bars"></i>
            </span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav ml-auto mr-5" style="font-size:small !important;">
                <li class="nav-item">
                    <a class="nav-link @yield('homeA')" href="/qzwf/dashboard#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @yield('artA')" href="/qzwf/dashboard#artisans">Artisans</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/qzwf/dashboard#services">Services</a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link" href="#suggested">Suggested</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/qzwf/logout">Logout</a>
                </li>
            </ul>
        </div>  
    </nav>

@yield('body')
<footer class="footer myfooter text-center text-white py-3" style="font-size:small !important;">
    ©O'Bounce Technologies 2020
</footer>
<script>
    $(document).ready(function() {
        $('.data').DataTable();
        $("body").on("click",".disable",function () {
            var link='/qzwf/disable/';
            var id=$(this).attr('id');
            link+=id;
            $.get(link, function(data, status){
                let myresult = ("Data: " + data + "\nStatus: " + status);
            });
            $(this).attr('class','btn btn-danger enable');
            $(this).html('Disabled');
        });
        $("body").on("click",".enable",function () {
            var link='/qzwf/enable/';
            var id=$(this).attr('id');
            link+=id;
            $.get(link, function(data, status){
                let myresult = ("Data: " + data + "\nStatus: " + status);
            });
            $(this).attr('class','btn btn-success disable');
            $(this).html('Enabled');
        });




        $("body").on("click",".agentdisable",function () {
            var link='/qzwf/agentdisable/';
            var id=$(this).attr('id');
            link+=id;
            $.get(link, function(data, status){
                let myresult = ("Data: " + data + "\nStatus: " + status);
            });
            $(this).attr('class','btn btn-danger agentenable');
            $(this).html('Disabled');
        });
        $("body").on("click",".agentenable",function () {
            var link='/qzwf/agentenable/';
            var id=$(this).attr('id');
            link+=id;
            $.get(link, function(data, status){
                let myresult = ("Data: " + data + "\nStatus: " + status);
            });
            $(this).attr('class','btn btn-success agentdisable');
            $(this).html('Enabled');
        });







        if($(window).width() < 800) {
                $('table').addClass('table-sm');
                $('pushup').addClass('bmarg');
              }
    } );
</script>
@endsection