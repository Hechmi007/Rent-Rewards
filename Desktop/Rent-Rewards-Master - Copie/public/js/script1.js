// ICONS CALLBACK
feather.replace();

// SVGs
$('#svgDribbble').load('https://s3-us-west-2.amazonaws.com/s.cdpn.io/373860/references.html #dribbble');
$('#svgTwitter').load('https://s3-us-west-2.amazonaws.com/s.cdpn.io/373860/references.html #twitter');

/*

ADD NEW ICONS
Take it from
https://feathericons.com/

And then replace the icon name in data-father.
<i data-feather="circle"></i>

*/

// NEW CODE HERE

// Focus
$('.text-field').focus(function() {
  if (true) {

    // Expand resoluts bar
    setTimeout(function() {
      $('.resoult-tab').addClass('resoult-tab-active');
    }, 199);

    // Ul title
    setTimeout(function() { 
      $('.ul-title').animate({
        opacity: 1
      }, 299);
    }, 299);
    // List fade in
    setTimeout(function() {
      $('.li').addClass('li-active');
    }, 0);

    // Icon
    $('.search-icon').animate({
      opacity: .3
    },69, 'linear', function() {
      // Icon
      $('.text-field').focusout(function() {
        $('.search-icon').animate({
          opacity: 1
        },69, 'linear',function() {

          // Shrink resoluts bar
          setTimeout(function() {
            $('.resoult-tab').removeClass('resoult-tab-active');
          }, 329);

          // Ul title
          $('.ul-title').animate({
            opacity: 0
          }, 299);

          // List fade out
          setTimeout(function() {
            $('.li').removeClass('li-active');
          }, 99);
          $('.li').animate({
            opacity: 0
          }, 299);
        });
      });
    });

    // Text
    $('.search-placeholder').animate({
      opacity: 0
    }, 69, 'linear', function() {

      // NO FOCUS
      $('.text-field').focusout(function() {
        // if filled
        if (true && $('.text-field').val().length >= 1 ) {
          $('.search-placeholder').css('opacity', '0');
          console.log('very valid');
        } 
        // if empty
        else if (true && $('.text-field').val().length == 0 ) {
          $('.search-placeholder').animate({
            opacity: 1
          },99);
          console.log('non valid');
        }
      });
    });
    console.log('Focus');
  }
});