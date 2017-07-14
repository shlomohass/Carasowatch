<?php

Trace::add_step(__FILE__,"Loading Sub Page: dash -> dash");

/****************************** Load  Page Data ***********************************/
Trace::add_step(__FILE__,"Loading Page data");
$Page->variable("all-targets", $Page::$conn->get("targets"));
$Page->variable("recent-articles", 
    $Page::$conn->select(
        "articles",
        "*",
        false,
        false,
        array('DESC',array("date_pub_uni_articles")),
        array(5)
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
<h2><?php Lang::P("page_dash_title"); ?></h2>
<div class="container-fluid">
    <div id="dashpage" class="row" >
        <div class="make-box" >
            <h4>כתבות על פי שבועות:</h4>
            <div class="text-right" style="position:relative; height:200px; width:100%">
                <canvas id="dashcrawlchart"></canvas>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 text-right" style="position:relative; height:250px;">
            <div class="make-box">
                <h4>כתבות אחרונות:</h4>
                <ul class="dash-latest-articales">
                    <?php
                    /*
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
                }*/
                    
foreach($Page->variable("recent-articles") as $key => $art) {
    echo "<li>".
        "<table style='width:100%'><tr>".
        "<td style='width:61px;'><div class='dash-thumb-recent-image' style='background-image:url(".str_replace("'", "%27", $art["image_articles"]).")'></div></td>".
        "<td style='vertical-align: text-top; padding:5px; position: relative;'>".
            "<h5 class='elip'>".$art["title_articles"]."</h5>".
            "<span class='date-show'>".$art["date_pub_uni_articles"].
                "<em style='color:".$Page->in_variable("all-targets",$art["from_target_articles"],"use_tag_color")."'>".
                    $Page->in_variable("all-targets",$art["from_target_articles"],"name_targets")
                ."</em></span>".
            "<a class='goto-article' href='".str_replace("'", "%27", $art["link_articles"])."' target='_blank'>עבור לכתבה</a>".
        "</td>".
        "</tr></table>".
        "</li>";
}
                    ?>
                </ul>
            </div>
        </div>

        <div class="col-sm-6 text-right" style="position:relative; height:250px;">
            <div class="make-box">
                <h4>דוחות שהופצו:</h4>
            </div>
        </div>
    </div>
</div>


<script>
    
    
//Get data:
            var data = {
                req:       "api",
                token:     $("#pagetoken").val(),
                type:      "getarticlescountbyweek",
                limit    : "54"
            };
            //Getting from Server:
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
                        console.log(response.results.map(function(e) { return e.week_name; }).reverse());
                        console.log(response.results.map(function(e) { return parseInt(e.count_articles); }).reverse());
                        if (response.results.length) {
                            update_parsed_by_week_year(
                                response.results.map(function(e) { return e.week_name; }).reverse(),
                                response.results.map(function(e) {  return parseInt(e.count_articles); }).reverse()
                            );
                        } else {

                        }

                    } else {
                        console.log(response);
                        //window.alertModal("שגיאה",window.langHook("watch_error_load_results"));
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError);
                    //window.alertModal("שגיאה",window.langHook("watch_error_load_results"));
                },
            });
    
    //The function to build the weeks chart:
    function update_parsed_by_week_year(datalabels, datacounts) {
        var ctx = document.getElementById("dashcrawlchart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: datalabels,
                datasets: [{
                    label: 'Articles Scraped: ',
                    data: datacounts,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive : true,
                maintainAspectRatio : false,
                legend: {
                    display: false,
                    labels : {
                        
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true,
                            fontSize : 9
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            fontSize : 9
                        }
                    }]
                }
            }
        });
    }
</script>
