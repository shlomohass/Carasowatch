/*************************************************************
 *  Formi
 *  Author: 
 *  Author URI: 
 *  Description:.
 *  Version:
 *  License: SM proj.
**************************************************************/

$(function() {
    
/*** Nav Bar actions: ***/
(function($, window, document) {
    var slide_speed = "fast";
    var selector_trigger = 'li.add-collapse';
    var selector_state = 'menu-collapsed';
    var selector_menu = 'ul.add-menu';
    var selector_hamburger = '.hamburger-nav';
  	//Collapse:
  	$(selector_trigger).click(function() {
		var $this = $(this);
      	var $menu = $this.find(selector_menu).eq(0);
        if ($menu.length) {
         	if ($this.hasClass(selector_state)) {
              $menu.slideUp(slide_speed,function(){
            	$this.removeClass(selector_state);
              });
            } else {
              //Close others:
              $(document).trigger("click");
              $this.addClass(selector_state);
              $menu.slideDown(slide_speed,function(){
              });
            }
        }
    });
    
    //Auto hide nav collapse:
    $(document).click(function(e) {
      if ($(e.target).is(
        	selector_trigger + ", " + selector_trigger + " *")) {
            return;
        }
		$(selector_trigger + "." + selector_state).each(function(i,el) {
            $(el).find(selector_menu).eq(0).slideUp(slide_speed,function(){
            	$(el).removeClass(selector_state);
            });
        });
    });
    
    //hamburger trigger:
    $(selector_hamburger).click(function(){
      	var $this = $(this);
      	var $con = $this.next("div");
      	$con.toggleClass("hide-nav-xs");
    });
    
    window["alertModal"] = function(title, body, callback, ar) {
        callback = typeof callback !== 'undefined' ? callback : function(){ };
        ar = typeof ar === 'object' ? ar : [];
        $alert = $("#modal-alert");
        if ($alert.length) {
            $alert.find("#modal-alert-title").text(title);
            $alert.find(".modal-body").html(body);
            $alert.modal("show");
            $alert.off("hidden.bs.modal").on("hidden.bs.modal",
                callback.bind.apply(callback, [null].concat(ar))
            );
        }
    };
}(jQuery, window, document));

  
/*** Mansory Handles: ***/
(function($, window, document) {
    
    //Parsing function:
    function parse_date(str) {
        // validate year as 4 digits, month as 01-12, and day as 01-31 
        if ((str = str.match (/^(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/))) {
           // make a date
           str[0] = new Date (+str[1], +str[2] - 1, +str[3]);
           // check if month stayed the same (ie that day number is valid)
           if (str[0].getMonth () === +str[2] - 1)
              return str[0];
        }
        return undefined;
     }
    
    // filter functions
    var filterFns = {
      pastonemonth: function() {
        var d1 = new Date();
        var d2 = parse_date($(this).find('.datepub').text());
        return Math.floor(Math.abs(d1-d2)  / 86400000) <= 30;
      },
      pastthreemonth: function() {
        var d1 = new Date();
        var d2 = parse_date($(this).find('.datepub').text());
        return Math.floor(Math.abs(d1-d2)  / 86400000) <= 90;
      },
      pastsixmonth: function() {
        var d1 = new Date();
        var d2 = parse_date($(this).find('.datepub').text());
        return Math.floor(Math.abs(d1-d2)  / 86400000) <= 180;
      },
      pastyear: function() {
        var d1 = new Date();
        var d2 = parse_date($(this).find('.datepub').text());
        return Math.floor(Math.abs(d1-d2)  / 86400000) <= 365;
      },
      thisyear: function() {
        var d1 = new Date();
        var d2 = parse_date($(this).find('.datepub').text());
        return d1.getYear() == d2.getYear();
      },
      thismonth: function() {
        var d1 = new Date();
        var d2 = parse_date($(this).find('.datepub').text());
        return d1.getMonth() == d2.getMonth() && d1.getYear() == d2.getYear();
      },
    };

    // store filter for each group
    var filters = {};
    
    //The grid
    var $grid = $('.row-mans').isotope({
        itemSelector: '.item-mans',
        layoutMode: 'masonry',
        percentPosition: true,
        masonry: {
            columnWidth: '.grid-sizer',
            horizontalOrder: true
        },
        filter: function() {

            var isMatched = true;
            var $this = $(this);

            for ( var prop in filters ) {
                var filter = filters[ prop ];
                // use function if it matches
                filter = filterFns[ filter ] || filter;
                // test each filter
                if ( filter && typeof filter === "string" ) {
                    isMatched = isMatched && $(this).find(".liveana-source-tag").text() == filter;
                } else if (filter) {
                    isMatched = isMatched && $(this).is(filter);
                }
                // break if not matched
                if ( !isMatched ) {
                    break;
                }
            }
            return isMatched;
        }
    });

    $('#liveanacontrols').on( 'click', '.btn', function() {
      var $this = $(this);
      // get group key
      var $buttonGroup = $this.parents('.btn-group');
      var filterGroup = $buttonGroup.attr('data-filter-group');
      // set filter for group
      filters[ filterGroup ] = $this.attr('data-filter');
      // arrange, and use filter fn
      $grid.isotope();
    });

    // change is-checked class on buttons
    $('#liveanacontrols .btn-group').each( function( i, buttonGroup ) {
      var $buttonGroup = $( buttonGroup );
      $buttonGroup.on( 'click', 'button', function() {
        $buttonGroup.find('.is-checked').removeClass('is-checked');
        $(this).addClass('is-checked').blur();
      });
    });

    
    
    
    
    
    
    
    
    
}(jQuery, window, document));
    
});