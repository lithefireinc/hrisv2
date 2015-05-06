<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Hr extends MY_Controller{

    function Hr(){
        parent::__construct();
  
       // $this->load->scaffolding('entries');
    }
    function index(){

        $data['title'] = 'HRIS: Employee Information';
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');
        $data['query'] = $this->db->last_query();

        #$this->load->view('header_view', $data);
        #$this->load->view('menu_view', $data);
        $this->layout->view('hr/hr_view', $data);
        #$this->load->view('footer_view', $data);
    }

    function getRecords(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "sms";
        $table = "filereferredby";
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
        $records = array();
        $records = $this->commonmodel->getAllRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit);



        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = array("id"=>$row['id'], "description"=>$row['description']);


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query);
        die(json_encode($data));
    }

    function getEmployees(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_employee_info";
        $fields = array("id", "firstname", "lastname", "middlename", "gender", "civil_status", "email", "birthdate", "birth_place", "address", "provincial_address");

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
            $queryby = array("id", "firstname", "lastname");

        }

        $filter = array("is_delete"=>0);

        $records = array();
        $records = $this->commonmodel->getFilteredRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit, $filter);



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

    function insertReferredBy(){
        $db = "sms";
        $table = "filereferredby";

        $this->load->model('commonmodel', '', TRUE);
        $data = $this->commonmodel->insertRecord($db, $table, $this->input->post());
        die(json_encode($data));

    }

    function loadReferredBy(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "sms";
        $table = "filereferredby";
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

	

    function getDepartment(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "fr";
        $table = "filedept";
        $fields = "*";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "dept_type";
            $dir = "ASC";
        }

        if(!empty($query)){
            $queryby = array("dept_type");

        }
        $records = array();
        $records = $this->commonmodel->getAllRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit);



        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query);
        die(json_encode($data));
    }

    function getSubDepartment(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "fr";
        $table = "filesubdepartment";
        $fields = "*";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "dept_type";
            $dir = "ASC";
        }

        if(!empty($query)){
            $queryby = array("dept_type");

        }
        $records = array();
        $records = $this->commonmodel->getAllRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit);



        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query);
        die(json_encode($data));
    }

    function getPosition(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "fr";
        $table = "fileposi";
        $fields = "*";

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
        $records = array();
        $records = $this->commonmodel->getAllRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit);



        $temp = array();
        if($records){
        foreach($records as $row):
            $row['name'] = $row['description'];
            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query);
        die(json_encode($data));
    }

    function getTrainingType(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "fr";
        $table = "filetrainingtype";
        $fields = "*";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "trainingtype";
            $dir = "ASC";
        }

        if(!empty($query)){
            $queryby = array("trainingtype");

        }
        $records = array();
        $records = $this->commonmodel->getAllRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit);



        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = array("id"=>$row['filetrainingtypeid'], "name"=>$row['trainingtype']);


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query);
        die(json_encode($data));
    }

    function getSupplier(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "supplier";
        $fields = "*";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "SUPPLIERNAME";
            $dir = "ASC";
        }

        if(!empty($query)){
            $queryby = array("SUPPLIERNAME");

        }
        $records = array();
        $records = $this->commonmodel->getAllRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit);



        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = array("id"=>$row['SUPPIDNO'], "name"=>$row['SUPPLIERNAME'], "address"=>$row['ADDRESS01']."\n".$row['ADDRESS02']."\n".$row['ADDRESS03']);


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query);
        die(json_encode($data));
    }

    function getEmployeeCategory(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "fr";
        $table = "fileemca";
        $fields = "*";

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
        $records = array();
        $records = $this->commonmodel->getAllRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit);



        $temp = array();
        if($records){
        foreach($records as $row):

            $row['name'] = $row['description'];
            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query);
        die(json_encode($data));
    }

    function getEmployeeStatus(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "fr";
        $table = "fileemst";
        $fields = "*";

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
        $records = array();
        $records = $this->commonmodel->getAllRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit);



        $temp = array();
        if($records){
        foreach($records as $row):

            $row['name'] = $row['description'];
            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query);
        die(json_encode($data));
    }


    //
    function insertEmployee(){
        $db = "default";
        $table = "tbl_employee_info";
        $this->load->model('commonmodel', '', TRUE);
		$this->load->model('lithefire_model', 'lithefire', TRUE);
        //$input = $this->input->post();
        $input = array();
        foreach($this->input->post() as $key => $val){
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        $firstname = $input['firstname'];
        $lastname = $input['lastname'];

        //$input['username'] = strtolower($firstname.".".$lastname);
        $input['password'] = md5("12345678");
       // $input['department'] = $this->commonmodel->getFieldWhere($db, "filedepartment", "description", $input['department'], "id");
      //  $input['position'] = $this->commonmodel->getFieldWhere($db, "fileposition", "description", $input['position'], "id");
       // $input['employee_category'] = $this->commonmodel->getFieldWhere($db, "fileemployeecategory", "description", $input['employee_category'], "id");
        //$input['employee_status'] = $this->commonmodel->getFieldWhere($db, "fileemployeestatus", "description", $input['employee_status'], "id");
        
        $data = $this->commonmodel->insertRecord($db, $table, $input, 1);
		
		$this->lithefire->insertRow($db, "tbl_user", array("id"=>$data['id'], "username"=>$input['username'], "password"=>$input['password']));
        die(json_encode($data));

    }

    function loadEmployee(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_employee_info";
        $param = "id";
        $fields = "*";

        $id=$this->input->post('id');



        $records = array();
        $records = $this->commonmodel->getRecordWhere($db, $table, $param, $id, $fields);

		

        $temp = array();

        foreach($records as $row):
            $row['department_description'] = $this->commonmodel->getFieldWhere("fr", "filedept", "dept_idno", $row['department'], "dept_type");
            $row['position_description'] = $this->commonmodel->getFieldWhere("fr", "fileposi", "id", $row['position'], "description");
            $row['employee_category_description'] = $this->commonmodel->getFieldWhere("fr", "fileemca", "id", $row['employee_category'], "description");
            $row['employee_status_description'] = $this->commonmodel->getFieldWhere("fr", "fileemst", "id", $row['employee_status'], "description");
        	$row['CITIZENSHIP'] = $this->commonmodel->getFieldWhere("fr", "FILECITI", "CITIIDNO", $row['CITIIDNO'], "CITIZENSHIP");
            $data["data"] = $row;


        endforeach;

       // $data['data'] = $temp;
        $data['success'] = true;

        die(json_encode($data));
    }

    function updateEmployee(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_employee_info";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $input = array();
        foreach($this->input->post() as $key => $val){
            if(!empty($val)){
                $input[$key] = $val;
                if($key == 'password')
                    $input[$key] = md5($val);
            }
        }

        //$firstname = $input['firstname'];
        //$lastname = $input['lastname'];

        //$input['username'] = strtolower($firstname.".".$lastname);
        //$input['password'] = md5("12345678");
       // $input['department'] = $this->commonmodel->getFieldWhere($db, "filedepartment", "description", $input['department'], "id");
      //  $input['position'] = $this->commonmodel->getFieldWhere($db, "fileposition", "description", $input['position'], "id");
       // $input['employee_category'] = $this->commonmodel->getFieldWhere($db, "fileemployeecategory", "description", $input['employee_category'], "id");
       // $input['employee_status'] = $this->commonmodel->getFieldWhere($db, "fileemployeestatus", "description", $input['employee_status'], "id");




        $records = array();
        $data = $this->commonmodel->updateRecord($db, $table, $input, $param, $id);


        die(json_encode($data));
    }

    function deleteEmployee(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = "default";
        $table = "tbl_employee_info";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');
		$filter = "$param = $id";
        $input = array("is_delete"=>1);

        $data = $this->lithefire->updateRow($db, $table, $input, $filter);


        die(json_encode($data));
    }

    function getEmployment(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_employment_history";
        $fields = "*";

        $employee_id = $this->input->post('employee_id');

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "date_start";
            $dir = "DESC";
        }

        if(!empty($query)){
            $queryby = array("id", "firstname", "lastname");

        }

        $filter = array("employee_id"=>$employee_id);

        $records = array();
        $records = $this->commonmodel->getFilteredRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit, $filter);



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

    function insertEmployment(){
        $db = "default";
        $table = "tbl_employment_history";
        $this->load->model('commonmodel', '', TRUE);
        //$input = $this->input->post();
        $input = array();
        foreach($this->input->post() as $key => $val){
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        $data = $this->commonmodel->insertRecord($db, $table, $input);
        die(json_encode($data));

    }

    function loadEmployment(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_employment_history";
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

    function updateEmployment(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_employment_history";
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

    function deleteEmployment(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_employment_history";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

       // $input = array("is_delete"=>1);

        $data = $this->commonmodel->deleteRecord($db, $table, $param, $id);


        die(json_encode($data));
    }

    function getCourses(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "filecourse";
        $fields = "*";

        $employee_id = $this->input->post('employee_id');

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
            $queryby = array("id", "description");

        }

       // $filter = array("employee_id"=>$employee_id);

        $records = array();
        $records = $this->commonmodel->getAllRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit);



        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query);
        die(json_encode($data));
    }

    function insertCourse(){
        $db = "default";
        $table = "filecourse";
        $this->load->model('commonmodel', '', TRUE);
        //$input = $this->input->post();
        $input = array();
        foreach($this->input->post() as $key => $val){
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        $data = $this->commonmodel->insertRecord($db, $table, $input);
        die(json_encode($data));

    }

    function getSchools(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "fileschool";
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
            $queryby = array("id", "description", "abbreviation");

        }

       // $filter = array("employee_id"=>$employee_id);

        $records = array();
        $records = $this->commonmodel->getAllRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit);



        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query);
        die(json_encode($data));
    }

    function insertSchool(){
        $db = "default";
        $table = "fileschool";
        $this->load->model('commonmodel', '', TRUE);
        //$input = $this->input->post();
        $input = array();
        foreach($this->input->post() as $key => $val){
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        $data = $this->commonmodel->insertRecord($db, $table, $input);
        die(json_encode($data));

    }

    function getEducation(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_educational_background";
        $fields = "*";

        $employee_id = $this->input->post('employee_id');

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "date_start";
            $dir = "DESC";
        }

        if(!empty($query)){
            $queryby = array("id", "school_id", "course_id");

        }

        $filter = array("employee_id"=>$employee_id);

        $records = array();
        $records = $this->commonmodel->getFilteredRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit, $filter);



        $temp = array();
        if($records){
        foreach($records as $row):
            $row['school'] = $this->commonmodel->getFieldWhere($db, "fileschool", "id", $row['school_id'], "description");
            $row['course'] = $this->commonmodel->getFieldWhere($db, "filecourse", "id", $row['course_id'], "description");

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query, $filter);
        die(json_encode($data));
    }

    function insertEducation(){
        $db = "default";
        $table = "tbl_educational_background";
        $this->load->model('commonmodel', '', TRUE);
        //$input = $this->input->post();
        $input = array();
        $temp_input = $this->input->post();
        $temp_input['school_address2'] = null;
        foreach($temp_input as $key => $val){
            if(!empty($val)){
                $input[$key] = $val;
            }
        }




        $input['school_id'] = $this->commonmodel->getFieldWhere($db, "fileschool", "description", $input['school_id'], "id");
        $input['course_id'] = $this->commonmodel->getFieldWhere($db, "filecourse", "description", $input['course_id'], "id");


        $data = $this->commonmodel->insertRecord($db, $table, $input);
        die(json_encode($data));

    }

    function loadEducation(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_educational_background";
        $param = "id";
        $fields = "*";

        $id=$this->input->post('id');



        $records = array();
        $records = $this->commonmodel->getRecordWhere($db, $table, $param, $id, $fields);



        $temp = array();

        foreach($records as $row):
            $row['school_address2'] = $this->commonmodel->getFieldWhere($db, "fileschool", "id", $row['school_id'], "school_address");
             $row['school_id'] = $this->commonmodel->getFieldWhere($db, "fileschool", "id", $row['school_id'], "description");
            $row['course_id'] = $this->commonmodel->getFieldWhere($db, "filecourse", "id", $row['course_id'], "description");
            $row['school_date_start'] = $row['date_start'];
            $row['school_date_end'] = $row['date_end'];
            $data["data"] = $row;


        endforeach;

       // $data['data'] = $temp;
        $data['success'] = true;

        die(json_encode($data));
    }

    function updateEducation(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_educational_background";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');
        $temp_input = $this->input->post();
        $temp_input['school_address2'] = null;
        $input = array();
        foreach($temp_input as $key => $val){
            if(!empty($val)){
                $input[$key] = $val;

            }
        }



        $input['school_id'] = $this->commonmodel->getFieldWhere($db, "fileschool", "description", $input['school_id'], "id");
        $input['course_id'] = $this->commonmodel->getFieldWhere($db, "filecourse", "description", $input['course_id'], "id");
        

        $records = array();
        $data = $this->commonmodel->updateRecord($db, $table, $input, $param, $id);


        die(json_encode($data));
    }

    function deleteEducation(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_educational_background";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

       // $input = array("is_delete"=>1);

        $data = $this->commonmodel->deleteRecord($db, $table, $param, $id);


        die(json_encode($data));
    }

    function getTraining(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_training";
        $fields = "*";

        $employee_id = $this->input->post('employee_id');

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "date_start";
            $dir = "DESC";
        }

        if(!empty($query)){
            $queryby = array("title");

        }

        $filter = array("employee_id"=>$employee_id);

        $records = array();
        $records = $this->commonmodel->getFilteredRecords($db, $table, $sort, $dir, $queryby, $query,  $fields, $start, $limit, $filter);



        $temp = array();
        if($records){
        foreach($records as $row):
           // $row['school'] = $this->commonmodel->getFieldWhere($db, "fileschool", "id", $row['school_id'], "description");
          //  $row['course'] = $this->commonmodel->getFieldWhere($db, "filecourse", "id", $row['course_id'], "description");

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query, $filter);
        die(json_encode($data));
    }

    function insertTraining(){
        $db = "default";
        $table = "tbl_training";
        $this->load->model('commonmodel', '', TRUE);
        //$input = $this->input->post();
        $input = array();
        $temp_input = $this->input->post();
       // $temp_input['school_address2'] = null;
        foreach($temp_input as $key => $val){
            if(!empty($val)){
                $input[$key] = $val;
            }
        }




        $input['supplier_id'] = $this->commonmodel->getFieldWhere($db, "supplier", "SUPPLIERNAME", $input['supplier_id'], "SUPPIDNO");
        $input['training_type_id'] = $this->commonmodel->getFieldWhere($db, "filetrainingtype", "description", $input['training_type_id'], "id");


        $data = $this->commonmodel->insertRecord($db, $table, $input);
        die(json_encode($data));

    }

    function loadTraining(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_training";
        $param = "id";
        $fields = "*";

        $id=$this->input->post('id');



        $records = array();
        $records = $this->commonmodel->getRecordWhere($db, $table, $param, $id, $fields);



        $temp = array();

        foreach($records as $row):
           // $row['school_address2'] = $this->commonmodel->getFieldWhere($db, "fileschool", "id", $row['school_id'], "school_address");
             $row['training_type_id'] = $this->commonmodel->getFieldWhere($db, "filetrainingtype", "id", $row['training_type_id'], "description");
            $row['supplier_id'] = $this->commonmodel->getFieldWhere($db, "supplier", "SUPPIDNO", $row['supplier_id'], "SUPPLIERNAME");

            $data["data"] = $row;


        endforeach;

       // $data['data'] = $temp;
        $data['success'] = true;

        die(json_encode($data));
    }

    function updateTraining(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_training";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');
        $temp_input = $this->input->post();
        //$temp_input['school_address2'] = null;
        $input = array();
        foreach($temp_input as $key => $val){
            if(!empty($val)){
                $input[$key] = $val;

            }
        }



        $input['supplier_id'] = $this->commonmodel->getFieldWhere($db, "supplier", "SUPPLIERNAME", $input['supplier_id'], "SUPPIDNO");
        $input['training_type_id'] = $this->commonmodel->getFieldWhere($db, "filetrainingtype", "description", $input['training_type_id'], "id");


        $records = array();
        $data = $this->commonmodel->updateRecord($db, $table, $input, $param, $id);


        die(json_encode($data));
    }

    function deleteTraining(){
        $this->load->model('commonmodel', '', TRUE);
        $db = "default";
        $table = "tbl_training";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

       // $input = array("is_delete"=>1);

        $data = $this->commonmodel->deleteRecord($db, $table, $param, $id);


        die(json_encode($data));
    }
	
	function getCitizenshipCombo(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = "fr";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');
        //$db = "fr";


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";

        if(empty($sort) && empty($dir)){
            $sort = "CITIZENSHIP ASC";
            
        }

        $records = array();
        $table = "FILECITI";
        $fields = array("CITIIDNO as id", "CITIZENSHIP as name");

        $filter = array("ACTIVATED"=>1);

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, "", "");
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):
          //  $row['COURSE'] = $this->commonmodel->getFieldWhere($db, "FILECOUR", "COURIDNO", $row['COURIDNO'], "COURSE");
            $temp[] = $row;
            $total++;

        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, "");
        die(json_encode($data));
    }
}
?>