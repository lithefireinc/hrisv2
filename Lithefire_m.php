<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use \Illuminate\Validation\Factory as Validator;
use \Symfony\Component\Translation\Translator;

class Lithefire_m extends Eloquent
{
    protected $rules = array();
    protected $messages;
    protected $errors;
    protected $add_message;
    protected $updated_message;
    protected $deleted_message;
    protected $title;


    public function __construct()
    {
        $this->initMessages();
        $this->initResultMessages();
    }

    public function validate($data, $id = null)
    {
        $v = $this->initializeValidator($data, $id);

        if($v->fails())
        {
            $this->errors = $v->errors();
            return false;
        }
        return true;
    }

    protected function getValidationRules($id)
    {
        if($id)
        {
            array_walk($this->rules, function (&$v) use ($id){
                $v = str_replace(':id', $id, $v);
            });
        }
        return $this->rules;
    }

    public function errors(){
        return $this->errors;
    }

    public function listErrors()
    {
        $errors = '<ul>';
        foreach($this->errors->all('<li>:message</li>') as $message)
        {
            $errors.=$message;
        }
        $errors.='</ul>';
        return $errors;
    }

    public function addMsg(){
        return $this->add_message;
    }

    public function updatedMsg(){
        return $this->updated_message;
    }

    public function deletedMsg(){
        return $this->deleted_message;
    }

    protected function initMessages()
    {
        $this->messages['min'] = 'The minimum required length is :min';
        $this->messages['email'] = 'The :attribute field should be of the format user@example.com';
        $this->messages['required'] = ':attribute is required';
        $this->messages['unique'] = 'The :attribute must be unique';
    }

    protected function initResultMessages()
    {
        $this->add_message = $this->title." successfully added";
        $this->updated_message = $this->title." successfully updated";
        $this->deleted_message = $this->title." successfully deleted";
    }

    protected function initializeValidator($data, $id = null){
        $ci = &get_instance();
        $factory = new Validator(new Translator('en'));
        $v = $factory->make($data, $this->getValidationRules($id), $this->messages);
        $manager = $ci->db->capsule->getDatabaseManager();
        $manager->setDefaultConnection($this->connection);
        $v->setPresenceVerifier(new \Illuminate\Validation\DatabasePresenceVerifier($manager));

        return $v;
    }
}