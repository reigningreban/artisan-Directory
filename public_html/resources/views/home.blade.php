@extends('nav')
@section('pstyle')
    <style>
      
      .smal{
            font-size: 13px !important;
        }
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
              <span class="text-center text-white font-weight-bold h2">Find <a href="/artisans"><span style="color:#C33764;text-decoration:underline;">Artisans</span></a> in Nigeria</span>
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
                        <div class="col-md-11 col-12">
                            <h4 class="servicet text-center mb-2">Services</h4>
                            <div class="py-2 text-center" id="servetarg">
                                
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
                <div class="col-md-9 col-12 ">
                    <h4 class="mb-2 servicet">Suggested Artisans</h4>
                    <div class="row text-center" id="randart">
                        

                    <div class='col-12 col-md-4 '>
                    <div class='row'>
                        <div class='col-md-11 col-12 mb-4 art-card card'>
                            <div class='row'>
                                <div class='imgcover text-center col-5'>
                                  <img src="{{asset('img/tempavt.png')}}" alt='profile picture' class='home-card-img fixedimg'>
                                  <div class="mb-1">
                                      <a  href="#" data-toggle="popover" title="Address" data-content="huio" data-trigger="focus" class="mr-3 stoplink text-white"><i class="fas fa-map-marker-alt fa-lg"></i></a>
                                      <a href="#" title="Phone" data-toggle="popover" data-trigger="focus" data-content="Phone number" class="stoplink text-white"><i class="fas fa-phone fa-lg"></i></a>
                                      
                                  </div>
                                </div>
                                <div class='col-7 infoside text-left align-items-center'>
                                    <p class='infopar compname'>companyname (slog)</p>
                                    <p class='infopar'>city, state state.</p>
                                    <p class='infopar'>services</p>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-1'></div>
                    </div>
                </div>


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
            // $('[data-toggle="popover"]').popover({html:true});
            function gene() {
                 $("[data-toggle=popover]")
                    .popover({ html: true})
                        .on("focus", function () {
                            $(this).popover("show");
                        }).on("focusout", function () {
                            var _this = this;
                            if (!$(".popover:hover").length) {
                                $(this).popover("hide");
                            }
                            else {
                                $('.popover').mouseleave(function() {
                                    $(_this).popover("hide");
                                    $(this).off('mouseleave');
                                });
                            }
                        });
            }
               



            $.get('/randartisan', function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            
           document.getElementById('randart').innerHTML =data;
        //    $('[data-toggle="popover"]').popover({html:true});
        gene();
       });
        
    $("body").on("click",".stoplink", function(e){
        e.preventDefault();
    });
       $.get('/getservices', function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            
           document.getElementById('servetarg').innerHTML =data;
       });
       $('#searchicon').click(function () {
           var search=$('#search').val();
           var link='/search/'+search;
           $('#searchlink').attr('href',link);
           $('#linkbtn').click();
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