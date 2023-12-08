<?php

namespace JackiePuppet;

class Songs extends \JWS\Collection{

    public $data;

    public $class = "JackiePuppet\Song";

    public function renderPage(){
        ?>
        <h1>Songs</h1>
        <ul>
            <?php foreach( $this->data as $song ) : ?>
                <li>
                    <?php $song->renderLink(); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <?php
    }

}