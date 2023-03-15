<?php

namespace JackiePuppet;

class Person extends \JWS\NamedEntity{

    public $data;
    public $collectionClass = "JackiePuppet\People";

    public function slug(){
        return $this->data->slug;
    }

    public function name(){
        return $this->data->name;
    }

    public function renderPage(){
        ?>
        <h1><?php echo $this->name(); ?></h1>
        <?php
    }
    

}