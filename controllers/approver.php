<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Approver extends MY_Controller{

    function approver(){
        parent::__construct();

    }

    function index(){

        
        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        
        $this->layout->view('approver/index_view', $data);
        
        
    }

    function getRecords()
    {
        $db = "default";
        $table = $this->input->post('table');
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
    
    function insertRecord()
    {
        $db = "default";
        $table = $this->input->post("table");
        $input = array();
        foreach($this->input->post() as $key => $val){
            if($key == 'table')
                continue;
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        $data = $this->commonmodel->insertRecord($db, $table, $input);
        die(json_encode($data));
    }

    function insertAppGroupMember()
    {
    	$this->load->model('lithefire_model', 'lithefire', TRUE);
        
        $db = "default";
        $table = "tbl_app_group_members";
		
        $input = array();
        $input['employee_id'] = $this->input->post('username');
        $input['app_group_id'] = $this->input->post('app_group_id');
        $input['start_date'] = date("Y-m-d");
		
		if($this->lithefire->countFilteredRows($db, $table, "employee_id = '".$input['employee_id']."' AND app_group_id = '".$input['app_group_id']."' AND end_date is NULL", "")){
            $data['success'] = false;
            $data['data'] = "Username already exists";
            die(json_encode($data));
        }
		
        $data = $this->lithefire->insertRow($db, $table, $input);
        die(json_encode($data));
    }

    function getAppGroupMembers()
    {

        $this->load->model('app_group_model', '', TRUE);
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');

        $app_group_id = $this->input->post('app_group_id');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "emp_name";
            $dir = "ASC";
        }

        $records = array();
        $records = $this->app_group_model->getAllAppGroupMembers($app_group_id, $start, $limit, $sort, $dir, $query);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):

            $temp[] = $row;
            $total++;

        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->app_group_model->countAppGroupMembers($app_group_id);
        die(json_encode($data));
    }

    function getUsers()
    {
        $db = "default";
        $table = "tbl_employee_info";
        $fields = "*";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "username";
            $dir = "ASC";
        }

        if(!empty($query)){
            $queryby = array("firstname", "lastname", "username", "id");

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

    function getAppGroup()
    {
        $db = "default";
        $table = "tbl_app_group";
        $fields = "id, description as name";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "description";
            $dir = "ASC";
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

    function getAppGroupParent()
    {

        $this->load->model('app_tree_model', '', TRUE);
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');

        $app_tree_id = $this->input->post('app_tree_id');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "b.description";
            $dir = "ASC";
        }

        $records = array();
        $records = $this->app_tree_model->getAppGroupParent($app_tree_id, $start, $limit, $sort, $dir, $query);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):

            $temp[] = $row;
            $total++;

        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = 1;
        die(json_encode($data));
    }

    function getAppType()
    {
        $db = "default";
        $table = "tbl_app_type";
        $fields = "id, description as name";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "description";
            $dir = "ASC";
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

    function getAppTree()
    {
        $db = "default";
        $table = "tbl_app_tree";
        $fields = "id, description as name";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "description";
            $dir = "ASC";
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

    function getEmpGroupMembers()
    {

        $this->load->model('emp_group_model', '', TRUE);
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');

        $emp_group_id = $this->input->post('emp_group_id');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "emp_name";
            $dir = "ASC";
        }

        $records = array();
        $records = $this->emp_group_model->getAllEmpGroupMembers($emp_group_id, $start, $limit, $sort, $dir, $query);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):

            $temp[] = $row;
            $total++;

        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->emp_group_model->countEmpGroupMembers($emp_group_id);
        die(json_encode($data));
    }

    function insertEmpGroupMember()
    {
		$this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = "default";
        $table = "tbl_employee_group_members";
        $input = array();
        $input['employee_id'] = $this->input->post('username');
        $input['employee_group_id'] = $this->input->post('emp_group_id');
        $input['start_date'] = date("Y-m-d");
        
		
		if($this->lithefire->countFilteredRows($db, $table, "employee_id = '".$input['employee_id']."' AND end_date is NULL", "")){
            $data['success'] = false;
            $data['data'] = "Username is already a member of an employee group";
            die(json_encode($data));
        }
		$data = $this->lithefire->insertRow($db, $table, $input);
		
        die(json_encode($data));
    }

    function getAppTreeDetails()
    {

        $this->load->model('app_tree_model', '', TRUE);
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');

        $app_tree_id = $this->input->post('app_tree_id');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "a.id";
            $dir = "ASC";
        }

        $records = array();
        $records = $this->app_tree_model->getAppTreeDetails($app_tree_id, $start, $limit, $sort, $dir, $query);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):

            $temp[] = $row;
            $total++;

        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->app_tree_model->countAppTreeDetails($app_tree_id);
        die(json_encode($data));
    }

    function insertAppTreeDetail()
    {

        $db = "default";
        $table = "tbl_app_tree_details";
        $input = array();
        $input['app_group_id'] = $this->input->post('appgroup');
        $input['app_tree_id'] = $this->input->post('app_tree_id');
        $parent = $this->input->post('parent');
        if(!empty($parent)){
        if($parent == $input['app_group_id']){
        	$data['success'] = false;
			$data['data'] = "Parent field cannot be the same with approver group";
			die(json_encode($data));
        }
        $input['parent'] = $parent;
		}
        $data = $this->commonmodel->insertRecord($db, $table, $input);
        die(json_encode($data));
    }

    function insertAppFlow()
    {
		$this->load->model("lithefire_model", "lithefire", TRUE);
        $app_tree = $this->input->post('app_tree');
        $app_type = $this->input->post('app_type');

        $db = "default";
        $table = "tbl_app_flow";
        $input = array();
        $input['employee_group_id'] = $this->input->post('emp_group_id');
        $input['app_type_id'] = $this->commonmodel->getFieldWhere($db, "tbl_app_type", "description", $app_type, "id");
        $input['app_tree_id'] = $this->commonmodel->getFieldWhere($db, "tbl_app_tree", "description", $app_tree, "id");
		
		if($this->lithefire->countFilteredRows($db, $table, "employee_group_id = '".$input['employee_group_id']."' AND app_type_id = ".$input['app_type_id'], "")){
            $data['success'] = false;
            $data['data'] = "Application flow for $app_type is already set";
            die(json_encode($data));
        }

        $data = $this->commonmodel->insertRecord($db, $table, $input);
        die(json_encode($data));
    }
    
    function getAppFlow()
    {

        $this->load->model('emp_group_model', '', TRUE);
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');

        $emp_group_id = $this->input->post('emp_group_id');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "app_type";
            $dir = "ASC";
        }

        $records = array();
        $records = $this->emp_group_model->getAppFlow($emp_group_id, $start, $limit, $sort, $dir, $query);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):

            $temp[] = $row;
            $total++;

        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->emp_group_model->countAppFlow($emp_group_id);
        die(json_encode($data));
    }

	function loadAppGroup(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = "default";
        

        $id=$this->input->post('id');
        $table = "tbl_app_group";

        $filter = "id = '$id'";
        $fields = array("id", "description");

        $records = array();
        $records = $this->lithefire->getRecordWhere($db, $table, $filter, $fields);

        $temp = array();

        foreach($records as $row):

            $data["data"] = $row;


        endforeach;
        $data['success'] = true;

        die(json_encode($data));
    }
	
	function updateAppGroup(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = 'default';

        $table = "tbl_app_group";
        
       // $fields = $this->input->post();

        $id=$this->input->post('id');
        $filter = "id = '$id'";

        $input = array();
        foreach($this->input->post() as $key => $val){
            if($key == 'id')
                continue;
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        if($this->lithefire->countFilteredRows($db, $table, "description = '".$this->input->post("description")."' AND id != '$id'", "")){
            $data['success'] = false;
            $data['data'] = "Record already exists";
            die(json_encode($data));
        }


        $data = $this->lithefire->updateRow($db, $table, $input, $filter);


        die(json_encode($data));
    }
	
	function expireAppGroupMember(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = 'default';

        $table = "tbl_app_group_members";
        
       // $fields = $this->input->post();

        $id=$this->input->post('id');
		//$app_group_id=$this->input->post('app_group_id');
		$today = date('Y-m-d');
		
        $filter = "id = '$id'";

        $input = array("end_date"=>$today);
        

        if($this->lithefire->countFilteredRows($db, $table, $filter." AND end_date is not NULL", "")){
            $data['success'] = false;
            $data['data'] = "Member already removed from this approver group";
            die(json_encode($data));
        }


        $data = $this->lithefire->updateRow($db, $table, $input, $filter);
		$data['data'] = "Member successfully removed";

        die(json_encode($data));
    }
	
	function loadEmpGroup(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = "default";
        

        $id=$this->input->post('id');
        $table = "tbl_employee_group";

        $filter = "id = '$id'";
        $fields = array("id", "description");

        $records = array();
        $records = $this->lithefire->getRecordWhere($db, $table, $filter, $fields);

        $temp = array();

        foreach($records as $row):

            $data["data"] = $row;


        endforeach;
        $data['success'] = true;

        die(json_encode($data));
    }
	
	function updateEmpGroup(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = 'default';

        $table = "tbl_employee_group";
        
       // $fields = $this->input->post();

        $id=$this->input->post('id');
        $filter = "id = '$id'";

        $input = array();
        foreach($this->input->post() as $key => $val){
            if($key == 'id')
                continue;
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        if($this->lithefire->countFilteredRows($db, $table, "description = '".$this->input->post("description")."' AND id != '$id'", "")){
            $data['success'] = false;
            $data['data'] = "Record already exists";
            die(json_encode($data));
        }


        $data = $this->lithefire->updateRow($db, $table, $input, $filter);


        die(json_encode($data));
    }
	
	function expireEmpGroupMember(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = 'default';

        $table = "tbl_employee_group_members";
        
       // $fields = $this->input->post();

        $id=$this->input->post('id');
		//$app_group_id=$this->input->post('app_group_id');
		$today = date('Y-m-d');
		
        $filter = "id = '$id'";

        $input = array("end_date"=>$today);
        

        if($this->lithefire->countFilteredRows($db, $table, $filter." AND end_date is not NULL", "")){
            $data['success'] = false;
            $data['data'] = "Member already removed from this employee group";
            die(json_encode($data));
        }


        $data = $this->lithefire->updateRow($db, $table, $input, $filter);
		$data['data'] = "Member successfully removed";

        die(json_encode($data));
    }
	
	function deleteAppFlow(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = 'default';

        $table = "tbl_app_flow";


        $id=$this->input->post('id');

		
        $filter = "id = '$id'";

        $data = $this->lithefire->deleteRow($db, $table, $filter);
		$data['data'] = "App Flow successfully deleted";

        die(json_encode($data));
    }
	
	function deleteAppTreeDetail(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = 'default';

        $table = "tbl_app_tree_details";


        $id=$this->input->post('id');
		$app_tree_id = $this->input->post('app_tree_id');
		$app_group_id = $this->input->post('app_group_id');
		
        $filter = "id = '$id'";
        
        if($this->lithefire->countFilteredRows($db, $table, "app_tree_id = '$app_tree_id' AND parent = '$app_group_id'", "")){
            $data['success'] = false;
            $data['data'] = "Can't delete app groups with reference as parent";
            die(json_encode($data));
        }

        $data = $this->lithefire->deleteRow($db, $table, $filter);
		$data['data'] = "Approver group successfully removed";

        die(json_encode($data));
    }

}
?>