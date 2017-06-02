<?php
Trace::add_step(__FILE__,"Loading Sub Page: dash -> makeform");


/****************************** Load  Page Data ***********************************/
Trace::add_step(__FILE__,"Loading Forms available");
$Page->variable("all-targets", $Page::$conn->get("targets"));
$Page->variable("all-articles-base", 
                $Page::$conn->select(
                    "articles",
                    " * ",
                    false,
                    false,
                    array('DESC',array("date_pub_uni_articles")),
                    array(100)
                )
               );
/****************************** Manipulate Some data ******************************/
//Object to identify source
$temp = array();
foreach($Page->variable("all-targets") as $key => $target) {
    $temp[$target["id_targets"]] = $target;
}
$Page->variable("all-targets", $temp);

/****************************** Page Debugger Output ***********************************/
Trace::reg_var("all-targets", $Page->variable("all-targets"));
Trace::reg_var("all-articles-base", $Page->variable("all-articles-base"));

?>
<h2><?php Lang::P("page_liveana_title"); ?></h2>
<div class="container-fluid">
    <div id="liveanacontrols" class="row">
        <div class="col-sm-12 text-right">
            <div class="btn-group" role="group" data-filter-group="source">
              <?php
                    foreach($Page->variable("all-targets") as $tarId => $tar) {
                        echo "<button type='button' class='btn btn-default' data-filter='".$tar['name_targets']."'>".$tar['name_targets']."</button>";
                    }
              ?>
              <button type="button" class="btn btn-default is-checked"  data-filter="">הכל</button>
            </div>
        </div>
        <br /><br />
        <div class="col-sm-12 text-right">
            <div class="btn-group" role="group" data-filter-group="past">
              <button type="button" class="btn btn-default"  data-filter="pastonemonth">חודש</button>
              <button type="button" class="btn btn-default"  data-filter="pastthreemonth">3 חודשים</button>
              <button type="button" class="btn btn-default"  data-filter="pastsixmonth">6 חודשים</button>
              <button type="button" class="btn btn-default"  data-filter="pastyear">שנה</button>
              <button type="button" class="btn btn-default"  data-filter="thismonth">החודש</button>
              <button type="button" class="btn btn-default"  data-filter="thisyear">השנה</button>
              <button type="button" class="btn btn-default is-checked"  data-filter="">הכל</button>
            </div>
        </div>
    </div>
    <br />
    <div class="container" style="width:100%; padding:0">
        <div class="row-mans" style="width:100%; padding:0">
            <div class="grid-sizer"></div>
            <?php
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
            ?>
        </div>
    </div>
</div>
<div class="clearfix"></div>