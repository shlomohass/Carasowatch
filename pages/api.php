<?php
/****************************** secure api include ***********************************/
if (!isset($conf)) { die("E:01"); }

/****************************** Build API ********************************************/
$Api = new api( $conf );

/****************************** Needed Values ****************************************/
$inputs = $Api->Func->synth($_POST, array('type'));

/****************************** Building response ***********************************/
$success = "general";
$results = false;

/****************************** API Logic  ***********************************/
if ( $inputs['type'] !== '' ) {
    
    switch (strtolower($inputs['type'])) {
        
            
        /**** List Locations: ****/
        case "listlocations":  
            
            //Logic:
            $Op = new Operation();
            
            $locList = $Op->get_location_list($Api::$conn);
            
            //Output:
            if (is_array($locList)) {
               $results = array(
                   "locations" => $locList,
                );
                $success = "with-results";
            } else {
                $Api->error("results-false");
            }
            
        break;
            
        /**** Set new Part: ****/
        case "loadtpl":
            
            //Synth needed:
            $get = $Api->Func->synth($_REQUEST, array('mode','which'),false);
            
            //Validation input:
            if (
                empty($get['mode']) || 
                !is_numeric($get['which']) ||
                ($get['mode'] != "show" && $get['mode'] != "build")
            ) {
                $Api->error("not-legal");
            }
            
            //Logic:
            $Op = new Operation();
            $theObject = $Op->get_tpl_html($Api::$conn, $get['mode'], $get['which']);

            //Output:
            if (!empty($theObject)) {
               $results = array(
                   "html" => preg_replace( "/\r|  |\t|\n/", "", $theObject)
                );
                $success = "with-results";
            } else {
                $Api->error("tpl-err");
            }
            
        break;
            
        //Unknown type - error:
        default : 
            $Api->error("bad-who");
        
    }
    
    //Run Response generator:
    $Api->response($success, $results);
    
} else {
    $Api->error("not-secure");
}

//Kill Page.
exit;