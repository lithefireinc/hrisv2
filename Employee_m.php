<?php

class Employee_m extends Lithefire_m
{
    protected $table = 'tbl_employee_info';
    protected $title = "Employee";
    public $timestamps = false;
    protected $guarded = ['id'];
}