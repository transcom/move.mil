// (function($) {
//   var offsetJumpScroll = function() {
//     var jumpLink = $('.jump-link a');
//     jumpLink.click(function(e) {
//       var href = $(this).attr('href');
//
//       console.log(href);
//       e.preventDefault();
//       var targetId = href.substring(1, href.length);
//       var target = $("div[id*='" + targetId + "']");
//       var offset = $(target).offset();
//       offset.top -= 200;
//       $('html, body').animate({
//         scrollTop: offset.top,
//         scrollLeft: 0
//       }, 1000);
//       return;
//     });
//   };
//
//   $(window).on('load', function() {
//     offsetJumpScroll();
//   });
// })(jQuery);
