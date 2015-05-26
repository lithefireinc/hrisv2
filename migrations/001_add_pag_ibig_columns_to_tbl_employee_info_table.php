<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_pag_ibig_columns_to_tbl_employee_info_table extends CI_Migration
{
    protected $table = "tbl_employee_info";

    public function up()
    {
        $columns = array("pagibig_tracking_number"=>array("type"=>"VARCHAR", "constraint"=>"20", "default"=>""),
            "pagibig_rtn"=>array("type"=>"VARCHAR", "constraint"=>"20", "default"=>""),
            "pagibig_mid_number"=>array("type"=>"VARCHAR", "constraint"=>"20", "default"=>""),
            "philhealth_number"=>array("type"=>"VARCHAR", "constraint"=>"20", "default"=>""),
            "tax_exemption"=>array("type"=>"VARCHAR", "constraint"=>"20", "default"=>""));
        $this->dbforge->add_column($this->table, $columns);
    }

    public function down()
    {
        $this->dbforge->drop_column($this->table, "pagibig_tracking_number");
        $this->dbforge->drop_column($this->table, "pagibig_rtn");
        $this->dbforge->drop_column($this->table, "pagibig_mid_number");
        $this->dbforge->drop_column($this->table, "philhealth_number");
        $this->dbforge->drop_column($this->table, "tax_exemption");
    }
}