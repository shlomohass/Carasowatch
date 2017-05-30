<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Operation {
    
    public function __construct() {
        Trace::add_trace('construct class',__METHOD__);  
    }
    
    /* Get all the saved location list:
     * @param $conn -> DB connection.
     * @return Array()
     */
    public function get_tpl_html($conn, $mode, $which) {
        $block = $conn->select(
            "blocks", 
            " * ",
            array(array("block_id", "=", $which)),
            false,
            false,
            array(1)
        );
        if (empty($block) || !isset($block[0]) || !isset($block[0]["block_builder_object"])) return false;
        $vars = array(
            "lang" => Lang::get_langCode(),
            "type" => $mode,
            "wrap"   => $block[0]["block_wrap_class"]
        );
        ob_start();
        include(
            GPATH_TPL.
            $block[0]["block_builder_object"].
            ".php"
        );
        $result = ob_get_clean();
        return (!empty($result))?$result:false;
    }
    
    /* Get all the saved parts catalog:
     * @param $conn -> DB connection.
     * @return Array()
     */
    public function get_parts_list($conn) {
        $results = $conn->get("amlah_parts_cat");
        return (!empty($results))?$results:array();
    }
    
    /* Get A unit information provide unit ID:
     * @param $unitId -> Integer.
     * @param $conn -> DB connection.
     * @return Array()
     */
    public function get_unit_info($unitId, $conn) {
        $results = $conn->get_joined(
            array(
                array("LEFT JOIN","unit_list.unit_location","location.loc_id")
            ), 
            "`unit_list`.`unit_id`, 
             `unit_list`.`unit_name`, 
             `unit_list`.`unit_type`, 
             `unit_list`.`unit_location`, 
             `unit_list`.`unit_info`, 
             `location`.`loc_name`, 
             `location`.`loc_is_border`, 
             `location`.`loc_is_base`, 
             `location`.`loc_is_terain`, 
             `location`.`loc_is_civilian`
            ",
            "`unit_list`.`unit_id` = ".$conn->filter($unitId)
        );
        return (!empty($results))?$results:array();
    }
}
