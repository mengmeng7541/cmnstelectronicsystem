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

	/**
	* 
	* @param undefined $arr
	* @param undefined $in_arr
	* 
	* @return The num of array elements matched the other array
	*/
	function array_in_array($arr,$in_arr)
	{
		$num_matched = 0;
		foreach($arr as $row)
		{
			if(in_array($row,$in_arr)) $num_matched++;
		}
		return $num_matched;
	}


	function range_array_unique($outage_times){
		foreach($outage_times as $key=>$value)
		{
			foreach($outage_times as $i=>$v)
			{
				if($i>=$key) break;
				if($value[0]>=$outage_times[$i][0]&&$value[0]<=$outage_times[$i][1])
				{
					//起始點已重複
					if($value[1]>$outage_times[$i][1])
					{
						//結束點未重複
						$outage_times[$i][1] = $value[1];
						unset($outage_times[$key]);
						return range_array_unique($outage_times);
					}else{
						//結束點亦重複
						unset($outage_times[$key]);
					}
				}else if($value[1]>=$outage_times[$i][0]&&$value[1]<=$outage_times[$i][1]){
					//結束點已重複
					if($value[0]<$outage_times[$i][0])
					{
						//起始點未重複
						$outage_times[$i][0] = $value[0];
						unset($outage_times[$key]);
						return range_array_unique($outage_times);
					}else{
						//起始點亦重複
						unset($outage_times[$key]);
					}
				}
			}
		}
		return $outage_times;
	}

?>