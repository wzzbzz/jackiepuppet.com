<?php

namespace JackiePuppet;

class Theme extends \JWS\NamedEntity{

    public $data;

    public $collectionClass = "JackiePuppet\Themes";

    public function songs(){
        return Songs::fromSlugList( $this->data->songs );
    }

    public function renderPage(){
        ?>
        <h1><?php echo $this->name(); ?></h1>
        <p>Songs: <?php echo $this->songs()->renderCommaSeparatedListOfLinks(); ?></p>
        <?php
    }
}