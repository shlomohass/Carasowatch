<?php

Trace::add_step(__FILE__,"Loading Page: admin");


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
Trace::add_step(__FILE__,"Set admin page data");
$Page->title = Lang::P("gen_title_prefix",false).Lang::P("admin_title",false);
$Page->description = Lang::P("admin_desc",false);
$Page->keywords = Lang::P("admin_keys",false);



/****************************** Set Page Variables ***********************************/
$_view = $Page->Func->synth($_GET, array("t"))["t"];
$Page->variable("load-view", (!empty($_view) 
                                && (
                                    $_view === "dash" || 
                                    $_view === "makereport" || 
                                    $_view === "showreport" || 
                                    $_view === "stats" || 
                                    $_view === "inventory")
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
    case "makereport":
        $cond_css[]     = GPATH_LIB_STYLE."bootstrap-datetimepicker.min.css";
        $cond_js_head[] = GPATH_LIB_JS."datetimepicker/moment.min.js";
        $cond_js_head[] = GPATH_LIB_JS."datetimepicker/bootstrap-datetimepicker.min.js";
    break; 
    case "showreport":
    break;           
    case "stats":
    break; 
    case "inventory":
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
    include_once PATH_PAGES."admin".DS."page-struct-top.php";
?>
<section class='container-fluid'>
    <div class="row">
        <div class='admin-right-bar col-fixed-240'>
            <?php 
                $tabs = array(
                    "dash"           => ($Page->variable("load-view") === "dash")?"nav-active":"",
                    "makereport"     => ($Page->variable("load-view") === "makereport")?"nav-active":"",
                    "showreport"     => ($Page->variable("load-view") === "showreport")?"nav-active":"",
                    "stats"          => ($Page->variable("load-view") === "stats")?"nav-active":"",
                    "inventory"      => ($Page->variable("load-view") === "inventory")?"nav-active":""
                );
            ?>
            <ul class="nav-but">
                <li class='<?php echo $tabs["dash"]; ?>'>
                    <a href="?page=dash&t=dash">
                        <span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span>
                        <?php Lang::P("admin_nav_dashboard"); ?>
                    </a>
                </li>
                <li class='<?php echo $tabs["makereport"]; ?>'>
                    <a href="?page=dash&t=makereport">
                        <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
                        <?php Lang::P("admin_nav_makereport"); ?>
                    </a>
                </li>
                <li class='<?php echo $tabs["showreport"]; ?>'>
                    <a href="?page=dash&t=showreport">
                        <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                        <?php Lang::P("admin_nav_showreport"); ?>
                    </a>
                </li>
                <li class='<?php echo $tabs["stats"]; ?>'>
                    <a href="?page=dash&t=stats">
                        <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                        <?php Lang::P("admin_nav_stats"); ?>
                    </a>
                </li>
                <li class='<?php echo $tabs["inventory"]; ?>'>
                    <a href="?page=dash&t=inventory">
                        <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
                        <?php Lang::P("admin_nav_inventory"); ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class='admin-main-bar col-md-12 col-offset-240'>
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