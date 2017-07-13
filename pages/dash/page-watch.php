<?php
Trace::add_step(__FILE__,"Loading Sub Page: dash -> makeform");


/****************************** Load  Page Data ***********************************/
Trace::add_step(__FILE__,"Loading Forms available");
$Page->variable("all-targets", $Page::$conn->get("targets"));
$Page->variable("all-valuegroups", $Page::$conn->get("valuegroup"));

/****************************** Manipulate Some data ******************************/
//Object to identify source
$temp = array();
$targetsIdColorName = array();
foreach($Page->variable("all-targets") as $key => $target) {
    $temp[$target["id_targets"]] = $target;
    $targetsIdColorName[$target["id_targets"]] = array(
        'name' =>  '"'.$target["name_targets"].'"',
        'color' => '"'.$target["use_tag_color"].'"'
    );
}
$Page->variable("all-targets", $temp);

//Create Script variable for targets:
echo "
    <script>
        var TargetOBJ = ".$Page->Func->json_encode_advanced($targetsIdColorName).";
    </script>
";


/****************************** Page Debugger Output ***********************************/
Trace::reg_var("all-targets", $Page->variable("all-targets"));
Trace::reg_var("all-valuegroups", $Page->variable("all-valuegroups"));

?>
<h2><?php Lang::P("page_watch_title"); ?></h2>
<div class="container-fluid">
    <div id="watchcontrols" class="row">
        <div class="col-sm-12 text-right">
            <label for="" style="width:100%;">בחר קבוצת ניטור:</label>
            <div class="btn-group" role="group" data-filter-group="source">
              <?php
                    foreach($Page->variable("all-valuegroups") as $tarId => $tar) {
                        echo "<button type='button' class='btn btn-default the-valuegroup-toload' data-id='".$tar['id_valuegroup']."'>".$tar['name_valuegroup']."</button>";
                    }
              ?>
            </div>
            <br /><br />
            <div class="form-group">
                <label for="" style="width:100%;">כמות תוצאות:</label>
                <select id="inputhowmanytoload" class="form-control small-input-control">
                    <option value="50">50</option>
                    <option value="100" selected="selected">100</option>
                    <option value="200">200</option>
                    <option value="500">500</option>
                    <option value="1000">1000</option>
                </select>
            </div>
        </div>
    </div>
    <br />
    <div class="container" style="width:100%; padding:0">
        <div class="row-mans-ajax" style="width:100%; padding:0">
            <div class="grid-sizer"></div>
            <?php
            /*
                foreach($Page->variable("all-articles-base") as $keyArticle => $article) {
                    echo "<div class='item item-mans'>
                            <div class='liveana-card'>
                                <div class='liveana-source-tag' style='background-color:".$Page->in_variable("all-targets",$article["from_target_articles"],"use_tag_color").";'>".
                                    $Page->in_variable("all-targets",$article["from_target_articles"],"name_targets")."</div>
                                <div class='liveana-card-image' style='background-image:url(".str_replace("'", "%27", $article["image_articles"]).")'></div>
                                <div class='liveana-card-meta'><a class='liveana_goto_article' href='".str_replace("'", "%27", $article["link_articles"])."' target='_blank'>עבור לכתבה</a><span class='datepub'>".$article["date_pub_uni_articles"]."</span></div>
                                <div class='liveana-card-title'><h3>".$article["title_articles"]."</h3></div>
                                <div class='liveana-card-desc'><p>".$article["desc_articles"]."</p></div>
                            </div>
                           </div>";
                }
                */
            ?>
        </div>
    </div>
</div>
<div class="clearfix"></div>