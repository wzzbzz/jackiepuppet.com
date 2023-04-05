<?php

namespace JackiePuppet;

class Setting extends \JWS\NamedEntity{

    public $data;

    public $collectionClass = "JackiePuppet\Settings";

    public function songs(){
        return Songs::fromSlugList( $this->data->songs );
    }


    public function notes(){
        return "";
        return $this->data->notes;
    }
    
    public function parent(){
        if( empty($this->data->parent)  ){
            return false;
        }
        $settings = new Settings(loadSettings());
        return $settings->find( $this->data->parent );
    }

    public function renderPage(){
           ?>
            <h1><?php echo $this->name(); ?></h1>
            <!-- if it has a parent, print the parent -->
            <?php if ( $this->parent() ) : ?>
                <p>Parent: <?php echo $this->parent()->renderLink(); ?></p>
            <?php endif; ?>
            <p>Songs: <?php echo $this->songs()->renderCommaSeparatedListOfLinks(); ?></p>
            <?php  
    }

}