<?php

namespace JackiePuppet;

class SongsData extends \JWS\PersistantData{


    public function validateItem($item)
    {
        return parent::validateItem($item) && isset( $item->title );;
    }

    public function validateSong( $item ){
        return isset( $item->title );
    }
}