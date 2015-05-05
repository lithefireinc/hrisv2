<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Leaves extends MY_Controller{
	
    function Leaves(){
        parent::__construct();
        $this->load->model('leaves_model', '', TRUE);

    }
    function index(){
        
        $data['title'] = 'Leaves';
        

        
        $this->layout->view('leaves/leaves_view');
    }

    function getLeaves(){

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
        $records = $this->leaves_model->getLeavesByEmployee($employee_id, $start, $limit, $sort, $dir, $query, $queryby);

        $temp = array();
        if($records){
        foreach($records as $row):

            $temp[] = $row;


        endforeach;
        }
        $data['data'] = $temp;
        $data['success'] = true;
        $data['totalCount'] = $this->leaves_model->countLeavesByEmployee($employee_id);
        die(json_encode($data));
    }

    function applyLeave(){
        $this->load->model('apps_model', '', TRUE);
        $this->load->model("lithefire_model", 'lithefire', TRUE);
		
		
        $date_from = $this->input->post("date_from");
        $date_to = $this->input->post('date_to');
        $no_days = $this->input->post('no_of_days');
        $portion = $this->input->post('portion_hdn');
        $reason = $this->input->post('reason');
        $employee_id = $this->session->userdata('userId');
        $leave_type = $this->input->post("leave_type");
		
		$call_log_id = $this->input->post("call_log_id");
		
		if(empty($call_log_id))
		$call_log_id = 0;
		$vacation_leave = $this->input->post("vacation_leave");
		$sick_leave = $this->input->post("sick_leave");
		$emergency_leave = $this->input->post("emergency_leave");
		$maternity_leave = $this->input->post("maternity_leave");
		$paternity_leave = $this->input->post("paternity_leave");
		
		$db="default";

        //$leave_type = $this->commonmodel->getFieldWhere($db, "tbl_leave_type", "description", $leave_type, "id");
		
		$today = date("Y-m-d");
        $now = date("Y-m-d H:i:s");
		
		
		$exemptions = array();
		$exempted = $this->lithefire->getAllRecords($db, "tbl_exemption", "employee_id", "", "", "", "(date_to is NULL OR date_to >= '$today') AND app_type = 2", "", "");
		if($exempted){
		foreach($exempted as $employee):
			$exemptions[] = $employee['employee_id'];
		endforeach;
		}
		if(!in_array($employee_id, $exemptions)){
		switch($leave_type):
			case 1: $grace_period = $this->lithefire->getFieldWhere($db, "tbl_company_setup", "", "vacation_leave_grace_period");
					$days = (strtotime($date_from)-strtotime($today))/86400;
					if($days < $grace_period){
						$data['success'] = false;
						$data['data'] = "Vacation leaves must be applied $grace_period days before leave date";
						die(json_encode($data));
					}
					
					if(strtotime($today) > strtotime($date_from)){
						$data['success'] = false;
						$data['data'] = "Vacation leave cannot be applied after leave date.";
						die(json_encode($data));
					}
					
					
			break;
			
			case 4: $grace_period = $this->lithefire->getFieldWhere($db, "tbl_company_setup", "", "vacation_leave_grace_period");
					$days = (strtotime($date_from)-strtotime($today))/86400;
					if($days < $grace_period){
						$data['success'] = false;
						$data['data'] = "Vacation leaves must be applied $grace_period days before leave date";
						die(json_encode($data));
					}
					
					if(strtotime($today) > strtotime($date_from)){
						$data['success'] = false;
						$data['data'] = "Vacation leave cannot be applied after leave date.";
						die(json_encode($data));
					}
					
			break;
			
			case 2: $grace_period = $this->lithefire->getFieldWhere($db, "tbl_company_setup", "", "sick_leave_grace_period");
			$days = (strtotime($today)-strtotime($date_to))/86400;
					if($days > $grace_period){
						
							$num = ($grace_period > 1 ? "days" : "day");
						$data['success'] = false;
						$data['data'] = "Sick leaves must be applied not later than $grace_period $num after leave date";
						die(json_encode($data));
					}
					//die("today: ".strtotime($today)." date_from:".strtotime($date_from));
					if(strtotime($today) < strtotime($date_from)){
						$data['success'] = false;
						$data['data'] = "Sick leave cannot be applied in advance.";
						die(json_encode($data));
					}
					break;
					
			case 3: $grace_period = $this->lithefire->getFieldWhere($db, "tbl_company_setup", "", "sick_leave_grace_period");
			$days = (strtotime($today)-strtotime($date_to))/86400;
					if($days > $grace_period){
						
							$num = ($grace_period > 1 ? "days" : "day");
						$data['success'] = false;
						$data['data'] = "Emergency leaves must be applied not later than $grace_period $num after leave date";
						die(json_encode($data));
					}
					
					if(strtotime($today) < strtotime($date_from)){
						$data['success'] = false;
						$data['data'] = "Emergency leave cannot be applied in advance.";
						die(json_encode($data));
					}
					break;
					
			case 5: $grace_period = $this->lithefire->getFieldWhere($db, "tbl_company_setup", "", "sick_leave_grace_period");
			$days = (strtotime($today)-strtotime($date_to))/86400;
					if($days > $grace_period){
						
							$num = ($grace_period > 1 ? "days" : "day");
						$data['success'] = false;
						$data['data'] = "Sick leaves must be applied not later than $grace_period $num after leave date";
						die(json_encode($data));
					}
					
					if(strtotime($today) < strtotime($date_from)){
						$data['success'] = false;
						$data['data'] = "Sick leave cannot be applied in advance.";
						die(json_encode($data));
					}
					break;
		endswitch;
		}
		
		if($leave_type == 1){
			if(number_format($no_days, 2) > number_format($vacation_leave,2)){
						$data['success'] = false;
						$data['data'] = "Not enough vacation leave credits to apply for this leave";
						die(json_encode($data));
			}
		}elseif($leave_type == 2){
			if(number_format($no_days, 2) > number_format($sick_leave,2)){
						$data['success'] = false;
						$data['data'] = "Not enough sick leave credits to apply for this leave";
						die(json_encode($data));
			}
			
			
		}elseif($leave_type == 3){
			if(number_format($no_days, 2) > number_format($emergency_leave,2)){
						$data['success'] = false;
						$data['data'] = "Not enough emergency leave credits to apply for this leave";
						die(json_encode($data));
			}
		}elseif($leave_type == 6){
			if(number_format($no_days, 2) > number_format($maternity_leave,2)){
						$data['success'] = false;
						$data['data'] = "Not enough leave credits to apply for this leave";
						die(json_encode($data));
			}
		}elseif($leave_type == 7){
			if(number_format($no_days, 2) > number_format($paternity_leave,2)){
						$data['success'] = false;
						$data['data'] = "Not enough leave credits to apply for this leave";
						die(json_encode($data));
			}
		}       
        
        $emp_group = $this->apps_model->getEmpGroup($employee_id);
        
        $app_flow_details = $this->apps_model->getAppFlowDetails($emp_group, 2);

        //die(print_r($app_flow_details));





        $fields = array("employee_id"=>$employee_id, "date_from"=>$date_from,
            "date_to"=>$date_to, "no_days"=>$no_days, "date_requested"=>$today,
            "reason"=>$reason, "leave_type"=>$leave_type, "portion"=>$portion);

        $audit_details = array("app_type_id"=>2, "app_type"=>"Leave", "action_timestamp"=>$now, "requestor"=>$employee_id, "employee_group_id"=>$app_flow_details[0]['employee_group_id'],
            "app_group_id"=>$app_flow_details[0]['app_group_id'], "app_tree_id"=>$app_flow_details[0]['app_tree_id'],
            "status_id"=>1, "is_active"=>1);
        $is_valid = $this->leaves_model->checkValid($fields, $call_log_id);
        if(!$is_valid['success']){
            die(json_encode($is_valid));
        }else{
        $leave_data = $this->leaves_model->insertLeave($fields, $audit_details);
		$call_log_update['leave_filed'] = 1;
		$call_log_update['leave_id'] = $leave_data['leave_id'];
		$data = $this->lithefire->updateRow("default", "tbl_call_log", $call_log_update, "id = '$call_log_id'");
        die(json_encode($data));
        }
    }
	
}
?>