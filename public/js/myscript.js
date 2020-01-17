
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
});