<?php
/************************************************************************/
/********************* THE TPL Parser ************************************/
/************************************************************************/

    //The tpl object definition:
    $tpl = [
        "type"       => "build", // show / build
        "wrap_class" => "",
        "lang"       => [
            "he" => [
                "dir"                         => "rtl",
                "build_main_instruct"         => "יצירת כותרת ראשית:",
                "build_main_placeholder"      => "הקלד כותר מודגשת",
                "build_textarea_placeholder"  => "טקסט נוסף תחת הכותרת"
            ],
            "en" => [
                "dir"                         => "ltr",
                "build_main_instruct"         => "Create main title:",
                "build_main_placeholder"      => "The bold main title",
                "build_textarea_placeholder"  => "Additional text under the title"
            ]
        ]
    ];
    
    //Parse all needed:
    $lang = "en";
    if (isset($vars["lang"])) 
        $lang = strlen($vars["lang"]) == 2 ? filter_var($vars["lang"], FILTER_SANITIZE_STRING) : $lang;
    
    if (isset($vars["wrap"])) 
        $tpl["wrap_class"] = filter_var($vars["wrap"], FILTER_SANITIZE_STRING);
    
    if (isset($vars["type"])) 
        $tpl["type"] = $vars["type"] === "show" ? "show" : "build";
?>


<?php
/************************************************************************/
/********************* THE TPL PARTS ************************************/
/************************************************************************/

    if ($tpl["type"] === "build" && isset($tpl["lang"][$lang]) && $tpl["lang"][$lang]["dir"] === "rtl") { 
    /*************** BUILD -> RTL ******************/
?>


        <div class="<?php echo $tpl["wrap_class"]; ?>">
            <span>
                <?php echo htmlentities($tpl["lang"][$lang]["build_main_instruct"]); ?>
            </span>
            <input name='main_sh_title' type="text" placeholder="<?php echo htmlentities($tpl["lang"][$lang]["build_main_placeholder"]); ?>" />
            <textarea name='main_sh_moretext' placeholder="<?php echo htmlentities($tpl["lang"][$lang]["build_textarea_placeholder"]); ?>"></textarea>
        </div>



<?php 
    } elseif($tpl["type"] === "build") { 
    /*************** BUILD -> LTR ******************/
?>

        <div class="<?php echo $tpl["wrap_class"]; ?>">
            <span>
                <?php echo htmlentities($tpl["lang"][$lang]["build_main_instruct"]); ?>
            </span>
            <input name='main_sh_title' type="text" placeholder="<?php echo htmlentities($tpl["lang"][$lang]["build_main_placeholder"]); ?>" />
            <textarea name='main_sh_moretext' placeholder="<?php echo htmlentities($tpl["lang"][$lang]["build_textarea_placeholder"]); ?>"></textarea>
        </div>




<?php } ?>

<?php
    
    if ($tpl["type"] === "show" && isset($tpl["lang"][$lang]) && $tpl["lang"][$lang]["dir"] === "rtl") { 
    /*************** SHOW -> RTL ******************/
?>

        <h1>
            *{main_sh_title}*
            <p>*{main_sh_moretext}*</p>
        </h1>



<?php 
    } elseif($tpl["type"] === "show") { 
    /*************** SHOW -> LTR ******************/
?>

        <h1>
            *{main_sh_title}*
            <p>*{main_sh_moretext}*</p>
        </h1>


<?php } ?>