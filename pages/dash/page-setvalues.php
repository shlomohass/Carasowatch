<?php

Trace::add_step(__FILE__,"Loading Sub Page: admin -> inventory");

//Load all amlah types:
$Page->variable("all-am-types", $Page::$conn->get("amlah_type"));
$Page->variable("all-am-groups", $Page::$conn->get("amlah_group"));

//Get user privs:
$Page->variable("god", $User->is_god());
$Page->variable("all-units-priv", $User->list_privs());

?>
<h2><?php Lang::P("page_inventory_title"); ?></h2>

<table id="inventory-grid" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered display" width="100%">
    <thead>
        <tr>
            <th><?php Lang::P("inven_table_header_id"); ?></th>
            <th><?php Lang::P("inven_table_header_unit"); ?></th>
            <th><?php Lang::P("inven_table_header_type"); ?></th>
            <th><?php Lang::P("inven_table_header_ofunit"); ?></th>
            <th><?php Lang::P("inven_table_header_place"); ?></th>
            <th><?php Lang::P("inven_table_header_gen"); ?></th>
            <th><?php Lang::P("inven_table_header_actions"); ?></th>
        </tr>
    </thead>
</table>

<div id="manage_sadac" class='modalformi'>
    <div class="modalformi_wrap">
        <div class='modalformi_head'><?php Lang::P("inven_modal_gen_header"); ?><span class="highlighted_name"></span></div>
        <div class='modalformi_bodyFixed'>
            <div class="add_sadac_form">
                <h4><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    <?php Lang::P("inven_modal_addam_header"); ?>
                </h4>
                <form id="add_sadac_form"class="form-inline">
                  <div class="form-group">
                    <label for="zid">
                        <?php Lang::P("inven_modal_input_label_am_id"); ?>
                      </label>
                    <input type="text" class="form-control" id="zid" placeholder="<?php Lang::P("inven_modal_input_placeholder_am_id"); ?>">
                  </div>
                  <div class="form-group">
                    <label for="ztype" style='margin-bottom:0px;'>
                        <?php Lang::P("inven_modal_input_label_am_type"); ?>
                        <select class="" dir='rtl' lang='he' style='width:150px;' id="ztype" placeholder="">
                            <?php
                                foreach ($Page->variable("all-am-groups") as $group) {
                                    echo "<optgroup label='".$group["am_group_name"]."'>";
                                    foreach ($Page->variable("all-am-types") as $type) {
                                        if ($type["am_type_of_group"] === $group["am_group_id"]) {
                                            echo "<option value='".$type["am_type_id"]."'>".$type["am_type_name"]."</option>";
                                        }
                                    }
                                    echo "</optgroup>";
                                }
                            ?>
                        </select>
                    </label>
                  </div>
                  <div class="form-group">
                    <label for="zyeud">
                        <?php Lang::P("inven_modal_input_label_am_yeud"); ?>
                    </label>
                    <input type="email" class="form-control" id="zyeud" placeholder="<?php Lang::P("inven_modal_input_placeholder_am_yeud"); ?>">
                  </div>
                  <button type="button" class="btn btn-primary addambutton" onclick="window.manage_sadac.addToAmList(this);">
                      <?php Lang::P("inven_modal_but_add_am"); ?>
                  </button>
                </form>
            </div>
            <br />
            <h4><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                <?php Lang::P("inven_modal_amlist_header"); ?>
            </h4>
        </div>
        <div class='modalformi_body'>
            <div class='show_sadac'>
                
            </div>
        </div>
        <div class='modalformi_foot'>
            <button type="button" class="btn btn-primary" onclick="window.manage_sadac.disManageModal()">
                <?php Lang::P("inven_modal_but_close_ammodal"); ?>
            </button>
        </div>
    </div>
