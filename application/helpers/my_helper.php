<?php

	function is_multi_array($arr)
	{
		foreach($arr as $row)
		{
			if(is_array($row))	return TRUE;
		}
		return FALSE;
	}

?>