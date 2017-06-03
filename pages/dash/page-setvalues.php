<?php

Trace::add_step(__FILE__,"Loading Sub Page: Dash -> setvalues");

/****************************** Load  Page Data ***********************************/
Trace::add_step(__FILE__,"Loading Page Data");

$Page->variable("all-targets", $Page::$conn->get("targets"));


/****************************** Manipulate Some data ******************************/
Trace::add_step(__FILE__,"Manipulate Page Data");

//Object to identify source
$temp = array();
foreach($Page->variable("all-targets") as $key => $target) {
    $temp[$target["id_targets"]] = $target;
}
$Page->variable("all-targets", $temp);

/****************************** Page Debugger Output ***********************************/
Trace::reg_var("all-targets", $Page->variable("all-targets"));



Trace::add_step(__FILE__,"Executing Page:");

?>
<h2 style="margin-bottom:0;">
    <?php Lang::P("page_setvalues_title"); ?>
</h2>
<div class="container-fluid">
    <div id='setvalueform' class="dis-table" style="width: 98%; margin: 0 auto;">
        <div class="dis-table-row">
            <div class="dis-table-cell">
                <div class="form-group">
                    <label for="">שם קבוצה:</label>
                    <input type="text" class="form-control" placeholder="הקלד שם קבוצה" />
                </div>
                <div class="form-group">
                    <label for="" style="width:100%;">הגדר ערכים:</label>
                    <input type="text" id="inputvalueadd" class="form-control twothird-input-control" id="" placeholder="הקלד ערך ובחר עדיפות" />
                    <select id="inputvalueprio" class="form-control small-input-control">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                    <span id="newvalue_addvalue" class="glyphicon glyphicon-plus mr10 curs" aria-hidden="true"></span>
                    <table class="setvalues_table" id="setvalues_values_table">
                        <tr><th>ערכים</th><th>עדיפות</th><th>הסר</th></tr>
                    </table>
                </div>
            </div>
            <div class="dis-table-cell" style="border-right:solid 1px #d2ced4">
                <div class="form-group">
                    <label for="" style="width:100%;">החל על:</label>
                    <select id="inputtargetuse" class="form-control twothird-input-control">
                        <?php
                            foreach($Page->variable("all-targets") as $tarId => $tar) {
                                echo "<option value='".$tarId."'>".$tar["name_targets"]."</option>";  
                            }
                        ?>
                    </select>
                    <span id="newvalue_addtarget" class="glyphicon glyphicon-plus mr10 curs" aria-hidden="true"></span>
                    <table class="setvalues_table" id="setvalues_target_table">
                        <tr><th>מזהה</th><th>מטרה</th><th>הסר</th></tr>
                    </table>
                </div>
                <div class="form-group">
                    <label for="" style="width:100%;">נמענים להתראות:</label>
                    <input type="text" class="form-control twothird-input-control text-left" id="inputrecipadd" placeholder="some@example.com" />
                    <span id="newvalue_addrecip" class="glyphicon glyphicon-plus mr10 curs" aria-hidden="true"></span>
                    <table class="setvalues_table" id="setvalues_recip_table">
                        <tr><th>נמען</th><th>הסר</th></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="dis-table-row">
            <div class="dis-table-cell text-left" style="border-top:solid 1px #d2ced4; border-bottom:solid 1px #d2ced4">
                <button type="button" class="btn btn-success third-input-control">צור</button>

            </div>
            <div class="dis-table-cell text-right" style="border-top:solid 1px #d2ced4; border-bottom:solid 1px #d2ced4">
                <button type="button" class="btn btn-warning third-input-control">אפס</button>
            </div>
        </div>
    </div>

    <br />
    <div style="width:100%; padding:0" class="dev">
        שדגשדגשג
    </div>
</div>
<div class="clearfix"></div>

<script type="text/javascript" language="javascript" >

/*** SetValue Form Handles: ***/
(function($, window, document) {
    
    var $theSetValueForm = $("#setvalueform");
    if ($theSetValueForm.length) {
        
        //Triggers:
        var $addValueBut = $('#newvalue_addvalue');
        var $addTargetBut = $('#newvalue_addtarget');
        var $addRecipBut = $('#newvalue_addrecip');
        
        //Form elemnts:
        var $invalueadd = $("#inputvalueadd");
        var $invalueprio = $("#inputvalueprio");
        var $intargetuse = $("#inputtargetuse");
        var $inrecipaddress = $("#inputrecipadd");
        
        //Tables:
        var $tabvalue = $("#setvalues_values_table");
        var $tabtarget = $("#setvalues_target_table");
        var $tabrecip = $("#setvalues_recip_table");
        
        //Add value:
        $addValueBut.click(function(){
            var valadd = $invalueadd.val().trim(),
                valprio = $invalueprio.val();
            //Validate empty:
            if (valadd === "") { console.log("Error:1"); $invalueadd.blink(3, 100, "#ff9696"); return; }
            //Validate already created:
            var validateDual = true;
            $tabvalue.find("td.stored_value").each(function(i, e){
                if ($(e).text() === valadd) { 
                    validateDual = false;
                    $(e).blink(3, 100, "#ff9696");
                }
            });
            if (!validateDual) { console.log("Error:2"); return; }
            //Add to table:
            $tabvalue.append(
                "<tr><td class='stored_value'>" + valadd + "</td>" +
                "<td>" + valprio + "</td>" +
                "<td class='text-center'><span class='glyphicon glyphicon-remove curs remove-values' aria-hidden='true'></span></td></tr>"
            )
        });
        
        //Add target:
        $addTargetBut.click(function(){
            var taraddname = $intargetuse.find("option:selected").text().trim(),
                taraddid = $intargetuse.val();
            
            //Validate empty:
            if (taraddname === "") { console.log("Error:1"); $intargetuse.blink(3, 100, "#ff9696"); return; }
            
            //Validate already created:
            var validateDual = true;
            $tabtarget.find("td.stored_value").each(function(i, e){
                if ($(e).text() == taraddid) { 
                    validateDual = false;
                    $(e).blink(3, 100, "#ff9696");
                }
            });
            if (!validateDual) { console.log("Error:2"); return; }
            //Add to table:
            $tabtarget.append(
                "<tr><td class='stored_value'>" + taraddid + "</td>" +
                "<td>" + taraddname + "</td>" +
                "<td class='text-center'><span class='glyphicon glyphicon-remove curs remove-values' aria-hidden='true'></span></td></tr>"
            )
        });
        
        //Add recip:
        $addRecipBut.click(function(){
            var recipaddress = $inrecipaddress.val().trim();
            
            //Validate email:
            if (!$inrecipaddress.checkEmail()) { console.log("Error:1"); $inrecipaddress.blink(3, 100, "#ff9696"); return; }
            
            //Validate already created:
            var validateDual = true;
            $tabrecip.find("td.stored_value").each(function(i, e){
                if ($(e).text() == recipaddress) { 
                    validateDual = false;
                    $(e).blink(3, 100, "#ff9696");
                }
            });
            if (!validateDual) { console.log("Error:2"); return; }
            //Add to table:
            $tabrecip.append(
                "<tr><td class='stored_value'>" + recipaddress + "</td>" +
                "<td class='text-center'><span class='glyphicon glyphicon-remove curs remove-values' aria-hidden='true'></span></td></tr>"
            )
        });
        
        //Removers table:
        $(document).on("click",".remove-values", function(){
           // TODO do some studd here
           console.log("remove"); 
            var $this = $(this);
            var $row = $this.closest('tr');
            if ($row.length) {
                $row.fadeOut(function(){
                   $(this).remove(); 
                });
            }
        });
    }
    
}(jQuery, window, document));
</script>
