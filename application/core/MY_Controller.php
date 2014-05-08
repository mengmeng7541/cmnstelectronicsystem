<?php

class MY_Controller extends CI_Controller
{
	//exception code
//	const SUCCESS = 0;
//	const WARNING = 1;
//	const ERROR = 2;
	
	public function show_error_page($code = 404){
		$this->load->view('templates/header');
		$this->load->view('templates/sidebar');
		$this->load->view("error/{$code}");
		$this->load->view('templates/footer');
	}
    /**
     * login user data
     *
     * @var string
     **/
    //public $person = array();
	public function big5_to_utf8($arr)
	{
		foreach($arr as $key => $value)
		{
			if(is_array($arr[$key]))
			{
				$arr[@ICONV("BIG5","UTF-8//IGNORE",$key)] = $this->big5_to_utf8($arr[$key]);
			}else{
				$arr[@ICONV("BIG5","UTF-8//IGNORE",$key)] = @ICONV("BIG5","UTF-8//IGNORE",$value);		
			}
		}
		return $arr;
	}
	public function utf8_to_big5($arr)
	{
		if(!is_array($arr))
		{
			$arr = @ICONV("UTF-8","BIG5//IGNORE",$arr);	
			return $arr;
		}
		foreach($arr as $key => $value)
		{
			if(is_array($arr[$key]))
			{
				$arr[@ICONV("UTF-8","BIG5//IGNORE",$key)] = $this->utf8_to_big5($arr[$key]);
			}else{
				$arr[@ICONV("UTF-8","BIG5//IGNORE",$key)] = @ICONV("UTF-8","BIG5//IGNORE",$value);	
			}	
		}
		return $arr;
	}
	public function rotate_2D_array($arr)
	{
		$result_arr = array(array());
		foreach($arr as $key1 => $arr2)
		{
			foreach((array)$arr2 as $key2 => $val)
			{
				$result_arr[$key2][$key1] = $val;
			}
		}
		return $result_arr;
	}
	
	/**
	* 
	* @param undefined $arr
	* @param undefined $in_arr
	* 
	* @return The num of array elements matched the other array
	*/
	public function array_in_array($arr,$in_arr)
	{
		$num_matched = 0;
		foreach($arr as $row)
		{
			if(in_array($row,$in_arr)) $num_matched++;
		}
		return $num_matched;
	}
	/**
	* 
	* @param undefined $needle
	* @param undefined $haystack
	* @param undefined $strict
	* 
	* @return if element in multidimension array(recursive)
	*/
	public function in_array_r($needle, $haystack, $strict = false) {
	    foreach ($haystack as $item) {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
	            return true;
	        }
	    }

	    return false;
	}	
	public function secs_to_hours($secs)
	{
		return $secs/3600;
	}
	public function info_modal($str,$link = "",$state = "success")
	{
		$data_dismiss = empty($link)?$data_dismiss = "data-dismiss='modal'":"";
		if($state === SUCCESS_CODE) 
			$state = "success";
		else if($state === WARNING_CODE) 
			$state = "warning";
		else if($state === ERROR_CODE) 
			$state = "error";
		return "<div class='modal-header'>
			        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>
			        <h3 id='myModalLabel'>訊息</h3>
			    </div>
			    <div class='modal-body'>
					<div class='alert alert-{$state}'><strong>".strtoupper($state)."!</strong> {$str}</div>
			    </div>
			    <div class='modal-footer'>".anchor($link,"OK","class='btn btn-primary' {$data_dismiss}")."
			    </div>";
	}
	public function confirm_modal()
	{
		return "<div class='modal-header'>
			        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>
			        <h3 id='myModalLabel'>訊息</h3>
			    </div>
			    <div class='modal-body'>
					<div class='alert alert-{$state}'><strong>".strtoupper($state)."!</strong> {$str}</div>
			    </div>
			    <div class='modal-footer'>"
			    	.anchor(site_url().$link,"確認","class='btn btn-warning' {$data_dismiss}").
			    	"<button type='button' class='btn btn-primary'>取消</button>
			    </div>";
	}
	
	
	
	
	
    public function __construct()
    {
    	parent::__construct();
        
        
        
        $this->load->library('session');
        
        //user tracker plug-in
        $this->load->library('whence',array(
        	"maxwhence"=>10,
        	"homepage"=>''
        ));
        $this->whence->push();
		
		//EMAIL設定
		$email_config['protocol'] = "smtp";
		$email_config['smtp_host'] = "email.ncku.edu.tw";
		$email_config['smtp_port'] = "25";
		$email_config['smtp_user'] = "em31380";
		$email_config['smtp_pass'] = "mina31380";
		$email_config['newline'] = "\r\n";
		$email_config['mailtype'] = "html";
    	$this->load->library('email',$email_config);
		$this->email->from('em31380@email.ncku.edu.tw', '成大微奈米科技研究中心');//set default from email address
		//表單驗證設定
		$this->form_validation->set_message('required', '請填寫 %s ');
		$this->form_validation->set_message('min_length', '%s 長度不可小於 %s ');
		$this->form_validation->set_message('max_length', '%s 長度不可大於 %s ');
		//其他
    }
    /**
	* 
	* @param string assign what action you want
	* 
	* @return user clicked action string if no argument passed, or true if assigned action matched, or false otherwise.
	*/
    public function get_user_action($act = NULL)
    {
		$action_btn = $this->input->post('action_btn',TRUE);
		if(isset($act)){
			return $action_btn == $act;
		}else{
			return $action_btn;
		}
			
	}
	//BOOLEAN------------------------------------------
	public function is_user_login($redirect = TRUE)
	{
		if($this->session->userdata('ID'))
		{
			return TRUE;
		}else{
			if($redirect)
			{
				//before redirect, save the current url;
				$this->session->set_flashdata('prev_url',current_url());
				redirect('/');
			}
			return FALSE;
		}
	}
	public function is_admin_login($redirect = TRUE)
	{
		if($this->session->userdata('status')=="admin")
		{
			return TRUE;
			
		}else{
			if($redirect)
			{
				//before redirect, save the current url;
				$this->session->set_flashdata('prev_url',current_url());
				redirect('/');
			}			
			return FALSE;
		}
	}
	//--------------------------FORM VALIDATION---------------------------
	public function user_ID_existed($str)
	{
		$this->load->model("user_model");
		
		if ($this->user_model->get_user_profile_by_ID($str))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('user_ID_existed', '%s 不存在');
			return FALSE;
		}
	}
	public function user_ID_not_existed($str)
	{
		$this->load->model("user_model");
		
		if ($this->user_model->get_user_profile_by_ID($str))
		{
			$this->form_validation->set_message('user_ID_not_existed', '%s 已存在，不可重複');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function user_email_existed($str)
	{
		$this->load->model("user_model");
		
		if ($this->user_model->get_user_profile_by_email($str))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('user_email_existed', '%s 不存在');
			return FALSE;
		}
	}
	public function user_email_not_existed($str)
	{
		$this->load->model("user_model");
		
		if ($this->user_model->get_user_profile_by_email($str))
		{
			$this->form_validation->set_message('user_email_not_existed', '%s 已存在，不可重複');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}

?>