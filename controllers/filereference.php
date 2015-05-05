<?php
class Filereference extends MY_Controller{

    function Filereference(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->database();
        $this->load->model('login_model','login',TRUE);

       // $this->load->scaffolding('entries');
    }

    function employeeStatus(){

        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');
        $data['title'] = 'Online Grading System';


        
        $this->layout->view('filereference/employeeStatus_view', $data);
        
    }

    function getEmployeeStatus(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = 'fr';
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

        if(!empty($querystring)){
            $filter = "(id LIKE '%$querystring%' OR description LIKE '%$querystring%')";
        }
        

        $records = array();
        $table = "fileemst";
        $fields = array("id", "description");

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

    function addEmployeeStatus(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = 'fr';
        $table = "fileemst";

        if($this->lithefire->countFilteredRows($db, $table, "description = '".$this->input->post("description")."'", "")){
            $data['success'] = false;
            $data['data'] = "Record already exists";
            die(json_encode($data));
        }
        
        $data = $this->lithefire->insertRow($db, $table, $this->input->post());

        die(json_encode($data));
    }

    function loadEmployeeStatus(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = "fr";
        

        $id=$this->input->post('id');
        $table = "fileemst";

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

    function updateEmployeeStatus(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = 'fr';

        $table = "fileemst";
        
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

    function deleteEmployeeStatus(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = 'fr';

        $table = "fileemst";
        
        $id=$this->input->post('id');
        $filter = "id = '$id'";

        $data = $this->lithefire->deleteRow($db, $table, $filter);

        die(json_encode($data));
    }

    function department(){

        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');
        $data['title'] = 'Online Grading System';


        
        $this->layout->view('filereference/department_view', $data);
        
    }

    function getDepartment(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = 'fr';
        $filter = "";
        $group = "";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');



        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');


        if(empty($sort) && empty($dir)){
            $sort = "dept_type ASC";
        }else{
            $sort = "$sort $dir";
        }

        if(!empty($querystring)){
            $filter = "(dept_idno LIKE '%$querystring%' OR dept_type LIKE '%$querystring%')";
        }


        $records = array();
        $table = "filedept";
        $fields = array("dept_idno", "dept_type");

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

    function addDepartment(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = 'fr';
        $table = "filedept";

        if($this->lithefire->countFilteredRows($db, $table, "dept_type = '".$this->input->post("dept_type")."'", "")){
            $data['success'] = false;
            $data['data'] = "Record already exists";
            die(json_encode($data));
        }

        $data = $this->lithefire->insertRow($db, $table, $this->input->post());

        die(json_encode($data));
    }

    function loadDepartment(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = "fr";


        $dept_idno=$this->input->post('id');
        $table = "filedept";

        $filter = "dept_idno = '$dept_idno'";
        $fields = array("dept_idno", "dept_type");

        $records = array();
        $records = $this->lithefire->getRecordWhere($db, $table, $filter, $fields);

        $temp = array();

        foreach($records as $row):

            $data["data"] = $row;


        endforeach;
        $data['success'] = true;

        die(json_encode($data));
    }

    function updateDepartment(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = 'fr';

        $table = "filedept";

       // $fields = $this->input->post();

        $dept_idno=$this->input->post('id');
        $filter = "dept_idno = '$dept_idno'";

        $input = array();
        foreach($this->input->post() as $key => $val){
            if($key == 'id')
                continue;
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        if($this->lithefire->countFilteredRows($db, $table, "dept_type = '".$this->input->post("dept_type")."' AND dept_idno != '$dept_idno'", "")){
            $data['success'] = false;
            $data['data'] = "Record already exists";
            die(json_encode($data));
        }


        $data = $this->lithefire->updateRow($db, $table, $input, $filter);


        die(json_encode($data));
    }

    function deleteDepartment(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = 'fr';

        $table = "filedept";

        $dept_idno=$this->input->post('id');
        $filter = "dept_idno = '$dept_idno'";

        $data = $this->lithefire->deleteRow($db, $table, $filter);

        die(json_encode($data));
    }

    function position(){

        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');
        $data['title'] = 'Online Grading System';


        
        $this->layout->view('filereference/position_view', $data);
        
    }

    function getPosition(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = 'fr';
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

        if(!empty($querystring)){
            $filter = "(id LIKE '%$querystring%' OR description LIKE '%$querystring%')";
        }


        $records = array();
        $table = "fileposi";
        $fields = array("id", "description");

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

    function addPosition(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = 'fr';
        $table = "fileposi";

        if($this->lithefire->countFilteredRows($db, $table, "description = '".$this->input->post("description")."'", "")){
            $data['success'] = false;
            $data['data'] = "Record already exists";
            die(json_encode($data));
        }

        $data = $this->lithefire->insertRow($db, $table, $this->input->post());

        die(json_encode($data));
    }

    function loadPosition(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = "fr";


        $id=$this->input->post('id');
        $table = "fileposi";

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

    function updatePosition(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = 'fr';

        $table = "fileposi";

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

    function deletePosition(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = 'fr';

        $table = "fileposi";

        $id=$this->input->post('id');
        $filter = "id = '$id'";

        $data = $this->lithefire->deleteRow($db, $table, $filter);

        die(json_encode($data));
    }

    function clientPurpose(){

        $data['userId'] = $this->session->userdata('userId');
        $data['userName'] = $this->session->userdata('userName');
        $data['title'] = 'Online Grading System';


        
        $this->layout->view('filereference/clientPurpose_view', $data);
        
    }

    function getClientPurpose(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = 'fr';
        $filter = "";
        $group = "";

        $start=$this->input->post('start');
        $limit=$this->input->post('limit');



        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');
        $querystring = $this->input->post('query');


        if(empty($sort) && empty($dir)){
            $sort = "clientpurpose ASC";
        }else{
            $sort = "$sort $dir";
        }

        if(!empty($querystring)){
            $filter = "(fileclientpurposeid LIKE '%$querystring%' OR clientpurpose LIKE '%$querystring%')";
        }


        $records = array();
        $table = "fileclientpurpose";
        $fields = array("fileclientpurposeid", "clientpurpose");

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

    function addClientPurpose(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = 'fr';
        $table = "fileclientpurpose";

        if($this->lithefire->countFilteredRows($db, $table, "clientpurpose = '".$this->input->post("clientpurpose")."'", "")){
            $data['success'] = false;
            $data['data'] = "Record already exists";
            die(json_encode($data));
        }

        $data = $this->lithefire->insertRow($db, $table, $this->input->post());

        die(json_encode($data));
    }

    function loadClientPurpose(){
        $this->load->model('lithefire_model','lithefire',TRUE);
        $db = "fr";


        $fileclientpurposeid=$this->input->post('id');
        $table = "fileclientpurpose";

        $filter = "fileclientpurposeid = '$fileclientpurposeid'";
        $fields = array("fileclientpurposeid", "clientpurpose");

        $records = array();
        $records = $this->lithefire->getRecordWhere($db, $table, $filter, $fields);

        $temp = array();

        foreach($records as $row):

            $data["data"] = $row;


        endforeach;
        $data['success'] = true;

        die(json_encode($data));
    }

    function updateClientPurpose(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = 'fr';

        $table = "fileclientpurpose";

       // $fields = $this->input->post();

        $fileclientpurposeid=$this->input->post('id');
        $filter = "fileclientpurposeid = '$fileclientpurposeid'";

        $input = array();
        foreach($this->input->post() as $key => $val){
            if($key == 'id')
                continue;
            if(!empty($val)){
                $input[$key] = $val;
            }
        }

        if($this->lithefire->countFilteredRows($db, $table, "clientpurpose = '".$this->input->post("clientpurpose")."' AND fileclientpurposeid != '$fileclientpurposeid'", "")){
            $data['success'] = false;
            $data['data'] = "Record already exists";
            die(json_encode($data));
        }


        $data = $this->lithefire->updateRow($db, $table, $input, $filter);


        die(json_encode($data));
    }

    function deleteClientPurpose(){
        $this->load->model('lithefire_model', 'lithefire', TRUE);
        $db = 'fr';

        $table = "fileclientpurpose";

        $fileclientpurposeid=$this->input->post('id');
        $filter = "fileclientpurposeid = '$fileclientpurposeid'";

        $data = $this->lithefire->deleteRow($db, $table, $filter);

        die(json_encode($data));
    }
}
?>