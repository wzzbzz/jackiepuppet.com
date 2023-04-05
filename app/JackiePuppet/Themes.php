<?php

namespace JackiePuppet;

class Themes extends \JWS\Collection{

    public $data;
    public $class = "JackiePuppet\Theme";

    public function renderPage(){
        $themes = array_map( function( $theme ){
            return $this->find( $theme->slug );
        }, $this->data );
        
        ?>
        <h1>Themes</h1>
        <ul>
            <?php foreach( $themes as $theme ) : ?>
                <li>
                    <?= $theme->renderLink(); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }

}