</div>
<script type="text/javascript" language="javascript" >
    var dataTable = $('#inventory-grid')
        .on('xhr.dt', function ( e, settings, json, xhr ) {
                
                //Override actions by user priviliges:
                if (typeof json.user_god !== 'undefined' && typeof json.user_priv !== 'undefined' && !json.user_god) {
                    for (var i = 0; i < json.data.length; i++) {
                        var u_id = json.data[i][0];
                        var has_edit_units = false;
                        var has_edit_am = false;
                        for (var j = 0; j < json.user_priv.length; j++) {
                            if (json.user_priv[j].priv_on_unit === u_id) {
                                if (json.user_priv[j].user_can_edit_amlah == 1) {
                                    has_edit_am = true;
                                }
                                if (json.user_priv[j].user_can_edit_units == 1) {
                                    has_edit_units = true;
                                }
                            }
                        }
                        json.data[i][6] = "";
                        if (has_edit_am) {
                            json.data[i][6] += '<button class="manage_amlah_but" data-uid="' + u_id + '">' + window.langHook("inven_modal_but_edit_sadac") + '</button>';
                        }
                        if (has_edit_units) {
                            json.data[i][6] += '<button>' + window.langHook("inven_modal_but_erase_unit") + '</button>' + 
                                               '<button>' + window.langHook("inven_modal_but_edit_unit") + '</button>';
                        }
                    }
                }
                if (typeof json.user_god !== 'undefined' && json.user_god) {
                    for (var i = 0; i < json.data.length; i++) {
                        var u_id = json.data[i][0];
                        json.data[i][6] = '<button class="manage_amlah_but" data-uid="' + u_id + '">' + window.langHook("inven_modal_but_edit_sadac") + '</button>'+
                                          '<button>' + window.langHook("inven_modal_but_erase_unit") + '</button>' + 
                                          '<button>' + window.langHook("inven_modal_but_edit_unit") + '</button>';
                    }
                }
            
            }).DataTable( {
                "processing": true,
                "serverSide": true,
                "language": {
                    paginate: {
                        previous: '‹ הקודם',
                        next:     'הבא ›'
                    },
                    aria: {
                        paginate: {
                            previous: 'Previous',
                            next:     'Next'
                        }
                    },
                    info: "מציג _START_ עד _END_ מתוך _TOTAL_ רשומות",
                    lengthMenu: "הצג _MENU_ רשומות",
                    search:         "חפש: ",
                },
                columnDefs: [
                    {bSortable: false, targets: [-1,-2]} 
                ],
                "ajax":{
                    url : "index.php",
                    type: "post",
                    data: function ( d ) {
                            return $.extend( {}, d, {
                                    req:"api",
                                    token:$("#pagetoken").val(),
                                    type:"listmanageunits"
                                } ); 
                    },
                    error: function(err, ms){  // error handling
                        console.log("error",err);
                    }
                }
            } );
    
    window["manage_sadac"] = {
        
        loadManageModal : function($ele) {
            
            var $modal = $("#manage_sadac");
            
            //Unit Id:
            var unitId = parseInt($ele.closest('tr').find('td').eq(0).text());
            var unitName = $ele.closest('tr').find('td').eq(1).text();
            
            //Set Title:
            $modal.find('.highlighted_name').text(unitName);
            
            //Set add but id:
            $modal.find('.addambutton').data('uid', unitId);
            
            //Load Amlah list:
            window.manage_sadac.refreshManageAmlahList(
                unitId,
                function() {
                    $("#manage_sadac").fadeIn();
                }
            );
        },
        disManageModal : function() {
            $("#manage_sadac").fadeOut();
        },
        getAmlistHtmlRepresentation : function(obj, priv) {
            prev_group = "";
            var retHtml = "<table class='amlist_quick_view'><tr><th>" + window.langHook("header_table_units_amnum") + "</th>" + 
                                                                "<th>" + window.langHook("header_table_units_amtype") + "</th>" + 
                                                                "<th>" + window.langHook("header_table_units_amyeud") + "</th>" + 
                                                                "<th>" + window.langHook("header_table_units_amactions") + "</th></tr>";
            if (obj.length > 0) {
                for (var i = 0; i < obj.length; i++) {
                    if (obj[i].am_group_name !== prev_group) {
                        prev_group = obj[i].am_group_name;
                        retHtml += "<tr><td colspan='4' class='amlist_amgroup_row'>" + prev_group + "</td></tr>";
                    }
                    retHtml += "<tr><td>" + obj[i].am_list_number + "</td><td>" + obj[i].am_type_name + "</td><td>" + obj[i].am_list_yeud + "</td>";
                    if (priv) {
                        retHtml += "<td><span class='glyphicon glyphicon-trash' onclick='' data-amid='" + obj[i].am_list_id + "'></span>" + 
                                    "<span class='glyphicon glyphicon-pencil' onclick='' data-amid='" + obj[i].am_list_id + "'></span></td></tr>";
                    } else {
                        retHtml += "<td></td></tr>";
                    }
                }
            } else {
                retHtml += "<tr><td colspan='4'>" + window.langHook("header_table_units_amnodata") + "</td></tr>";
            }
            retHtml += "</table>";
            return retHtml;
        },
        refreshManageAmlahList : function(unitId, callAfter) {
            
            var data = {
                req     : "api",
                token   : $("#pagetoken").val(),
                type    : "listAmlahOfUnit",
                unit    : unitId
            };
            $.ajax({
                url: 'index.php',  //Server script to process data
                type: 'POST',
                data: data,
                dataType: 'json',             
                beforeSend: function() {
                },
                success: function(response) {
                    if (
                        typeof response === 'object' && 
                        typeof response.code !== 'undefined' &&
                        response.code == "202"
                    ) {
                        $("#manage_sadac").find(".modalformi_body").html(
                            window.manage_sadac.getAmlistHtmlRepresentation(response.results.amlist, response.results.editPriv)
                        );
                        callAfter();
                    } else {
                        window.alertModal("שגיאה",window.langHook("err_load_units_amlist"));
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError);
                    window.alertModal("שגיאה",window.langHook("err_load_units_amlist"));
                },
            });
        },
        addToAmList : function(t) {
            var $ele = $(t);
            var $form = $ele.closest('form');
            var data = {
                 req:       "api",
                 token:     $("#pagetoken").val(),
                 type:      "addamtounit",
                 unit_id:   $ele.data('uid'),
                 amnum:     $form.find("#zid").val(),
                 amtype:    $form.find("#ztype").val(),
                 amyeud:    $form.find("#zyeud").val()
            };
            $.ajax({
                url: 'index.php',  //Server script to process data
                type: 'POST',
                data: data,
                dataType: 'json',             
                beforeSend: function() {
                    $ele.prop("disabled", true);
                },
                success: function(response) {
                    if (
                        typeof response === 'object' && 
                        typeof response.code !== 'undefined' &&
                        response.code == "202"
                    ) {
                        $form.find("#zid").val("");
                        $form.find("#zyeud").val("");
                        window.manage_sadac.refreshManageAmlahList($ele.data('uid'),function(){});
                    } else {
                        console.log("fail",response);
                        window.alertModal("שגיאה",window.langHook("err_save_units_amlist"));
                    }
                    $ele.prop("disabled", false);
                },
                error: function(xhr, ajaxOptions, thrownError){
                    $ele.prop("disabled", false);
                    console.log(thrownError);
                    window.alertModal("שגיאה",window.langHook("err_save_units_amlist"));
                },
            });
        }
    };
    
    //Manage Sadac:
    $("#ztype").select2();
    
    $(document).on("click",".manage_amlah_but",function(){
        window.manage_sadac.loadManageModal($(this));
    });

</script>
