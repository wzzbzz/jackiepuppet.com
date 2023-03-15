<?php

namespace JackiePuppet;

class Locations extends \JWS\Collection{

    public $data;
    public $class = "JackiePuppet\Location";

    public function renderPage(){
        ?>
        <h1>Locations</h1>
        <ul>
            <?php foreach( $this->data as $location ) : ?>
                <li>
                    <?php $location->renderLink(); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }

}