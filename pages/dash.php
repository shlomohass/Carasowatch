<?php

Trace::add_step(__FILE__,"Loading Page: dash -> dash");


/****************************** Get more classes ***********************************/



/********************* Set additional head CSS import ****************************/
Trace::add_step(__FILE__,"Define css libs for head section");
$Page->include_css(array(
    GPATH_LIB_STYLE."font-awesome.min.css",
    GPATH_LIB_STYLE."bootstrap/css/bootstrap.min.css",
    GPATH_LIB_STYLE."datatables/dataTables.bootstrap.css",
    GPATH_LIB_STYLE."selectjquery/select2.css"
));
    

/********************* Set additional head JS import ********************/
Trace::add_step(__FILE__,"Define js libs for head section");
$Page->include_js(array(
    GPATH_LIB_JS."jquery-1.12.3.min.js",
    GPATH_LIB_STYLE."bootstrap/js/bootstrap.min.js",
    GPATH_LIB_JS."datatables/jquery.dataTables.js",
    GPATH_LIB_JS."datatables/dataTables.bootstrap.js",
    GPATH_LIB_JS."typeahead.js",
    GPATH_LIB_JS."selectjquery/select2.full.min.js"
));

/****************************** Include JS Lang hooks ***********************************/
Trace::add_step(__FILE__,"Load page js lang hooks");
$Page->set_js_lang(Lang::lang_hook_js("script-admin"));



/****************************** Set Page Meta ***********************************/
Trace::add_step(__FILE__,"Set dash page data");
$Page->title        = Lang::P("gen_title_prefix",false).Lang::P("gen_title_for_display",false);
$Page->description  = Lang::P("gen_desc",false);
$Page->keywords     = Lang::P("gen_keys",false);



/****************************** Set Page Variables ***********************************/
$_view = $Page->Func->synth($_GET, array("t"))["t"];
$Page->variable("load-view", (!empty($_view) 
                                && (
                                    $_view === "dash" || 
                                    $_view === "" || 
                                    $_view === "liveana" || 
                                    $_view === "setvalues")
                             ) ? $_view : "dash" );


/****************************** Load  Page Data ***********************************/
//$Page->variable("all-plans", $Page::$conn->get("settingsplan"));



/***************  Set additional end body JS import and Conditional JS  *******************/
Trace::add_step(__FILE__,"Define js libs for end body section");
$Page->include_js(array(
    GPATH_LIB_JS."app.js"
), false);
   

/***************  Conditional Loading Libraries  **********************************/
Trace::add_step(__FILE__,"Define conditional libs base on display mode");
$cond_css = array();
$cond_js_head = array();
$cond_js_body = array();

switch ($Page->variable("load-view")) {
    case "dash":
    break;
    case "setvalues":
        //$cond_css[]     = GPATH_LIB_STYLE."bootstrap-datetimepicker.min.css";
        //$cond_js_head[] = GPATH_LIB_JS."datetimepicker/moment.min.js";
        //$cond_js_head[] = GPATH_LIB_JS."datetimepicker/bootstrap-datetimepicker.min.js";
    break; 
    case "liveana":
    break;           
}
$Page->include_css($cond_css);
$Page->include_js($cond_js_head);
$Page->include_js($cond_js_body, false);


/****************************** Set page header ***********************************/
Trace::add_step(__FILE__,"Load page header");
require_once PATH_STRUCT.'head.php';



/****************************** Page Debugger Output ***********************************/
Trace::reg_var("onload view", $Page->variable("load-view"));
Trace::add_step(__FILE__,"Load page HTML");

?>
<?php
    include_once PATH_PAGES."dash".DS."page-struct-top.php";
?>
<section class='container-fluid'>
    <div class="row">
        <div class='dash-right-bar col-fixed-240'>
            <?php 
                $tabs = array(
                    "dash"           => ($Page->variable("load-view") === "dash")?"nav-active":"",
                    "setvalues"     => ($Page->variable("load-view") === "setvalues")?"nav-active":"",
                    "liveana"     => ($Page->variable("load-view") === "liveana")?"nav-active":""
                );
            ?>
            <ul class="nav-but">
                <li class='<?php echo $tabs["dash"]; ?>'>
                    <a href="?page=dash&t=dash">
                        <span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span>
                        <?php Lang::P("dash_nav_dashboard"); ?>
                    </a>
                </li>
                <li class='<?php echo $tabs["setvalues"]; ?>'>
                    <a href="?page=dash&t=setvalues">
                        <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                        <?php Lang::P("dash_nav_setvalues"); ?>
                    </a>
                </li>
                <li class='<?php echo $tabs["liveana"]; ?>'>
                    <a href="?page=dash&t=liveana">
                        <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                        <?php Lang::P("dash_nav_liveana"); ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class='dash-main-bar col-md-12 col-offset-240'>
            <?php
                include_once PATH_PAGES."dash".DS."page-".$Page->variable("load-view").".php";
            ?>
        </div>
    </div>
</section>

<!-- START Footer loader -->
<?php 
Trace::add_step(__FILE__,"Load page footer");
require_once PATH_STRUCT.'modals.php'; 
require_once PATH_STRUCT.'foot.php'; 
?> 
<!-- END Footer loader -->

<script>

</script>
</body>
</html>
