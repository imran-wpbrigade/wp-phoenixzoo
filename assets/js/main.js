/*
* jquery-match-height 0.7.2 by @liabru
* http://brm.io/jquery-match-height/
* License MIT
*/
!function(t){"use strict";"function"==typeof define&&define.amd?define(["jquery"],t):"undefined"!=typeof module&&module.exports?module.exports=t(require("jquery")):t(jQuery)}(function(t){var e=-1,o=-1,n=function(t){return parseFloat(t)||0},a=function(e){var o=1,a=t(e),i=null,r=[];return a.each(function(){var e=t(this),a=e.offset().top-n(e.css("margin-top")),s=r.length>0?r[r.length-1]:null;null===s?r.push(e):Math.floor(Math.abs(i-a))<=o?r[r.length-1]=s.add(e):r.push(e),i=a}),r},i=function(e){var o={
  byRow:!0,property:"height",target:null,remove:!1};return"object"==typeof e?t.extend(o,e):("boolean"==typeof e?o.byRow=e:"remove"===e&&(o.remove=!0),o)},r=t.fn.matchHeight=function(e){var o=i(e);if(o.remove){var n=this;return this.css(o.property,""),t.each(r._groups,function(t,e){e.elements=e.elements.not(n)}),this}return this.length<=1&&!o.target?this:(r._groups.push({elements:this,options:o}),r._apply(this,o),this)};r.version="0.7.2",r._groups=[],r._throttle=80,r._maintainScroll=!1,r._beforeUpdate=null,
  r._afterUpdate=null,r._rows=a,r._parse=n,r._parseOptions=i,r._apply=function(e,o){var s=i(o),h=t(e),l=[h],c=t(window).scrollTop(),p=t("html").outerHeight(!0),u=h.parents().filter(":hidden");return u.each(function(){var e=t(this);e.data("style-cache",e.attr("style"))}),u.css("display","block"),s.byRow&&!s.target&&(h.each(function(){var e=t(this),o=e.css("display");"inline-block"!==o&&"flex"!==o&&"inline-flex"!==o&&(o="block"),e.data("style-cache",e.attr("style")),e.css({display:o,"padding-top":"0",
  "padding-bottom":"0","margin-top":"0","margin-bottom":"0","border-top-width":"0","border-bottom-width":"0",height:"100px",overflow:"hidden"})}),l=a(h),h.each(function(){var e=t(this);e.attr("style",e.data("style-cache")||"")})),t.each(l,function(e,o){var a=t(o),i=0;if(s.target)i=s.target.outerHeight(!1);else{if(s.byRow&&a.length<=1)return void a.css(s.property,"");a.each(function(){var e=t(this),o=e.attr("style"),n=e.css("display");"inline-block"!==n&&"flex"!==n&&"inline-flex"!==n&&(n="block");var a={
  display:n};a[s.property]="",e.css(a),e.outerHeight(!1)>i&&(i=e.outerHeight(!1)),o?e.attr("style",o):e.css("display","")})}a.each(function(){var e=t(this),o=0;s.target&&e.is(s.target)||("border-box"!==e.css("box-sizing")&&(o+=n(e.css("border-top-width"))+n(e.css("border-bottom-width")),o+=n(e.css("padding-top"))+n(e.css("padding-bottom"))),e.css(s.property,i-o+"px"))})}),u.each(function(){var e=t(this);e.attr("style",e.data("style-cache")||null)}),r._maintainScroll&&t(window).scrollTop(c/p*t("html").outerHeight(!0)),
  this},r._applyDataApi=function(){var e={};t("[data-match-height], [data-mh]").each(function(){var o=t(this),n=o.attr("data-mh")||o.attr("data-match-height");n in e?e[n]=e[n].add(o):e[n]=o}),t.each(e,function(){this.matchHeight(!0)})};var s=function(e){r._beforeUpdate&&r._beforeUpdate(e,r._groups),t.each(r._groups,function(){r._apply(this.elements,this.options)}),r._afterUpdate&&r._afterUpdate(e,r._groups)};r._update=function(n,a){if(a&&"resize"===a.type){var i=t(window).width();if(i===e)return;e=i;
  }n?o===-1&&(o=setTimeout(function(){s(a),o=-1},r._throttle)):s(a)},t(r._applyDataApi);var h=t.fn.on?"on":"bind";t(window)[h]("load",function(t){r._update(!1,t)}),t(window)[h]("resize orientationchange",function(t){r._update(!0,t)})});

