$(document).ready(function() {
    $('.background').css('background', 'url(img/bg' + parseInt(Math.random() * 6 + 1) + '.jpg) center center / cover');
    $('input').focus(function() {
        $('.foreground').fadeIn('fast');
    });
    $('input').blur(function() {
        $('.foreground').fadeOut('fast');
    });
});
