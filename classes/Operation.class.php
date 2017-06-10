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
    
    /* Creates a new group value:
     * @param $conn         -> DB connection.
     * @param $name         -> String { group name }.
     * @param $values       -> Array { the values to be stored }.
     * @param $targets      -> Array { the values to be stored }.
     * @param $notify       -> Array { the values to be stored }.
     * @param $user         -> Int { user id }.
     * @return Integer : 
     *        0 { Success      }
     *        1 { Duplicate    } 
     *        2 { Insert Error }
     */
    public function create_new_valuegroup($conn, $name, $values, $targets, $notify, $user) {
        $name = strtolower($name);
        
        //Validate duplicates
        $block = $conn->select(
            "valuegroup", 
            "1",
            array(array("name_valuegroup", "=", $name)),
            false,
            false,
            array(1)
        );
        if (!empty($block)) return 1;
        $vars = array(
            "name_valuegroup"    => $name,
            "values_valuegroup"  => json_encode($values),
            "targets_valuegroup" => json_encode($targets),
            "notify_valuegroup"  => json_encode($notify),
            "added_by_valuegroup" => $user,
            "added_date_valuegroup" => "NOW()",
        );
        return ($conn->insert_safe("valuegroup", $vars))?0:2;
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
