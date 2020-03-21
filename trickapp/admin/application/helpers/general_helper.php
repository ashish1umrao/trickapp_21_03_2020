<?php
function getDataByID($tblName = '', $where='') {
    $CI = &get_instance();
    $subQuery         =	"select * from ".$tblName.$where;		
	//print $subQuery; die;
    $data['STUDENTDATA'] = $CI->common_model->get_data_by_query('multiple', $subQuery);	
    return $data;
}
