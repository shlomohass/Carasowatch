<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$Lang = array( 
    "lang" => "English",
    "code" => "en",
    "dir"  => "ltr",
    "dic"  => array(
    //GENERAL:
        "gen_title_prefix"              => "formi | ",
        "gen_title_for_display"         => "דיווח וניתוח כשירות",
        
    //Login page:
        "login_title"                   => "Login",
        "login_desc"                    => "",
        "login_keys"                    => "",
    
    //Admin nav right:
        "admin_nav_dashboard"           => "שולחן עבודה",
        "admin_nav_makereport"          => "דיווח כשירות",
        "admin_nav_showreport"          => "הפק דוח כשירות",
        "admin_nav_stats"               => "ניתוח כשירות",
        "admin_nav_inventory"           => "הגדר סדכ",
    
    //Page Dashboard:
        "page_dash_title"               => "שולחן עבודה",
        
    //Page Make Report:
        "page_makereport_title"             => "דיווח כשירות",
        "page_makereport_select_unit_label" => "בחר יחידה",
        "page_makereport_select_rep_label"  => "טען דוח קודם",
        "page_makereport_but_load_unit"     => "טען יחידה",
        "page_makereport_but_edit_rep"      => "טען לעריכה",
        "page_makereport_but_unload_unit"   => "בטל טעינה",
        "page_makereport_loaded_unit_title" => "יחידה: ",
        "page_makereport_newRep_title"      => "הוספת דוח חדש לתאריך: ",
        "page_makereport_but_create_report" => "הפק דוח כשירות חדש",
        "page_makereport_but_save_report" => "עדכן דוח כשירות: ",
        //Edit location Modal:
        "page_makereport_modal_header"      => "ערוך מיקום",
        "page_makereport_modal_header_add"  => "הוסף מיקום",
        "page_makereport_modal_loc_name_place"  => "הקלד שם",
        "page_makereport_modal_select_loc_head"  => "בחר מיקום",
        "page_makereport_modal_but_loc_new"  => "הוסף מיקום וסגור",
        "page_makereport_modal_but_loc_select"  => "בחר מיקום וסגור",
        "page_makereport_modal_but_loc_add"  => "סגור עריכת מיקום",
        "page_makereport_modal_loc_exists"  => "מיקום זה כבר מוזן - אנא בחר את המיקום מלמטה",
        "page_makereport_label_terrain"     => "שטח",
        "page_makereport_label_border"      => "גבול",
        "page_makereport_label_civil"       => "אזרחי",
        "page_makereport_label_base"        => "בסיס",
        
        //Edit parts Modal:
        "page_makereport_modal_parts_header"        => "ערוך חלפים",
        "page_makereport_modal_parts_header_add"    => "צור חלפים חדשים",
        "page_makereport_modal_select_parts_head"   => "בחר חלפים והוסף",
        "page_makereport_modal_update_parts_head"   => "רשימת חלפים",
        "page_makereport_modal_but_parts_add"       => "סגור ושמור עריכת חלפים",
        "page_makereport_modal_but_parts_close"     => "סגור ואל תשמור",
        "page_makereport_modal_part_num_place"      => "הקלד מקט",
        "page_makereport_modal_part_name_place"     => "הקלד שם קצר",
        "page_makereport_modal_part_desc_place"     => "הקלד תיאור",
        "page_makereport_modal_but_part_new"        => "צור חלף והוסף",
        "page_makereport_modal_but_parts_select"    => "בחר חלף והוסף",
        
    //Page Dashboard:
        "page_showreport_title"         => "הפק דוח כשירות",
        
    //Page Dashboard:
        "page_stats_title"              => "ניתוח כשירות",
        
    //Page Inventory:
        //Unit table:
        "page_inventory_title"          => "הגדר סדכ",
        "inven_table_header_id"         => "מזהה",
        "inven_table_header_unit"       => "יחידה",
        "inven_table_header_type"       => "סוג",
        "inven_table_header_ofunit"     => "יחידת בת של",
        "inven_table_header_place"      => "מיקום",
        "inven_table_header_gen"        => "כללי",
        "inven_table_header_actions"    => "פעולות",
        
        //Edit sadac madal:
        "inven_modal_gen_header"                => 'עדכון סד"כ: ',
        "inven_modal_addam_header"              => 'הוסף:',
        "inven_modal_amlist_header"             => 'סד"כ מוזן:',
        "inven_modal_input_label_am_id"         => 'מס צ:',
        "inven_modal_input_placeholder_am_id"   => '000000',
        "inven_modal_input_label_am_type"       => 'סוג: ',
        "inven_modal_input_label_am_yeud"       => 'ייעוד: ',
        "inven_modal_input_placeholder_am_yeud" => 'ייעוד',
        "inven_modal_but_add_am"                => 'הוסף',
        "inven_modal_but_close_ammodal"         => 'סגור וחזור',
        
    //App Page:
        "home_title"                    => "formi Home",
        "home_desc"                     => "",
        "home_keys"                     => "",
        
    //Admin Pages:
        "admin_title"                    => "ניתוח ודיווח כשירות",
        "admin_desc"                     => "",
        "admin_keys"                     => ""
    ),
    
    "js" => array(
        "script-frontend" => array(

        ),
        "script-login" => array(

        ),
        "script-admin" => array(
            
            //MakeReport window:
            "makerep_placeholder_select_unit"       => "בחר יחידה לטעינה",
            "makerep_placeholder_select_prevrep"    => "בחר דוח קודם",
            "makerep_warn_select_unit"              => "עליך לבחור יחידה לפני טעינתה",
            "makerep_error_load_unit"               => "אירעה שגיאה בעת טעינת יחידה",
            "makerep_error_load_loc"                => "אירעה שגיאה בטיענת רשימת מיקומים",
            "makerep_error_load_parts"              => "אירעה שגיאה בטיענת רשימת חלפים",
            "makerep_placeholder_select_loc"        => "בחר מיקום",
            "makerep_placeholder_select_part"       => "בחר חלף",
            "makerep_warn_setlocname"               => "אנא הזן שם מיקום",
            "makerep_warn_setlocname_exists"        => "מיקום חדש כבר מוזן במערכת בחר מהרשימה מתחת - לא ניתן להזין אותו מיקום.",
            "makerep_error_savenew_loc"             => "אירעה שגיאה בעת שמירת מיקום חדש במערכת",
            "makerep_warn_select_loc_empty"         => "עליך לבחור מיקום מהרשימה הנפתחת טרם השמירה",
            "makerep_no_parts_witing_row"           => "לא ממתין לחלפים",
            "makerep_warn_must_select_part"         => "עליך לבחור חלף מהרשימה על מנת להוסיף",
            "makerep_warn_must_part_is_in"          => "החלק כבר מוזן שנה כמות במקום",
            "makerep_set_part_num_req"              => "הקלד כמות נדרשת",
            "makerep_set_part_status_req"           => "הקלד סטאטוס טיפול",
            "makerep_error_create_parts_server"     => "אירעה שגיאה בצד השרת אנא נסה מאוחר יותר",
            "makerep_error_create_parts_legal"      => "יש להזין מספר קטלוגי ושם פריט על מנת ליצור חלק חדש",
            "makerep_error_create_parts_uni"        => "הפריט שאתה מנסה ליצור כבר קיים, אנא בחר אותו מהקטלוג במקום",
            "makerep_error_create_new_server"       => "אירעה שגיאה בצד השרת בזמן יצירת הדוח",
            
            //inventory window:
            "inven_modal_but_edit_sadac"            => 'נהל סד"כ',
            "inven_modal_but_erase_unit"            => 'מחק יחידה',
            "inven_modal_but_edit_unit"             => 'עדכן יחידה',
            
            //Amlah list in modal view:
            "header_table_units_amnum" => "מס צ",
            "header_table_units_amtype" => 'סוג אמלח',
            "header_table_units_amyeud" => "ייעוד / שייכות",
            "header_table_units_amactions" => "פעולות",
            "header_table_units_amnodata" => "לא מוזן סדכ ליחידה זאת",
            "err_save_units_amlist" => "אירעה שגיאה בעת הזנת אמלח ליחידה זאת",
            "err_load_units_amlist" => "אירעה שגיאה בעת טעינת רשימת אמלח ליחדיה זאת"

        )
    )
);
