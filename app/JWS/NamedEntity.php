<?php

namespace JWS;

class NamedEntity{

    public $data;
    public $collectionClass = "JWS\Collection";

    public function __construct( $data=null ){

        $this->data = $data;
    }

    public function slug(){
        return $this->data->slug;
    }

    public function name(){
        return $this->data->name;
    }

    public function renderLink(){
        
        $section = strtolower( ( new \ReflectionClass( $this ) )->getShortName() );
    
        ob_start();
        ?>
        <a href="/<?php echo $section; ?>/<?php echo $this->slug(); ?>/">
            <?php echo $this->name(); ?>
        </a>
        <?php
        return ob_get_clean();
    }

    public static function fromSlug( $slug ){
        $class = ( new \ReflectionClass( get_called_class() ) )->getShortName();
        $collectionClass = ( new \ReflectionClass( get_called_class() ) )->getNamespaceName() . "\\" . $class . "s";
        $collection = new $collectionClass();
        return $collection->find( $slug );
    }


    // find an item by its title, or create it if it doesn't exist.  
    // don't add to a collection, just return the item
    // minimal required data is a title and a slug
    public static function fromTitle( $title, $createIfDoesntExist=false ){
        $class = ( new \ReflectionClass( get_called_class() ) )->getShortName();
        $collectionClass = ( new \ReflectionClass( get_called_class() ) )->getNamespaceName() . "\\" . $class . "s";
        $collection = new $collectionClass();
        $slug = slugify( $title );
        $item = $collection->find( $slug );
        if( $item ){
            return $item;
        }
        if( $createIfDoesntExist ){
            $item = new static();
            $item->data = (object)[
                "title" => $title,
                "slug" => $slug
            ];
            return $item;
        }
        return false;
        
    }
    

}