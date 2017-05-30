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

  
/*** Creat Form Section: ***/
(function($, window, document) {
    
    //Set:
    var sel_form_type = ".dash-make-form-type";
    var sel_forms_builder_container = ".dash-make-build";
    var sel_form_builder_ele = "form.dash-make-formele-build";
    var sel_form_builder_snap_view = ".dash-make-view";
    var sel_form_builder_block_add = ".dash-make-formele-add";
    var sel_form_builder_block_add_con = ".dash-make-add-ele";
    var sel_form_builder_block_add_which = "select[name='form_add_type']";
    
    //Load & toggle form types builders:
    $(sel_form_type).click(function(){
      	var $this = $(this);
      	var id_toload = $this.data("formid");
        $(sel_forms_builder_container).find(sel_form_builder_ele).addClass("hidden");
      	$(sel_forms_builder_container).find(sel_form_builder_ele).each(function(inde, ele) {
            if ($(ele).data("formid") == id_toload) {
                $(ele).removeClass("hidden");
            }
        });
        $(sel_form_builder_snap_view).removeClass("hidden");
    });
    
    
    $(sel_form_builder_block_add).click(function(){
        var $this = $(this);
        var $theSelect = $this.closest(sel_form_builder_block_add_con).find(sel_form_builder_block_add_which);
        var which = $theSelect.val();
        var data = {
            req     : "api",
            token   : $("#pagetoken").val(),
            type    : "loadtpl",
            mode    : "build",
            which   : which
        };
        $.ajax({
            url: 'index.php', //Server script to process data
            type: 'POST',
            data: data,
            dataType: 'json',
            beforeSend: function() {
            },
            success: function(response) {
                console.log(response)
                if (
                    typeof response === 'object' && 
                    typeof response.code !== 'undefined' &&
                    response.code == "202"
                ) {
                    
                } else {
                    window.alertModal("שגיאה",window.langHook("makerep_error_load_loc"));
                }
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(thrownError);
                console.log(xhr);
                window.alertModal("שגיאה",window.langHook("makerep_error_load_loc"));
            },
        });  
    });
}(jQuery, window, document));
    
});