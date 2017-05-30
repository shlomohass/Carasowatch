<?php
Trace::add_step(__FILE__,"Loading Sub Page: dash -> makeform");


/****************************** Load  Page Data ***********************************/
Trace::add_step(__FILE__,"Loading Forms available");
$Page->variable("all-forms-build", $Page::$conn->get("form_struct"));
$Page->variable("all-blocks", $Page::$conn->get("blocks"));
$Page->variable("all-forms-blocks", $Page::$conn->get("form_struct_blocks"));

/****************************** Manipulate Some data ******************************/
//Sort the index of the types available:
if (!empty($Page->variable("all-forms-build"))) {
    $temp_arr = $Page->variable("all-forms-build");
    usort($temp_arr, function($a,$b){  return $a["form_struct_index"]-$b["form_struct_index"];});
    $Page->variable("all-forms-build", $temp_arr);
    unset($temp_arr);
}
//Open jsons in the forms types:
if (!empty($Page->variable("all-forms-build"))) {
    $res = array();
    foreach ($Page->variable("all-forms-build") as $key => $form) {
        $res[$key] = $form;
        
        //Parse the json:
        $res[$key]['form_struct_name'] = json_decode($form['form_struct_name'], true);
        $res[$key]['form_struct_help'] = json_decode($form['form_struct_help'], true);
        
        //Set the correct display names and text:
        if (isset($res[$key]['form_struct_name'][Lang::get_langCode().'-name'])) {
            $res[$key]['form_struct_name']['parsed-name'] = $res[$key]['form_struct_name'][Lang::get_langCode().'-name'];
            $res[$key]['form_struct_help']['parsed-desc'] = $res[$key]['form_struct_help'][Lang::get_langCode().'-desc'];

        } elseif (isset($res[$key]['form_struct_name']['en-name'])) {
            $res[$key]['form_struct_name']['parsed-name'] = $res[$key]['form_struct_name']['en-name'];
            $res[$key]['form_struct_help']['parsed-desc'] = $res[$key]['form_struct_help']['en-desc'];
        } else {
            $res[$key]['form_struct_name']['parsed-name'] = "un-named";
            $res[$key]['form_struct_help']['parsed-desc'] = "not-described";
        }
    }
    $Page->variable("all-forms-build", $res);
    unset($res);
}
//Open jsons in the blocks:
if (!empty($Page->variable("all-blocks"))) {
    $res = array();
    foreach ($Page->variable("all-blocks") as $key => $block) {
        $res[$key] = $block;
        $res[$key]['block_name'] = json_decode($block['block_name'], true);
        $res[$key]['block_help'] = json_decode($block['block_help'], true);
    }
    $Page->variable("all-blocks", $res);
     unset($res);
}
//Sort the blocks by names:
if (!empty($Page->variable("all-blocks"))) {
    $temp_arr = $Page->variable("all-blocks");
    usort($temp_arr, function($a,$b){ return strcmp($a["block_name"][Lang::get_langCode()."-name"], $a["block_name"][Lang::get_langCode()."-name"]); });
    $Page->variable("all-blocks", $temp_arr);
    unset($temp_arr);
}
//Group the blocks allowed on a form:
if (!empty($Page->variable("all-forms-blocks"))) {
    $res = array();
    foreach($Page->variable("all-forms-blocks") as $key => $block) {
        if (!isset($res[$block["struct_blocks_id_struct"]]))
            $res[$block["struct_blocks_id_struct"]] = array();
        $res[$block["struct_blocks_id_struct"]][] = array("block" => $block["struct_blocks_id_block"], "block-max" => $block["struct_blocks_max"]);
    }
    $Page->variable("all-forms-blocks", $res);
    unset($res);
}

/****************************** Page Debugger Output ***********************************/
Trace::reg_var("all-forms", $Page->variable("all-forms-build"));
Trace::reg_var("all-blocks", $Page->variable("all-blocks"));
Trace::reg_var("forms-blocks", $Page->variable("all-forms-blocks"));

?>
<h2><?php Lang::P("page_makeform_title"); ?></h2>
<div class="container-fluid">
    <div class="row dev">
        <h4>בחר תבנית טופס</h4>
        <div class="dash-make-select col-sm-12">
            <div class="dash-make-select-types">
                <?php
                    //TODO: escape title {help} from html entities.
                    foreach($Page->variable("all-forms-build") as $key => &$form) {
                        //Skips disabled forms structures:
                        if (isset($form['form_struct_enable']) && $form['form_struct_enable'] == 0) continue;
                        //Output:
                        echo "<div data-formid='".$form['form_struct_id']."' class='dash-make-form-type dev' title='".$form['form_struct_help']['parsed-desc']."'>".
                                $form['form_struct_name']['parsed-name'].
                                "</div>";
                    }
                ?>
            </div>
        </div>
    </div>
    <br />
    <div>
        <div class="row dev">
            <div class="dash-make-view col-sm-4 hidden dev">
                <h4>חלונית צפייה</h4>
            </div>
            <div class="dash-make-build col-sm-8 dev">
                <?php
                    foreach ($Page->variable("all-forms-build") as $form_key => $form) {
                        if (isset($form['form_struct_enable']) && $form['form_struct_enable'] == 0) continue;
                ?>
                <form class="dash-make-formele-build hidden dev" data-formid="<?php echo $form['form_struct_id']; ?>">
                    <h4>
                        <?php 
                            echo Lang::P("page_makereport_form_build_header", false)." - ".
                                 $form["form_struct_name"]["parsed-name"];
                        ?>
                    </h4>
                    <div class="dash-make-add-ele col-sm-12 dev">
                        <div class="col-sm-3">
                            <input type="button" value="הוסף" class="dash-make-formele-add btn btn-default btn-block" />
                        </div>
                        <div class="col-sm-9">
                            <select name="form_add_type" class="form-control">
                                <?php
                                    $ava = $Page->in_variable("all-forms-blocks", $form["form_struct_id"]);
                                    if (!empty($ava)) {
                                        foreach($Page->variable("all-blocks") as $key => $block) {
                                            //Will print only blocks that are attached to the form:
                                            if ($Page->Func->search_multi_secondDim($ava, "block", $block["block_id"]) !== false) {
                                                echo "<option value=".$block["block_id"].">".$block["block_name"][Lang::get_langCode()."-name"]."</option>";
                                            }
                                        }
                                    } else {
                                        //TODO: Handle the case that their are no blocks attached.
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </form>
                <div class="clearfix"></div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>