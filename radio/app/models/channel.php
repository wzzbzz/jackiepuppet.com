<?php

class Channel{
    protected $data;

    public function __construct($data){
        $this->data = $data;
    }

    public function get_title(){
        return $this->data['title'];
    }

    public function get_description(){
        return $this->data['description'];
    }

    public function get_episodes(){
        $episodes = array();
        foreach( $this->data['episodes'] as $episode ){
            $episodes[] = new Episode( $this, $episode );
        }
        return $episodes;
    }

}