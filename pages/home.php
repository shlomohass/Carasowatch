<?php

Trace::add_step(__FILE__,"Loading Page: home");


/****************************** Get more classes ***********************************/




/********************* Set additional head CSS import ****************************/
Trace::add_step(__FILE__,"Define css libs for head section");
$Page->include_css(array(
    GPATH_LIB_STYLE."font-awesome.min.css",
    GPATH_LIB_STYLE."bootstrap/css/bootstrap.min.css",
    GPATH_LIB_STYLE."dropzone.css"
));
    

/********************* Set additional head JS import ********************/
Trace::add_step(__FILE__,"Define js libs for head section");
$Page->include_js(array(
    GPATH_LIB_JS."jquery-1.12.3.min.js"
));



/****************************** Include JS Lang hooks ***********************************/
Trace::add_step(__FILE__,"Load page js lang hooks");
$Page->set_js_lang(Lang::lang_hook_js("script-frontend"));



/****************************** Set Page Meta ***********************************/
Trace::add_step(__FILE__,"Set home page data");
$Page->title = Lang::P("gen_title_prefix",false).Lang::P("home_title",false);
$Page->description = Lang::P("home_desc",false);
$Page->keywords = Lang::P("home_keys",false);



/****************************** Set Page Variables ***********************************/
$_view = $Page->Func->synth($_GET, array("t"))["t"];
$Page->variable("load-view", (!empty($_view) && ($_view === "debugger" || $_view === "process" || $_view === "settings" || $_view === "server")) ? $_view : "process" );



/****************************** Load  Page Data ***********************************/
//$Page->variable("all-plans", $Page::$conn->get("settingsplan"));



/***************  Set additional end body JS import and Conditional JS  *******************/
Trace::add_step(__FILE__,"Define conditional js libs for end body section");
$Page->include_js(array(
    GPATH_LIB_STYLE."bootstrap/js/bootstrap.min.js",
    GPATH_LIB_JS."dropzone.js",
    GPATH_LIB_JS."app.js"
), false);
   


/****************************** Set page header ***********************************/
Trace::add_step(__FILE__,"Load page header");
require_once PATH_STRUCT.'head.php';



/****************************** Page Debugger Output ***********************************/
Trace::reg_var("onload view", $Page->variable("load-view"));
//Trace::reg_var("all encodings", $Page->variable("all-encodings"));
Trace::add_step(__FILE__,"Load page HTML");


?>

    <section class="container-fluid main-nav">
      <div class="row">
          <a href="#" class='pull-left '>
            <img src='dummy/200x40' class="logo-nav"/>
          </a>
          <div href="#" class='hamburger-nav pull-right'>
            <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
          </div>
        <div class="col-xs-12 hide-nav-xs col-sm-8 pull-right noselect">
          <div class="row">
            <div class="col-xs-12 nopadding-xs">
              <ul class="list-reset pull-right add-nav">
                <li>
                  <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                  <span>Home</span>
                </li>
                <li>
                  <span class="glyphicon glyphicon-education" aria-hidden="true"></span>
                  <span>About</span>
                </li>
                <li class="add-collapse">
                  <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                  <span>Dashboard</span>
                  <ul class="list-reset add-menu">
                    <li>Profile</li>
                    <li>Archive</li>
                    <li>Contacts</li>
                  </ul>
                </li>
                <li class="add-collapse">
                  <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
                  <span>Sign In / Up</span>
                  <ul class="list-reset add-menu">
                    <li>Sign In</li>
                    <li>Sign Up</li>
                    <li>Terms</li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="container main-body">
      <form method="POST">
        <nav class="navbar">
            <div class="logo">
                <img src="<?php echo $Page::$conf["general"]["fav_url"]; ?>logo.png" alt="logo" />
                <em><?php echo "v".$Page::$conf["general"]["app_version"]; ?></em>
            </div>
            <ul>
                <li><input type="text" name="username" class="field" placeholder="Username" required="required" /></li>
                <li><input type="password" name="password" class="field" placeholder="Password" required="required" /></li>
                <li><button type="submit" class="btn btn-primary bg-orange px3">Login</button></li>
            </ul>
        </nav>
    </form>
    </section>

    <section class="container-fluid main-footer"> 
    © Copyright 2016 SM Projects | Powered by SMProj              
    </section>


<!-- START Footer loader -->
<?php 
Trace::add_step(__FILE__,"Load page footer");
//require_once PATH_STRUCT.'modals.php'; 
require_once PATH_STRUCT.'foot.php'; 
?> 
<!-- END Footer loader -->

<script>

</script>
</body>
</html>