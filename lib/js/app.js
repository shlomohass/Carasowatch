/*************************************************************
 *  Formi
 *  Author: 
 *  Author URI: 
 *  Description:.
 *  Version:
 *  License: SM proj.
**************************************************************/


//Some basic stuff we need:

$.fn.blink = function (count, dur, color) {
    var $this = $(this);
    color = color || false;
    dur   = dur || 100;
    count = count - 1 || 0;
    var animObjStart = {}, animObjEnd = {};
    if (color) {
        animObjStart["backgroundColor"] = color;
        animObjEnd["backgroundColor"] = $this.css("backgroundColor");
    } else {
        animObjStart["opacity"] = ".25";
        animObjEnd["opacity"] = $this.css("opacity");
    }
    $this.animate(animObjStart, dur, function () {
        $this.animate(animObjEnd, dur, function () {
            if (count > 0) {
                $this.blink(count, dur, color);
            } else {
                
            }
        });
    });
};

$.fn.checkEmail = function(trim) {
  trim = trim || true;
  var $this = $(this);
  var tocheck = trim ? $this.val().trim() : $this.val();
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return tocheck !== "" && emailReg.test(tocheck);
}

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
    
    //The grid simple style:
    var $grid = $('.row-mans');
    if ($grid.length) {
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
        $('#liveanacontrols .btn-group, #watchcontrols .btn-group').each( function( i, buttonGroup ) {
          var $buttonGroup = $( buttonGroup );
          $buttonGroup.on( 'click', 'button', function() {
            $buttonGroup.find('.is-checked').removeClass('is-checked');
            $(this).addClass('is-checked').blur();
          });
        });
        
    }
    
    //The grid ajax style:
    var $gridajax = $('.row-mans-ajax');
    var loadingStateForAjax = false;
    if ($gridajax.length) {
        var $gridajax = $('.row-mans-ajax').isotope({
            itemSelector: '.item-mans',
            layoutMode: 'masonry',
            percentPosition: true,
            masonry: {
                columnWidth: '.grid-sizer',
                horizontalOrder: true
            }
        });
        
        // change is-checked class on buttons
        $('#watchcontrols .btn-group').each( function( i, buttonGroup ) {
          var $buttonGroup = $( buttonGroup );
          $buttonGroup.on( 'click', 'button', function() {
            $buttonGroup.find('.is-checked').removeClass('is-checked');
            $(this).addClass('is-checked').blur();
          });
        });
            
        $groups = $(".the-valuegroup-toload");
        $groups.on("click", function(){
            $btn = $(this);
            //Get data:
            var data = {
                req:       "api",
                token:     $("#pagetoken").val(),
                type:      "getresultsofvaluegroup",
                groupid :  $btn.data("id"),
                limit    : parseInt($("#inputhowmanytoload").val())
            };
            if (!loadingStateForAjax) {
            loadingStateForAjax = true;
                //Getting from Server:
                $.ajax({
                    url: 'index.php',  //Server script to process data
                    type: 'POST',
                    data:  data,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (
                            typeof response === 'object' && 
                            typeof response.code !== 'undefined' &&
                            response.code == "202"
                        ) {
                            if (response.results.results.length) {
                                $toPut = [];
                                for (var i = 0; i < response.results.results.length; i++) {
                                    var res = response.results.results[i];
$toPut.push(
    "<div class='item item-mans'>"
        + "<div class='liveana-card'>"
        + "<div class='watchres-score-tag'>" + res.score_watch + "</div>"
        + "<div class='liveana-source-tag' style='background-color:" + TargetOBJ[res.from_target_articles].color + ";'>" + TargetOBJ[res.from_target_articles].name + "</div>"
        + "<div class='liveana-card-image' style='background-image:url(" + res.image_articles.replace(/'/g,"%27") + ")'></div>"
        + "<div class='liveana-card-meta'><a class='liveana_goto_article' href='" + res.link_articles.replace(/'/g,"%27") + "' target='_blank'>עבור לכתבה</a><span class='datepub'>" + res.date_pub_articles + "</span></div>"
        + "<div class='liveana-card-title'><h3>" + res.title_articles + "</h3></div>"
        + "<div class='liveana-card-desc'><p>" + res.desc_articles + "</p></div>"
    + "</div>"
   + "</div>"
);
                                }
                                $toPut = $($toPut.join(""));
                                $toPut.hide();
                                $gridajax
                                    .isotope( 'remove', $(".item-mans"))
                                    .isotope('layout')
                                    .append($toPut)
                                    .isotope("appended", $toPut);
                                $toPut.fadeIn(500, function(){  
                                });
                            } else {
                                
                            }
                            
                        } else {
                            console.log(response);
                            window.alertModal("שגיאה",window.langHook("watch_error_load_results"));
                        }
                        loadingStateForAjax = false;
                    },
                    error: function(xhr, ajaxOptions, thrownError){
                        console.log(thrownError);
                        window.alertModal("שגיאה",window.langHook("watch_error_load_results"));
                        loadingStateForAjax = false;
                    },
                });
            }
            
        });
        
        

            /*
            //save to server:
            $.ajax({
                url: 'index.php',  //Server script to process data
                type: 'POST',
                data:  data,
                dataType: 'json',
                success: function(response) {
                    if (
                        typeof response === 'object' && 
                        typeof response.code !== 'undefined' &&
                        response.code == "202"
                    ) {
                        console.log(response);
                        $("#setvalues_reset_form").trigger("click");
                    } else {
                        console.log(response);
                        window.alertModal("שגיאה",window.langHook("setvalues_error_set_new_group"));
                    }
                    $but.prop("disabled",false);
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError);
                    window.alertModal("שגיאה",window.langHook("setvalues_error_set_new_group"));
                    $but.prop("disabled",false);
                },
            });
            */
    }
    
    
    
    
    
}(jQuery, window, document));
    
});