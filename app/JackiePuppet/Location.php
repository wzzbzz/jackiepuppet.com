<?php

namespace JackiePuppet;

class Location extends \JWS\NamedEntity{

    public $data;

    public $collectionClass = "JackiePuppet\Locations";

    public function songs(){
        return Songs::fromSlugList( $this->data->songs );
    }


    public function notes(){
        return "";
        return $this->data->notes;
    }
    
    public function parent(){
        $locations = new Locations();
        return $locations->find( $this->data->parent );
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



    public function find( $slug ){
        $locations = loadLocations();
        foreach( $locations as $$location ){
            if( $location->slug == $slug ){
                return $location;
            }
        }
    }
}