<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Apps extends MY_Controller{

    function Apps(){
        parent::__construct();

        
    }
    function myRequest(){

        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        $this->layout->view('apps/my_request_view', $data);
    }

    function checkEmployeeFlow(){
        $type = $this->input->post('type');
        $this->load->model('apps_model', '', TRUE);

        
        $user_id = $this->session->userdata('userId');
        $emp_group = $this->apps_model->getEmpGroup($user_id);

        if(empty($emp_group)){
            $data['success'] = false;
            $data['msg'] = "Employee Group Not Set";
            die(json_encode($data));
        }

        $app_flow = $this->apps_model->getAppFlow($emp_group, $type);

        if(empty($app_flow)){
            $data['success'] = false;
            $data['msg'] = "Application From Flow Not Set";
            die(json_encode($data));
        }

        $approvers = $this->apps_model->getApprovers($emp_group, $type);
		
		if(empty($approvers)){
            $data['success'] = false;
            $data['msg'] = "Approver group not set";
            die(json_encode($data));
        }
		
        $leave_credits = array();
        if($type == 2){
        	$today = date("Y-m-d");
            $year_array = $this->lithefire->getRecordWhere("default", "tbl_leave_reset", "reset_date <= '$today'", array("MAX(year) as year", "MAX(reset_date) as reset_date"));
			//die($this->lithefire->currentQuery());
			$year = $year_array[0]['year'];
			$reset_date = $year_array[0]['reset_date'];
            $this->load->model('employee_model', '', TRUE);
			
            $leave_credits_balance = $this->employee_model->getLeaveCredits($user_id, $year);

            $leave_credits_used = $this->employee_model->getUsedLeaves($user_id, $reset_date);
            //die(print_r($leave_credits_used));

            $table = "tbl_leave_type";
            $fields = array("description");

            $records = $this->lithefire->getAllRecords("default", $table, $fields, 0, 100, 'description ASC', '', '', '');

//die(print_r($records));
            foreach($records as $row):
                $leaves[$row['description']] = 0;
            endforeach;
			if($leave_credits_used){
            foreach($leave_credits_used as $row):
                $leaves[$row['description']] = $row['days'];
            endforeach;
            }
            $leave_credits['vacation_leave'] = $leave_credits_balance[0]['vacation_leave']-$leaves['Vacation Leave'];
            $leave_credits['vacation_leave_used'] = $leaves['Vacation Leave'];

            $leave_credits['sick_leave'] = $leave_credits_balance[0]['sick_leave']-$leaves['Sick Leave'];
            $leave_credits['sick_leave_used'] = $leaves['Sick Leave'];

            $leave_credits['emergency_leave'] = $leave_credits_balance[0]['emergency_leave']-$leaves['Emergency Leave'];
            $leave_credits['emergency_leave_used'] = $leaves['Emergency Leave'];

           // $leave_credits['unpaid_vacation_leave'] = $leave_credits_balance[0]['unpaid_vacation_leave']-$leaves['Unpaid Vacation Leave'];
            $leave_credits['unpaid_vacation_leave_used'] = $leaves['Unpaid Vacation Leave'];

           // $leave_credits['unpaid_sick_leave'] = $leave_credits_balance[0]['unpaid_sick_leave']-$leaves['Unpaid Sick Leave'];
            $leave_credits['unpaid_sick_leave_used'] = $leaves['Unpaid Sick Leave'];
			
			$leave_credits['maternity_leave'] = $leave_credits_balance[0]['maternity_leave']-$leaves['Maternity Leave'];
            $leave_credits['maternity_leave_used'] = $leaves['Maternity Leave'];
			
			$leave_credits['paternity_leave'] = $leave_credits_balance[0]['paternity_leave']-$leaves['Paternity Leave'];
            $leave_credits['paternity_leave_used'] = $leaves['Paternity Leave'];

            $leave_credits['leave_title'] = "Leave Credits for $year";
            //$leave_credits = $leave_credits_balance;
        }

        $data['approvers'] = $approvers;
        $data['success'] = true;
        $data['data'] = $leave_credits;
        die(json_encode($data));
    }

    function myApproval(){
        
        $data['header'] = 'Header Section';
        $data['footer'] = 'Footer Section';
        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');

        
        $this->layout->view('apps/approve_application_view', $data);
        
    }

    function getPendingApplications(){
        $this->load->model('apps_model', '', TRUE);
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');

        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";
        $approver_id = $this->session->userdata('userId');

        if(empty($sort) && empty($dir)){
            $sort = "date_requested";
            $dir = "DESC";
        }

        if(!empty($query)){
            $queryby = array("date_from", "date_to", "status");
        }

        //$filter = array("employee_id"=>$this->session->userdata('userId'));

        //$join = array("tbl_app_status b", "a.status_id = b.id");

        //$records = array();
        $records = $this->apps_model->getPendingApplicationsPerApprover($approver_id, $start, $limit, $sort, $dir, $query);
        //die(print_r($records));
        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->apps_model->countApplicationsByApprover($approver_id);
        die(json_encode($data));
    }

    function viewApplication(){
        $this->load->model('apps_model', '', TRUE);
		
        $audit_id = $this->input->post('id');
		$apps_pk = $this->input->post('pk');
        $app_type = $this->input->post('app_type');
		
		if(empty($audit_id)){
			$audit_id = $this->lithefire->getFieldWhere("default", "tbl_application_audit", "app_type = '$app_type' AND application_pk = '$apps_pk'", "id");
		}
		
       
        //$app_flow

        $audit_details = $this->apps_model->getAuditDetails($audit_id);

        $approvers = $this->apps_model->getApprovers($audit_details[0]['employee_group_id'], $audit_details[0]['app_type_id']);

        $approver_details = array();
        $approver_details = $this->apps_model->getAuditApproverDetails($apps_pk, $app_type);
        //$approver_details[] = array("approver"=>"test", "action_timestamp"=>"12345", "status"=>"Pending", "remarks"=>"test");
        
        $details = array();
        switch($app_type):
            case "Leave": $this->load->model('leaves_model', '', TRUE);
                           $details = $this->leaves_model->getLeaveDetails($audit_details[0]['application_pk']);
                          break;
            case "OT": $this->load->model('ot_model', '', TRUE);
                           $details = $this->ot_model->getOTDetails($audit_details[0]['application_pk']);
                          break;
			case "TITO": $this->load->model('ot_model', '', TRUE);
                           $details = $this->ot_model->getTITODetails($audit_details[0]['application_pk']);
                          break;			  
            case "Client Schedule": $this->load->model('ot_model', '', TRUE);
                           $records = $this->ot_model->getCSDetails($audit_details[0]['application_pk']);
                           foreach($records as $row):
                                $row['purpose'] = $this->commonmodel->getFieldWhere("fr", "fileclientpurpose", "fileclientpurposeid", $row['purpose_id'], "clientpurpose");
                                if($row['type'] == 'Client'){
                                $row['client'] = $this->commonmodel->getFieldWhere("fr", "clients", "clieidno", $row['client_id'], "clientname");
                                $row['contact'] = $this->commonmodel->getFieldWhere("fr", "contacts", "CONTIDNO", $row['contact_person_id'], "CONTACTNAME");
                                }elseif($row['type'] == 'Supplier'){
                                    $row['client'] = $this->commonmodel->getFieldWhere("fr", "supplier", "SUPPIDNO", $row['client_id'], "SUPPLIERNAME");
                                    $row['contact'] = $this->commonmodel->getFieldWhere("fr", "supplier_contacts", "CONTIDNO", $row['contact_person_id'], "CONTACTNAME");
                                }
                               $details = $row;
                           endforeach;
						   break;
			case "Training": $this->load->model('ot_model', '', TRUE);
                           $records = $this->ot_model->getTrainingDetails($audit_details[0]['application_pk']);
                           foreach($records as $row):
                                $row['training_type'] = $this->commonmodel->getFieldWhere("fr", "fileclientpurpose", "fileclientpurposeid", $row['training_type_id'], "clientpurpose");
                                if($row['type'] == 'Client'){
                                $row['client'] = $this->commonmodel->getFieldWhere("fr", "clients", "clieidno", $row['supplier_id'], "clientname");
                               // $row['contact'] = $this->commonmodel->getFieldWhere("fr", "contacts", "CONTIDNO", $row['contact_person_id'], "CONTACTNAME");
                                }elseif($row['type'] == 'Supplier'){
                                    $row['client'] = $this->commonmodel->getFieldWhere("fr", "supplier", "SUPPIDNO", $row['supplier_id'], "SUPPLIERNAME");
                                //    $row['contact'] = $this->commonmodel->getFieldWhere("fr", "supplier_contacts", "CONTIDNO", $row['contact_person_id'], "CONTACTNAME");
                                }
								$row['training_type'] = $this->commonmodel->getFieldWhere("fr", "filetrainingtype", "filetrainingtypeid", $row['training_type_id'], "trainingtype");
                               $details = $row;
                           endforeach;			   
						   
                          break;          
        endswitch;
        //die(print_r($details));
        $data['approvers'] = $approvers;
        $data['approver_details'] = $approver_details;
        $data['data'] = $details;
        $data['success'] = true;
        die(json_encode($data));
    }

    function approveApplication(){
        $audit_id = $this->input->post('audit_id');
        $remarks = $this->input->post('reason');
        $approver_id = $this->session->userdata('userId');
        $db = "default";
        $table = "tbl_application_audit";
        $param = "id";

        $today = date("Y-m-d");
        $now = date("Y-m-d H:i:s");


        $this->load->model('apps_model', '', TRUE);

        $audit_details = $this->apps_model->getAuditDetails($audit_id);

        $next_approver = $this->apps_model->getNextApprover($audit_details[0]['app_tree_id'], $audit_details[0]['app_group_id']);

        //die(print_r($next_approver));
        if($next_approver != null){
            $update_audit = array("action_timestamp"=>$now, "approver_id"=>$approver_id, "remarks"=>$remarks, "is_active"=>0);
            $data = $this->commonmodel->updateRecord($db, $table, $update_audit, $param, $audit_id);
            $audit_array = array("application_pk"=>$audit_details[0]["application_pk"], "app_type_id"=>$audit_details[0]["app_type_id"],
                "app_type"=>$audit_details[0]["app_type"], "action_timestamp"=>$now, "requestor"=>$audit_details[0]["requestor"],
                "employee_group_id"=>$audit_details[0]["employee_group_id"], "app_group_id"=>$next_approver[0]["app_group_id"],
                "app_tree_id"=>$audit_details[0]["app_tree_id"], "status_id"=>1, "is_active"=>1);
            $data = $this->apps_model->insertAuditDetails($audit_array);
            $data['data'] = "Application approved successfully.";
            die(json_encode($data));
        }else{
            $update_audit = array("action_timestamp"=>$now, "approver_id"=>$approver_id, "remarks"=>$remarks, "status_id"=>2);
            $data = $this->commonmodel->updateRecord($db, $table, $update_audit, $param, $audit_id);
            $data['data'] = "Application approved successfully.";
            die(json_encode($data));
        }
    }

    function denyApplication(){
        $audit_id = $this->input->post('audit_id');
        $remarks = $this->input->post('reason');
        $approver_id = $this->session->userdata('userId');
        $db = "default";
        $table = "tbl_application_audit";
        $param = "id";

        $today = date("Y-m-d");
        $now = date("Y-m-d H:i:s");


        $this->load->model('apps_model', '', TRUE);

        $audit_details = $this->apps_model->getAuditDetails($audit_id);

            $update_audit = array("action_timestamp"=>$now, "approver_id"=>$approver_id, "remarks"=>$remarks, "status_id"=>3);
            $data = $this->commonmodel->updateRecord($db, $table, $update_audit, $param, $audit_id);
            $data['data'] = "Application denied successfully.";
            die(json_encode($data));
        
    }

    function voidApplication(){
        $audit_id = $this->input->post('audit_id');

        $db = "default";
        $table = "tbl_application_audit";
        $param = "id";

        $today = date("Y-m-d");
        $now = date("Y-m-d H:i:s");


        $this->load->model('apps_model', '', TRUE);
		$this->load->model('lithefire_model', 'lithefire', TRUE);

        $audit_details = $this->apps_model->getAuditDetails($audit_id);
		//die(print_r($audit_details));
		
		$apps_pk = $audit_details[0]['application_pk'];
		
		if(in_array($audit_details[0]['status_id'], array(3,4,5))){
			$data['success'] =false;
			$data['data'] = "Application cannot be cancelled.";
            die(json_encode($data));
		}
		if($audit_details[0]['app_type_id'] == 2){
		$force_leave_id = $this->lithefire->getFieldWhere("default", "tbl_leave_application", "id = ".$audit_details[0]['application_pk'], "force_leave_id");
		if(!empty($force_leave_id)){
			$data['success'] =false;
			$data['data'] = "Force leaves cannot be cancelled by user, please contact the administrator";
            die(json_encode($data));
		}
		
		$this->lithefire->updateRow("default", "tbl_call_log", array("leave_filed"=>0), "leave_id = '$apps_pk'");
		}
		
            $update_audit = array("action_timestamp"=>$now, "status_id"=>4, "voided_by"=>$this->session->userData("userId"));
            $data = $this->commonmodel->updateRecord($db, $table, $update_audit, $param, $audit_id);
            $data['data'] = "Application cancelled successfully.";
            die(json_encode($data));

    }

    function getleaveTypeCombo(){
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');
        $db = "default";
		$filter = "";
		$group = "";
		$having = "";


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";
		
		if(!empty($query))
        $filter = " (description LIKE '%$querystring%' OR id LIKE '%$querystring%')";

        if(empty($sort) && empty($dir)){
            $sort = "description";
        }else{
        	$sort = "$sort $dir";
        }

        $records = array();
        $table = "tbl_leave_type";
        $fields = array("id", "description as name");

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
		$gender = $this->lithefire->getFieldWhere("default", "tbl_employee_info", "id = ".$this->session->userData('userId'), "gender");
		if($gender == 'M'){
			$temp[] = array("id"=>7, "name"=>"Paternity Leave");
		}elseif($gender == 'F'){
			$temp[] = array("id"=>6, "name"=>"Maternity Leave");
		}
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, $group);
        die(json_encode($data));
    }

    function getPurposeCombo(){
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');
        $db = "fr";
		$filter = "ACTIVATED = 1";
		$group = "";
		$having = "";


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $query = $this->input->post('query');
        $queryby = "";
		
		if(!empty($query))
        $filter .= " AND (clientpurpose LIKE '%$querystring%' OR fileclientpurposeid LIKE '%$querystring%')";

        if(empty($sort) && empty($dir)){
            $sort = "clientpurpose";
        }else{
        	$sort = "$sort $dir";
        }

        $records = array();
        $table = "fileclientpurpose";
        $fields = array("fileclientpurposeid as id", "clientpurpose as name");

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

    function getClientCombo(){
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');

        $id = $this->input->post('id');
        $db = "fr";
		$filter = "ACTIVATED = 1";
		$group = "";
		$having = "";


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        
        $queryby = "";
        if($id == 'Client'){
        if(empty($sort) && empty($dir)){
            $sort = "clientname";
        }else{
        	$sort = "$sort $dir";
        }

        $records = array();
        $table = "clients";
        $fields = array("clieidno as id", "clientname as name", "bus_address01", "bus_address02", "bus_address03");
        $query = $this->input->post('query');
		if(!empty($query))
			$filter .= " AND (clientname LIKE '%$query%' OR abbr LIKE '%$query%')";
        }else{
            if(empty($sort) && empty($dir)){
            $sort = "SUPPLIERNAME";
        	}else{
        	$sort = "$sort $dir";
        	}

        $records = array();
        $table = "supplier";
        $fields = array("SUPPIDNO as id", "SUPPLIERNAME as name", "ADDRESS01 as bus_address01", "ADDRESS02 as bus_address02", "ADDRESS03 as bus_address03");
        $query = $this->input->post('query');
		if(!empty($query))
			$filter .= " AND (SUPPLIERNAME LIKE '%$query%')";
        }

        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group, $having);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):
          //  $row['COURSE'] = $this->commonmodel->getFieldWhere($db, "FILECOUR", "COURIDNO", $row['COURIDNO'], "COURSE");
            $row['address'] = $row['bus_address01']."\n".$row['bus_address02']."\n".$row['bus_address03'];
            $temp[] = $row;
            $total++;

        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->lithefire->countFilteredRows($db, $table, $filter, $group);
        die(json_encode($data));
    }

    function getContactCombo(){
        $start=$this->input->post('start');
        $limit=$this->input->post('limit');

        $type = $this->input->post('type');
        $name = $this->input->post('name');
        $db = "fr";
		$filter = "ACTIVATED = 1";
		$group = "";
		$having = "";


        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        
        $queryby = "";
        if($type == 'Client'){

        $client_id = $this->commonmodel->getFieldWhere("fr", "clients", "clientname", $name, "clieidno");
		
		$filter .= " AND clients_id=$client_id";

        if(empty($sort) && empty($dir)){
            $sort = "CONTACTNAME";
        }else{
        	$sort = "$sort $dir";
        }

        $records = array();
        $table = "contacts";
        $fields = array("CONTIDNO as id", "CONTACTNAME as name");
        $query = $this->input->post('query');
		if(!empty($query))
			$filter .= " AND (CONTACTNAME LIKE '%$query%')";
        
        
        
        }else{
            if(empty($sort) && empty($dir)){
            $sort = "SUPPLIERNAME";

        }else{
        	$sort = "$sort $dir";
        }

        $records = array();
        $table = "supplier_contacts";
        $fields = array("CONTIDNO as id", "CONTACTNAME as name");
        $query = $this->input->post('query');
		if(!empty($query))
			$filter .= " AND (CONTACTNAME LIKE '%$query%')";
        }


        $records = $this->lithefire->getAllRecords($db, $table, $fields, $start, $limit, $sort, $filter, $group, $having);
       // die($this->db->last_query());


        $temp = array();
        $total = 0;
        if($records){
        foreach($records as $row):
          //  $row['COURSE'] = $this->commonmodel->getFieldWhere($db, "FILECOUR", "COURIDNO", $row['COURIDNO'], "COURSE");
           // $row['address'] = $row['bus_address01']."\n".$row['bus_address02']."\n".$row['bus_address03'];
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