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
           
        /**** List Locations: ****/
        case "getresultsofvaluegroup":  
            
            //Synth needed:
            $get = $Api->Func->synth($_REQUEST, array('groupid','limit'),false);
            
            
            //Validation input:
            if (
                empty($get['groupid']) || 
                !is_numeric($get['groupid']) || 
                empty($get['limit']) ||
                !is_numeric($get['limit'])
            ) {
                $Api->error("not-legal");
            }
            
            //Logic:
            $Op = new Operation();
            
            $ResultList = $Op->get_valuegroup_results(
                $Api::$conn, 
                intval($get['groupid']),  
                intval($get['limit'])
            );
            
            //Output:
            if (is_array($ResultList)) {
               $results = array(
                   "results" => $ResultList,
                );
                $success = "with-results";
            } else {
                $Api->error("results-false");
            }
            
        break;
        
            
        /**** Set new Group value to db: ****/
        case "createnewgroupvalue":
            
            //Synth needed:
            $get = $Api->Func->synth($_REQUEST, array('groupname','values', 'targets', 'notify'),false);
            
            //Validation input:
            if (
                empty($get['groupname']) || 
                empty($get['values']) || 
                empty($get['targets'])
            ) {
                $Api->error("not-legal");
            }
            
            //Logic:
            $Op = new Operation();
            $theObject = $Op->create_new_valuegroup(
                $Api::$conn, 
                $get['groupname'],
                $get['values'],
                $get['targets'],
                $get['notify'],
                $User->user_info
            );

            //Output:
            if ($theObject === 0) {
               $results = array(
                   "groupId" => $Api::$conn->lastid()
                );
                $success = "with-results";
            } elseif ($theObject === 1) {
                $Api->error("duplicate");
            } else {
                $Api->error("query");
            }
        break;
            
        /**** Enable / Disable value groups: ****/
        case "disablevaluegroup":
            
            //Synth needed:
            $get = $Api->Func->synth($_REQUEST, array('groupid','state'),false);
            
            //Validation input:
            if (
                empty($get['groupid']) || 
                !is_numeric($get['groupid']) ||
                !is_numeric($get['state'])
            ) {
                $Api->error("not-legal");
            }
            
            //Logic:
            $Op = new Operation();
            $theObject = $Op->set_state_valuegroup(
                $Api::$conn, 
                $get['groupid'],
                $get['state']
            );

            //Output:
            if ($theObject === 0) {
               $results = array(
                   "groupId" => $get['groupid']
                );
                $success = "with-results";
            } else {
                $Api->error("query");
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