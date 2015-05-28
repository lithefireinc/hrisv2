<?php
use Illuminate\Database\Capsule\Manager as CapsuleManager;

class Capsule extends CapsuleManager {

    function __construct()
    {
        parent::__construct();
        $ci = &get_instance();
        $db = $ci->db;
        $this->addConnection(array(
            'driver'    => $db->dbdriver,
            'host'      => $db->hostname,
            'database'  => $db->database,
            'username'  => $db->username,
            'password'  => $db->password,
            'charset'   => $db->char_set,
            'collation' => $db->dbcollat,
            'prefix'    => $db->dbprefix,
        ));

        $this->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $this->bootEloquent();
    }
}