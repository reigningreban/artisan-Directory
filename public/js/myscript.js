
$(document).ready(function () {
    
    $('#seepass').click(function () {
        var con=$('#eyecon').attr('class');
        if (con=="fas fa-eye-slash") {
            $('#lpassword').attr('type','text')
            $('#eyecon').attr('class','fas fa-eye');
        } else {
            $('#lpassword').attr('type','password')
            $('#eyecon').attr('class','fas fa-eye-slash');
        }
        $('#lpassword').focus();
    });

    $('#prfimg').click(function () {
        $('#upprfimg').click();
    });
                $(window).load(function () {
                    $('.fixedimg').height(function () {
                        return $(this).width();
                    });
                })
              $(window).resize(function () {
                $('.fixedimg').height(function () {
                    return $(this).width();
                });
              });


            //   var width=$('.fixedimg').width();
            //   $(this).height(width);
            //   $(window).resize(function () {
            //     var width=$('.fixedimg').width();
            //     $(this).height(width);
            //   })
});




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


 