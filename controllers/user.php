<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class User extends MY_Controller{

    function User(){
        parent::__construct();
 
    }
	
    function index(){

        $data['title'] = 'HRIS: User Profile';


         $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');
		$data['employee_id'] = $this->session->userdata('employee_id');

        
        $this->layout->view('user/profile_view', $data);
        
    }

    function changePassword(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = "default";
        $table = "tbl_employee_info";

        $id = $this->input->post('id');
        $password = md5($_POST['oldpass']);
        $new_password = md5($_POST['pass']);

        if(!$this->lithefire->countFilteredRows($db, $table, "id = '$id' AND password = '$password'", "")){
            $data['success'] = false;
            $data['data'] = "Old password does not match";
            die(json_encode($data));
        }

        $input = array("password"=>$new_password);
        $filter = "id = '$id'";

        $data = $this->lithefire->updateRow($db, $table, $input, $filter);
        $data['data'] = "Password Successfully changed";

        die(json_encode($data));
        
    }

    function getRecords(){
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
        $db = "default";
        $table = "filedept";
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

            $temp[] = array("id"=>$row['DEPTIDNO'], "name"=>$row['description']);


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query);
        die(json_encode($data));
    }

    function getPosition(){
        $db = "default";
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

            $temp[] = array("id"=>$row['POSIIDNO'], "name"=>$row['description']);


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query);
        die(json_encode($data));
    }

    function getTrainingType(){
        $db = "default";
        $table = "filetrainingtype";
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

            $temp[] = array("id"=>$row['id'], "name"=>$row['description']);


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query);
        die(json_encode($data));
    }

    function getSupplier(){
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
        $db = "default";
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

            $temp[] = array("id"=>$row['EMCAIDNO'], "name"=>$row['description']);


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->commonmodel->getNumRecords($db, $table, $queryby, $query);
        die(json_encode($data));
    }

    function getEmployeeStatus(){
        $db = "default";
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

            $temp[] = array("id"=>$row['EMSTIDNO'], "name"=>$row['description']);


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
        //$input = $this->input->post();
        $input = array();
        foreach($this->input->post() as $key => $val){
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        $firstname = $input['firstname'];
        $lastname = $input['lastname'];

        $input['username'] = strtolower($firstname.".".$lastname);
        $input['password'] = md5("12345678");
        $input['department'] = $this->commonmodel->getFieldWhere($db, "filedept", "description", $input['department'], "DEPTIDNO");
        $input['position'] = $this->commonmodel->getFieldWhere($db, "fileposi", "description", $input['position'], "POSIIDNO");
        $input['employee_category'] = $this->commonmodel->getFieldWhere($db, "fileemca", "description", $input['employee_category'], "EMCAIDNO");
        $input['employee_status'] = $this->commonmodel->getFieldWhere($db, "fileemst", "description", $input['employee_status'], "EMSTIDNO");

        $data = $this->commonmodel->insertRecord($db, $table, $input);
        die(json_encode($data));

    }

    function loadEmployee(){
        $db = "default";
        $table = "tbl_employee_info";
        $param = "id";
        $fields = "*";

        $id=$this->input->post('id');

		$filter = "$param = '$id'";

        $records = array();
        $records = $this->lithefire->getRecordWhere($db, $table, $filter, $fields);



        $temp = array();

        foreach($records as $row):
            $row['department'] = $this->commonmodel->getFieldWhere($db, "filedept", "DEPTIDNO", $row['department'], "description");
            $row['position'] = $this->commonmodel->getFieldWhere($db, "fileposi", "POSIIDNO", $row['position'], "description");
            $row['employee_category'] = $this->commonmodel->getFieldWhere($db, "fileemca", "EMCAIDNO", $row['employee_category'], "description");
            $row['employee_status'] = $this->commonmodel->getFieldWhere($db, "fileemst", "EMSTIDNO", $row['employee_status'], "description");

            $data["data"] = $row;


        endforeach;

       // $data['data'] = $temp;
        $data['success'] = true;

        die(json_encode($data));
    }

    function updateEmployee(){
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
        $input['department'] = $this->commonmodel->getFieldWhere($db, "filedept", "description", $input['department'], "DEPTIDNO");
        $input['position'] = $this->commonmodel->getFieldWhere($db, "fileposi", "description", $input['position'], "POSIIDNO");
        $input['employee_category'] = $this->commonmodel->getFieldWhere($db, "fileemca", "description", $input['employee_category'], "EMCAIDNO");
        $input['employee_status'] = $this->commonmodel->getFieldWhere($db, "fileemst", "description", $input['employee_status'], "EMSTIDNO");




        $records = array();
        $data = $this->commonmodel->updateRecord($db, $table, $input, $param, $id);


        die(json_encode($data));
    }

    function deleteEmployee(){
        $db = "default";
        $table = "tbl_employee_info";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $input = array("is_delete"=>1);

        $data = $this->commonmodel->updateRecord($db, $table, $input, $param, $id);


        die(json_encode($data));
    }

    function getEmployment(){
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
        $db = "default";
        $table = "tbl_training";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

       // $input = array("is_delete"=>1);

        $data = $this->commonmodel->deleteRecord($db, $table, $param, $id);


        die(json_encode($data));
    }

    function memo(){
 
        $data['title'] = 'HRIS: My Memos';


         $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

      
        $this->layout->view('user/memo_view', $data);

    }

    function getMemo(){
        $this->load->model('admin_model', '', TRUE);
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');



        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');

        $query = array();
        if(!empty($querystring)){
            $query = array("employee_id"=>$querystring, "date_effective"=>$querystring);
        }


        if(empty($sort) && empty($dir)){
            $sort = "id";
            $dir = "DESC";
        }

        $records = array();
        $table = "tbl_memo";
        $fields = array("a.id", "CONCAT(b.lastname, ', ', b.firstname) AS employee_name", "a.date_requested", "a.date_effective",
                "a.reason", "CONCAT(c.lastname, ', ', c.firstname) AS requested_by");

        $filter = array("employee_id"=>$this->session->userData("userId"));

        $records = $this->admin_model->getMemo($table, $fields, $start, $limit, $sort, $dir, $query, $filter);
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
        $data['totalCount'] = $this->admin_model->countMemo($table);
        die(json_encode($data));
    }

    function notification(){

        $data['title'] = 'HRIS: My Notifications';


        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

   
        $this->layout->view('user/notification_view', $data);

    }

    function getNotification(){
        $this->load->model('admin_model', '', TRUE);
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');



        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');

        $query = array();
        if(!empty($querystring)){
            $query = array("employee_id"=>$querystring, "date_effective"=>$querystring);
        }


        if(empty($sort) && empty($dir)){
            $sort = "id";
            $dir = "DESC";
        }

        $records = array();
        $table = "tbl_notification";
        $fields = array("a.id", "a.employee_id","CONCAT(b.lastname, ', ', b.firstname) AS employee_name", "a.date_requested",
                "a.message", "CONCAT(c.lastname, ', ', c.firstname) AS requested_by");

         $filter = "(employee_id = '".$this->session->userData("userId")."' OR employee_id = 0)";

        $records = $this->admin_model->getNotification($table, $fields, $start, $limit, $sort, $dir, $query, $filter);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):
            if($row['employee_id'] == 0)
                $row['employee_name'] = "All Employees";
            $temp[] = $row;
            $total++;

        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->admin_model->countMemo($table);
        die(json_encode($data));
    }
    
    function suspension(){
        $data['title'] = 'HRIS: My Suspensions';


         $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');


        $this->layout->view('user/suspension_view', $data);

    }

    function getSuspension(){
        $this->load->model('admin_model', '', TRUE);
		$this->load->model('lithefire_model', 'lithefire', TRUE);
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');
		$employee_id = $this->session->userData("userId");
		$db = 'default';
		$filter = "employee_id = '$employee_id'";
		$group = "";
		$having = "";


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');

        $query = array();
        if(!empty($querystring)){
            $filter .= " AND (date_requested like '%$querystring%' OR date_from like '%$querystring%' OR date_to like '%$querystring%')";
        }


        if(empty($sort) && empty($dir)){
            $sort = "date_requested DESC";
        }else{
        	$sort = "$sort $dir";
        }

        $records = array();
        $table = "tbl_suspension a LEFT JOIN tbl_employee_info b ON a.employee_id = b.id LEFT JOIN tbl_employee_info c ON a.requested_by = c.id";
        $fields = array("a.id", "a.employee_id","CONCAT(b.lastname, ', ', b.firstname) AS employee_name", "a.date_requested", "a.date_from",
            "a.date_to", "a.no_days",
                "a.reason", "CONCAT(c.lastname, ', ', c.firstname) AS requested_by");

        //$filter = array("employee_id"=>);

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group, $having);
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
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, $group);
        die(json_encode($data));
    }

    function dtr(){

        $data['title'] = 'HRIS: My DTR';


        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');


        $this->layout->view('user/my_dtr_view', $data);

    }

    function getDtr(){
        $db = 'default';
        $filter = "";
        $group = "";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');



        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');


        if(empty($sort) && empty($dir)){
            $sort = "dtr_date DESC, dtr_time ASC";
        }else{
            $sort = "$sort $dir";
        }

        $filter = "id = '".$this->session->userData('userId')."'";
		
		$biometrics_id = $this->lithefire->getFieldWhere($db, "tbl_employee_info", $filter, "biometrics_id");
		
		$filter = "biometrics_id = '$biometrics_id'";

        if(!empty($querystring)){
            $filter .= " AND (dtr_log LIKE '%$querystring%')";
        }


        $records = array();
        $table = "tbl_dtr";
        $fields = array("id", "dtr_log");

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):
			$row['dtr_date'] = date('Y-m-d', strtotime($row['dtr_log']));
			$row['dtr_time'] = date('H:i:s', strtotime($row['dtr_log']));
            $temp[] = $row;
            $total++;

        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, $group);
        die(json_encode($data));
    }
    
	function whereabouts(){

        $data['title'] = 'HRIS: My DTR';


        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        $this->layout->view('user/my_whereabouts_view', $data);

    }

    function getWhereAbouts(){
        $db = 'default';
        $filter = "";
        $group = "";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');



        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');


        if(empty($sort) && empty($dir)){
            $sort = "dtr_date DESC";
        }else{
            $sort = "$sort $dir";
        }

        $filter = "employee_id = '".$this->session->userData('userId')."'";
		
		

        if(!empty($querystring)){
            $filter .= " AND (dtr_date LIKE '%$querystring%' OR time_in LIKE '%$querystring%')";
        }


        $records = array();
        $table = "tbl_whereabouts a LEFT JOIN tbl_app_type c ON a.app_type = c.id";
        $fields = array("a.id", "a.dtr_date", "a.time_in", "a.time_out", "c.description as app_type", "a.application_pk", "a.restday", "a.is_leave", "a.client_schedule", "a.training", "a.call_log");

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group);
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
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, $group);
        die(json_encode($data));
    }
    
    function getForceLeave(){
        $db = 'default';
        $filter = "";
        $group = "";
        
		

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');



        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');


        if(empty($sort) && empty($dir)){
            $sort = "date_requested DESC";
        }else{
            $sort = "$sort $dir";
        }

        $filter = "employee_id = ".$this->session->userData("userId")." OR employee_id = 0";
		
		

        if(!empty($querystring)){
            $filter = " AND (dtr_date LIKE '%$querystring%' OR d.lastname LIKE '%$querystring%' OR d.firstname LIKE '%$querystring%' OR d.middlename LIKE '%$querystring%')";
        }


        $records = array();
        $table = "tbl_force_leave a LEFT JOIN tbl_employee_info d ON a.employee_id = d.id LEFT JOIN tbl_employee_info b ON a.requested_by = b.id";
        $fields = array("a.id", "a.employee_id", "a.date_from", "a.date_to", "a.date_requested", "a.no_days", "a.reason", "a.status",
        "d.lastname", "d.firstname", "d.middlename", "CONCAT(d.lastname, ', ', d.firstname, ' ', d.middlename) AS employee_name",
		"b.lastname as lname", "b.firstname as fname", "b.middlename as mname", "CONCAT(b.lastname, ', ', b.firstname, ' ', b.middlename) AS requested_by");

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):
			if($row['employee_id'] == 0)
                $row['employee_name'] = "All Employees";
            $temp[] = $row;
            $total++;

        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, $group);
        die(json_encode($data));
    }
    
	function callLogCombo(){
        $db = 'default';
        $filter = "";
        $group = "";
        $type = $this->input->post("type");
        
        $employee_id = $this->session->userData("userId");
        
		

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');



        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');


        if(empty($sort) && empty($dir)){
            $sort = "date_from DESC";
        }else{
            $sort = "$sort $dir";
        }

        $filter = "employee_id = $employee_id AND leave_filed = 0";
		
		if($type == 2){
			$filter.=" AND call_log_type_id = 1";
		}elseif($type == 3 || $type == 5){
			$filter.=" AND call_log_type_id = 2";
		}

        if(!empty($querystring)){
            $filter .= " AND (date_from LIKE '%$querystring%' OR date_to LIKE '%$querystring%')";
        }


        $records = array();
		
		 $table = "tbl_call_log";
        $fields = array("id", "CONCAT(date_from, '-', date_to) as name, date_from, date_to, portion, no_days, reason");
        
        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group);
       // die($this->lithefire->currentQuery());


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
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, $group);
        die(json_encode($data));
    }
}
?>