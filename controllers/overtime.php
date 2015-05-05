<?php
class Overtime extends MY_Controller{

    function Overtime(){
        parent::__construct();
	    $this->load->model('ot_model', '', TRUE);

    }
    function index(){
 

        $data['title'] = 'Leaves';


        
        $this->layout->view('leaves/leaves_view', $data);
        
    }

    function getOT(){

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');

        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";
        $employee_id = $this->session->userdata('userId');

        if(empty($sort) && empty($dir)){
            $sort = "id";
            $dir = "DESC";
        }

        if(!empty($query)){
            $queryby = array("date_from", "date_to", "status");
        }

        //$filter = array("employee_id"=>$this->session->userdata('userId'));

        //$join = array("tbl_app_status b", "a.status_id = b.id");

        //$records = array();
        $records = $this->ot_model->getOTByEmployee($employee_id, $start, $limit, $sort, $dir, $query, $queryby);

        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->ot_model->countOTByEmployee($employee_id);
        die(json_encode($data));
    }

    function applyOT(){
        $this->load->model('apps_model', '', TRUE);
		$this->load->model('lithefire_model', 'lithefire', TRUE);
		$date_ot = $this->input->post("date_to");
		$employee_id = $this->session->userdata('userId');
		$today = date("Y-m-d");
		$exempted = $this->lithefire->getAllRecords("default", "tbl_exemption", "employee_id", "", "", "", "(date_to is NULL OR date_to >= '$today') AND app_type = 1", "", "");
		
		$exemptions = array();
		if(!empty($exempted)){
		foreach($exempted as $employee):
			$exemptions[] = $employee['employee_id'];
		endforeach;
		}
		
		if(!in_array($employee_id, $exemptions) || empty($exemptions)){
			$days = (strtotime($today)-strtotime($date_ot))/86400;
			
			//die($days);
			if($days > 3){
						$data['success'] = false;
						$data['data'] = "Over-time applications must be filed not more than 3 days from rendered hours.";
						die(json_encode($data));
					}
		}

        $date_from = date("Y-m-d H:i:s", strtotime($this->input->post("date_from")." ".$this->input->post("time_from")));
        $date_to = date("Y-m-d H:i:s", strtotime($this->input->post("date_to")." ".$this->input->post("time_to")));
        $no_hours = $this->input->post('no_of_hours');
        $reason = $this->input->post('reason');


        $today = date("Y-m-d");
        $now = date("Y-m-d H:i:s");

        $emp_group = $this->apps_model->getEmpGroup($employee_id);

        $app_flow_details = $this->apps_model->getAppFlowDetails($emp_group, 1);

        //die(print_r($app_flow_details));



        $fields = array("employee_id"=>$employee_id, "date_from"=>$date_from,
            "date_to"=>$date_to, "no_hours"=>$no_hours, "date_requested"=>$today,
            "reason"=>$reason);

        $audit_details = array("app_type_id"=>1, "app_type"=>"OT", "action_timestamp"=>$now, "requestor"=>$employee_id, "employee_group_id"=>$app_flow_details[0]['employee_group_id'],
            "app_group_id"=>$app_flow_details[0]['app_group_id'], "app_tree_id"=>$app_flow_details[0]['app_tree_id'],
            "status_id"=>1, "is_active"=>1);

        $data = $this->ot_model->insertOT($fields, $audit_details);
        die(json_encode($data));
    }

