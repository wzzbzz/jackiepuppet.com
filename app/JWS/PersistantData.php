<?php

namespace JWS;

class PersistantData{

    public $path = __DIR__ . "/data-files/";

    public $data;

    public $filename;

    // filename of the json file to load;
    // path will be in data-files directory
    public function __construct ( $filename=null ){

        // path is the path to the data-files directory in the namespace of the class
        $this->path = dirname( ( new \ReflectionClass( $this ) )->getFileName() ) . "/data-files/";

        $this->setFilename( $filename );
        $this->load();
        // else, filename = class name in lowercase
        
    }

    public function load(){

        $path = $this->path . $this->filename . ".json";
        $this->data = json_decode( file_get_contents( $path ) );
    }

    public function findBy( $key, $value ){
        foreach( $this->data as $item ){
            if( $item->$key == $value ){
                return $item;
            }
        }
        return false;
    }

    public function updateItem( $slug, $newItem ){

        if( !$this->validateItem( $newItem ) ){
            echo "invalid item<br>";
            return false;
        }

        foreach( $this->data as $key=>$item ){
            if( $item->slug == $slug ){
                $this->data[$key] = $newItem;
                return true;
            }
        }
        return false;
    }

    public function addItem( $newItem ){
        if( !$this->validateItem( $newItem ) ){
            echo "invalid item<br>";
            return false;
        }
        
        if( $this->findBy( "slug", $newItem->slug ) ){
            $this->updateItem( $newItem->slug, $newItem );
            return true;
        }
        
        $this->data[] = $newItem;

        return true;
    }

    public function removeItem( $slug ){
        foreach( $this->data as $key=>$item ){
            if( $item->slug == $slug ){
                unset( $this->data[$key] );
                return true;
            }
        }
        return false;
    }

    public function itemExists( $slug ){
        foreach( $this->data as $key=>$item ){
            if( $item->slug == $slug ){
                return true;
            }
        }
        return false;
    }

    public function save(){
        $path = $this->path . $this->filename . ".json";
        
        // validate JSON before saving 
        $json = json_encode( $this->data, JSON_PRETTY_PRINT );
        $json = json_decode( $json );
        if( json_last_error() != JSON_ERROR_NONE ){

            return false;
        }

        file_put_contents( $path, json_encode( $this->data, JSON_PRETTY_PRINT ) );
    }

    public function setFilename( $filename=null ){
        if( !empty( $filename ) ){
            $this->filename = $filename;
        } else {
            $this->filename = $this->getDefaultFilename();
        }

    }

    public function getDefaultFilename(){
        // replace Data in filename with empty string
        $class = ( new \ReflectionClass( $this ) )->getShortName();
        $class = str_replace( "Data", "", $class );

        return strtolower( $class );
    }

    public function validateItem( $item ){

        $valid = is_object( $item ) && !empty( $item->slug );
        return $valid;
    }

}