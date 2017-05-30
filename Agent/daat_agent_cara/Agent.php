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


/*************************** Load Assets *********************************/

$Page->variable("all-targets", $Page::$conn->get("targets"));
$tarCount = count($Page->variable("all-targets"));
for ($i = 0; $i < $tarCount; $i++) {

    if (intval($Page->in_variable("all-targets",$i,"active_targets")) === 1) {
        
        echo "<br /><u>Executing: ".$Page->in_variable("all-targets",$i,"name_targets")."</u><br />";
        $outdir  = $Page->in_variable("all-targets",$i,"outdir_targets");
        $target  = $Page->in_variable("all-targets",$i,"url_base_targets");
        $payload = $Page->in_variable("all-targets",$i,"payload_base_targets");
        $theCommand = "Release\daat_agent_cara.exe --out ".$outdir." --target ".$target." --payload ".$payload;
        $output = shell_exec($theCommand);
        if (strlen($output) > 100) { 
            $output = base64_decode($output);
        }
        echo "<pre style='width: 500px; word-wrap: break-word; white-space: pre-wrap; height: 200px; overflow-y: auto; border: 1px solid black;'>".$output."</pre><br />";
        /*
        Trace::reg_var("all-targets", $Page->variable("all-targets"));
        Trace::reg_var("outdir", $outdir);
        Trace::reg_var("target", $target);
        Trace::reg_var("payload", $payload);
        */
    } else {
        echo "<br /><u>Skiping: ".$Page->in_variable("all-targets",$i,"name_targets")."</u><br />";
    }
}

echo "<br /><strong>Done Job!</strong><br />";

/**************************** Debuger Expose **********************************/

//Expose Trace
Trace::expose_trace();
