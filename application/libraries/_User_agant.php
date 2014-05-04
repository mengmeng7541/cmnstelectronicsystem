<?php
class My_User_agant extends CI_User_agant {

    function __construct() {
        parent::__construct();
    }

	// --------------------------------------------------------------------

	/**
	 * Get the referrer
	 *
	 * @access	public
	 * @return	bool
	 */
	public function referrer()
	{
		return ( ! isset($_SERVER['HTTP_REFERER']) OR $_SERVER['HTTP_REFERER'] == '') ? '' : trim($_SERVER['HTTP_REFERER']);
	}
}
?>