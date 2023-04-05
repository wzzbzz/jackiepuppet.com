<?php

namespace JackiePuppet;

class CharactersData extends \JWS\PersistantData{


    public function validateItem($item)
    {
        return true;
        return parent::validateItem($item) && isset( $item->title );;
    }

    public function validatePerson( $item ){
        return true;
        return isset( $item->title );
    }
}