    function getCS(){
        $this->load->model('fr_model', '', TRUE);
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');

        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";
        $employee_id = $this->session->userdata('userId');

        if(empty($sort) && empty($dir)){
            $sort = "id";
            $dir = "DESC";
        }

        if(!empty($query)){
            $queryby = array("date_from", "date_to", "status");
        }

        //$filter = array("employee_id"=>$this->session->userdata('userId'));

        //$join = array("tbl_app_status b", "a.status_id = b.id");

        //$records = array();
        $records = $this->ot_model->getCSByEmployee($employee_id, $start, $limit, $sort, $dir, $query, $queryby);

        $temp = array();
        if($records){
        foreach($records as $row):

        if($row['type'] == "Client"){

        $row['contact'] = $this->commonmodel->getFieldWhere("fr", "contacts", "CONTIDNO", $row['contact_person_id'], "CONTACTNAME");

        $row['client'] = $this->commonmodel->getFieldWhere("fr", "clients", "clieidno", $row['client_id'], "clientname");

        }elseif($row['type'] == 'Supplier'){

        $row['contact'] = $this->commonmodel->getFieldWhere("fr", "supplier_contacts", "CONTIDNO", $row['contact_person_id'], "CONTACTNAME");

        $row['client'] = $this->commonmodel->getFieldWhere("fr", "supplier", "SUPPIDNO", $row['client_id'], "SUPPLIERNAME");
        }

        $row['purpose'] = $this->commonmodel->getFieldWhere("fr", "fileclientpurpose", "fileclientpurposeid", $row['purpose_id'], "clientpurpose");

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $filter = array("employee_id"=>$employee_id);
        $data['totalCount'] = $this->lithefire->countFilteredRows("default", "tbl_client_schedule", $filter, "");
        die(json_encode($data));
    }

    function getTraining(){
        $this->load->model('fr_model', '', TRUE);
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');

        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";
        $employee_id = $this->session->userdata('userId');

        if(empty($sort) && empty($dir)){
            $sort = "id";
            $dir = "DESC";
        }

        if(!empty($query)){
            $queryby = array("date_from", "date_to", "status");
        }

        //$filter = array("employee_id"=>$this->session->userdata('userId'));

        //$join = array("tbl_app_status b", "a.status_id = b.id");

        //$records = array();
        $records = $this->ot_model->getTrainingByEmployee($employee_id, $start, $limit, $sort, $dir, $query, $queryby);

        $temp = array();
        if($records){
        foreach($records as $row):

        if($row['type'] == "Client"){

        //$row['contact'] = $this->commonmodel->getFieldWhere("fr", "contacts", "CONTIDNO", $row['contact_person_id'], "CONTACTNAME");

        $row['client'] = $this->commonmodel->getFieldWhere("fr", "clients", "clieidno", $row['supplier_id'], "clientname");

        }elseif($row['type'] == 'Supplier'){

        //$row['contact'] = $this->commonmodel->getFieldWhere("fr", "supplier_contacts", "CONTIDNO", $row['contact_person_id'], "CONTACTNAME");

        $row['client'] = $this->commonmodel->getFieldWhere("fr", "supplier", "SUPPIDNO", $row['supplier_id'], "SUPPLIERNAME");
        }

        $row['training_type'] = $this->commonmodel->getFieldWhere("fr", "filetrainingtype", "filetrainingtypeid", $row['training_type_id'], "trainingtype");

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $filter = array("employee_id"=>$employee_id);
        $data['totalCount'] = $this->lithefire->countFilteredRows("default", "tbl_training", $filter, "");
        die(json_encode($data));
    }

    function applyCS(){
    	$this->load->model('lithefire_model', 'lithefire', TRUE);
        $date_scheduled = $this->input->post("date_scheduled");
        $employee_id = $this->session->userdata('userId');
		$today = date("Y-m-d");
		$exempted = $this->lithefire->getAllRecords("default", "tbl_exemption", "employee_id", "", "", "", "(date_to is NULL OR date_to >= '$today') AND app_type = 4", "", "");
		
		$exemptions = array();
		
		if($exempted){
		foreach($exempted as $employee):
			$exemptions[] = $employee['employee_id'];
		endforeach;
		}
		
		if(!in_array($employee_id, $exemptions)){
			$days = (strtotime($date_scheduled)-strtotime($today))/86400;
			if($days < 1){
						$data['success'] = false;
						$data['data'] = "Client Schedules must be applied at least 1 day before scheduled date";
						die(json_encode($data));
					}
		}

        $time_in = date("H:i:s", strtotime($this->input->post("time_in")));
        $time_out = date("H:i:s", strtotime($this->input->post("time_out")));
        $type = $this->input->post("type");
        
        $purpose = $this->input->post("purpose_id");
        $purpose_id = $this->commonmodel->getFieldWhere("fr", "fileclientpurpose", "clientpurpose", $purpose, "fileclientpurposeid");

        

        $contact = $this->input->post("contact_person_id");
        $client = $this->input->post("client_id");

        if($type == "Client"){

        $contact_id = $this->commonmodel->getFieldWhere("fr", "contacts", "CONTACTNAME", $contact, "CONTIDNO");

        $client_id = $this->commonmodel->getFieldWhere("fr", "clients", "clientname", $client, "clieidno");

        }elseif($type == 'Supplier'){

        $contact_id = $this->commonmodel->getFieldWhere("fr", "supplier_contacts", "CONTACTNAME", $contact, "CONTIDNO");

        $client_id = $this->commonmodel->getFieldWhere("fr", "supplier", "SUPPLIERNAME", $client, "SUPPIDNO");
        }

        $reason = $this->input->post('reason');
        

        $today = date("Y-m-d");
        $now = date("Y-m-d H:i:s");

        $this->load->model('apps_model', '', TRUE);

        $emp_group = $this->apps_model->getEmpGroup($employee_id);

        $app_flow_details = $this->apps_model->getAppFlowDetails($emp_group, 4);

        //die(print_r($app_flow_details));



        $fields = array("employee_id"=>$employee_id, "time_in"=>$time_in,
            "time_out"=>$time_out, "date_scheduled"=>$date_scheduled, "date_requested"=>$today,
            "agenda"=>$reason, "type"=>$type, "client_id"=>$client_id, "contact_person_id"=>$contact_id,
            "purpose_id"=>$purpose_id);

        $audit_details = array("app_type_id"=>4, "app_type"=>"Client Schedule", "action_timestamp"=>$now, "requestor"=>$employee_id, "employee_group_id"=>$app_flow_details[0]['employee_group_id'],
            "app_group_id"=>$app_flow_details[0]['app_group_id'], "app_tree_id"=>$app_flow_details[0]['app_tree_id'],
            "status_id"=>1, "is_active"=>1);

        $data = $this->ot_model->insertCS($fields, $audit_details);
        die(json_encode($data));
    }

	function applyTITO(){
        $this->load->model('apps_model', '', TRUE);

        //$date_from = date("Y-m-d H:i:s", strtotime($this->input->post("date_from")." ".$this->input->post("time_from")));
        //$date_to = date("Y-m-d H:i:s", strtotime($this->input->post("date_to")." ".$this->input->post("time_to")));
        $date_time_in = $this->input->post("date_time_in");
		$time_in = $this->input->post("time_in");
		$date_time_out = $this->input->post("date_time_out");
		$time_out = $this->input->post("time_out");
        
        $reason = $this->input->post('reason');
        $employee_id = $this->session->userdata('userId');

        $today = date("Y-m-d");
        $now = date("Y-m-d H:i:s");

        $emp_group = $this->apps_model->getEmpGroup($employee_id);

        $app_flow_details = $this->apps_model->getAppFlowDetails($emp_group, 5);

        //die(print_r($app_flow_details));



        $fields = array("employee_id"=>$employee_id, "date_time_in"=>$date_time_in, "time_in"=>$time_in,
            "date_time_out"=>$date_time_out, "time_out"=>$time_out, "date_requested"=>$today,
            "reason"=>$reason);

        $audit_details = array("app_type_id"=>5, "app_type"=>"TITO", "action_timestamp"=>$now, "requestor"=>$employee_id, "employee_group_id"=>$app_flow_details[0]['employee_group_id'],
            "app_group_id"=>$app_flow_details[0]['app_group_id'], "app_tree_id"=>$app_flow_details[0]['app_tree_id'],
            "status_id"=>1, "is_active"=>1);

        $data = $this->ot_model->insertTITO($fields, $audit_details);
        die(json_encode($data));
    }

	function applyTraining(){
    	$this->load->model('lithefire_model', 'lithefire', TRUE);
        $date_start = $this->input->post("date_start");
		$date_end = $this->input->post("date_end");
        $employee_id = $this->session->userdata('userId');
		$today = date("Y-m-d");
		$exempted = $this->lithefire->getAllRecords("default", "tbl_exemption", "employee_id", "", "", "", "(date_to is NULL OR date_to >= '$today') AND app_type = 6", "", "");
		$exemptions = array();
		if($exempted){
		foreach($exempted as $employee):
			$exemptions[] = $employee['employee_id'];
		endforeach;
		}
		
		if(!in_array($employee_id, $exemptions)){
			$days = (strtotime($date_start)-strtotime($today))/86400;
			if($days < 1){
						$data['success'] = false;
						$data['data'] = "Trainings/Seminars must be applied at least 1 day before scheduled date";
						die(json_encode($data));
					}
		}

        $time_in = date("H:i:s", strtotime($this->input->post("time_in")));
        $time_out = date("H:i:s", strtotime($this->input->post("time_out")));
        $type = $this->input->post("type");
		$location = $this->input->post("address");
        
        
       $training_type_id = $this->input->post("training_type_id");

        
        $client = $this->input->post("client_id");

        if($type == "Client"){

       

        $client_id = $this->commonmodel->getFieldWhere("fr", "clients", "clientname", $client, "clieidno");

        }elseif($type == 'Supplier'){

        

        $client_id = $this->commonmodel->getFieldWhere("fr", "supplier", "SUPPLIERNAME", $client, "SUPPIDNO");
        }

        $title = $this->input->post('title');
        $details = $this->input->post('details');
        

        $today = date("Y-m-d");
        $now = date("Y-m-d H:i:s");

        $this->load->model('apps_model', '', TRUE);

        $emp_group = $this->apps_model->getEmpGroup($employee_id);

        $app_flow_details = $this->apps_model->getAppFlowDetails($emp_group, 6);

        //die(print_r($app_flow_details));



        $fields = array("employee_id"=>$employee_id, "start_time"=>$time_in,
            "end_time"=>$time_out, "date_start"=>$date_start, "date_end"=>$date_end, "date_requested"=>$today,
            "details"=>$details, "title"=>$title, "type"=>$type, "supplier_id"=>$client_id, 
            "training_type_id"=>$training_type_id, "location"=>$location);

        $audit_details = array("app_type_id"=>6, "app_type"=>"Training", "action_timestamp"=>$now, "requestor"=>$employee_id, "employee_group_id"=>$app_flow_details[0]['employee_group_id'],
            "app_group_id"=>$app_flow_details[0]['app_group_id'], "app_tree_id"=>$app_flow_details[0]['app_tree_id'],
            "status_id"=>1, "is_active"=>1);

        $data = $this->ot_model->insertTraining($fields, $audit_details);
        die(json_encode($data));
    }

	function getTITO(){
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
            $sort = "a.id DESC";
        }else{
            $sort = "$sort $dir";
        }

       $filter = "employee_id = '".$this->session->userData('userId')."' AND is_active = 1";

        if(!empty($querystring)){
            $filter = "(dtr_date LIKE '%$querystring%' OR dtr_time LIKE '%$querystring%')";
        }


        $records = array();
        $table = "tbl_tito_application a LEFT JOIN tbl_application_audit b ON a.id=b.application_pk AND app_type_id = 5 LEFT JOIN tbl_app_status c ON b.status_id = c.id";
        $fields = array("a.id", "a.date_time_in", "a.time_in", "a.date_time_out", "a.time_out", "a.reason", "b.id as audit_id", "b.app_type", "c.description as status", "a.date_requested");

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):
			//$row['name'] = $row['lastname'].", ".$row['firstname']." ".$row['middlename'];
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
