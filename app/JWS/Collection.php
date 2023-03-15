<?php

namespace JWS;

class Collection{

    public $data;

    public $class = "JWS\NamedEntity";

    public function __construct( $data=null ){
        if(empty($data)){
            // if data is empty, load a file with the same name as the class in the same directory
            $class = ( new \ReflectionClass( $this ) )->getShortName();
            $file = strtolower( $class ) . ".json";
            $path = dirname( ( new \ReflectionClass( $this ) )->getFileName() ) . "/" . $file;
            $data = json_decode( file_get_contents( $path ) );

        }
        $this->data = $data;
    }

    public function find( $slug, $data_or_class="class", $debug=false ){
        
        foreach( $this->data as $item ){
            if(is_string($item)){
                pre($this->data);
                
                pre(debug_backtrace());
                die;
            }
            if( $item->slug == $slug ){
                if( $data_or_class == "data" ){
                    return $item;
                }
                return new $this->class( $item );
            }
        }
        return false;
    }

    public function items(){
        $items = [];
        foreach( $this->data as $item ){
            // if it's an object, 
            if( !is_object( $item )){
                $item = $this->find( $item );
            }
            
            $items[] = new $this->class( $item );
            
        }
        return $items;
    }

    public function renderCommaSeparatedListOfLinks(){
        
        $links = "";
        
        foreach( $this->items() as $key=>$item ){
            $links .= $item->renderLink();
            if( $key < count( $this->items() ) - 1 ){
                $links .= ", ";
            }
        }
        
        echo $links;
    }

    public function renderUnorderedListOfLinks(){
        
        $links = "";
        
        foreach( $this->items() as $key=>$item ){
            $links .= "<li>" . $item->renderLink() . "</li>";
        }
        
        echo "<ul>" . $links . "</ul>";
    }

    public static function fromSlugList( $slugs, $class="JWS\NamedEntity" ){

        $items = [];

        // collection is a new insta nce of this class use reflection
        $collection = new static();

        // loop through the slugs
        foreach( $slugs as $slug ){
            $items[] = $collection->find( $slug, "data", true );
        }

        // return a new collection 
        return new static( $items );
        
    }
    

}