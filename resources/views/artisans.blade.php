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
            <input type="text" class="form-control" placeholder="Search..." id="search">
        </div>
        <div class="container-fluid">
            <div class="row my-4" id="art">
                
                
                
            
        


            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
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
                });
            });

            $("#search").keyup(function(){
                var cat=$("#search").val();
                if (cat!="") {
                    var link="/artisans/search/";
                    link+=cat;
                    $.get(link, function(data, status){
                        let myresult = ("Data: " + data + "\nStatus: " + status);
                        document.getElementById('art').innerHTML =data;
                    });
                }else{
                    getartisans();
                }
            });
        });
        function getartisans() {
            $.get('/getartisans', function(data, status){
                let myresult = ("Data: " + data + "\nStatus: " + status);
                document.getElementById('art').innerHTML =data;

            });
        }
    </script>
@endsection