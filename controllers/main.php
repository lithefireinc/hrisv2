<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Main extends CI_Controller{

    function Main(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->database();
        $this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->model('lithefire_model','lithefire',TRUE);
		$this->load->library('layout', array('layout'=>$this->config->item('layout_file')));
       // $this->load->scaffolding('entries');
    }
    function index(){
    	
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('main/login', 'refresh');
		}
		else
		{
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$data['header'] = 'Header Section';
            $data['footer'] = 'Footer Section';
			$data['title'] = "HRIS: Dashboard";
            $data['userId'] = $this->session->userData('userId');
            $data['userName'] = $this->session->userData('userName');

        
            $this->layout->view('main/dashboard_view', $data);

		}
		

    }

    function login(){
    	
	   $this->data['title'] = "Login";

		//validate form input
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true)
		{ //check to see if the user is logging in
			//check for "remember me"
			//$remember = (bool) $this->input->post('remember');
			$remember = FALSE;


			if ($this->ion_auth->login($this->input->post('username'), $this->input->post('password'), $remember))
			{ //if the login is successful
				//redirect them back to the home page
				//$this->session->set_flashdata('message', $this->ion_auth->messages());
				$data['success'] = true;
           		$data['errorMsg'] = "Login Successful. Redirecting...";
           		$data['link'] = site_url("main");
           		die(json_encode($data));
			}
			else
			{ //if the login was un-successful
				//redirect them back to the login page
				$data['success'] = false;
           		$data['errorMsg'] = "You have entered an invalid username/password";
          		// $data['link'] = "http://www.pixelcatalyst.net/hrisv2/dashboard/";
           		die(json_encode($data));
			}
		}
		else
		{  //the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
			);

			$this->load->view('login_view', $this->data);
		}
		/*
       $userName = $this->input->post('username');
       $password = $this->input->post('password');
       $chkAuth = $this->login->checkAuth($userName,$password);

       if($chkAuth){

       //redirect('/main'); //load cpanel file – authentication successful
           $data['success'] = true;
           $data['errorMsg'] = "Login Successful. Redirecting...";
           $data['link'] = site_url("dashboard");
           die(json_encode($data));
       }else{
           $data['success'] = false;
           $data['errorMsg'] = "You have entered an invalid username/password";
          // $data['link'] = "http://www.pixelcatalyst.net/hrisv2/dashboard/";
           die(json_encode($data));
       }*/

    }

    function logout(){
       $this->data['title'] = "Logout";

		//log the user out
		$logout = $this->ion_auth->logout();

		//redirect them back to the page they came from
		redirect('main/login', 'refresh');
    }

    function maintenance(){
       $data['header'] = 'Header Section';
       $data['footer'] = 'Footer Section';

        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        $this->load->view('header_view', $data);
        $this->load->view('menu_view', $data);
        $this->load->view('maintenance_view');
        $this->load->view('footer_view', $data);
    }

}
?>