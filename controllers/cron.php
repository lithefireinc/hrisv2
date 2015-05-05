<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function insertTime(){
		$this->load->model('lithefire_model','lithefire',TRUE);
		
		$employees = $this->lithefire->fetchAllRecords("default", "tbl_employee_info", "id != 1", 
		array("biometrics_id", "date_hired"));
		
		$today = date("Y-m-d H:i:s");
		foreach($employees as $row):
			$date = $row['date_hired'];
			while($date <= $today):
			
			$this->lithefire->insertRow("default", "tbl_dtr", 
			array("biometrics_id"=>$row['biometrics_id'], "dtr_log"=>$date." 09:00:00", 
			"dtr_date"=>date("Y-m-d", strtotime("$date")), "dtr_time"=>date("H:i:s", strtotime("09:00:00"))));
			
			$this->lithefire->insertRow("default", "tbl_dtr", 
			array("biometrics_id"=>$row['biometrics_id'], "dtr_log"=>$date." 18:00:00", 
			"dtr_date"=>date("Y-m-d", strtotime("$date")), "dtr_time"=>date("H:i:s", strtotime("18:00:00"))));
			
			$date = date("Y-m-d", strtotime("$date+1day"));
			
			endwhile;
			
		endforeach;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */