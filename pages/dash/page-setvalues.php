<?php

Trace::add_step(__FILE__,"Loading Sub Page: Dash -> setvalues");

/****************************** Load  Page Data ***********************************/
Trace::add_step(__FILE__,"Loading Page Data");

$Page->variable("all-targets", $Page::$conn->get("targets"));
$Page->variable("all-users", $Page::$conn->get("users"));
$Page->variable("all-valuegroups", $Page::$conn->get("valuegroup"));

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
Trace::reg_var("all-users", $Page->variable("all-users"));
Trace::reg_var("all-valuegroups", $Page->variable("all-valuegroups"));

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
                    <input type="text" id="inputnamegroup" class="form-control" placeholder="הקלד שם קבוצה" />
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
                <button id="setvalues_create_new" type="button" class="btn btn-success third-input-control">צור</button>

            </div>
            <div class="dis-table-cell text-right" style="border-top:solid 1px #d2ced4; border-bottom:solid 1px #d2ced4">
                <button id="setvalues_reset_form" type="button" class="btn btn-warning third-input-control">אפס</button>
            </div>
        </div>
    </div>

    <br />
    <div style="width:100%; padding:0" class="">
        
        <?php
            
            foreach($Page->variable("all-valuegroups") as $key => $group) {
                $vals = [];
                $targets = [];
                $notifyer = [];
                foreach (json_decode($group["values_valuegroup"], true) as $valkey => $val) {
                    $vals[] = $val["text"]." (".$val["impact"]."), &nbsp;";
                }
                foreach (json_decode($group["targets_valuegroup"], true) as $valkey => $val) {
                    Trace::reg_var("testval", $Page->in_variable("all-targets", $val["id"]));
                    $targets[] = $Page->in_variable("all-targets", $val["id"] ,"name_targets").", &nbsp;";
                }
                foreach (json_decode($group["notify_valuegroup"], true) as $valkey => $val) {
                    $notifyer[] = $val["email"].", &nbsp;";
                }
                echo "<h4>".$group["name_valuegroup"]."</h4>";
                echo "<table class='setvalues_table m0i'>"
                        ."<tr>"
                        ."<th>פעולות</th>"
                        ."<th>ערכים</th>"
                        ."<th>יעדים</th>"
                        ."<th>הודעה ל</th>"
                    ."</tr>";
                echo "<tr>"
                    ."<td class='p10'>"
                        ."<span class='glyphicon glyphicon-trash ml10 curs' aria-hidden='true'></span>"
                        ."<span class='glyphicon glyphicon-edit curs' aria-hidden='true'></span>"
                    ."</td>"
                    ."<td>".implode($vals)."</td>"
                    ."<td>".implode($targets)."</td>"
                    ."<td>".implode($notifyer)."</td>"
                    ."</tr>";
                echo "</table>";
                echo "<span class='group_notice_by'>יוצר: ".
                            $Page->Func->search_by_value_pair(
                                $Page->variable("all-users"), 
                                "id", 
                                $group["added_by_valuegroup"], 
                                "username"
                            )
                        ." בתאריך ".$group["added_date_valuegroup"]."</span>";
            }
        ?>
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
        var $innamegroup = $("#inputnamegroup");
        
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
            var $this = $(this);
            var $row = $this.closest('tr');
            if ($row.length) {
                $row.fadeOut(function(){
                   $(this).remove(); 
                });
            }
        });
        
        //Reset button:
        $("#setvalues_reset_form").click(function(){
            //Reset name:
            $innamegroup.val("");
            //Reset values:
            $invalueadd.val("");
            $tabvalue.find("td.stored_value").each(function(i,e){ $(e).closest("tr").remove(); });
            //Reset target:
            $tabtarget.find("td.stored_value").each(function(i,e){ $(e).closest("tr").remove(); });
            //Reset recip:
            $inrecipaddress.val("")
            $tabrecip.find("td.stored_value").each(function(i,e){ $(e).closest("tr").remove(); });
        });
        
        
        //Grab all form Values and pack them:
        // who -> values, targets, recipients
        function getformvalues(who) {
            var res = [], $who;
            switch (who) {
                case "values":
                    $who = $("#setvalues_values_table td.stored_value");
                    break;
                case "targets":
                    $who =  $("#setvalues_target_table td.stored_value");
                    break;
                case "recipients":
                    $who =  $("#setvalues_recip_table td.stored_value");
                    break;
            }
            $who.each(function(i, e) { 
                var $e = $(e);
                switch (who) {
                case "values":
                        res.push({
                            text    : $e.text().trim(),
                            impact  : parseInt($e.next('td').text().trim())
                        });
                        break;
                    case "targets":
                        res.push({
                            id    : parseInt($e.text().trim())
                        });
                        break;
                    case "recipients":
                        res.push({
                            email    : $e.text().trim()
                        });
                        break;
                }
                
            });
            return res;
        }
        
        //Create button:
        $("#setvalues_create_new").click(function(){
            var $but = $(this);
            //Disable button:
            $but.prop("disabled",true);
            //Get data:
            var data = {
                req:       "api",
                token:     $("#pagetoken").val(),
                type:      "createnewgroupvalue",
                
                groupname : $innamegroup.val().trim(),
                values    : getformvalues("values"),
                targets   : getformvalues("targets"),
                notify    : getformvalues("recipients")
            }

            console.log(data);
            
            //save to server:
            $.ajax({
                url: 'index.php',  //Server script to process data
                type: 'POST',
                data:  data,
                dataType: 'json',
                beforeSend: function() {
                },
                complete: function() {
                    $but.prop("disabled",false);
                },
                success: function(response) {
                    if (
                        typeof response === 'object' && 
                        typeof response.code !== 'undefined' &&
                        response.code == "202"
                    ) {
                        console.log(response);
                        
                    } else {
                        console.log(response);
                        window.alertModal("שגיאה",window.langHook("setvalues_error_set_new_group"));
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError);
                    window.alertModal("שגיאה",window.langHook("setvalues_error_set_new_group"));
                },
            });
        });
    }
    
}(jQuery, window, document));
</script>
