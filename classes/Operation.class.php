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
            "notify_valuegroup"  => json_encode(empty($notify)?array():$notify),
            "added_by_valuegroup" => $user,
            "added_date_valuegroup" => "NOW()",
        );
        return ($conn->insert_safe("valuegroup", $vars))?0:2;
    }
    /* Creates a new group value:
     * @param $conn         -> DB connection.
     * @param $groupid      -> Integer { group id }.
     * @param $state        -> Integer { the boolean state }.
     * @return Integer : 
     *        0 { Success      }
     *        1 { Insert Error }
     */
    public function set_state_valuegroup($conn, $groupid, $state) {
        $vars = array(
            "enabled_valuegroup" => $state
        );
        return (
            $conn->update(
                "valuegroup", 
                $vars,
                array(array("id_valuegroup","=",$groupid)),
                array(1)
            )
        ) ? 0 : 1;
    }
}
