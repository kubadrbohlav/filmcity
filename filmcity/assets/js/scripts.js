// remove classes if javascript is allowed by browser
$('header, #mobile-header, #toggleMenu, #mobile-menu').removeClass('no-js');

// Toggle main menu
$('#toggleMenu').click( function () {

  if ( $(this).hasClass('active') ) {
    $(this).removeClass('active');
  }
  else {
    $(this).addClass('active');
  }

  var mobileMenu = $('#mobile-menu');
  if( mobileMenu.hasClass('active') ) {
    mobileMenu.removeClass('active');
    mobileMenu.css('margin-right', '-200px');
  }
  else {
    mobileMenu.addClass('active');
    mobileMenu.css('margin-right', '0px');
  }
});

// Scroll Top button
$('#scrollTop').click(function(){
  $(window.opera ? 'html' : 'html, body').animate({
    scrollTop: 0
  }, 500);
});

// Show and hode Toggle button
$(window).scroll(function() {
  var scrollBtn = $('#scrollTop');

  if ($(this).scrollTop() > 300) { // this refers to window
    scrollBtn.fadeIn();
  }
  else {
    scrollBtn.fadeOut();
  }
});
