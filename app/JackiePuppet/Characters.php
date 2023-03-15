<?php

namespace JackiePuppet;

class Characters extends \JWS\Collection{

    public $data;
    public $class = "JackiePuppet\Character";

    public function renderPage(){
        $characters = array_map( function( $character ){
            return $this->find( $character->slug );
        }, $this->data );

        ?>
        <h1>Characters</h1>
        <ul>
            <?php foreach( $characters as $character ) : ?>
                <li>
                    <?= $character->renderLink(); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }

}