<?php
/* The email tpl returned by a function:
 *
 * Object sample:
 
 Array (1)
[0] = Array (3)
    |     [score] = Number: 4
    |     [parts] = Array (1)
    |        |     [0] = Array (2)
    |        |        |     [key] = String: 'לישראל'
    |        |        |     [score] = Number: 4
    |     [article] = Array (18)
    |        |     [id_watch] = Number: 659
    |        |     [article_watch] = Number: 686
    |        |     [values_watch] = Number: 5
    |        |     [notify_watch] = Number: 0
    |        |     [found_watch] = NULL
    |        |     [score_watch] = NULL
    |        |     [id_articles] = Number: 686
    |        |     [date_scraped_articles] = String: '2017-06-24 19:50:29'
    |        |     [from_target_articles] = Number: 7
    |        |     [date_pub_articles] = String: '2017-06-13'
    |        |     [date_used_format_articles] = String: 'Y-m-d'
    |        |     [date_pub_uni_articles] = String: '2017-06-13'
    |        |     [link_articles] = String: 'http://www.thecar.co.il/%d7%a1%d7%a7%d7%95%d7'
    |        |     [image_articles] = String: 'http://www.thecar.co.il/wp-content/uploads/2016/250x250.jpg'
    |        |     [title_articles] = String: 'סקודה לא מתנצלת: משיקה את קודיאק במחיר גבוה'
    |        |     [desc_articles] = String: 'צ'מפיון מוטורס, יבואנית קבוצת פולקסווגן לישראל חשפה היום (ג') את מחירי המחירון של ג'יפ הדגל של  '
    |        |     [full_content_articles] = String: ''
    |        |     [hide_articles] = Number: 0
    
 *
*/



function getEmailTpl(
    $groupvaluename = false,
    $dateofproccess = false,
    $valueingroup = false,
    $thearticles = false,
    $targets
) {
    
    $groupvaluename = $groupvaluename !== false ? $groupvaluename : "ללא שם";
    $dateofproccess = $dateofproccess !== false ? $dateofproccess : "לא ידוע"; 
    $valueingroup = $valueingroup !== false ? $valueingroup : "לא ידוע";
    
    $artHtml = array();
    
    foreach($thearticles as $k => $a) {
        
        $partsHtml = array();
        foreach($a["parts"] as $part) {
            $partsHtml[] = "<li><span>".$part["key"]."</span> - <span>".$part["score"]."</span></li>";
        }
        $artHtml[] = "
        <div style='font-size:0px; border:1px solid #c1c1c1; margin-bottom:20px;'>
            <div style='width:160px; height:130px; position: relative; display:inline-block; vertical-align: top; background-image: url(".$a["article"]["image_articles"].");background-repeat: no-repeat;
    background-position: center;
    background-size: cover;'>
        	<span style='background-color:".$targets[$a["article"]["from_target_articles"]]["use_tag_color"]."; position: absolute; top: 0; right: 0; color: white; font-size: 10px; display: inline-block; padding: 2px 4px; font-weight: 600;'>
              ".$targets[$a["article"]["from_target_articles"]]["name_targets"]."
            </span>
        </div>
        <div style='width:500px; height:130px; display:inline-block; background-color:white; font-size:14px; vertical-align: top;'>
          <h4 style='margin:0px; margin-top:8px; padding-right:10px;'>
            <a href='".$a["article"]["link_articles"]."' style='color:#353535;' target='_blank'>".$a["article"]["title_articles"]."</a>
          </h4>
          <p style='margin:0px; margin-top:8px; padding-right:10px; padding-left:10px; font-size:12px;'>".$a["article"]["desc_articles"]."</p>
          <span style='font-size:10px; color:grey; padding-right:10px;'>".$a["article"]["date_pub_articles"]."</span>
        </div>
        <div style='width:216px; height:130px; vertical-align: top; border-right:1px solid #c1c1c1; display:inline-block; background-color:white; font-size:12px; overflow-y:scroll;'>
        	<h4 style='margin:0px; margin-top:8px; padding-right:10px;'>ערכי מטרה</h4>
          	<ul>
              ".implode("", $partsHtml)."
            </ul>
        </div>
      </div>
        ";
    }
    
return "
<html>
    <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta name='description' content='Daily Report From Carasowatch.'>
        <meta name='keywords' content=''>
        <meta name='author' content='Shlomo Hassid'>
        <title>Daily Report From Carasowatch.</title>
    </head>
<body>
<div style='
            max-width:900px; 
            background-color:#f6f6f6; 
            margin:0 auto; 
            direction:rtl; 
            font-family:arial; 
            color:#353535; 
            border:1px solid #c1c1c1;
     		padding:15px'>
  <div class='header' style='font-weight:bold; font-size:16px'>דוח ניטור תכני אתרים -".$groupvaluename."</div>
</div>
<div style='
            max-width:900px; 
            background-color:white; 
            margin:0 auto; 
            direction:rtl; 
            font-family:arial; 
            color:#353535; 
            border:1px solid #c1c1c1;
            border-top:0px;
     		padding:15px'>
  <div class='subheader' style='font-weight:normal; font-size:10px'>
    <span>הופק ב:</span>
    <span>".$dateofproccess."</span>
    <div style='border:1px solid #c1c1c1; background-color:#f8ecff; font-weight:normal; font-size:14px; padding:10px; margin-top:15px;'>
      <h4 style='margin:0px;'>קבוצת ערכים לניטור</h4>
      <p style='margin-bottom:0px;'>".$valueingroup."</p>
    </div>
    <div style='font-weight:normal; font-size:14px; padding:10px; margin-top:15px;'>
      <h4 style='margin:0px; margin-bottom:15px;'>כתבות חדשות שמתאימות לקבוצת הניטור:</h4>
      ".implode("", $artHtml)."
    </div>
  </div>
</div>
</body></html>";
    
}
