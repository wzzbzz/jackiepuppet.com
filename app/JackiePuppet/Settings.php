<?php

namespace JackiePuppet;

class Settings extends \JWS\Collection{

    public $data;
    public $class = "JackiePuppet\Setting";

    public function renderPage(){
        $settings = array_map( function( $setting ){
            return $this->find( $setting->slug );
        }, $this->data );
        
        ?>
        <h1>Settings</h1>
        <ul>
            <?php foreach( $settings as $setting ) : ?>
                <li>
                    <?= $setting->renderLink(); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }

}