<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_subdepartment_table extends CI_Migration
{

    public function up()
    {
        $this->db->query('use engine');
        $this->dbforge->add_field(array(
            'id',
            'description' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('FILESUBDEPT');
        $this->db->query('use hrisv2');
    }

    public function down()
    {
        $this->dbforge->drop_table('FILESUBDEPT');

    }
}