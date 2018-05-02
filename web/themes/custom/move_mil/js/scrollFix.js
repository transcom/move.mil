(function($) {
  var offsetJumpScroll = function() {
    var jumpLink = $('.jump-link a');
    jumpLink.click(function(e) {
      var href = $(this).attr('href');
      if(href[0] === '#' || (href.indexOf(window.location.pathname) > -1 && href.indexOf('#') > -1)){
        e.preventDefault();
        var targetId = href.split('#')[1];
        // var targetId = href.substring(1, href.length);
        var target = $("div[id*='" + targetId + "']");
        var offset = $(target).offset();
        offset.top -= 200;
        $('html, body').animate({
          scrollTop: offset.top,
          scrollLeft: 0
        }, 1000);
        window.location.hash = targetId;
      }
      return;
    });
  };

  $(window).on('load', function() {
    offsetJumpScroll();
  });
})(jQuery);
