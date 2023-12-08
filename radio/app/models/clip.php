<?php

class Clip{
    protected $data;

    public function __construct($data){
        $this->data = $data;
    }

    public function get_title(){
        return $this->data['title'];
    }

    public function get_file(){
        return $this->data['file'];
    }
}
