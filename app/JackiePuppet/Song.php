<?php

namespace JackiePuppet;

class Song extends \JWS\NamedEntity{

    public $data;

    public $collectionClass = "JackiePuppet\Songs";

    public function slug(){
        return $this->data->slug;
    }

    public function name(){
        return $this->data->title;
    }

    public function title(){
        return $this->data->title;
    }

    public function credits(){

        $credits = [];      
        foreach( $this->data->credits as $role ){

            foreach( $role->credits as $key=>$person ){
                $people = new People();
                $person = $people->find( $person );
                $role->credits[$key] = $person;
            }

            $credits[] = (object)[
                "role" => $role->role,
                "credits" => $role->credits
            ];
 
        }

        return $credits;
    }

    public function renderCredits(){
       
        $credits = "";

        foreach( $this->credits() as $credit ){
            
            if(!empty($credit->role))
                $credits .= $credit->role . ": ";
            foreach( $credit->credits as $key=>$person ){
                $credits .= $person->renderLink();
                if( $key < count( $credit->credits ) - 1 ){
                    $credits .= ", ";
                }
            }
            $credits .= "<br>";
        }
        return $credits;
    }

    public function characters(){
        $characters = \JackiePuppet\Characters::fromSlugList((array)$this->data->characters);
        return $characters;
    }

    public function locations(){
        return \JackiePuppet\Locations::fromSlugList((array)$this->data->locations);
    }

    public function settings(){
        $settings = \JackiePuppet\Settings::fromSlugList((array)$this->data->settings);
        return $settings;
    }

    public function lyrics(){
        return $this->data->lyrics;
    }

    public function themes(){
        $themes = \JackiePuppet\Themes::fromSlugList((array)$this->data->themes);
        return $themes;
    }

    public function notes(){
        return $this->data->notes;
    }

    public function alternate_titles(){
        return $this->data->alternate_titles;
    }

    public function renderPage(){
        ?>
        <h1><?php echo $this->title(); ?></h1>
        <!-- credits -->
        <ul>

            <li>Credits: <?=  $this->renderCredits(); ?></li>

        <?php

        // characters
        if( !( $this->characters() ) == false ) : ?>

            <li>Characters: <?php $this->characters()->renderCommaSeparatedListOfLinks(); ?></li>
        <?php
        endif;

        // locations
        if( !( $this->locations() ) == false ) : 
        ?>
            <li>Locations: <?php $this->locations()->renderCommaSeparatedListOfLinks(); ?></li>
        <?php  
        endif;

        // settings
        if( !( $this->settings() ) == false ) : ?>
           <li>Settings <?php $this->settings()->renderCommaSeparatedListOfLinks(); ?></li>
        <?php
        endif;

        // themes
        if( !( $this->themes() ) == false ) : ?>
            <li>Themes: <?php $this->themes()->renderCommaSeparatedListOfLinks(); ?></li>
        <?php
        endif;

        // alternate titles
        if( count( $this->alternate_titles() ) > 0 ) : ?>
            <li>Alternate Titles: <?= implode( ", ", $this->alternate_titles() ); ?></li>
        <?php
        endif;

        ?>
        </ul>
        <?php

    }

    public function toJSON(){
        return json_encode( $this->data );
    }


}

