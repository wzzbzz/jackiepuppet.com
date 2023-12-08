<?php

// This file will contain functions to migrate data from the text file into 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$songs_json = file_get_contents( __DIR__ . '/data-files/new-songs-raw.json' );

$songs = json_decode( $songs_json,  );

$peopleData = new \JackiePuppet\PeopleData();

foreach( $songs as $song ) {
    // make the slug
    $song->slug = slugify( $song->title );

    // parse the credits
    foreach( $song->credits as $credit ){

        // check if person exists in PeopleData Object
        $people = explode( "/", $credit->credits );

        foreach( $people as $person ){
            $person = trim( $person );
            $person = preg_replace( "/\s+/", " ", $person );
            $person = $peopleData->findBy( "name", $person );
            if( !$person ){
                

                $person = new \JackiePuppet\Person();
                $person->name = $person;
                $person->slug = slugify( $person );
                $person->songs = [];
                $peopleData->add( $person );
            }
            pre($song);
            die;
         
        }

        pre($song);
        pre( $people );
        die;
    }
    die;
}

die;