(function($){
function generate_select(selector) {
  $(selector).each(function() {
    // Cache the number of options
    var $this = $(this),
    classselect = $this.attr("class"),
    numberOfOptions = $(this).children("option").length;

    // Hides the select element
    $this.addClass("s-hidden");

    // Wrap the select element in a div
    $this.wrap('<div class="select ' + classselect + '"></div>');

    // Insert a styled div to sit over the top of the hidden select element
    $this.after('<div class="styledSelect"></div>');

    // Cache the styled div
    var $styledSelect = $this.next("div.styledSelect");

      var getHTML = $this
        .children('option[value="' + $this.val() + '"]')
        .text();

    //   if ($this.children('option[value="' + $this.val() + '"]').length > 1) {
    // var getHTML = $this
    // .children("option")
    // .eq(0)
    // .text();
    //   }
    // Show the first select option in the styled div
    $styledSelect.html('<span class="text-ellipses">'+getHTML+'</span>');

    // Insert an unordered list after the styled div and also cache the list
    var $list = $("<ul />", {
      class: "options"
    }).insertAfter($styledSelect);

    // Insert a list item into the unordered list for each select option
    for (var i = 1; i < numberOfOptions; i++) {
      var Cls = $this.children("option").eq(i).attr('class');

      $("<li />", {
        text: $this
        .children("option")
        .eq(i)
        .text(),
        rel: $this
        .children("option")
        .eq(i)
        .val(),
        class: Cls
      }).appendTo($list);
    }

    // Cache the list items
    var $listItems = $list.children("li");

    // Show the unordered list when the styled div is clicked (also hides it if the div is clicked again)
    $styledSelect.click(function(e) {
      e.stopPropagation();
      if(!$(this).hasClass('active')){
        $('div.styledSelect.active').each(function () {
          $(this).removeClass('active').next('ul.options').slideUp();
          // return false;
        });
        $(this)
        .toggleClass("active");
        $(this).next("ul.options")
        .stop(true)
        .slideToggle();
      }else{
        $('div.styledSelect.active').each(function () {
          $(this).removeClass('active').next('ul.options').slideUp();
          // return false;
        });
      }
    });

    // Hides the unordered list when a list item is clicked and updates the styled div to show the selected list item
    // Updates the select element to have the value of the equivalent option
    $listItems.click(function(e) {
      e.stopPropagation();
      $styledSelect.html('<span class="text-ellipses">'+$(this).text()+'</span>').removeClass("active");
      var value = $(this)
      .attr("rel")
      .toString();
      $($this).val(value);
      $($this).trigger("change");
      $('ul.options').slideUp();
      $(this)
      .toggleClass("active")
      .siblings()
      .removeClass("active");
      /* alert($this.val()); Uncomment this for demonstration! */
    });

    // Hides the unordered list when clicking outside of it
    $(document).click(function() {
      $styledSelect.removeClass("active");
      $list.slideUp();
    });
  });
}
    $(function(){
  if (document.documentMode || /Edge/.test(navigator.userAgent)) {
    $('body').addClass('ie_edge');
  }
        generate_select('select');
        document.activeElement.blur();
        $(document).on('gform_post_render', function () {
            generate_select('select');
          });

        $('body:not(.elementor-editor-active) .elementor-price-table__header').matchHeight({
          byRow: true,
          property: 'min-height',
        });
        $('body:not(.elementor-editor-active) .elementor-price-table__features-list').matchHeight({
          byRow: true,
          property: 'min-height',
        });
        $('body:not(.elementor-editor-active) .supporting-member-section.content-detail-wrapper .detail-column2 .detail-main-heading .elementor-heading-title').matchHeight({
          byRow: true,
          property: 'min-height',
        });
        document.addEventListener("touchstart", function() {}, true);
      //   var is_safari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
      // // console.log(is_safari + " log1");
      //   if(is_safari == true){
      //       $('html').addClass('is_safari');
      //     // console.log(is_safari + " log2");
      //   }
        $(document).on('click','body:not(.elementor-editor-active) .load-more-btn .elementor-button',function(e){
          e.preventDefault();
          var el = $(this);
          $(this).toggleClass('loaded');
          $(this).find('.elementor-button-text').html($(this).find('.elementor-button-text').html() == 'See More' ? 'See Less' : 'See More');
          if($(window).width()>=1025){
            $(this).closest('.our-critters-section').find('.critters-block-row + .critters-block-row ~ .critters-block-row').slideToggle(400, function(){
              if($.trim(el.text()) == 'See More'){
                var body = $("html, body");
                body.stop().animate({scrollTop:el.offset().top - ($(window).height()/2)}, 500);
              }
              if($.trim(el.text()) == 'See Less'){
                var body = $("html, body");
                body.stop().animate({scrollTop:el.closest('.our-critters-section').find('.critters-block-row').eq(2).offset().top - $('.elementor-section-wrap>header').outerHeight()}, 500);
              }
            });
          }else{
            $(this).closest('.our-critters-section').find('.critters-block-row ~ .critters-block-row').slideToggle(400, function(){
              if($.trim(el.text()) == 'See More'){
                var body = $("html, body");
                body.stop().animate({scrollTop:el.offset().top - ($(window).height()/2)}, 500);
              }
              if($.trim(el.text()) == 'See Less'){
                var body = $("html, body");
                body.stop().animate({scrollTop:el.closest('.our-critters-section').find('.critters-block-row').eq(1).offset().top - $('.elementor-section-wrap>header').outerHeight()}, 500);
              }
            });

          }
        });
        $('.breadcrumb-column').each(function(){
          if($(this).next('.language-column').length>0 && $.trim($(this).next('.language-column').text()) == ''){
            $(this).addClass('breadcrumb-fullwidth');
          }
        });
        // $('.main-nav-container-mobile')
        $('.main-nav-container-mobile').insertBefore($('.site-main'));
        var myScroll = new IScroll('.menu-inner', {
          scrollbars: true,
          mouseWheel: true,
          interactiveScrollbars: true,
          shrinkScrollbars: 'scale',
          fadeScrollbars: true,
          click:true,
          disablePointer: true, // important to disable the pointer events that causes the issues
          disableTouch: false, // false if you want the slider to be usable with touch devices
          disableMouse: false // false if you want the slider to be usable with a mouse (desktop)
            });
      $('.main-menu-btn, .hours-directions a').on('click', function(){
                $('html').toggleClass('no_menu_scroll');
                myScroll.refresh();
                myScroll.scrollTo(0, 0, 0);
            });
            $('.main-nav-container-mobile .sub-menu').each(function(){
                $(this).parent('li').append('<span class="caret"><svg width="11px" height="7px" viewBox="0 0 11 7" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <g id="#---Navigation" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Homepage_mobile-Copy" transform="translate(-324.000000, -152.000000)" fill="currentColor" fill-rule="nonzero"> <g id="chevron-left-regular-copy" transform="translate(329.500000, 155.500000) rotate(-90.000000) translate(-329.500000, -155.500000) translate(326.000000, 150.000000)"> <path d="M6.03822671,10.5260573 L6.52474904,10.0693738 C6.63989847,9.96128653 6.63989847,9.78603115 6.52474904,9.67792086 L2.08441883,5.50000144 L6.52474904,1.32205895 C6.63989847,1.21397173 6.63989847,1.03871635 6.52474904,0.930606064 L6.03822671,0.473922559 C5.92307728,0.365835337 5.73637105,0.365835337 5.62119704,0.473922559 L0.475250964,5.30428653 C0.360101531,5.41237375 0.360101531,5.58762913 0.475250964,5.69573942 L5.62119704,10.5260803 C5.73637105,10.6341675 5.92307728,10.6341675 6.03822671,10.5260573 Z" id="Path"></path> </g> </g> </g> </svg></span>');
            });
            $(document).on('click', '.caret',function(){
                $(this).closest('li').siblings().find('.caret').removeClass('rotated').prev('ul').slideUp();
                $(this).toggleClass('rotated').prev('ul').slideToggle(400,function(){
                    myScroll.refresh();
                });
            });
            $(document).on('click',function(){
                $('.search-hide').hide();
            });
            $('.search-btn').on('click',function(e){
                e.stopPropagation();
                $('.search-hide').toggle();
                setTimeout( function() {
                    $('.search-hide').find('input[type="search"]').focus();
                }, 400);
            });
            $('.cross-btn-sec').on('click',function(e){
                e.stopPropagation();
                $('.search-hide').hide();
            });
            $('.search-hide').on('click',function(e){
                e.stopPropagation();
            });
            var adminbar = 0;
    if($('#wpadminbar').length>0){
        adminbar = $('#wpadminbar').height();
    }
            $('.elementor-tabs .elementor-tab-title').on('click',function(){
                var body = $("html, body");
                var el = $(this);
                if($(window).width()<=767){
                    setTimeout( function() {
                        body.stop().animate({
                            scrollTop: el.offset().top - $('.elementor-section-wrap>header').height()
                        }, 500, 'swing');
                    }, 400);
                }
            });
            $('.elementor-accordion-item .elementor-tab-title').on('click',function(){
              var body = $("html, body");
              var el = $(this);
                  setTimeout( function() {
                      body.stop().animate({
                          scrollTop: el.offset().top - $('.elementor-section-wrap>header').height()
                      }, 500, 'swing');
                  }, 400);
          });
          $('body:not(.elementor-editor-active) .helper-bar-section .elementor-image-box-title').each(function(){
              if($(this).find('a').length>0){

                var attributes = $(this).find('a').prop("attributes");
                $(this).next().wrapInner('<a></a>');
                var $div = $(this).next().find('a');
                $.each(attributes, function() {
                    $div.attr(this.name, this.value);
                });
                $(this).find('a').replaceWith(function () {
                    return this.childNodes;
                });
                $(this).parent().prev().find('a').replaceWith(function () {
                    return this.childNodes;
                });
              }
          });
          $('body:not(.elementor-editor-active)  .helper-links-section .elementor-image-box-img').each(function(){
            if($(this).find('a').length>0){

              var attributes = $(this).find('a').prop("attributes");
              $(this).next().children().wrapInner('<a></a>');
              var $div = $(this).next().children().find('a');
              $.each(attributes, function() {
                  $div.attr(this.name, this.value);
              });
              $(this).find('a').replaceWith(function () {
                  return this.childNodes;
              });
            }
        });
        $('.hour-toggle a').on('click',function(e){
          e.preventDefault();
          // $(this).See all seasonal hours
          var el = $(this).closest('.hour-toggle').prev('.toggle-section').prev();
          $(this).html($(this).html() == 'See all seasonal hours' ? 'See fewer seasonal hours' : 'See all seasonal hours');
          $(this).closest('.hour-toggle').prev('.toggle-section').slideToggle(400,function(){
            if($(window).width()<=767){
              $('html, body').stop().animate({
                  scrollTop: el.offset().top - $('.elementor-section-wrap>header').height()
              }, 500, 'swing');
            }
          });
        });
        $('body:not(.elementor-editor-active) .our-critters-toggle').each(function(){
          if($(this).find('.critters-block-row').length>2){
            $('<div class="elementor-element elementor-element-2e993e0 elementor-align-center load-more-btn elementor-widget elementor-widget-button" data-id="2e993e0" data-element_type="widget" data-widget_type="button.default"> <div class="elementor-widget-container"> <div class="elementor-button-wrapper"> <a href="#" class="elementor-button-link elementor-button elementor-size-md" role="button"> <span class="elementor-button-content-wrapper"> <span class="elementor-button-text">See More</span> </span> </a> </div> </div> </div>').insertAfter($(this).closest('.elementor-row').parent('.elementor-container'));
          }
        });
        var $menuItem = $('.header-main-menu>li');
        $menuItem.on('touchstart mouseenter focus', function(e) {
          if(e.type == 'touchstart' && $(window).width()>1024 && $(this).children('ul').length>0) {
            // Don't trigger mouseenter even if they hold
            if(!$(this).hasClass('menu-open')){
              e.stopImmediatePropagation();
              // If $item is a link (<a>), don't go to said link on mobile, show menu instead
              e.preventDefault();
            }
            $(this).addClass('menu-open').siblings().removeClass('menu-open');
          }



          // Show the submenu here
      });
        // if($('body:not(.elementor-editor-active) .our-critters-toggle .critters-block-row').length>2){
        //   $('body:not(.elementor-editor-active) .our-critters-toggle').append('')
        // }
    });
    var adminbar = 0;
    $(window).on('load',function(){
      if(jQuery(window.location.hash).length>0){
        if($('#wpadminbar').length>0){
          adminbar = $('#wpadminbar').height();
        }
        jQuery(window.location.hash).trigger('click');
        $("html, body").animate({ scrollTop: jQuery(window.location.hash).closest('.phoenix-tabs-section').offset().top - $('.elementor-section-wrap>header').height() - adminbar }, 600);
      }

    });
    $('a[href*="#"]').on('click',function(e){
      // console.log(this.hash);
      if(jQuery(this.hash).length>0){
        if($('#wpadminbar').length>0){
          adminbar = $('#wpadminbar').height();
        }
        e.preventDefault();
        jQuery(this.hash).trigger('click');
        if ($('.phoenix-tabs-section').length){
          $("html, body").animate({ scrollTop: jQuery(this.hash).closest('.phoenix-tabs-section').offset().top - $('.elementor-section-wrap>header').height() - adminbar }, 600);
        }
        
      }
    });
    $(window).on('resize load',function(){

        // $('body:not(.elementor-editor-active) .elementor-price-table').each(function(){
        //     if($(this).find('.elementor-price-table__footer').length>0){
        //         var paddingBtm = $(this).find('.elementor-price-table__footer').outerHeight();
        //         $(this).css('padding-bottom', paddingBtm+'px');
        //     }
        // });
        $('body:not(.elementor-editor-active) .elementor-price-table__header').matchHeight({
          byRow: true,
          property: 'min-height',
        });
        $('body:not(.elementor-editor-active) .elementor-price-table__features-list').matchHeight({
          byRow: true,
          property: 'min-height',
        });
        $('body:not(.elementor-editor-active) .supporting-member-section.content-detail-wrapper .detail-column2 .detail-main-heading .elementor-heading-title').matchHeight({
          byRow: true,
          property: 'min-height',
        });
    });
}(jQuery));