<?php
ini_set('max_execution_time', 0);
define('PREVENT_OUTPUT', false );  

require_once $_SERVER['DOCUMENT_ROOT']."/Carasowatch/conf.php";
require_once $_SERVER['DOCUMENT_ROOT']."/Carasowatch/Classes/Trace.class.php";
require_once $_SERVER['DOCUMENT_ROOT']."/Carasowatch/Classes/Func.class.php";
require_once $_SERVER['DOCUMENT_ROOT']."/Carasowatch/Classes/DB.class.php";
require_once $_SERVER['DOCUMENT_ROOT']."/Carasowatch/Classes/Basic.class.php";
require_once $_SERVER['DOCUMENT_ROOT']."/Carasowatch/Classes/Page.class.php";
setlocale(LC_TIME, 'hebrew');

$Oper = isset($_REQUEST["save"]) ? true : false;

?>
<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="SM projects" />
</head>
<body>
<?php
/***************** Load Page (DB, Func, conf, page ) *********************/

Trace::add_step(__FILE__,"Create objects");
$Page = new Page( $conf );
/*************************** Load Assets *********************************/

$Page->variable("all-articles", $Page::$conn->get("articles"));
$tarCount = count($Page->variable("all-articles"));
echo "<table>";
for ($i = 0; $i < $tarCount; $i++) {
    if (!empty($Page->in_variable("all-articles", $i, "date_pub_uni_articles"))) continue;
    $id            = $Page->in_variable("all-articles", $i, "id_articles");
    $strdate       = $Page->in_variable("all-articles", $i, "date_pub_articles");
    $dateOldFormat = $Page->in_variable("all-articles", $i, "date_used_format_articles");
    
    $date = DateTime::createFromFormat($dateOldFormat, $strdate);
    if ($date) {
        $strNewDate = $date->format('Y-m-d');
        echo "<tr><td>".$strdate."</td><td>".$strNewDate."</td></tr>";
        if ($Oper) {
            $Page::$conn->update( 
                        "articles", 
                        array("date_pub_uni_articles" => $strNewDate),
                        array(array("id_articles","=", $id))
            );
        }
    }
}
echo "</table>";

echo "<br /><strong>Done Job!";

/**************************** Debuger Expose **********************************/

//Expose Trace
Trace::expose_trace();
?>
    </body></html>