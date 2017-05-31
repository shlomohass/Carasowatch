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

$Result = [];

/*************************** Load Assets *********************************/

$Page->variable("all-targets", $Page::$conn->get("targets"));
$tarCount = count($Page->variable("all-targets"));
for ($i = 0; $i < $tarCount; $i++) {

    if (intval($Page->in_variable("all-targets",$i,"active_targets")) === 1) {

        $name    = $Page->in_variable("all-targets",$i,"name_targets");
        $outdir  = $Page->in_variable("all-targets",$i,"outdir_targets");
        $target  = $Page->in_variable("all-targets",$i,"url_base_targets");
        $payload = $Page->in_variable("all-targets",$i,"payload_base_targets");
        $theCommand = "Release\daat_agent_cara.exe --out ".$outdir." --name ".$name." --target ".$target." --payload ".$payload;
        $found = [];
        $targetId = $Page->in_variable("all-targets",$i,"id_targets");
        echo "<br /><u>Executing: ".$name."</u><br />";
        
        $output = shell_exec($theCommand);
        if (!empty($output) && strlen($output) > 100) { 
            $output = base64_decode($output);
            $found = @json_decode($output, true);
            Trace::reg_var("scraped-".$i, $found);
        }
        echo "<pre style='width: 500px; word-wrap: break-word; white-space: pre-wrap; height: 200px; overflow-y: auto; border: 1px solid black;'>".$output."</pre><br />";
        
        //Add to Results:
        $Result[$targetId] = array();
        if (is_array($found) && is_array($found["obj"])) {
            foreach ($found["obj"] as $key => $art) {
                $Result[$targetId][] = [
                    "date_scraped_articles"  => date('Y-m-d H:i:s'),
                    "from_target_articles"   => $targetId,
                    "date_pub_articles"      => $art["date"],
                    "link_articles"          => $art["link"],
                    "image_articles"         => $art["img"],
                    "title_articles"         => $art["title"],
                    "desc_articles"          => $art["desc"],
                    "full_content_articles"  => "",
                    "hide_articles"          => 0
                ];
            }
        }
        /*
        
        Trace::reg_var("outdir", $outdir);
        Trace::reg_var("target", $target);
        Trace::reg_var("payload", $payload);
        */
    } else {
        echo "<br /><u>Skiping: ".$Page->in_variable("all-targets",$i,"name_targets")."</u><br />";
    }
}
Trace::reg_var("all-scraped", $Result);

//Save Uniques to db:
$countArticlesStored = 0;
$countArticlesFound = 0;
foreach ($Result as $targetId => $targetscraped) {
    if (is_array($targetscraped)) {
        foreach ($targetscraped as $key => $articlesFound) {
            $countArticlesFound++;
            $check = $Page::$conn->select(
                "articles", 
                " * ",
                array(
                    array("from_target_articles", "=", $targetId),
                    array("link_articles", "=", $articlesFound["link_articles"])
                )
            );
            //If no article store to DB:
            if (empty($check)) {
                if ($Page::$conn->insert_safe( 
                    "articles", 
                    $articlesFound
                )) {
                    $countArticlesStored++;
                }
                
            }
        }
    }
}

echo "<br /><strong>Done Job! -> Found : ".$countArticlesFound." , Stored : ".$countArticlesStored."</strong><br /><br /><br /><br />";

/**************************** Debuger Expose **********************************/

//Expose Trace
Trace::expose_trace();
