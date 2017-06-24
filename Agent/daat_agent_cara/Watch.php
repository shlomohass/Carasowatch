<?php
ini_set('max_execution_time', 0);
define('PREVENT_OUTPUT', false );  

require_once $_SERVER['DOCUMENT_ROOT']."/Carasowatch/conf.php";
require_once $_SERVER['DOCUMENT_ROOT']."/Carasowatch/Classes/Trace.class.php";
require_once $_SERVER['DOCUMENT_ROOT']."/Carasowatch/Classes/Func.class.php";
require_once $_SERVER['DOCUMENT_ROOT']."/Carasowatch/Classes/DB.class.php";
require_once $_SERVER['DOCUMENT_ROOT']."/Carasowatch/Classes/Basic.class.php";
require_once $_SERVER['DOCUMENT_ROOT']."/Carasowatch/Classes/Page.class.php";

/***************** Load Page (DB, Func, conf, page ) *********************/

Trace::add_step(__FILE__,"Create objects");
$Page = new Page( $conf );

$forceCreate = isset($_REQUEST["forcecreate"]) ? true : false;
$Oper = isset($_REQUEST["run"]) ? true : false;

/******************************Tools *************************************/

function parseValueObject($obj) {
    $res = $obj;
    $res["values_valuegroup"] = json_decode($res["values_valuegroup"]);
    $res["targets_valuegroup"] = json_decode($res["targets_valuegroup"]);
    $res["notify_valuegroup"] = json_decode($res["notify_valuegroup"]);
    return $res;
}
function inTargets($id, $obj) {
    $id = intval($id);
    foreach($obj["targets_valuegroup"] as $k => $tar) {
        if ($id === intval($tar->id)) {
            
            return true;
        }
    }
    return false;
}
/*************************** Load Assets *********************************/

$Page->variable("all-targets", $Page::$conn->get("targets"));
$Page->variable("all-groups", $Page::$conn->get("valuegroup"));
$Page->variable("all-articles", $Page::$conn->get("articles"));

//Force create operation:
if ($forceCreate) {
    foreach($Page->variable("all-groups") as $key => $group) {
        if (intval($group["enabled_valuegroup"]) === 1 ) {
            $theObj = parseValueObject($group);
            foreach($Page->variable("all-articles") as $article) {
                //Check if the source target is supported by the value group:
                if (inTargets($article["from_target_articles"], $theObj)) {
                    //Create this article line:
                    $check = $Page::$conn->select(
                        "watch", 
                        "1",
                        array(
                            array("article_watch", "=", $article["id_articles"]),
                            array("values_watch", "=", $theObj["id_valuegroup"])
                        )
                    );
                    if (empty($check)) {
                        if ($Page::$conn->insert_safe( 
                            "watch", 
                            array(
                                "article_watch" => $article["id_articles"],
                                "values_watch" => $theObj["id_valuegroup"],
                                "notify_watch" => "0"
                            )
                        )) {
                            echo "Success Create -> ".$article["id_articles"]." as watch id: ".$Page::$conn->lastid()." </br>";
                        } else {
                            echo "Failed Create -> ".$article["id_articles"]." seen Error: ".$Page::$conn->lasterror()." </br>";
                        }
                    }
                }
            }
        }
    }
}

//Trigger Watch:
if ($Oper) {
    echo "<table>";
    foreach($Page->variable("all-groups") as $key => $group) {
        if (intval($group["enabled_valuegroup"]) === 1 ) {
            $theObj = parseValueObject($group);
            $activeWatches = $Page::$conn->get_joined(
                array(
                    array('LEFT LOIN', 'watch.article_watch', 'articles.id_articles')
                ), 
                "*",
                " watch.notify_watch = '0' AND watch.values_watch = '".$theObj["id_valuegroup"]."' "
            );
            //Set the score:
            foreach($activeWatches as $watch) {
                $score = 0;
                $parts = array();
                foreach($theObj["values_valuegroup"] as $value) {
                    $txt = $value->text;
                    $imp = $value->impact;
                    $newscore = 0;
                    $newscore += substr_count($watch['title_articles'], $txt) * $imp * 1.5;
                    $newscore += substr_count($watch['desc_articles'], $txt) * $imp;
                    $score += $newscore;
                    if ($newscore > 0) {
                        $parts[] = array( "key" => $txt, "score" => $newscore);
                    }
                }
                if (!empty($parts)) {
                    echo "<tr><td style='border: 1px solid black;'>".$watch['title_articles']."</td><td style='border: 1px solid black;'>".$score."</td><td style='border: 1px solid black;'>";
                    foreach($parts as $part)
                        echo $part["key"]." - ".$part["score"]."</br>";
                    echo "</td></tr>";
                }
                $Page::$conn->update(
                    "watch",
                    array("found_watch" => json_encode($parts), "score_watch" => $score),
                    array(array("id_watch","=", $watch["id_watch"])),
                    array(1)
                );
            }
            Trace::reg_var("used-group", $theObj);
            Trace::reg_var("all-watches-target", $activeWatches);
        }
    }
     echo "</table>";
}
Trace::reg_var("all-targets", $Page->variable("all-targets"));
Trace::reg_var("all-groups", $Page->variable("all-groups"));

/**************************** Debuger Expose **********************************/

//Expose Trace
Trace::expose_trace();
