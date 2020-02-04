@extends('nav')
@section('artA','active')
@section('pstyle')
    <style>
        .nopad{
            padding-left:1% !important;
            padding-right:1% !important;
        }
        
    </style>
@endsection
@section('body')
    <div class="container-fluid scroll py-5" id="nopad">
        <div class="container">
            <input type="text" class="form-control" placeholder="Search..." id="search" @if (\Session::has('search')) value="{!! \Session::get('search') !!}" @endif>
            <input type="hidden" id="longitude"><input type="hidden" id="latitude">
        </div>
        <div class="container pt-3">
            <div class="row">
                <div class="col text-center">
                    <button class="btn pink-btn inactive" id="nearby"><i class="fas fa-map-marker-alt" id="nearicon"></i> Nearby</button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row my-4" id="art">
                
                
                
            
        


            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // setTimeout(function() {
            //     searcher();
            // }, 1000);
            
            $('html,body').animate({ scrollTop: 0 }, 'slow');
            $(document).on("keydown", "form", function(event) { 
                return event.key != "Enter";
            });
            getartisans();
            if($(window).width() < 800) {
                $('#nopad').addClass('nopad');
                
              }else{
              };
            $("body").on("click",".page-link", function(e){
                e.preventDefault();
                var link=$(this).attr('href');
                $.get(link, function(data, status){
                    let myresult = ("Data: " + data + "\nStatus: " + status);
                    document.getElementById('art').innerHTML =data;
                    // $('html,body').animate({ scrollTop: 0 }, 'slow');
                    $(document).scrollTop(0)
                    $('.fixedimg').height(function () {
                        return $(this).width();
                    });
                });
            });

            $("#search").keyup(function(){
                searcher();
            });
            $('#nearby').click(function () {
                var search=$('#search').val();
                if ($('#nearby').attr('class')=="btn pink-btn inactive") {
                    $('#nearicon').attr('class','fas fa-circle-notch fa-spin');
                        getLocation();                    
                }else{
                    getartisans();
                    $('#nearby').addClass('inactive');
                    
                }
               

                
            });
        });
        function getartisans() {
            $.get('/getartisans', function(data, status){
                let myresult = ("Data: " + data + "\nStatus: " + status);
                document.getElementById('art').innerHTML =data;
                $('.fixedimg').height(function () {
                        return $(this).width();
                    });
                searcher();
            });
        }
        function searcher() {
            var cat=$("#search").val();
                if (cat!="") {
                    var link;
                    if ($('#nearby').attr('class')=="btn pink-btn inactive") {
                        link="/artisans/search/";
                        link+=cat;
                        }else{
                            var lat=$('#latitude').val();
                            var lon=$('#longitude').val();
                            link="/artisans/closesearch/";
                            link+=cat+'/'+lat+'/'+lon;
                            
                        }
                    
                    $.get(link, function(data, status){
                        let myresult = ("Data: " + data + "\nStatus: " + status);
                        document.getElementById('art').innerHTML =data;
                        $('.fixedimg').height(function () {
                        return $(this).width();
                    });
                    });
                    
                }else{
                    if ($('#nearby').attr('class')=="btn pink-btn inactive") {
                        getartisans();
                    }else{
                        getLocation();
                    }
                    
                }
        }
        function closeartisans() {            
            var lat=$('#latitude').val();
            var lon=$('#longitude').val();
            var link='/getnearartisans/'+lat+'/'+lon;
            $.get(link, function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            document.getElementById('art').innerHTML =data;
            $('.fixedimg').height(function () {
                    return $(this).width();
                });
            $('#nearby').removeClass('inactive');
            $('#nearicon').attr('class','fas fa-map-marker-alt');
            
            searcher();
        });
           
            
        }
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
            closeartisans();
        }        
    </script>
@endsection