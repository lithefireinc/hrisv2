<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Dashboard extends CI_Controller{

    function Dashboard(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->database();


        $this->load->model('login_model','login',TRUE);
        $this->load->model('commonmodel', '', TRUE);
        /* check whether login or not */
        if(!$this->login->check_session()){
        redirect('main/');
        }
       // $this->load->scaffolding('entries');

        //print(uri_string());
        $this->load->library('layout', array('layout'=>'layouts/infobahn_hrisv2_layout'));
    }
    function index(){
        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        
        $this->layout->view('infobahn_hrisv2/dashboard_view', $data);
        
    }

    function accessDenied(){
        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';


        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        $this->load->view('header_view', $data);
        $this->load->view('menu_view', $data);
        $this->load->view('access_denied_view', $data);
        //$this->load->view('login_view');
        $this->load->view('footer_view', $data);
    }

    function getModuleGroup(){
        $db = "default";
        $table = "module_group";
        $fields = "*";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "id";
            $dir = "DESC";
        }

        if(!empty($query)){
            $queryby = array("description");

        }
        $filter = "";
        $join = array();
        //$filter = array("is_delete"=>0);

        $records = array();
        $records = $this->commonmodel->getFilteredRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit, $filter, $join);



        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query, $filter);
        die(json_encode($data));
    }

    function insertModuleGroup(){
        $db = "default";
        $table = "module_group";

        $input = array();
        foreach($this->input->post() as $key => $val){
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        $data = $this->commonmodel->insertRecord($db, $table, $input);
        die(json_encode($data));

    }

    function loadModuleGroup(){

        $db = "default";
        $table = "module_group";
        $param = "id";
        $fields = "*";

        $id=$this->input->post('id');



        $records = array();
        $records = $this->commonmodel->getRecordWhere($db, $table, $param, $id, $fields);



        $temp = array();

        foreach($records as $row):

            $data["data"] = $row;


        endforeach;

       // $data['data'] = $temp;
        $data['success'] = true;

        die(json_encode($data));
    }

    function updateModuleGroup(){
        $db = "default";
        $table = "module_group";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $input = array();
        foreach($this->input->post() as $key => $val){
            if(!empty($val)){
                $input[$key] = $val;

            }
        }

        $records = array();
        $data = $this->commonmodel->updateRecord($db, $table, $input, $param, $id);


        die(json_encode($data));
    }

    function deleteModuleGroup(){
        $db = "default";
        $table = "module_group";
        $param = "id";

        $id=$this->input->post('id');



        $data = $this->commonmodel->deleteRecord($db, $table, $param, $id);


        die(json_encode($data));
    }

    function getModuleGroupUsers(){
        $db = "default";
        $table = "module_group_users";
        $fields = "*";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";
        $id = $this->input->post('id');

        if(empty($sort) && empty($dir)){
            $sort = "username";
            $dir = "DESC";
        }

        if(!empty($query)){
            $queryby = array("username");

        }
       // $filter = "";
        $filter = array("group_id"=>$id);
        $join = array();

        $records = array();
        $records = $this->commonmodel->getFilteredRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit, $filter, $join);



        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query, $filter);
        die(json_encode($data));
    }

    function insertModuleGroupUsers(){
        $db = "default";
        $table = "module_group_users";

        $input = array();
        foreach($this->input->post() as $key => $val){
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        $data = $this->commonmodel->insertRecord($db, $table, $input);
        die(json_encode($data));

    }

    function deleteModuleGroupUsers(){
        $db = "default";
        $table = "module_group_users";
        $param = "id";

        $id=$this->input->post('id');



        $data = $this->commonmodel->deleteRecord($db, $table, $param, $id);


        die(json_encode($data));
    }

    function getUserName(){
        $db = "default";
        $table = "tbl_employee_info";
        $fields = "*";

        $this->load->model('employee_model', 'employee', TRUE);


        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        $id = $this->input->post('id');

        if(empty($sort) && empty($dir)){
            $sort = "username";
            $dir = "ASC";
        }

        if(!empty($query)){
            $queryby = array("username");

        }




        $records = array();
        $records = $this->employee->getModuleUsers($id, $start, $limit, $sort, $dir, $query, $queryby);

        $where = "username NOT IN (SELECT username from module_group_users WHERE group_id = $id)";


        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getFilteredNumRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit, $where, array());
        die(json_encode($data));
    }

    function getModuleGroupAccess(){
        $db = "default";
        $table = "module_group_access";
        $fields = "module_group_access.id, module.description as module, b.description as category";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";
        $id = $this->input->post('id');

        if(empty($sort) && empty($dir)){
            $sort = "module.description";
            $dir = "ASC";
        }

        if(!empty($query)){
            $queryby = array("description");

        }
        $filter = array("module.is_public"=>0, "module_group_access.group_id"=>$id);
        //$filter = array("is_delete"=>0);

        $join = array("module"=>"module.id = module_group_access.module_id", "module_category b"=>"module.category_id = b.id");

        $records = array();
        $records = $this->commonmodel->getFilteredRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit, $filter, $join);



        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getFilteredNumRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit, $filter, $join);
        die(json_encode($data));
    }

    function insertModuleGroupAccess(){
        $db = "default";
        $table = "module_group_access";
        $group_id = $this->input->post("groupid");

        /*$input = array();
        foreach($this->input->post() as $key => $val){
            if(!empty($val)){
                $input[$key] = $val;
            }
        }*/
        $selected_items_json = $this->input->post('selected_items');

	$selected_items_json = str_replace("\\", "", $selected_items_json);
	$selected_item = json_decode($selected_items_json);

	if(empty($selected_item)){
		die(json_encode(array("success"=> false, "data" => "Unable to retrieve selected item.")));
	}
        $input = array();
        $input['group_id'] = $group_id;
        foreach($selected_item->data as $key => $value){
			try{
			$input['module_id'] = $value;
                        $data = $this->commonmodel->insertRecord($db, $table, $input);
			}catch(Exception $e){
				continue;
			}
	}


        die(json_encode($data));

    }

    function deleteModuleGroupAccess(){
        $db = "default";
        $table = "module_group_users";
        $param = "id";

        $id=$this->input->post('id');



        $data = $this->commonmodel->deleteRecord($db, $table, $param, $id);


        die(json_encode($data));
    }

    function getModule(){
        $db = "default";
        $table = "module";
        $fields = "*";
        $this->load->model('module_model', 'module', TRUE);

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";
        $id = $this->input->post('id');

        if(empty($sort) && empty($dir)){
            $sort = "module.description";
            $dir = "ASC";
        }

        if(!empty($query)){
            $queryby = array("module.description");

        }

        //$filter = array("is_delete"=>0);


        $records = array();
        $records = $this->module->getFilteredModule($id, $start, $limit, $sort, $dir, $query, $queryby);
        $where = "module.id NOT IN (SELECT module_id from module_group_access WHERE group_id = $id) AND module.is_public = 0";


        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getFilteredNumRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit, $where, array());
        die(json_encode($data));
    }
}
?>