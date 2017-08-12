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
$Page->variable("recent-reports", 
    $Page::$conn->select(
        "reports",
        "*",
        false,
        false,
        array('DESC',array("report_datetime")),
        array(5)
    )
);
$Page->variable("all-groups", $Page::$conn->get("valuegroup"));
$Page->variable("count-bytargets", 
    $Page::$conn->select(
        "articles",
        "`articles`.`from_target_articles`as target, COUNT(*) as counter",
        false,
        array("from_target_articles")
    )
);

//SELECT watch.score_watch, COUNT(*)as counter FROM carasowatchdb.watch group by watch.score_watch;
$Page->variable("count-relevant", 
    $Page::$conn->select(
        "watch",
        "`watch`.`score_watch`as score, COUNT(*) as counter",
        false,
        array("score_watch")
    )
);

/****************************** Manipulate Some data ******************************/
//Object to identify source
$temp = array();
foreach($Page->variable("all-targets") as $key => $target) {
    $temp[$target["id_targets"]] = $target;
}
$Page->variable("all-targets", $temp);

//Object to identify groups (Watches)
$temp = array();
foreach($Page->variable("all-groups") as $key => $group) {
    $temp[$group["id_valuegroup"]] = $group;
}
$Page->variable("all-groups", $temp);

//Object to identify relevance:
$temp = array("no" => 0, "yes" => 0, "total" => 0, "no_per" => 0, "yes_per" => 0);
foreach($Page->variable("count-relevant") as $key => $rel) {
    $score = intval($rel["counter"]);
    if (empty($rel["score"]) || $score < 5) {
        $temp["no"] += $score;
    } else {
        $temp["yes"] += $score;
    }
    $temp["total"] += $score;
}
$temp["yes_per"] =  $temp["total"] > 0 ? round(($temp["yes"] / $temp["total"]) * 100) : 0;
$temp["no_per"] =  $temp["total"] > 0 ? round(($temp["no"] / $temp["total"]) * 100) : 0;

$Page->variable("count-relevant", $temp);

/****************************** Page Debugger Output ***********************************/
Trace::reg_var("all-targets", $Page->variable("all-targets"));
Trace::reg_var("all-articles-base", $Page->variable("all-articles-base"));
Trace::reg_var("all-groups", $Page->variable("all-targets"));
Trace::reg_var("count-bytargets", $Page->variable("count-bytargets"));
Trace::reg_var("count-relevant", $Page->variable("count-relevant"));
?>
<h2><?php Lang::P("page_dash_title"); ?></h2>
<div class="dash-quicknab">
    <a href='#dashcrawlchart'>כתבות ע"פ שבועות</a>
    <a href='#latestreports'>דוחות אחרונים</a>
    <a href='#latestarticles'>כתבות אחרונות</a>
    <a href='#dashscoreschart'>רלוונטיות כתבות</a>
    <a href='#dashtargetchart'>כתבות ע"פ מקורות</a>
