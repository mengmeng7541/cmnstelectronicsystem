<?php
class MY_Session extends CI_Session {

    function __construct() {
        parent::__construct();
        
		$this->CI->load->helper('url');
        
        $this->tracker();
    }

    function tracker() {
        $tracker =& $this->userdata('_tracker');
		
		$is_ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        if( !$is_ajax ) {
            $tracker[] = array(
                'uri'   =>      uri_string(),
                'timestamp' =>  time()
            );
        }

        $this->set_userdata( '_tracker', $tracker );
    }


    function last_page( $offset = 1, $key = 'uri' ) {   
        if( !( $history = $this->userdata('_tracker') ) ) {
            return base_url();
        }

        $history = array_reverse($history); 

        if( isset( $history[$offset][$key] ) ) {
            return site_url($history[$offset][$key]);
        } else {
            return base_url();
        }
    }
}
?>