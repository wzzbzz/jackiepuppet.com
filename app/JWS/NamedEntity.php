<?php

namespace JWS;

class NamedEntity{

    public $data;
    public $collectionClass = "JWS\Collection";

    public function __construct( $data=null ){

        $this->data = $data;
    }

    public function slug(){
        return $this->data->slug;
    }

    public function name(){
        return $this->data->name;
    }

    public function renderLink(){
        
        $section = strtolower( ( new \ReflectionClass( $this ) )->getShortName() );
        ob_start();
        ?>
        <a href="/<?php echo $section; ?>/<?php echo $this->slug(); ?>/">
            <?php echo $this->name(); ?>
        </a>
        <?php
        return ob_get_clean();
    }
    

}