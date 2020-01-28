@extends('nav')
@section('pstyle')
    <style>
      
    </style>
@endsection
@section('homeA','active')
@section('body')
    
    <header class="myheader">
       
        <div class="row align-items-center title">
            <div class="col-md-12" >
                <h1 class="font-weight-bolder text-white text-center" id="head" style="font-size: calc(2.5rem - 5px);">
                    <span id="target"></span>
                        <span id="cursor"></span> 
                </h1>
            </div>
        </div>
        <div class="container">
            <div class="row mb-5">
                <div class="col-7 col-md-5 text-white">
                    <h4>
                        Need a tested artisan?
                    </h4>
                    <a href="/artisans"><button class="btn btn-primary pink-btn pr-4 pl-4">Get An Artisan</button></a>
                </div>
                
            </div>
            <div class="row text-right">
                <div class="col-4 col-md-6"></div>
                <div class="col-8 col-md-5 text-right text-white">
                    <h4>Join our network of skilled artisans</h4>
                    <a href="/artisan/signup"><button class="btn btn-primary pr-4 pl-4">Join Us</button></a>
                </div>
            </div>
        </div>
        
    </header>


    <div class="container-fluid scroll pt-5 pb-5">
        
        <div class="container shad pt-3">
            <h4 class="text-center dpurple mb-4">Suggested Artisans</h4>
            <div class="row text-center" id="randart">
                
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
        });

     $(document).ready(function () {

        (function ($) {
    // writes the string
    //
    // @param jQuery $target
    // @param String str
    // @param Numeric cursor
    // @param Numeric delay
    // @param Function cb
    // @return void
    function typeString($target, str, cursor, delay, cb) {
      $target.html(function (_, html) {
        return html + str[cursor];
      });
  
      if (cursor < str.length - 1) {
        setTimeout(function () {
          typeString($target, str, cursor + 1, delay, cb);
        }, delay);
      }
      else {
        cb();
      }
    }
  
    // clears the string
    //
    // @param jQuery $target
    // @param Numeric delay
    // @param Function cb
    // @return void
    function deleteString($target, delay, cb) {
      var length;
  
      $target.html(function (_, html) {
        length = html.length;
        return html.substr(0, length - 1);
      });
  
      if (length > 1) {
        setTimeout(function () {
          deleteString($target, delay, cb);
        }, delay);
      }
      else {
        cb();
      }
    }
  
    // jQuery hook
    $.fn.extend({
      teletype: function (opts) {
        var settings = $.extend({}, $.teletype.defaults, opts);
  
        return $(this).each(function () {
          (function loop($tar, idx) {
            // type
            typeString($tar, settings.text[idx], 0, settings.delay, function () {
              // delete
              setTimeout(function () {
                deleteString($tar, settings.delay, function () {
                  loop($tar, (idx + 1) % settings.text.length);
                });
              }, settings.pause);
            });
  
          }($(this), 0));
        });
      }
    });
  
    // plugin defaults  
    $.extend({
      teletype: {
        defaults: {
          delay: 100,
          pause: 5000,
          text: []
        }
      }
    });
  }(jQuery));

        $('#target').teletype({
    text: [
      'ARTISANS AT THE CLICK OF A BUTTON',
      
    ]
  });
  
  $('#cursor').teletype({
    text: ['|', ' '],
    delay: 0,
    pause: 500
  });

            if($(window).width() < 800) {
                $('#head').html('ARTISANS AT THE CLICK OF A BUTTON');
              }else{
                $('#head').addClass('display-4');
              }
     })
    </script>
@endsection