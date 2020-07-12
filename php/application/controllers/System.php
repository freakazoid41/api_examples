<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System extends CI_Controller {
    public function __construct() {
		parent::__construct();
		if($this->uri->segment(1)!='login' && $this->uri->segment(1)!='client'){
			$rsp = _check_token($this->input->request_headers());
			//token failed
			if(!$rsp['rsp']){
				header('Content-Type: application/json');
				//Status Code 401 = Unauthorized
				header("HTTP/1.1 401 Unauthorized");
				//Writes the JSON message for the user
				echo json_encode(array('rsp'=>false,'message' => $rsp['msg'],'command'=>0));
				die; //Stops executing the PHP 
			}
		}
		/**/
	}
	
	public function index(){
		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode(array('rsp'=>false,'message' => 'Welcome to falan api project !!')));
	}


	public function request($model,$id = null){
		//load model 
		$this->load->model($model);
		//detect method
		switch($this->input->method()){
			case 'get':
				$rsp = $id!== null ? $this->$model->_get(array('id'=>$id)) : $this->$model->_get();
			break;
			case 'post':
				$rsp = $this->$model->_add($this->input->post());
			break;
			case 'patch':
				$var_array = array();
				mb_parse_str(file_get_contents("php://input"),$var_array);
				$rsp = $this->$model->_update($var_array);
			break;
			case 'delete':
				$var_array = array();
				parse_str(file_get_contents("php://input"),$var_array);
				$rsp = $this->$model->_delete($var_array);
			break;
		}		
		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode(array($rsp)));
	}


	public function getlist($model){
		//load model 
		$this->load->model($model);
		$rsp = $this->$model->_getList($this->input->post());	
		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($rsp));
	}
	
	public function query($model){
		//load model 
		$this->load->model($model);
		$rsp = $this->$model->_get(null,$this->input->post());	
		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($rsp));
	}

	public function client($barcode){
		//load model 
		$this->load->model('menus');
		$this->load->model('users');
		//first get user

		$rsp = $this->users->_get(null,array(
			'barcode'=>$barcode,
		));

		if($rsp['rsp']===true){
			$rsp = $this->menus->_get(null,array(
				'user_id'=>$rsp['data'][0]->id,
			));
		}else{
			$rsp = array('rsp'=>false,'data'=>array());
		}

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($rsp));
	}
	
    /**
	 * this method will do login
	 */
    public function login()
	{
		//take data
		$data = $this->input->post();
		//load user model
		$this->load->model('users');
		$this->load->model('users_keys');
		$rsp = $this->users->_get(null,array(
			'username'=>$data['username'],
			'password'=>$data['password']
		));
		if($rsp['rsp']===true){
			//get token and person info
			$token = _get_token();
			$label = $this->input->request_headers()['User-Agent'];
			//clean all old dated tokens
			$this->users_keys->_delete(array(
				'user_id'=>$rsp['data'][0]->id,
				'user_label'=>$label
			));
			//add new token to database
			$this->users_keys->_add(array(
				'user_label'=>$label,
				'user_id'=>$rsp['data'][0]->id,
				'user_key' => $token['token'],
				'end_at'=>$token['end']
			));
			
			//send new created token to client
			$rsp = array(
				'rsp'=>true,
				'msg' => 'Logged in !!',
				'data' => array(
					'id' =>$rsp['data'][0]->id,
					'type'=>$rsp['data'][0]->type,
					'token' => $token['token'],
					
				)
			);
		}else{
			$rsp = array('rsp'=>false,'msg'=>'Bilgiler Yanlış !!');
		}
        return $this->output
		->set_content_type('application/json')
		->set_status_header(200)
		->set_output(json_encode($rsp));
	}

	

}
