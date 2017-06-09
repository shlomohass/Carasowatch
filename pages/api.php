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
        case "createnewgroupvalue":
            
            /*
                groupname : $innamegroup.val().trim(),
                values    : getformvalues("values"),
                targets   : getformvalues("targets"),
                notify    : getformvalues("recipients")
                
                

array(1) {
  [0]=>
  array(2) {
    ["text"]=>
    string(5) "adsad"
    ["impact"]=>
    string(1) "1"
  }
}
array(1) {
  [0]=>
  array(1) {
    ["id"]=>
    string(1) "4"
  }
}
array(1) {
  [0]=>
  array(1) {
    ["email"]=>
    string(15) "asdasd@kjhf.com"
  }
}
                
                
                
                
                
            */
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
            
            var_dump($get['groupname']);
            var_dump($get['values']);
            var_dump($get['targets']);
            var_dump($get['notify']);
            
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