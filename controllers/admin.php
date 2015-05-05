<?php
class Admin extends MY_Controller{
private $date_today;
private $timestamp_today;
    function Admin(){
        parent::__construct();

        $this->date_today = date("Y-m-d");
        $this->timestamp_today = date("Y-m-d H:i:s");


    }


    function callLog(){

        $data['Title'] = 'HRIS: Call Log';
  
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        $this->layout->view('admin/call_log_view', $data);

    }

    function memo(){


        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        
        $this->layout->view('admin/memo_view', $data);
        
    }

    function notification(){


        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        
        $this->layout->view('admin/notification_view', $data);
        
    }

    function suspension(){


        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        
        $this->layout->view('admin/suspension_view', $data);
        
    }

    function callLogTypeCombo(){
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');
        $db = "fr";
		$filter = "ACTIVATED = 1";
		$group = "";
		$having = "";


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
		
        if(!empty($query))
        $filter .= " AND (caloid LIKE '%$query%' OR calllog LIKE '%$query%')";
        

        if(empty($sort) && empty($dir)){
            $sort = "calllog";
            $dir = "ASC";
        }

        $records = array();
        $table = "filecalo";
        $fields = array("caloid as id", "calllog as name");

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group, $having);
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
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, $group);
        die(json_encode($data));
    }

    function employeeCombo(){
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');
        $db = "default";
		$filter = "ACTIVATED = 1";
		$group = "";
		$having = "";


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');

        $query = array();
        
        if(!empty($querystring))
        $filter .= " AND (firstname LIKE '%$querystring%' OR lastname LIKE '%$querystring%')";


        if(empty($sort) && empty($dir)){
            $sort = "lastname";
        }else{
        	$sort = "$sort $dir";
        }

        $records = array();
        $table = "tbl_employee_info";
        $fields = array("id", "CONCAT(lastname, ', ', firstname) AS name");

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group, $having);
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
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, $group);
        die(json_encode($data));
    }

    function employeeAllCombo(){
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');
        $db = "default";
		$filter = "ACTIVATED = 1";
		$group = "";
		$having = "";


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');

        $query = array();

       if(!empty($querystring))
        $filter .= " AND (firstname LIKE '%$querystring%' OR lastname LIKE '%$querystring%')";

        if(empty($sort) && empty($dir)){
            $sort = "lastname";
        }else{
        	$sort = "$sort $dir";
        }

        $records = array();
        $table = "tbl_employee_info";
        $fields = array("id", "CONCAT(lastname, ', ', firstname) AS name");

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group, $having);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):
          //  $row['COURSE'] = $this->commonmodel->getFieldWhere($db, "FILECOUR", "COURIDNO", $row['COURIDNO'], "COURSE");
            if(empty($temp)){
                if(empty($querystring) || stristr("All Employees", $querystring))
                $temp[] = array("id"=>0, "name"=>"All Employees");
            }
            $temp[] = $row;
            $total++;

        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, $group);
        die(json_encode($data));
    }

    function addCallLog(){
        $this->load->model('ot_model', '', TRUE);

       /* $date_from = date("Y-m-d", strtotime($this->input->post("date_from")));
        $date_to = date("Y-m-d", strtotime($this->input->post("date_to")));
        $no_days = $this->input->post('no_of_days');
        $reason = $this->input->post('reason');
        */

        $employee_id = $this->session->userdata('userId');

        $today = date("Y-m-d");
        $now = date("Y-m-d H:i:s");




        $fields = $this->input->post();
        $insert = array();
        foreach($fields as $key => $val):
            if((!empty($val) && $val != "") && $key != 'portion_hdn')
                $insert[$key] = $val;

            endforeach;
        $insert['date_requested'] = $today;
        $insert['requested_by'] = $employee_id;


        $data = $this->ot_model->insertCallLog($insert);
        die(json_encode($data));
    }

    function getCallLog(){
        $this->load->model('ot_model', '', TRUE);
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');

        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');
        $queryby = "";
        $employee_id = $this->session->userdata('userId');

        if(empty($sort) && empty($dir)){
            $sort = "id";
            $dir = "DESC";
        }
        $query = array();
        $filter = array();
        if(!empty($querystring)){
            $query = array("date_from"=>$querystring);
        }

        //$filter = array("employee_id"=>$this->session->userdata('userId'));

        //$join = array("tbl_app_status b", "a.status_id = b.id");

        //$records = array();
        $records = $this->ot_model->getCallLogs($start, $limit, $sort, $dir, $query);

        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->ot_model->countCallLogs("tbl_call_log", $query, $filter);
        die(json_encode($data));
    }

     function loadCL(){
        $this->load->model('ot_model', '', TRUE);

        $id=$this->input->post('id');
        

        $records = array();
        $records = $this->ot_model->loadCallLog($id);

        $temp = array();

        foreach($records as $row):

            $data["data"] = $row;


        endforeach;
		if($row['leave_filed']){
		$data['success'] = false;
		$data['data'] = "Leave already filed for this call log.";
        die(json_encode($data));
	 	}
        $data['success'] = true;

        die(json_encode($data));
    }

    function updateCallLog(){

        $this->load->model('ot_model', '', TRUE);

        $table = "tbl_call_log";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $input = array();
        foreach($this->input->post() as $key => $val){
            if($key == 'id')
                continue;
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        $input['modified_by'] = $this->session->userdata('userId');

        $data = $this->ot_model->updateCallLog($table, $input, $param, $id);


        die(json_encode($data));
    }

    function deleteCallLog(){
        $this->load->model('commonmodel', '', TRUE);
        $this->load->model('ot_model', '', TRUE);
		$this->load->model('lithefire_model', 'lithefire', TRUE);

        $table = "tbl_call_log";
        $param = "id";
        
       // $fields = $this->input->post();

        $id=$this->input->post('id');
		$leave_filed = $this->lithefire->getFieldWhere("default", $table, "id = '$id'", "leave_filed");
		if($leave_filed){
		$data['success'] = false;
		$data['data'] = "Leave already filed for this call log.";
        die(json_encode($data));
	 	}
        $data = $this->ot_model->deleteCallLog($table, $param, $id);

        die(json_encode($data));
    }


    function getMemo(){
        $this->load->model('commonmodel','',TRUE);
        $this->load->model('admin_model', '', TRUE);
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');
		$db = "default";
		$group = "";
		$having = "";
		$filter = "";



        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');

        $query = array();
        if(!empty($querystring)){
            $filter = "date_effective LIKE '%$querystring%'";
        }


        if(empty($sort) && empty($dir)){
            $sort = "id DESC";
        }else{
        	$sort = "$sort $dir";
        }

        $records = array();
        $table = "tbl_memo a LEFT JOIN tbl_employee_info b ON a.employee_id = b.id LEFT JOIN tbl_employee_info c ON a.requested_by = c.id";
        $fields = array("a.id", "CONCAT(b.lastname, ', ', b.firstname) AS employee_name", "a.date_requested", "a.date_effective",
                "a.reason", "CONCAT(c.lastname, ', ', c.firstname) AS requested_by");

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group, $having);


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

    function addMemo(){
        $this->load->model('commonmodel','',TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_memo";
        
        $input = $this->input->post();

        $input['date_requested'] = $this->date_today;
        $input['requested_by'] = $this->session->userData("userId");


        
        
        $data = $this->admin_model->insertRow($table, $input);
        die(json_encode($data));
    }

    function loadMemo(){
        $this->load->model('commonmodel','',TRUE);
        $this->load->model('admin_model', '', TRUE);

        $id=$this->input->post('id');
        $table = "tbl_memo";
        $param = "a.id";
        $fields = array();

        $records = array();
        $records = $this->admin_model->getSingleMemo($table, $param, $id, $fields);

        $temp = array();

        foreach($records as $row):

            $data["data"] = $row;


        endforeach;
        $data['success'] = true;

        die(json_encode($data));
    }

    function updateMemo(){
        $this->load->model('commonmodel', '', TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_memo";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $input = array();
        foreach($this->input->post() as $key => $val){
            if($key == 'id')
                continue;
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        

        $input['modified_by'] = $this->session->userData("userId");
        $data = $this->admin_model->updateRow($table, $input, $param, $id);


        die(json_encode($data));
    }

    function deleteMemo(){
        $this->load->model('commonmodel', '', TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_memo";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $data = $this->admin_model->deleteRow($table, $param, $id);

        die(json_encode($data));
    }

    function getNotification(){
        $this->load->model('commonmodel','',TRUE);
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

        $records = $this->admin_model->getNotification($table, $fields, $start, $limit, $sort, $dir, $query);
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

    function addNotification(){
        $this->load->model('commonmodel','',TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_notification";

        $input = $this->input->post();

        $input['date_requested'] = $this->date_today;
        $input['requested_by'] = $this->session->userData("userId");



        $data = $this->admin_model->insertRow($table, $input);
        
        die(json_encode($data));
    }

    function loadNotification(){
        $this->load->model('commonmodel','',TRUE);
        $this->load->model('admin_model', '', TRUE);

        $id=$this->input->post('id');
        $table = "tbl_notification";
        $param = "a.id";
        $fields = array("a.id", "a.employee_id", "CONCAT(b.lastname, ', ', b.firstname) AS employee_name", 
                "a.message");

        $records = array();
        $records = $this->admin_model->getSingleNotification($table, $param, $id, $fields);

        $temp = array();

        foreach($records as $row):
            if($row['employee_id'] == 0)
                $row['employee_name'] = "All Employees";

            $data["data"] = $row;


        endforeach;
        $data['success'] = true;

        die(json_encode($data));
    }

    function updateNotification(){
        $this->load->model('commonmodel', '', TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_notification";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $input = array();
        foreach($this->input->post() as $key => $val){
            if($key == 'id')
                continue;
            if(!empty($val)){
                $input[$key] = $val;
            }
        }
        $input['employee_id'] = $this->input->post("employee_id");
        $input['modified_by'] = $this->session->userData("userId");
        $data = $this->admin_model->updateRow($table, $input, $param, $id);


        die(json_encode($data));
    }

    function deleteNotification(){
        $this->load->model('commonmodel', '', TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_notification";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $data = $this->admin_model->deleteRow($table, $param, $id);

        die(json_encode($data));
    }

    function getSuspension(){
        $this->load->model('commonmodel','',TRUE);
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
        $table = "tbl_suspension";
        $fields = array("a.id", "a.employee_id","CONCAT(b.lastname, ', ', b.firstname) AS employee_name", "a.date_requested", "a.date_from",
            "a.date_to", "a.no_days", 'a.status',
                "a.reason", "CONCAT(c.lastname, ', ', c.firstname) AS requested_by");

        $records = $this->admin_model->getSuspension($table, $fields, $start, $limit, $sort, $dir, $query);
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
        $data['totalCount'] = $this->admin_model->countSuspension($table, $query);
        die(json_encode($data));
    }

    function addSuspension(){
        $this->load->model('commonmodel','',TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_suspension";

        $input = $this->input->post();

        $input['date_requested'] = $this->date_today;
        $input['requested_by'] = $this->session->userData("userId");
        $input['status'] = 'Approved';
        //$leave_type = $this->input->post("leave_type");



        $data = $this->admin_model->insertRow($table, $input);

        die(json_encode($data));
    }

    function loadSuspension(){
        $this->load->model('commonmodel','',TRUE);
        $this->load->model('admin_model', '', TRUE);

        $id=$this->input->post('id');
        $table = "tbl_suspension";
        $param = "a.id";
        $fields = array("a.id", "a.employee_id", "CONCAT(b.lastname, ', ', b.firstname) AS employee_name",
                "a.reason", "a.date_from", "a.date_to", "a.portion", "a.no_days");

        $records = array();
        $records = $this->admin_model->getSingleSuspension($table, $param, $id, $fields);

        $temp = array();

        foreach($records as $row):
            if($row['employee_id'] == 0)
                $row['employee_name'] = "All Employees";

            $data["data"] = $row;


        endforeach;
        $data['success'] = true;

        die(json_encode($data));
    }

    function updateSuspension(){
        $this->load->model('commonmodel', '', TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_suspension";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $input = array();
        foreach($this->input->post() as $key => $val){
            if($key == 'id')
                continue;
            if(!empty($val)){
                $input[$key] = $val;
            }
        }
        $input['employee_id'] = $this->input->post("employee_id");
        $input['modified_by'] = $this->session->userData("userId");
        $data = $this->admin_model->updateRow($table, $input, $param, $id);


        die(json_encode($data));
    }

    function deleteSuspension(){
        $this->load->model('commonmodel', '', TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_suspension";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $data = $this->admin_model->deleteRow($table, $param, $id);

        die(json_encode($data));
    }
	
	function voidSuspension(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        

        $table = "tbl_suspension";
		$db="default";
        //$param = "id";
       $id = $this->input->post('id');
	   $input['status'] = "Cancelled";
       $filter = "id = $id";

        $id=$this->input->post('id');

        $data = $this->lithefire->updateRow($db, $table, $input, $filter);

        die(json_encode($data));
    }

    function leaveSetup(){


        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');
		$year = date("Y");
		
		$input_string = ""; 
		for($i = $year-5; $i <= $year+5; $i++):
				if($i == $year+5){
					$input_string.="['$i', '$i']";
				}else{
					$input_string.="['$i', '$i'],";
				}
		endfor;
		$data['input_string'] = $input_string;
        
        $this->layout->view('admin/setup_view', $data);
        
    }

    function holidaySetup(){


        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        
        $this->layout->view('admin/holiday_view', $data);
        
    }

    function getHoliday(){
        $this->load->model('commonmodel','',TRUE);
        $this->load->model('admin_model', '', TRUE);
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');



        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');

        $query = array();
        if(!empty($querystring)){
            $query = array("holiday_name"=>$querystring, "holiday_date"=>$querystring, "description"=>$querystring);
        }


        if(empty($sort) && empty($dir)){
            $sort = "id";
            $dir = "DESC";
        }

        $records = array();
        $table = "tbl_holiday";
        $fields = array("id", "holiday_name", "holiday_date", "description", "type");

        $records = $this->admin_model->getAllRows($table, $fields, $start, $limit, $sort, $dir, $query);
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
        $data['totalCount'] = $this->admin_model->countRows($table, $query);
        die(json_encode($data));
    }

    function addHoliday(){
        $this->load->model('commonmodel','',TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_holiday";

        $input = $this->input->post();


        $input['requested_by'] = $this->session->userData("userId");




        $data = $this->admin_model->insertRow($table, $input);
        die(json_encode($data));
    }

    function loadHoliday(){
        $this->load->model('commonmodel','',TRUE);
        $this->load->model('admin_model', '', TRUE);

        $id=$this->input->post('id');
        $table = "tbl_holiday";
        $param = "id";
        $fields = array("id", "holiday_name", "holiday_date", "description", "type");

        $records = array();
        $records = $this->admin_model->getRowWhere($table, $param, $id, $fields);

        $temp = array();

        foreach($records as $row):

            $data["data"] = $row;


        endforeach;
        $data['success'] = true;

        die(json_encode($data));
    }

    function updateHoliday(){
        $this->load->model('commonmodel', '', TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_holiday";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $input = array();
        foreach($this->input->post() as $key => $val){
            if($key == 'id')
                continue;
            if(!empty($val)){
                $input[$key] = $val;
            }
        }



        $input['modified_by'] = $this->session->userData("userId");
        $data = $this->admin_model->updateRow($table, $input, $param, $id);


        die(json_encode($data));
    }

    function deleteHoliday(){
        $this->load->model('commonmodel', '', TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_holiday";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $data = $this->admin_model->deleteRow($table, $param, $id);

        die(json_encode($data));
    }
	
	function userAdministration()
    {

        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';


        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        $this->load->view('header_view', $data);
        $this->load->view('menu_view', $data);
        $this->load->view('admin/user_administration_view', $data);
        //$this->load->view('login_view');
        $this->load->view('footer_view', $data);
    }
	
	function getUsers(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = "default";
        $filter = "";
        $group = "";

        $start = $this->input->post("start");
        $limit = $this->input->post("limit");
        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');


        if(empty($sort) && empty($dir)){
            $sort = "user_type ASC, username ASC";

        }else{
            $sort = "$sort $dir";
        }

        $query = $this->input->post('query');

        if(!empty($query))
            $filter = "(username LIKE '%$query%')";

        $table = "tbl_employee_info a LEFT JOIN tbl_user_type b ON a.user_type = b.code";
        $fields = "a.id, a.username, a.lastname, a.firstname, a.middlename, b.description as user_type, b.code";

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group);

        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):
            $row['name'] = $row['lastname'].", ".$row['firstname']." ".$row['middlename'];
            $temp[] = $row;
            $total++;

        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, $group);
        die(json_encode($data));
    }
    
    function loadUser(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = "default";
        $table = "tbl_employee_info a LEFT JOIN tbl_user_type b ON a.user_type = b.code";
        $fields = "a.id, a.username, a.lastname, a.firstname, a.middlename, b.description as user_type, b.code";

        $id=$this->input->post('id');

        $filter = "a.id = '$id'";
        //$filter.=" AND a.COURIDNO = FILECOUR.COURIDNO AND a.CITIIDNO = FILECITI.CITIIDNO AND a.RELIIDNO = FILERELI.RELIIDNO";

        $records = array();
        $records = $this->lithefire->getRecordWhere($db, $table, $filter, $fields);



        $temp = array();

        foreach($records as $row):
            $row['name'] = $row['lastname'].", ".$row['firstname']." ".$row['middlename'];
            $data["data"] = $row;


        endforeach;

       // $data['data'] = $temp;
        $data['success'] = true;

        die(json_encode($data));
    }

    function getUserTypeCombo(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = "default";
        $filter = "";
        $group = "";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');
        //$db = "fr";


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');


        if(empty($sort) && empty($dir)){
            $sort = "description ASC";
            
        }else{
            $sort = "$sort $dir";
        }

        if(!empty($query))
            $filter = "(code LIKE '%$query%' OR description LIKE '%$query%')";

        $records = array();
        $table = "tbl_user_type";
        $fields = array("code as id", "description as name");


        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group);
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
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, $group);
        die(json_encode($data));
    }

    function changePassword(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = "default";
        $table = "tbl_employee_info";

        $id = $this->input->post('id');

        $new_password = md5($this->input->post('new_pass'));



        $input = array("password"=>$new_password);
        $filter = "id = '$id'";

        $data = $this->lithefire->updateRow($db, $table, $input, $filter);
        $data['data'] = "Password Successfully changed";

        die(json_encode($data));

    }

    function updateUserName(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = "default";
        $table = "tbl_employee_info";

        $id = $this->input->post('id');
        
        $old_username = $this->lithefire->getFieldWhere("default", "tbl_employee_info", "id = '$id'","username");
        $username = $this->input->post('username');

        if($this->lithefire->countFilteredRows($db, $table, "id != '$id' AND username = '$username'", "")){
            $data['success'] = false;
            $data['data'] = "Username already exists";
            die(json_encode($data));
        }


        $input = array("username"=>$username);
        $filter = "id = '$id'";

        $data = $this->lithefire->updateRow($db, $table, $input, $filter);
        $data = $this->lithefire->updateRow($db, "module_group_users", $input, "username = '$old_username'");
        $data['data'] = "Username successfully updated";

        die(json_encode($data));

    }
	
	function addEmployeeLeave(){

        $this->load->model('lithefire_model','lithefire',TRUE);
		$db = 'default';

        $table = "tbl_employee_leave_credits";

        $employee_id = $this->input->post('employee_id');
		$year = $this->input->post('year');
		
		$leave_limit = $this->lithefire->getRecordWhere($db, "tbl_company_setup", "", "*");
		
		$vl_limit = $leave_limit[0]['vl_limit'];
		$sl_limit = $leave_limit[0]['sl_limit'];
		$el_limit = $leave_limit[0]['el_limit'];
		$ml_limit = $leave_limit[0]['ml_limit'];
		$pl_limit = $leave_limit[0]['pl_limit'];

		if($employee_id == 0){
		$input['year'] = $year;
		$input['vacation_leave'] = $vl_limit;
		$input['sick_leave'] = $sl_limit;
		
		$input['emergency_leave'] = $el_limit;
			$records = $this->lithefire->getAllRecords($db, 'tbl_employee_info', 'id, gender', "", "", "", "(date_resigned is NULL OR date_resigned = '')", "");
        


        if($records){
        foreach($records as $row):
          	if($row['gender'] == "F"){
				$input['maternity_leave'] = $ml_limit;
				$input['paternity_leave'] = 0;
			}else{
				$input['paternity_leave'] = $pl_limit;
				$input['maternity_leave'] = 0;
			}
			$input['employee_id'] = $row['id'];
			if($this->lithefire->countFilteredRows($db, $table, "employee_id = '".$row['id']."' AND year = '$year'", "")){
				//$data['success'] = false;
				//$data['data'] = 'Leave credits already exists for this employee';
				//die(json_encode($data));
				continue;
			}

			$is_regular = $this->lithefire->getFieldWhere($db, "tbl_employee_info", array("id"=>$employee_id), "employee_category");
			if($is_regular == 1){
				//$data['success'] = false;
				//$data['data'] = 'Employee is still probationary and not qualified for leave applications';
				//die(json_encode($data));
				continue;
			}
			
			$data = $this->lithefire->insertRow($db,$table, $input);
        endforeach;
        }
		}else{
			$is_regular = $this->lithefire->getFieldWhere($db, "tbl_employee_info", array("id"=>$employee_id), "employee_category");
			if($is_regular == 1){
				$data['success'] = false;
				$data['data'] = 'Employee is still probationary and not qualified for leave applications';
				die(json_encode($data));
			}
		
		$input['employee_id'] = $employee_id;
		$input['year'] = $year;
		$input['vacation_leave'] = $vl_limit;
		$input['sick_leave'] = $sl_limit;
		
		$input['emergency_leave'] = $el_limit;
		
		$gender = $this->lithefire->getFieldWhere($db, "tbl_employee_info", "id = '$employee_id'", "gender");
		if($gender == "F"){
			$input['maternity_leave'] = $ml_limit;
			$input['paternity_leave'] = 0;
		}elseif($gender == "M"){
			$input['paternity_leave'] = $pl_limit;
			$input['maternity_leave'] = 0;
		}
		if($this->lithefire->countFilteredRows($db, $table, "employee_id = '$employee_id' AND year = '$year'", "")){
				$data['success'] = false;
				$data['data'] = 'Leave credits already exists for this employee';
				die(json_encode($data));
			}
        $data = $this->lithefire->insertRow($db,$table, $input);
		}
        die(json_encode($data));
    
	}
	
	function getLeaveCredits(){
        $this->load->model('lithefire_model','lithefire',TRUE);

        
        $db = "default";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');
        


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');
        $filter = "";
        $group = "";

        if(empty($sort) && empty($dir)){
            $sort = "a.year ASC, b.lastname ASC";
        }else{
            $sort = "$sort $dir";
        }

        

        $records = array();
        $table = "tbl_employee_leave_credits a LEFT JOIN tbl_employee_info b ON a.employee_id = b.id";
        $fields = array("a.*", "lastname", "firstname", "middlename");

        //$filter = "a.GENDIDNO = b.GENDIDNO";

        if(!empty($querystring))
            $filter = "(b.lastname LIKE '%$querystring%' OR b.firstname LIKE '%$querystring%' OR b.middlename LIKE '%$querystring%' OR a.year LIKE '%$querystring%')";

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):
          //  $row['COURSE'] = $this->commonmodel->getFieldWhere($db, "FILECOUR", "COURIDNO", $row['COURIDNO'], "COURSE");
          $row['employee_name'] = $row['lastname'].", ".$row['firstname']." ".$row['middlename'];
            $temp[] = $row;
            $total++;

        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, "");
        die(json_encode($data));
    }

	function updateLeaves(){
        $this->load->model('lithefire_model','lithefire',TRUE);

        $db = "default";
        $table = "tbl_employee_leave_credits";
       // $param = "SCHEDULEIDNO";
        $data = array();
        //$user_name = $this->session->userdata('userName');

        $update_data = str_replace('\\', '', $this->input->post('data'));

        $update_data = json_decode($update_data, true);

        //die(print_r($update_data));

        $input = array();
        $log_input = array();

        foreach($update_data as $key => $value):
        //die($this->encrypt->encode($value['PRELIM']));
            $input = array("vacation_leave"=>$value['vacation_leave'], "sick_leave"=>$value['sick_leave'], "paternity_leave"=>$value['paternity_leave'], 
			"maternity_leave"=>$value['maternity_leave'], "emergency_leave"=>$value['emergency_leave']);
        $filter = "id = '".$value['id']."'";
        $data = $this->lithefire->updateRow($db, $table, $input, $filter);

        //$log_input = array("SCHEDULEIDNO"=>$value['id'], "MODIFIED_BY"=>$user_name, "DMODIFIED"=>$this->current_date, "TMODIFIED"=>$this->current_time);
        //$this->faculty_model->insertRow($db, "grade_log", $log_input);
        endforeach;

        die(json_encode($data));
    }

	function dtr(){

     //   redirect('main/maintenance');
        $data['title'] = 'HRIS: My DTR';


        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        
        $this->layout->view('admin/admin_dtr_view', $data);
        
    }
	
	function getDtr(){
		$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');
		
		if(empty($date_from) || empty($date_to)){
			$data['success']=true;
			$data['data'] = array();
		die(json_encode($data));
		}
		
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = 'default';
        $filter = "";
        $group = "";
		$having = "";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');



        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');


        if(empty($sort) && empty($dir)){
            $sort = "dtr_log DESC";
        }else{
            $sort = "$sort $dir";
        }

        $filter = "dtr_log BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59'";

        if(!empty($querystring)){
            $filter .= " AND (dtr_log LIKE '%$querystring%' OR lastname LIKE '%$querystring%' OR firstname LIKE '%$querystring%' OR middlename LIKE '%$querystring%')";
			//$having = "name LIKE '%$querystring%'";
        }


        $records = array();
        $table = "tbl_dtr a JOIN tbl_employee_info b ON a.biometrics_id = b.biometrics_id";
        $fields = array("a.id", "b.id as employee_id", "b.employee_idno", "dtr_log", "lastname", "firstname", "middlename", "CONCAT(b.lastname, ', ', b.firstname, ' ', b.middlename) AS name");

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        	
        foreach($records as $row):
			//$row['name'] = $row['lastname'].", ".$row['firstname']." ".$row['middlename'];
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

	function companySetup(){


        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');
		$year = date("Y");
		
		$input_string = ""; 
		for($i = $year-5; $i <= $year+5; $i++):
				if($i == $year+5){
					$input_string.="['$i', '$i']";
				}else{
					$input_string.="['$i', '$i'],";
				}
		endfor;
		$data['input_string'] = $input_string;
        
        $this->layout->view('admin/company_setup_view', $data);
        
    }
	
	function getCompanySetup(){
        $this->load->model('lithefire_model','lithefire',TRUE);

        
        $db = "default";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');
        


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');
        $filter = "";
        $group = "";

        if(empty($sort) && empty($dir)){
            $sort = "id DESC";
        }else{
            $sort = "$sort $dir";
        }

        

        $records = array();
        $table = "tbl_company_setup";
        $fields = array("*");

        //$filter = "a.GENDIDNO = b.GENDIDNO";

        if(!empty($querystring))
            $filter = "(time_in LIKE '%$querystring%' OR time_out LIKE '%$querystring%')";

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):
          //  $row['COURSE'] = $this->commonmodel->getFieldWhere($db, "FILECOUR", "COURIDNO", $row['COURIDNO'], "COURSE");
         // $row['employee_name'] = $row['lastname'].", ".$row['firstname']." ".$row['middlename'];
            $temp[] = $row;
            $total++;

        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, "");
        die(json_encode($data));
    }

	function updateCompanySetup(){
        $this->load->model('lithefire_model','lithefire',TRUE);

        $db = "default";
        $table = "tbl_company_setup";
       // $param = "SCHEDULEIDNO";
        $data = array();
        //$user_name = $this->session->userdata('userName');

        $update_data = str_replace('\\', '', $this->input->post('data'));

        $update_data = json_decode($update_data, true);

        //die(print_r($update_data));

        $input = array();
        $log_input = array();

        foreach($update_data as $key => $value):
        //die($this->encrypt->encode($value['PRELIM']));
        $time_out = date('H:i:s', strtotime($value['time_in']."+9 hours"));
            $input = array("vacation_leave_grace_period"=>$value['vacation_leave_grace_period'], "sick_leave_grace_period"=>$value['sick_leave_grace_period'], "time_in"=>$value['time_in'], 
			"time_out"=>$time_out, "vl_limit"=>$value['vl_limit'], "sl_limit"=>$value['sl_limit'], "el_limit"=>$value['el_limit'],
			"ml_limit"=>$value['ml_limit'], "pl_limit"=>$value['pl_limit']);
        $filter = "id = '".$value['id']."'";
        $data = $this->lithefire->updateRow($db, $table, $input, $filter);

        //$log_input = array("SCHEDULEIDNO"=>$value['id'], "MODIFIED_BY"=>$user_name, "DMODIFIED"=>$this->current_date, "TMODIFIED"=>$this->current_time);
        //$this->faculty_model->insertRow($db, "grade_log", $log_input);
        endforeach;

        die(json_encode($data));
    }

	function uploadDtr(){
		set_time_limit(0);
		//$this->lo
        if (!empty($_FILES['file']['name']))
	{

            $file_name = $_FILES['file']['name'];

            $image_extensions_allowed = array('csv', 'CSV');
            $ext = strtolower(substr(strrchr($file_name, "."), 1));

            if(!in_array($ext, $image_extensions_allowed))
            {
            $exts = implode(', ',$image_extensions_allowed);
            $error = "You must upload a file with one of the following extensions: ".$exts;
            $data = array("succses"=>false, "data"=>$error);
            die(json_encode($data));
            }

           // $extension = $ext;

			$filename = $_FILES['file']['name'];
			$uploaddir = "dtr/".$filename;

			if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir))
			{
				$data = array("success"=>false, "data"=> "File was not uploaded." );
				die(json_encode($data));
			}
	}else{
		$data = array("success"=>false, "data"=> "File was not uploaded." );
		die(json_encode($data));
	}
	
		if (($handle = fopen($uploaddir, "r")) !== FALSE) {
		$this->load->model('lithefire_model','lithefire',TRUE);
		$fr_db = $this->config->item("engine_db");
		$default_db = $this->config->item("hris_db");
		
		$user = $this->config->item("USER");
		$pass = $this->config->item("PASS");
		$dsn = $this->config->item("DSN");
			
			
		$pdo = new PDO($dsn, $user, $pass);
		$stmt = $pdo->prepare("INSERT INTO tbl_dtr (biometrics_id, dtr_log, dtr_date, dtr_time) VALUES (?,?,?,?)");
		
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	        $input = array();
			//die(print_r($data));
		
			$biometrics_id = trim($data[0]);
			$dtr_date = date('Y-m-d', strtotime($data[1]));
			$dtr_time = date('H:i:s', strtotime($data[1]));
			//die($biometrics_id." ".$dtr_date." ".$dtr_time);
	     
	       //$input['biometrics_id'] = str_replace(" ", "", $biometrics_id);
	       //$input['dtr_date'] = date('Y-m-d', strtotime(str_replace(" ", "", $dtr_date)));
		   //$input['dtr_time'] = str_replace(" ", "", $dtr_time);
		   $input[] = str_replace(" ", "", $biometrics_id);
	       $input[] = date('Y-m-d H:i:s', strtotime(str_replace(" ", "", $dtr_date)." ".str_replace(" ", "", $dtr_time)));
		   $input[] = date('Y-m-d', strtotime(str_replace(" ", "", $dtr_date)));
		   $input[] = date('H:i:s',  strtotime(str_replace(" ", "", $dtr_time)));
		   
		   $filter['biometrics_id'] = str_replace(" ", "", $biometrics_id);
	       $filter['dtr_log'] = date('Y-m-d H:i:s', strtotime(str_replace(" ", "", $dtr_date)." ".str_replace(" ", "", $dtr_time)));
		   
		   
		   
		   //$this->lithefire->insertRow("default", "tbl_dtr", $input);
		   if(!$this->lithefire->countFilteredRows("default", "tbl_dtr", $filter, ""))
		   $stmt->execute($input);
		   
		}
		    fclose($handle);
			$pdo = null;
			$data['filename'] = base_url().$uploaddir;
	        $data['success'] = true;
	        $data['data'] = "File successfully uploaded";
	        die(json_encode($data));
	    
		}else{
			$data['success'] = false;
	        $data['data'] = "File was not uploaded";
	        die(json_encode($data));
		}

    
        
       

        $data['success'] = false;
        $data['data'] = mysql_error();
        die(json_encode($data));
        

    }

	function whereabouts(){

     //   redirect('main/maintenance');
        $data['title'] = 'HRIS: My DTR';


        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        
        $this->layout->view('admin/whereabouts_view', $data);
        
    }

    function getWhereAbouts(){
    	$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');
		
		if(empty($date_from) || empty($date_to)){
			$data['success']=true;
			$data['data'] = array();
		die(json_encode($data));
		}
        $this->load->model('lithefire_model','lithefire',TRUE);
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

        $filter = "dtr_date BETWEEN '$date_from' AND '$date_to'";
		
		

        if(!empty($querystring)){
            $filter .= " AND (dtr_date LIKE '%$querystring%' OR d.lastname LIKE '%$querystring%' OR d.firstname LIKE '%$querystring%' OR d.middlename LIKE '%$querystring%')";
        }


        $records = array();
        $table = "tbl_whereabouts a LEFT JOIN tbl_app_type c ON a.app_type = c.id LEFT JOIN tbl_employee_info d ON a.employee_id = d.id";
        $fields = array("a.id", "a.employee_id", "a.dtr_date", "a.time_in", "a.time_out", "c.description as app_type", "a.application_pk", "lastname", "firstname", "middlename", "CONCAT(d.lastname, ', ', d.firstname, ' ', d.middlename) AS name", "a.restday", "a.is_leave", "a.client_schedule");

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
    
    function ForceLeave(){


        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        
        $this->layout->view('admin/force_leave_view', $data);
        
    }
	
	function getForceLeave(){
    	
        $this->load->model('lithefire_model','lithefire',TRUE);
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

        //$filter = "dtr_date BETWEEN '$date_from' AND '$date_to'";
		
		

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
	
	
	function applyForceLeave(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
		$this->load->model('apps_model', '', TRUE);
		$this->load->model('employee_model', '', TRUE);
        
		$input = array();
		
        $date_from = $input['date_from'] = $this->input->post("date_from");
        $date_to = $input['date_to'] = $this->input->post('date_to');
        $no_days = $input['no_days'] = $this->input->post('no_days');
        //$input['portion'] = $this->input->post('portion_hdn');
        $reason =  $this->input->post('reason');
		$leave_type = $input['reason'] = $this->input->post('leave_type');
        $employee_id = $input['employee_id'] = $this->input->post('employee_id');
        $input['leave_type'] = 8;
		$table = "tbl_force_leave";
		$db = "default";
		$input['status'] = "Approved";
		$input['reason'] = $reason;


        $date_today = date("Y-m-d");
        $now = date("Y-m-d H:i:s");
		
		$input['date_requested'] = $date_today;
		$requested_by = $input['requested_by'] = $this->session->userData('userId');
		$requested_by_user = $this->session->userData('userName');
		
		if($this->lithefire->countFilteredRows($db, $table, "(employee_id = '$employee_id' OR employee_id = 0) AND ('$date_from' between date_from AND date_to OR '$date_to' BETWEEN date_from AND date_to OR date_from BETWEEN '$date_from' AND '$date_to' OR date_to BETWEEN '$date_from' AND '$date_to') AND status = 'Approved'", "")){
            $data['success'] = false;
            $data['data'] = "Application already exists for this date";
            die(json_encode($data));
        }
		


        $data = $this->lithefire->insertRow($db, $table, $input);
		
		$force_leave_id = $data['id'];
		
		if($employee_id == 0){
			$employee_list = $this->lithefire->getAllRecords($db, "tbl_employee_info", "id, username", "", "", "", "resigned = 'N'", "", "");
			foreach($employee_list as $row):
				
			
		$user_id = $row['id'];
        $emp_group = $this->apps_model->getEmpGroup($user_id);
		$type = 2;

        if(empty($emp_group)){
            $data['success'] = false;
            
			$this->lithefire->deleteRow($db, "tbl_application_audit", "force_leave_id = $force_leave_id");
			$this->lithefire->deleteRow($db, "tbl_leave_application", "force_leave_id = $force_leave_id");
			$this->lithefire->deleteRow($db, "tbl_force_leave", "id = $force_leave_id");
			$data['data'] = "Employee Group Not Set for ".$row['username'];
            die(json_encode($data));
        }

        $app_flow = $this->apps_model->getAppFlow($emp_group, $type);

        if(empty($app_flow)){
            $data['success'] = false;
            
			$this->lithefire->deleteRow($db, "tbl_application_audit", "force_leave_id = $force_leave_id");
			$this->lithefire->deleteRow($db, "tbl_leave_application", "force_leave_id = $force_leave_id");
			$this->lithefire->deleteRow($db, "tbl_force_leave", "id = $force_leave_id");
			$data['data'] = "Application From Flow Not Set for ".$row['username'];
            die(json_encode($data));
        }
        $year = date("Y");
        $leave_credits_balance = $this->employee_model->getEmployeeLeaveCredits($user_id, $year);
        
        if(empty($leave_credits_balance)){
            $data['success'] = false;
            
			$this->lithefire->deleteRow($db, "tbl_application_audit", "force_leave_id = $force_leave_id");
			$this->lithefire->deleteRow($db, "tbl_leave_application", "force_leave_id = $force_leave_id");
			$this->lithefire->deleteRow($db, "tbl_force_leave", "id = $force_leave_id");
			$data['data'] = "No leave credits setup for this employee";
            die(json_encode($data));
        }
        
        $app_flow_details = $this->apps_model->getAppFlowDetails($emp_group, 2);
			if($leave_type == 1){
			if($no_days > $leave_credits_balance[0]['vacation_leave']){
				$day_diff =number_format($no_days,2)-number_format($leave_credits_balance[0]['vacation_leave'],2);
				$date_to = date('Y-m-d', strtotime($date_to."-$day_diff days"));
				
				$leave_fields = array("employee_id"=>$employee_id, "date_from"=>$date_from,
	            "date_to"=>$date_to, "no_days"=>$leave_credits_balance[0]['vacation_leave'], "date_requested"=>$date_today,
	            "reason"=>$reason, "leave_type"=>$leave_type, "force_leave_id"=>$force_leave_id);
	
	            $leave_data = $this->lithefire->insertRow($db, "tbl_leave_application", $leave_fields);
				
				$apps_pk = $leave_data['id'];
				
				$audit_details = array("application_pk"=>$apps_pk,"app_type_id"=>2, "app_type"=>"Leave", "action_timestamp"=>$now, "approver_id"=>$requested_by, "requestor"=>$employee_id, 
				"employee_group_id"=>$app_flow_details[0]['employee_group_id'], "app_group_id"=>$app_flow_details[0]['app_group_id'], 
				"app_tree_id"=>$app_flow_details[0]['app_tree_id'], "remarks"=>"Force Leave by ".$requested_by_user,
	            "status_id"=>2, "is_active"=>1, "force_leave_id"=>$force_leave_id);
				
				$data = $this->lithefire->insertRow($db, "tbl_application_audit", $audit_details);
				
				$date_from = date('Y-m-d', strtotime($date_to."+1 day"));
				$date_to = date('Y-m-d', strtotime($date_to."+$day_diff days"));
				
				$leave_fields = array("employee_id"=>$employee_id, "date_from"=>$date_from,
	            "date_to"=>$date_to, "no_days"=>$day_diff, "date_requested"=>$date_today,
	            "reason"=>$reason, "leave_type"=>4, "force_leave_id"=>$force_leave_id);
	
	            $leave_data = $this->lithefire->insertRow($db, "tbl_leave_application", $leave_fields);
				
				$apps_pk = $leave_data['id'];
				
				$audit_details = array("application_pk"=>$apps_pk, "app_type_id"=>2, "app_type"=>"Leave", "action_timestamp"=>$now, "approver_id"=>$requested_by, "requestor"=>$employee_id, 
				"employee_group_id"=>$app_flow_details[0]['employee_group_id'], "app_group_id"=>$app_flow_details[0]['app_group_id'], 
				"app_tree_id"=>$app_flow_details[0]['app_tree_id'], "remarks"=>"Force Leave by ".$requested_by_user,
	            "status_id"=>2, "is_active"=>1, "force_leave_id"=>$force_leave_id);
	            
	            $data = $this->lithefire->insertRow($db, "tbl_application_audit", $audit_details);
			}else{
				$leave_fields = array("employee_id"=>$employee_id, "date_from"=>$date_from,
	            "date_to"=>$date_to, "no_days"=>$no_days, "date_requested"=>$date_today,
	            "reason"=>$reason, "leave_type"=>$leave_type, "force_leave_id"=>$force_leave_id);
	
	            $leave_data = $this->lithefire->insertRow($db, "tbl_leave_application", $leave_fields);
				
				$apps_pk = $leave_data['id'];
				
				$audit_details = array("application_pk"=>$apps_pk, "app_type_id"=>2, "app_type"=>"Leave", "action_timestamp"=>$now, "approver_id"=>$requested_by, "requestor"=>$employee_id, 
				"employee_group_id"=>$app_flow_details[0]['employee_group_id'], "app_group_id"=>$app_flow_details[0]['app_group_id'], 
				"app_tree_id"=>$app_flow_details[0]['app_tree_id'], "remarks"=>"Force Leave by ".$requested_by_user,
	            "status_id"=>2, "is_active"=>1, "force_leave_id"=>$force_leave_id);
				
				$data = $this->lithefire->insertRow($db, "tbl_application_audit", $audit_details);
			}
		}elseif($leave_type == 2){
			if($no_days > $leave_credits_balance[0]['sick_leave']){
				$day_diff =number_format($no_days,2)-number_format($leave_credits_balance[0]['sick_leave'],2);
				$date_to = date('Y-m-d', strtotime($date_to."-$day_diff days"));
				
				$leave_fields = array("employee_id"=>$employee_id, "date_from"=>$date_from,
	            "date_to"=>$date_to, "no_days"=>$leave_credits_balance[0]['sick_leave'], "date_requested"=>$date_today,
	            "reason"=>$reason, "leave_type"=>$leave_type, "force_leave_id"=>$force_leave_id);
	
	            $leave_data = $this->lithefire->insertRow($db, "tbl_leave_application", $leave_fields);
				
				$apps_pk = $leave_data['id'];
				
				$audit_details = array("application_pk"=>$apps_pk,"app_type_id"=>2, "app_type"=>"Leave", "action_timestamp"=>$now, "approver_id"=>$requested_by, "requestor"=>$employee_id, 
				"employee_group_id"=>$app_flow_details[0]['employee_group_id'], "app_group_id"=>$app_flow_details[0]['app_group_id'], 
				"app_tree_id"=>$app_flow_details[0]['app_tree_id'], "remarks"=>"Force Leave by ".$requested_by_user,
	            "status_id"=>2, "is_active"=>1, "force_leave_id"=>$force_leave_id);
				
				$data = $this->lithefire->insertRow($db, "tbl_application_audit", $audit_details);
				
				$date_from = date('Y-m-d', strtotime($date_to."+1 day"));
				$date_to = date('Y-m-d', strtotime($date_to."+$day_diff days"));
				
				$leave_fields = array("employee_id"=>$employee_id, "date_from"=>$date_from,
	            "date_to"=>$date_to, "no_days"=>$day_diff, "date_requested"=>$date_today,
	            "reason"=>$reason, "leave_type"=>5, "force_leave_id"=>$force_leave_id);
	
	            $leave_data = $this->lithefire->insertRow($db, "tbl_leave_application", $leave_fields);
				
				$apps_pk = $leave_data['id'];
				
				$audit_details = array("application_pk"=>$apps_pk, "app_type_id"=>2, "app_type"=>"Leave", "action_timestamp"=>$now, "approver_id"=>$requested_by, "requestor"=>$employee_id, 
				"employee_group_id"=>$app_flow_details[0]['employee_group_id'], "app_group_id"=>$app_flow_details[0]['app_group_id'], 
				"app_tree_id"=>$app_flow_details[0]['app_tree_id'], "remarks"=>"Force Leave by ".$requested_by_user,
	            "status_id"=>2, "is_active"=>1, "force_leave_id"=>$force_leave_id);
	            
	            $data = $this->lithefire->insertRow($db, "tbl_application_audit", $audit_details);
			}else{
				$leave_fields = array("employee_id"=>$employee_id, "date_from"=>$date_from,
	            "date_to"=>$date_to, "no_days"=>$no_days, "date_requested"=>$date_today,
	            "reason"=>$reason, "leave_type"=>$leave_type, "force_leave_id"=>$force_leave_id);
	
	            $leave_data = $this->lithefire->insertRow($db, "tbl_leave_application", $leave_fields);
				
				$apps_pk = $leave_data['id'];
				
				$audit_details = array("application_pk"=>$apps_pk, "app_type_id"=>2, "app_type"=>"Leave", "action_timestamp"=>$now, "approver_id"=>$requested_by, "requestor"=>$employee_id, 
				"employee_group_id"=>$app_flow_details[0]['employee_group_id'], "app_group_id"=>$app_flow_details[0]['app_group_id'], 
				"app_tree_id"=>$app_flow_details[0]['app_tree_id'], "remarks"=>"Force Leave by ".$requested_by_user,
	            "status_id"=>2, "is_active"=>1, "force_leave_id"=>$force_leave_id);
				
				$data = $this->lithefire->insertRow($db, "tbl_application_audit", $audit_details);
			}
		}
				
			endforeach;
		}else{
			
		$user_id = $employee_id;
        $emp_group = $this->apps_model->getEmpGroup($user_id);
		$type = 2;

        if(empty($emp_group)){
            $data['success'] = false;
            $data['data'] = "Employee Group Not Set";
			$this->lithefire->deleteRow($db, "tbl_force_leave", "id = $force_leave_id");
            die(json_encode($data));
        }

        $app_flow = $this->apps_model->getAppFlow($emp_group, $type);

        if(empty($app_flow)){
            $data['success'] = false;
            $data['data'] = "Application From Flow Not Set";
			$this->lithefire->deleteRow($db, "tbl_force_leave", "id = $force_leave_id");
            die(json_encode($data));
        }
        $year = date("Y");
        $leave_credits_balance = $this->employee_model->getEmployeeLeaveCredits($user_id, $year);
        
        if(empty($leave_credits_balance)){
            $data['success'] = false;
            
			$this->lithefire->deleteRow($db, "tbl_application_audit", "force_leave_id = $force_leave_id");
			$this->lithefire->deleteRow($db, "tbl_leave_application", "force_leave_id = $force_leave_id");
			$this->lithefire->deleteRow($db, "tbl_force_leave", "id = $force_leave_id");
			$data['data'] = "No leave credits setup for this employee";
            die(json_encode($data));
        }
        
        $app_flow_details = $this->apps_model->getAppFlowDetails($emp_group, 2);
			if($leave_type == 1){
			if($no_days > $leave_credits_balance[0]['vacation_leave']){
				$day_diff =number_format($no_days,2)-number_format($leave_credits_balance[0]['vacation_leave'],2);
				$date_to = date('Y-m-d', strtotime($date_to."-$day_diff days"));
				
				$leave_fields = array("employee_id"=>$employee_id, "date_from"=>$date_from,
	            "date_to"=>$date_to, "no_days"=>$leave_credits_balance[0]['vacation_leave'], "date_requested"=>$date_today,
	            "reason"=>$reason, "leave_type"=>$leave_type, "force_leave_id"=>$force_leave_id);
	
	            $leave_data = $this->lithefire->insertRow($db, "tbl_leave_application", $leave_fields);
				
				$apps_pk = $leave_data['id'];
				
				$audit_details = array("application_pk"=>$apps_pk,"app_type_id"=>2, "app_type"=>"Leave", "action_timestamp"=>$now, "approver_id"=>$requested_by, "requestor"=>$employee_id, 
				"employee_group_id"=>$app_flow_details[0]['employee_group_id'], "app_group_id"=>$app_flow_details[0]['app_group_id'], 
				"app_tree_id"=>$app_flow_details[0]['app_tree_id'], "remarks"=>"Force Leave by ".$requested_by_user,
	            "status_id"=>2, "is_active"=>1, "force_leave_id"=>$force_leave_id);
				
				$data = $this->lithefire->insertRow($db, "tbl_application_audit", $audit_details);
				
				$date_from = date('Y-m-d', strtotime($date_to."+1 day"));
				$date_to = date('Y-m-d', strtotime($date_to."+$day_diff days"));
				
				$leave_fields = array("employee_id"=>$employee_id, "date_from"=>$date_from,
	            "date_to"=>$date_to, "no_days"=>$day_diff, "date_requested"=>$date_today,
	            "reason"=>$reason, "leave_type"=>4, "force_leave_id"=>$force_leave_id);
	
	            $leave_data = $this->lithefire->insertRow($db, "tbl_leave_application", $leave_fields);
				
				$apps_pk = $leave_data['id'];
				
				$audit_details = array("application_pk"=>$apps_pk, "app_type_id"=>2, "app_type"=>"Leave", "action_timestamp"=>$now, "approver_id"=>$requested_by, "requestor"=>$employee_id, 
				"employee_group_id"=>$app_flow_details[0]['employee_group_id'], "app_group_id"=>$app_flow_details[0]['app_group_id'], 
				"app_tree_id"=>$app_flow_details[0]['app_tree_id'], "remarks"=>"Force Leave by ".$requested_by_user,
	            "status_id"=>2, "is_active"=>1, "force_leave_id"=>$force_leave_id);
	            
	            $data = $this->lithefire->insertRow($db, "tbl_application_audit", $audit_details);
			}else{
				$leave_fields = array("employee_id"=>$employee_id, "date_from"=>$date_from,
	            "date_to"=>$date_to, "no_days"=>$no_days, "date_requested"=>$date_today,
	            "reason"=>$reason, "leave_type"=>$leave_type, "force_leave_id"=>$force_leave_id);
	
	            $leave_data = $this->lithefire->insertRow($db, "tbl_leave_application", $leave_fields);
				
				$apps_pk = $leave_data['id'];
				
				$audit_details = array("application_pk"=>$apps_pk, "app_type_id"=>2, "app_type"=>"Leave", "action_timestamp"=>$now, "approver_id"=>$requested_by, "requestor"=>$employee_id, 
				"employee_group_id"=>$app_flow_details[0]['employee_group_id'], "app_group_id"=>$app_flow_details[0]['app_group_id'], 
				"app_tree_id"=>$app_flow_details[0]['app_tree_id'], "remarks"=>"Force Leave by ".$requested_by_user,
	            "status_id"=>2, "is_active"=>1, "force_leave_id"=>$force_leave_id);
				
				$data = $this->lithefire->insertRow($db, "tbl_application_audit", $audit_details);
			}
		}elseif($leave_type == 2){
			if($no_days > $leave_credits_balance[0]['sick_leave']){
				$day_diff =number_format($no_days,2)-number_format($leave_credits_balance[0]['sick_leave'],2);
				$date_to = date('Y-m-d', strtotime($date_to."-$day_diff days"));
				
				$leave_fields = array("employee_id"=>$employee_id, "date_from"=>$date_from,
	            "date_to"=>$date_to, "no_days"=>$leave_credits_balance[0]['sick_leave'], "date_requested"=>$date_today,
	            "reason"=>$reason, "leave_type"=>$leave_type, "force_leave_id"=>$force_leave_id);
	
	            $leave_data = $this->lithefire->insertRow($db, "tbl_leave_application", $leave_fields);
				
				$apps_pk = $leave_data['id'];
				
				$audit_details = array("application_pk"=>$apps_pk,"app_type_id"=>2, "app_type"=>"Leave", "action_timestamp"=>$now, "approver_id"=>$requested_by, "requestor"=>$employee_id, 
				"employee_group_id"=>$app_flow_details[0]['employee_group_id'], "app_group_id"=>$app_flow_details[0]['app_group_id'], 
				"app_tree_id"=>$app_flow_details[0]['app_tree_id'], "remarks"=>"Force Leave by ".$requested_by_user,
	            "status_id"=>2, "is_active"=>1, "force_leave_id"=>$force_leave_id);
				
				$data = $this->lithefire->insertRow($db, "tbl_application_audit", $audit_details);
				
				$date_from = date('Y-m-d', strtotime($date_to."+1 day"));
				$date_to = date('Y-m-d', strtotime($date_to."+$day_diff days"));
				
				$leave_fields = array("employee_id"=>$employee_id, "date_from"=>$date_from,
	            "date_to"=>$date_to, "no_days"=>$day_diff, "date_requested"=>$date_today,
	            "reason"=>$reason, "leave_type"=>5, "force_leave_id"=>$force_leave_id);
	
	            $leave_data = $this->lithefire->insertRow($db, "tbl_leave_application", $leave_fields);
				
				$apps_pk = $leave_data['id'];
				
				$audit_details = array("application_pk"=>$apps_pk, "app_type_id"=>2, "app_type"=>"Leave", "action_timestamp"=>$now, "approver_id"=>$requested_by, "requestor"=>$employee_id, 
				"employee_group_id"=>$app_flow_details[0]['employee_group_id'], "app_group_id"=>$app_flow_details[0]['app_group_id'], 
				"app_tree_id"=>$app_flow_details[0]['app_tree_id'], "remarks"=>"Force Leave by ".$requested_by_user,
	            "status_id"=>2, "is_active"=>1, "force_leave_id"=>$force_leave_id);
	            
	            $data = $this->lithefire->insertRow($db, "tbl_application_audit", $audit_details);
			}else{
				$leave_fields = array("employee_id"=>$employee_id, "date_from"=>$date_from,
	            "date_to"=>$date_to, "no_days"=>$no_days, "date_requested"=>$date_today,
	            "reason"=>$reason, "leave_type"=>$leave_type, "force_leave_id"=>$force_leave_id);
	
	            $leave_data = $this->lithefire->insertRow($db, "tbl_leave_application", $leave_fields);
				
				$apps_pk = $leave_data['id'];
				
				$audit_details = array("application_pk"=>$apps_pk, "app_type_id"=>2, "app_type"=>"Leave", "action_timestamp"=>$now, "approver_id"=>$requested_by, "requestor"=>$employee_id, 
				"employee_group_id"=>$app_flow_details[0]['employee_group_id'], "app_group_id"=>$app_flow_details[0]['app_group_id'], 
				"app_tree_id"=>$app_flow_details[0]['app_tree_id'], "remarks"=>"Force Leave by ".$requested_by_user,
	            "status_id"=>2, "is_active"=>1, "force_leave_id"=>$force_leave_id);
				
				$data = $this->lithefire->insertRow($db, "tbl_application_audit", $audit_details);
			}
		}
			
		}
		
        die(json_encode($data));
     

        
    }
    
	function leaves(){

     //   redirect('main/maintenance');
        $data['title'] = 'HRIS: My DTR';


        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        
        $this->layout->view('admin/leaves_view', $data);
        
    }
    
    function getLeaves(){
    	$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');
		
		if(empty($date_from) || empty($date_to)){
			$data['success']=true;
			$data['data'] = array();
		die(json_encode($data));
		}
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = 'default';
        $filter = "(date_from between '$date_from' and '$date_to')";
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

        //$filter = "dtr_date BETWEEN '$date_from' AND '$date_to'";
		
		

        if(!empty($querystring)){
            $filter .= " AND (e.lastname LIKE '%$querystring%' OR e.firstname LIKE '%$querystring%' OR e.middlename LIKE '%$querystring%' OR a.date_requested LIKE '%$querystring%')";
        }


        $records = array();
		
		 $table = "tbl_leave_application a LEFT JOIN tbl_application_audit b ON a.id = b.application_pk AND app_type_id = 2 
		 LEFT JOIN tbl_app_status c ON b.status_id = c.id LEFT JOIN tbl_leave_type d ON a.leave_type = d.id
		 LEFT JOIN tbl_employee_info e ON a.employee_id = e.id";
        $fields = array("a.id", "a.date_from", "a.employee_id",
            "a.date_to", "a.no_days", "b.status_id", "c.description as status",
            "a.reason", "a.date_requested", "b.id as audit_id", "b.app_type", "d.description as leave_type",
			"CONCAT(e.lastname, ', ', e.firstname, ' ', e.middlename) AS employee_name");
        
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
    
    function getExemption(){
        $this->load->model('lithefire_model','lithefire',TRUE);
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

        //$filter = "dtr_date BETWEEN '$date_from' AND '$date_to'";
		
		

        if(!empty($querystring)){
            $filter = "(e.lastname LIKE '%$querystring%' OR e.firstname LIKE '%$querystring%' OR e.middlename LIKE '%$querystring%' OR a.date_requested LIKE '%$querystring%')";
        }


        $records = array();
		
		 $table = "tbl_exemption a LEFT JOIN tbl_employee_info e ON a.employee_id = e.id
		 LEFT JOIN tbl_employee_info f ON a.requested_by = f.id
		 LEFT JOIN tbl_app_type g ON a.app_type = g.id";
        $fields = array("a.id", "a.employee_id",
            "a.date_to", "a.reason", "a.date_requested", 
			"CONCAT(e.lastname, ', ', e.firstname, ' ', e.middlename) AS employee_name",
			"CONCAT(f.lastname, ', ', f.firstname, ' ', f.middlename) AS requested_by",
			"g.description as app_type");
        
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

	function exemption(){


        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        
        $this->layout->view('admin/exemption_view', $data);
        
    }

    function addExemption(){
      
        $this->load->model('lithefire_model', 'lithefire', TRUE);
		
		$db = "default";
        $table = "tbl_exemption";
        
        $input = $this->input->post();

        $input['date_requested'] = $this->date_today;
        $input['requested_by'] = $this->session->userData("userId");
		
		$date_to_filter = "OR date_to >= '".$input['date_to']."'";
		
		if(empty($input['date_to'])){
		$input['date_to'] = null;
			
			$date_to_filter = "";
		}
		$filter = "employee_id = '".$input['employee_id']."' AND app_type = '".$input['app_type']."' AND (date_to IS NULL $date_to_filter)";
        if($this->lithefire->countFilteredRows($db, $table, $filter, "")){
        	$data['success'] = false;
			$data['data'] = "Exemption already exists for this employee";
			die(json_encode($data));	
        }
        $data = $this->lithefire->insertRow($db, $table, $input);
        die(json_encode($data));
    }

    function loadExemption(){
        $this->load->model('lithefire_model','lithefire',TRUE);
      

        $id=$this->input->post('id');
        $table = "tbl_exemption";
        $filter = "id = $id";
        $fields = array();
		$db = "default";

        $records = array();
        $records = $this->lithefire->getRecordWhere($db, $table, $filter, $fields);

        $temp = array();

        foreach($records as $row):
			$employee = $this->lithefire->getRecordWhere($db, "tbl_employee_info", "id = ".$row['employee_id'], array("lastname", "firstname"));
			$row['employee_combo'] = $employee[0]['lastname'].", ".$employee[0]['firstname'];
			$row['app_type_id'] = $this->lithefire->getFieldWhere($db, "tbl_app_type", "id = ".$row['app_type'], "description");
            $data["data"] = $row;


        endforeach;
        if(!empty($data['data']['date_to'])){
        	$data['success'] = false;
			$data['data'] = "Exemption date limit already set for this entry.";
        	die(json_encode($data));
        }
        $data['success'] = true;

        die(json_encode($data));
    }

    function updateExemption(){
        $this->load->model('commonmodel', '', TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_exemption";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $input = array();
        foreach($this->input->post() as $key => $val){
            if($key == 'id')
                continue;
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        

        $input['modified_by'] = $this->session->userData("userId");
        $data = $this->admin_model->updateRow($table, $input, $param, $id);


        die(json_encode($data));
    }
	
	function expireExemption(){
        $this->load->model('commonmodel', '', TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_memo";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $input = array();
        foreach($this->input->post() as $key => $val){
            if($key == 'id')
                continue;
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        

        $input['modified_by'] = $this->session->userData("userId");
        $data = $this->admin_model->updateRow($table, $input, $param, $id);


        die(json_encode($data));
    }

    function deleteExemption(){
        $this->load->model('commonmodel', '', TRUE);
        $this->load->model('admin_model', '', TRUE);

        $table = "tbl_memo";
        $param = "id";
       // $fields = $this->input->post();

        $id=$this->input->post('id');

        $data = $this->admin_model->deleteRow($table, $param, $id);

        die(json_encode($data));
    }
	
	function appTypeCombo(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = 'default';
        $filter = "";
        $group = "";
        
		

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');



        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');


        if(empty($sort) && empty($dir)){
            $sort = "description ASC";
        }else{
            $sort = "$sort $dir";
        }

        //$filter = "dtr_date BETWEEN '$date_from' AND '$date_to'";
		
		

        if(!empty($querystring)){
            $filter = "(description LIKE '%$querystring%' OR id LIKE '%$querystring%')";
        }


        $records = array();
		
		 $table = "tbl_app_type";
        $fields = array("id", "description as name");
        
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

	function viewForceLeave(){
		$this->load->model('lithefire_model','lithefire',TRUE);
      

        $id=$this->input->post('id');
        $table = "tbl_force_leave";
        $filter = "id = $id";
        $fields = array();
		$db = "default";

        $records = array();
        $records = $this->lithefire->getRecordWhere($db, $table, $filter, $fields);

        $temp = array();

        foreach($records as $row):
        if($row['employee_id'] != 0){
			$employee = $this->lithefire->getRecordWhere($db, "tbl_employee_info", "id = ".$row['employee_id'], array("lastname", "firstname"));
			$row['employee_name'] = $employee[0]['lastname'].", ".$employee[0]['firstname'];
		}else{
			$row['employee_name'] = "All Employees";
		}
			$row['leave_type'] = $this->lithefire->getFieldWhere($db, "tbl_leave_type", "id = ".$row['leave_type'], "description");
            $data["data"] = $row;


        endforeach;
        $data['success'] = true;

        die(json_encode($data));
	}
	
	function voidForceLeave(){
        $this->load->model('lithefire_model','lithefire',TRUE);

        $db = "default";
        $table = "tbl_force_leave";
		$id = $this->input->post("id");
		
		$filter = "id = $id";
		
		$input = array("status"=>"Cancelled", "modified_by"=>$this->session->userData("userId"));
		
		if($this->lithefire->countFilteredRows($db, $table, $filter." AND status = 'Cancelled'", "")){
			$data['success'] = false;
			$data['data'] = "Application cannot be cancelled";
			die(json_encode($data));
		}
      
        $data = $this->lithefire->updateRow($db, $table, $input, $filter);
		
		$this->lithefire->updateRow($db, 'tbl_application_audit', array("status_id"=>4), "force_leave_id = '$id'");

   
   

        die(json_encode($data));
    }

	function leaveTypeCombo(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = 'default';
        $filter = "";
        $group = "";
        
		

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');



        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');


        if(empty($sort) && empty($dir)){
            $sort = "description ASC";
        }else{
            $sort = "$sort $dir";
        }

        $filter = "id in (1,2)";
		
		

        if(!empty($querystring)){
            $filter .= " AND (description LIKE '%$querystring%' OR id LIKE '%$querystring%')";
        }


        $records = array();
		
		 $table = "tbl_leave_type";
        $fields = array("id", "description as name");
        
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

	function leaveReset(){

		$year = date("Y");
		$input_string = ""; 
		for($i = $year-1; $i <= $year+5; $i++):
				if($i == $year+5){
					$input_string.="['$i', '$i']";
				}else{
					$input_string.="['$i', '$i'],";
				}
		endfor;
		$data['input_string'] = $input_string;

        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        
        $this->layout->view('admin/leave_reset_view', $data);
        
    }
	
	function getLeaveReset(){
    	
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = 'default';
        $filter = "";
        $group = "";
        
		

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');



        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');


        if(empty($sort) && empty($dir)){
            $sort = "year DESC";
        }else{
            $sort = "$sort $dir";
        }

        //$filter = "dtr_date BETWEEN '$date_from' AND '$date_to'";
		
		

        if(!empty($querystring)){
            $filter = "(year LIKE '%$querystring%' OR reset_date LIKE '%$querystring%')";
        }


        $records = array();
		
		 $table = "tbl_leave_reset";
        $fields = array("id", "year", "reset_date");
        
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

	function addLeaveResetDate(){
      
        $this->load->model('lithefire_model', 'lithefire', TRUE);
		
		$db = "default";
        $table = "tbl_leave_reset";
        
        $input = $this->input->post();


		$filter = "year = '".$input['year']."'";
        if($this->lithefire->countFilteredRows($db, $table, $filter, "")){
        	$data['success'] = false;
			$data['data'] = "Reset date already exists for this year";
			die(json_encode($data));	
        }
		
		$filter = "year <= '".$input['year']."' AND reset_date >= '".$input['reset_date']."'";
        if($this->lithefire->countFilteredRows($db, $table, $filter, "")){
        	//die($this->lithefire->currentQuery());
        	$data['success'] = false;
			$data['data'] = "Reset date for year ".$input['year']." cannot be less than or equal to previous dates";
			die(json_encode($data));	
        }
		
		/*$filter = "year >= '".$input['year']."' AND reset_date <= ".$input['reset_date'];
        if($this->lithefire->countFilteredRows($db, $table, $filter, "")){
        	$data['success'] = false;
			$data['data'] = "Reset date already exists for this year";
			die(json_encode($data));	
        }*/
        $data = $this->lithefire->insertRow($db, $table, $input);
        die(json_encode($data));
    }

	function updateLeaveReset(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = "default";
        $table = "tbl_leave_reset";

        $id = $this->input->post('id');
    
		$input = $this->input->post();
		
        $filter = "year = '".$input['year']."' AND id != $id";
        if($this->lithefire->countFilteredRows($db, $table, $filter, "")){
        	$data['success'] = false;
			$data['data'] = "Reset date already exists for this year";
			die(json_encode($data));	
        }

	/*	$filter = "year <= '".$input['year']."' AND reset_date >= ".$input['reset_date']." AND id != $id";
        if($this->lithefire->countFilteredRows($db, $table, $filter, "")){
        	$data['success'] = false;
			$data['data'] = "Reset date for year ".$input['year']." cannot be less than or equal to previous dates";
			die(json_encode($data));	
        }*/
		
        $update = array("year"=>$input['year'], "reset_date"=>$input['reset_date']);
        $filter = "id = '$id'";

        $data = $this->lithefire->updateRow($db, $table, $update, $filter);
        //$data = $this->lithefire->updateRow($db, "module_group_users", $input, "username = '$old_username'");
        //$data['data'] = "Username successfully updated";

        die(json_encode($data));

    }
	
	function loadLeaveReset(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = "default";
        $table = "tbl_leave_reset";
        $fields = "id, year, reset_date";

        $id=$this->input->post('id');

        $filter = "id = '$id'";
        //$filter.=" AND a.COURIDNO = FILECOUR.COURIDNO AND a.CITIIDNO = FILECITI.CITIIDNO AND a.RELIIDNO = FILERELI.RELIIDNO";

        $records = array();
        $records = $this->lithefire->getRecordWhere($db, $table, $filter, $fields);



        $temp = array();

        foreach($records as $row):
            
            $data["data"] = $row;


        endforeach;

       // $data['data'] = $temp;
        $data['success'] = true;

        die(json_encode($data));
    }

	
}

?>
