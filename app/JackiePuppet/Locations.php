<?php

namespace JackiePuppet;

class Locations extends \JWS\Collection{

    public $data;
    public $class = "JackiePuppet\Location";

    public function renderPage(){
        
        $locations = array_map( function( $location ){
            return $this->find( $location->slug );
        }, $this->data );
        
        ?>
        <h1>Locations</h1>
        <ul>
            <?php foreach( $locations as $location ) : ?>
                <li>
                    <?= $location->renderLink(); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }

}