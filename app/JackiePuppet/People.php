<?php

namespace JackiePuppet;

class People extends \JWS\Collection{

    public $data;

    public $class = "JackiePuppet\Person";

    public function renderPage(){
        ?>
        <h1>People</h1>
        <ul>
            <?php foreach( $this->data as $person ) : ?>
                <li>
                    <?php $person->renderLink(); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }

}