</div>
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
        <div class="col-sm-6 text-right" style="position:relative;">
            <div class="make-box" id="latestarticles">
                <h4>כתבות אחרונות:</h4>
                <ul class="dash-latest-articales">
                    <?php                    
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

        <div class="col-sm-6 text-right" style="position:relative;">
            <div class="make-box" id="latestreports">
                <h4>דוחות אחרונים שהופצו:</h4>
                <ul class="dash-latest-reports">
                    <?php                    
                        foreach($Page->variable("recent-reports") as $key => $rep) {
                            $new_datetime = DateTime::createFromFormat ( "Y-m-d H:i:s", $rep["report_datetime"] );
                            echo "<li>".
                                "<table style='width:100%' class=''><tr>".
                                    "<td style='width:61px;'>".
                                        "<div class='dash-thumb-recent-report-date' style=''>".
                                            "<strong>".$new_datetime->format('d')."</strong>".
                                            "<span>".$new_datetime->format('M')."</span>".
                                        "</div>".
                                    "</td>".
                                    "<td style='vertical-align: text-top; padding:5px; position: relative;'>".
                                    "<h5 class='elip'>".
                                        $Page->in_variable("all-groups", $rep["report_of_group"], "name_valuegroup").
                                        "<em>".
                                            " נמצאו: ".
                                            count(json_decode($rep["report_articles"])).
                                            " כתבות".
                                        "</em>".
                                    "</h5>".
                                    "<span class='recip-show'>"."נשלח ל:".
                                        "<em>".
                                        count(json_decode($rep["report_sent_to"])).
                                        " נמענים".
                                        "</em>".
                                    "</span>".
                                    "<a class='goto-report' href='' target='_blank'>עבור לדוח</a>".
                                "</td>".
                                "</tr></table>".
                                "</li>";
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row" >
        
        <div class="col-sm-6" style="position:relative;">
            <div class="make-box">
                <div class="dash-pies" >
                    <h4>רלוונטיות מאגר ע"פ ניטורים</h4>
                    <div class="text-right" style="position:relative; height:200px; width:100%">
                        <canvas id="dashscoreschart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6" style="position:relative; height:250px;">
            <div class="make-box">
                <div class="dash-pies" >
                    <h4>מאגר כתבות ע"פ מקורות</h4>
                    <div class="text-right" style="position:relative; height:200px; width:100%">
                        <canvas id="dashtargetchart"></canvas>
                    </div>
                </div>
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
                        //console.log(response.results.map(function(e) { return e.week_name; }).reverse());
                        //console.log(response.results.map(function(e) { return parseInt(e.count_articles); }).reverse());
                        if (response.results.length) {
                            update_parsed_by_week_year(
                                response.results.map(function(e) { return e.week_name; }).reverse(),
                                response.results.map(function(e) {  return parseInt(e.count_articles); }).reverse()
                            );
                            build_piecharts_relevant();
                            build_piechart_targets_count();
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
    
    // The function to build the pie charts
    function build_piechart_targets_count() {
        var ctx_one = document.getElementById("dashtargetchart").getContext('2d');
        var pieChart_one = new Chart(ctx_one, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [
                        <?php
                            $values = [];
                            foreach($Page->variable("count-bytargets") as $key => $tar) {
                                $values[] = $tar["counter"];
                            }
                            echo implode(",",$values);
                        ?>
                    ],
                    backgroundColor: [
                        <?php
                            $colors = [];
                            foreach($Page->variable("count-bytargets") as $key => $tar) {
                                $colors[] = "'".$Page->in_variable(
                                    "all-targets", 
                                    $tar["target"],
                                    "use_tag_color"
                                )."'";
                            }
                            echo implode(",",$colors);
                        ?>
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                    <?php
                        $labels = [];
                        foreach($Page->variable("count-bytargets") as $key => $tar) {
                            $labels[] = "'".$Page->in_variable(
                                "all-targets", 
                                $tar["target"],
                                "name_targets"
                            )."'";
                        }
                        echo implode(",",$labels);
                    ?>
                ]
            },
            options: {
                responsive : true,
                maintainAspectRatio : false,
                legend: {
                    position:'left',
                    labels: {
                        fontColor: 'rgb(51, 51, 51)',
                        fontSize : 10
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    }
    
    // The function to build the pie charts
    function build_piecharts_relevant() {
        var ctx_one = document.getElementById("dashscoreschart").getContext('2d');
        var pieChart_one = new Chart(ctx_one, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [
                        <?php
                            echo $Page->in_variable("count-relevant", "yes_per").
                                 ",".
                                 $Page->in_variable("count-relevant", "no_per");
                        ?>
                    ],
                    backgroundColor: [
                        "rgb(75, 192, 192)",
                        "rgb(255, 99, 132)"
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                    "Relevant",
                    "Not Relevant"
                ]
            },
            options: {
                responsive : true,
                maintainAspectRatio : false,
                legend: {
                    position:'left',
                    labels: {
                        fontColor: 'rgb(51, 51, 51)',
                        fontSize : 10
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                tooltips: {
                  callbacks: {
                    label: function(tooltipItem, data) {
                      var currentValue = data.datasets[0].data[tooltipItem.index];      
                      return currentValue + "%";
                    }
                  }
                }
            }
        });
    }
</script>
