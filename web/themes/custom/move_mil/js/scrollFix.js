(function($) {
  var offsetJumpScroll = function() {
    var jumpLink = $('.jump-link a');
    jumpLink.click(function(e) {
      var href = $(this).attr('href');
      if(href[0] === '#' || (href.indexOf(window.location.pathname) > -1 && href.indexOf('#') > -1)){
        e.preventDefault();
        var targetId = href.split('#')[1];
        var target = $("div#" + targetId);
        if(!target.length){
          target = $("div[id*='" + targetId + "']");
        }
        var offset = $(target).offset();
        offset.top -= 100;
        $('html, body').animate({
          scrollTop: offset.top,
          scrollLeft: 0
        }, 1000);
        window.location.hash = targetId;
      }
      return;
    });
  };

  var smoothBackToTop = function(){
    var topLink = $('.back-to-top a');
    topLink.click(function(e) {
      e.preventDefault();
      $('html, body').animate({
        scrollTop: 0,
        scrollLeft: 0
      }, 1000);
      window.location.hash = '';
      return;
    });

  };

  $(window).on('load', function() {
    offsetJumpScroll();
    smoothBackToTop();
  });
})(jQuery);
