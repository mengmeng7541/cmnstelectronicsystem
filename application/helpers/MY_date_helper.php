<?php

	function split_utime_by_day($start,$end)
	{
		$output = array();
		$timezone_offset = timezones('UP8')*3600;
		$start = $start+$timezone_offset;
		$end = $end+$timezone_offset;
		$start_date = (int)(($start)/86400);
		$end_date = (($end)%86400==0)?((int)(($end)/86400)-1):((int)(($end)/86400));
		if($start_date!=$end_date)
		{
			$output[] = array($start-$timezone_offset,($start_date+1)*86400-$timezone_offset);
			$start = ($start_date+1)*86400;
			while($start+86400<$end){
				$output[] = array($start-$timezone_offset,$start+86400-$timezone_offset);
				$start = $start+86400;
			}
			$output[] = array($start-$timezone_offset,$end-$timezone_offset);
		}else{
			$output[] = array($start-$timezone_offset,$end-$timezone_offset);
		}
		return $output;
	}

?>