<?php
class MY_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        
    }
	/**
	* 
	* @param string $db
	* @param string $sTable
	* @param string $sJoin
	* @param string $aColumns
	* @param string $input_data
	* 
	* @return jQuery dataTables SQL query format by array
	*/
	public function get_jQ_DTs_array_with_join($db,$sTable,$sJoin,$aColumns,$input_data)
	{
		$keys = array_keys($aColumns);
		
		$sWhere = empty($input_data['sWhere'])?"":$input_data['sWhere']; 
		$sGroupBy = empty($input_data['sGroupBy'])?"":("GROUP BY ".$input_data['sGroupBy']); 
		
		/* 
		 * Paging
		 */
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
				intval( $_GET['iDisplayLength'] );
		}
		
		/*
		 * Ordering
		 */
		$sOrder = "";
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= $aColumns[ $keys[intval( $_GET['iSortCol_'.$i] )] ]." ".
						($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}
		
		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		if ( $sWhere != "" )
		{
			$sWhere = "WHERE ".$sWhere;
		}
		$sHaving = "";
		
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			if(empty($sWhere))
				$sWhere = "WHERE (";
			else
				$sWhere .= " AND (";
				
			//把字串根據" "拆開，並做AND搜尋
			$sSearchs = explode(" ",$_GET['sSearch']);
			foreach($sSearchs as $search)
			{
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					if(!preg_match("/[\(\)]/",$keys[$i]))
						$sWhere .= $keys[$i]." LIKE binary '%".mysql_real_escape_string( $search )."%' OR ";
				}
				$sWhere = substr_replace( $sWhere, "", -3 );//delete final "OR " string
				$sWhere .= ') AND (';
			}
			
			$sWhere = substr_replace( $sWhere, "", -5 );//delete final "AND (" string
		}
		
		/* Individual column filtering */
//		for ( $i=0 ; $i<count($aColumns) ; $i++ )
//		{
//			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
//			{
//				if ( $sWhere == "" )
//				{
//					$sWhere = "WHERE ";
//				}
//				else
//				{
//					$sWhere .= " AND ";
//				}
//				$sWhere .= $keys[$i]." LIKE binary '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
//			}
//		}
		
		
		
		/*
		 * SQL queries
		 * Get data to display
		 */
		foreach($aColumns as $key => $val)
		{
			if(!empty($aColumns[$key]))
				$aColumns[$key] = "{$key} AS {$val}";
		}
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
			FROM   $sTable
			$sJoin
			$sWhere
			$sGroupBy
			$sOrder
			$sHaving
			$sLimit
			";
		$query = $db->query($sQuery);
		$r['rResult'] = $query->result_array();
		
		/* Data set length after filtering */
		$sQuery = "
			SELECT FOUND_ROWS()
		";
		$rResultFilterTotal = $db->query($sQuery);
		$aResultFilterTotal = $rResultFilterTotal->row_array();
		$r['iFilteredTotal'] = $aResultFilterTotal["FOUND_ROWS()"];
		
		/* Total data set length */

		$r['iTotal'] = $db->count_all($sTable);
		
		return $r;
	}
    /**
	* 
	* @param string $db				The database to link
	* @param string $sTable			The main table to query
	* @param string $sJoin			The tables for join to main table
	* @param string $aColumns		The columns to return
	* @param string $input_data		Extra limit by case
	* 
	* @return jQuery dataTables SQL query format by array
	*/
	public function get_jQ_DTs_json_with_join($db,$sTable,$sJoin,$aColumns,$input_data)
	{

		$r = $this->get_jQ_DTs_array_with_join($db,$sTable,$sJoin,$aColumns,$input_data);
		
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $r['iTotal'],
			"iTotalDisplayRecords" => $r['iFilteredTotal'],
			"aaData" => array()
		);
		
		foreach ( $r['rResult'] as $aRow )
		{
			$row = array();
			foreach($aRow as $val)
			{
				$row[] = $val;
			}
			$output['aaData'][] = $row;
		}
		
		return json_encode( $output );
	}
	
}

?>