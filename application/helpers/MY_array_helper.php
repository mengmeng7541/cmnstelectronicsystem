<?php

    function sql_result_to_column($sql_results,$column){
		$output = array();
		foreach($sql_results as $result){
			$output[] = $result[$column];
		}
		return $output;
	}
	function sql_column_to_key_value_array($sql_results,$value,$key = NULL){
		$output = array();
		if(isset($key)){
			foreach($sql_results as $result){
				$output[$result[$key]] = $result[$value];
			}
		}else{
			foreach($sql_results as $result){
				$output[] = $result[$value];
			}
		}
		return $output;
	}

?>