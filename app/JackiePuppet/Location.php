<?php

namespace JackiePuppet;

class Location extends \JWS\NamedEntity{

    public $data;

    public $collectionClass = "JackiePuppet\Locations";



    public function slug(){
        return $this->data->slug;
    }

    public function songs(){
        return Songs::fromSlugList( $this->data->songs );
    }


    public function notes(){
        return "";
        return $this->data->notes;
    }
    
    public function parent(){
        return Locations::fromSlugList( $this->data->parent );
    }

    public function renderPage(){
           ?>
            <h1><?php echo $this->name(); ?></h1>
            <!-- if it has a parent, print the parent -->
            <p>Parent Location: <?php echo $this->parent()->renderLink(); ?></p>
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