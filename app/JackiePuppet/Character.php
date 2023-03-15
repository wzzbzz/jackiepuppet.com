<?php

namespace JackiePuppet;

class Character extends \JWS\NamedEntity{

    public $data;

    public $collectionClass = "JackiePuppet\Characters";



    public function slug(){
        return $this->data->slug;
    }

    public function songs(){
        return Songs::fromSlugList( $this->data->songs );
    }


    public function notes(){
        return $this->data->notes;
    }

    public function renderPage(){
           ?>
            <h1><?php echo $this->name(); ?></h1>
            <p><?php echo $this->notes(); ?></p>
            <p>Songs: <?php echo $this->songs()->renderCommaSeparatedListOfLinks(); ?></p>
            <?php  
    }



    public function find( $slug ){
        $characters = loadCharacters();
        foreach( $characters as $character ){
            if( $character->slug == $slug ){
                return $character;
            }
        }
    }
}