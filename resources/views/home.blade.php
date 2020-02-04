@extends('nav')
@section('pstyle')
    <style>
      
      
      .nomarg{
          margin:0% !important;
      }
      /* .nopad{
          padding-left:0% !important;
          padding-right:0% !important;
      } */
      .service-item a{
          display:block;
          color:white;
          border:1px solid white;
          
      }
      
      .service-item{
        min-height:80px;
        
      }
      .cont{
          border:1px solid white;
      }
        /* .stick {
        position: ;
        width:118.49;
        } */

        
    </style>
@endsection
@section('homeA','active')
@section('body')
    
    <header class="myheader">
       <div class="container-fluid">

       
          <div class="row align-items-center title text" id="topmark">
            <div class="col-md-3 col-1"></div>
            <div class="col-md-6 col-12 text-center">
              <span class="text-center text-white font-weight-bold h5">Find <a href="/artisans"><span style="color:#C33764;">artisans</span></a> in Nigeria</span>
              <form action="/search" method="post" id="myform">
                  <!-- <a href="" class="invisible" id="searchlink"><button id="linkbtn"></button></a> -->
                <div class="input-group mt-4">
                  <input type="text" class="form-control" placeholder="search..." id="search" name="search">
                  <div class="input-group-append">
                      <span class="input-group-text" id="searchicon">
                          <i class="fas fa-search"></i>
                      </span>
                  </div>
                </div>
                @csrf
              </form>
            </div>
            <div class="col-md-3 col-1"></div>
          </div>
        
        </div>
    </header>


    <div class="container-fluid scroll pt-5 pb-5">
        
        <div class="container-fluid  pt-3 nomarg">
            
            <div class="row ">
                <div class="col-md-3 col-12">
                    <div class="row">
                        <div class="col-md-11 col-12 shade">
                            <h4 class="servicet text-center">Services</h4>
                            <div class="row" id="servetarg">
                                

                                
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
                <div class="col-md-9 col-12 sug">
                    <h4 class="dpurple mb-4">Suggested Artisans</h4>
                    <div class="row text-center" id="randart">

                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="/artisans" class="btn purple-btn">Go to artisans <i class="fas fa-angle-double-right"></i></a href="/artisans">
            </div>
        </div>
    </div>
    <script>
       
        $(document).ready(function () {
            $.get('/randartisan', function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            
           document.getElementById('randart').innerHTML =data;
       });
       $.get('/services', function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            
           document.getElementById('servetarg').innerHTML =data;
       });
       $('#searchicon').click(function () {
           var search=$('#search').val();
        //    var link='/search/'+search;
        //    $('#searchlink').attr('href',link);
        //    $('#linkbtn').click();
            if (search!="") {
                $('#myform').submit();
            }
       });
        });
        function offset(el) {
    var rect = el.getBoundingClientRect(),
    scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
    scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    return { top: rect.top + scrollTop, left: rect.left + scrollLeft }
}






    </script>
@endsection