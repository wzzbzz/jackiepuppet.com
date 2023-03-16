<?php

//preformat print_r and give line number and file name which called it
function pre( $data ){
    $backtrace = debug_backtrace();
    $file = $backtrace[0]['file'];
    $line = $backtrace[0]['line'];
    echo "<pre>";
    echo "File: $file<br>";
    echo "Line: $line<br>";
    print_r( $data );
    echo "</pre>";

}
// register autoloader for namespace JackiePuppet
spl_autoload_register( function( $class ){

    $namespaces = [
        'JackiePuppet',
        'JWS'
    ];

    foreach( $namespaces as $namespace ){
        if( strpos( $class, $namespace ) === 0 ){
            
            $class = str_replace( $namespace, '', $class );
            $class = str_replace( '\\', '/', $class );
            $class = $namespace . $class;
            $class = __DIR__ . '/app/' . $class . '.php';
            if( file_exists( $class ) ){
                require $class;
            }
        }
    }
   
});


function renderError( $message = "Sorry there was an error" ){
    ?>
    <h1>404</h1>
    <p>Page not found</p>
    <p><?= $message; ?></p>
    <?php
}

function slugify( $text )
{
    if( !is_string( $text ) ){
        pre(debug_backtrace());
    }
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}


function renderSongList( ){
    $songs = loadSongs();
    ?>
    <h1>Jackie Puppet Songs</h1>
        <p>Here are the songs from the Jackie Puppet Show</p>
        <ul>
            <?php foreach( $songs as $song ) : ?>
                <li>
                    <a href="/song/<?php echo $song->slug; ?>/">
                        <?php echo $song->title; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php
    
}


function loadSongs(){
    $songs = json_decode( file_get_contents( "app/JackiePuppet/songs.json" ), false );
    return $songs;
}

function chooseRoute( $path ){

    // remove slashes from the beginning and end of the path
    $path = trim( $path, "/" );
    $parts = explode( "/", $path );

    switch (count( $parts )){
        case 1:
            $section = $parts[0];
            break;
        case 2:
            $section = $parts[0];
            $slug = $parts[1];
            break;
        default:
            $section = "error";
            break;
    }
    
    switch( $section ){
        case "song":
            $songs = new \JackiePuppet\Songs(loadSongs());
            $song = $songs->find( $slug );
            $song->renderPage();
            break;
        case "characters":
            $characters = new \JackiePuppet\Characters(loadCharacters());
            $characters->renderPage();
            break;
        case "character":
            $characters = new \JackiePuppet\Characters(loadCharacters());
            $character = $characters->find( $slug );
            $character->renderPage();
            break;
        case "locations":
            $locations = new \JackiePuppet\Locations(loadLocations());
            $locations->renderPage();
            break;
        case "location":
            $locations = new \JackiePuppet\Locations(loadLocations());
            $location = $locations->find( $slug );
            $location->renderPage();
            break;
        case "error":
            renderError();
            break;

        default:
            renderSongList();
            break;
    }
}


function loadCharacters(){
    $characters = json_decode( file_get_contents( "characters.json" ), false );
    return $characters;
}

function renderCharacters(){
    $characters = loadCharacters();
    ?>
    <h1>Characters</h1>
    <ul>
        <?php foreach( $characters as $character ) : ?>
            <li>
                <a href="/character/<?php echo $character->slug; ?>/">
                    <?php echo $character->name; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php
}


function parse_credit_line( $credit ){
    $credits = explode( ";", $credit );
    
    $credits = array_map( "trim", $credits );

    $credits = array_map( "parse_credit_roles", $credits );

    return $credits;
}

function parse_credit( $credit ){
    
    $credit = explode( ":", $credit );
    $credit = array_map( "trim", $credit );

    if( count( $credit ) == 1 ){
        // parse on "/" for multiple names
        $credit = explode( "/", $credit[0] );
        $credit = array_map( "trim", $credit );
        if( count( $credit ) == 1 ){
            $credit = [
                "role" => "",
                "name" => slugify($credit[0])
            ];
        }
        else{
            // left is role, right is name
            $credit = [
                "role" => $credit[0],
                "name" => slugify($credit[1])
            ];
        }

    }
    else{
        
    }

    return $credit;
}

function parse_credit_roles( $credit ){

    $credit = explode( ":", $credit );
    $credit = array_map( "trim", $credit );
    
    if(count($credit) == 1){
        $credit = [
            "role" => "",
            "credits" => parse_shared_credit($credit[0])
        ];
    }
    else{
        $credit = [
            "role" => $credit[0],
            "credits" => parse_shared_credit($credit[1])
        ];
    }

    return $credit;
 
}

function parse_shared_credit( $credit ){
    $credit = explode( "/", $credit );
    $credit = array_map( "trim", $credit );
    $credit = array_map( "slugify", $credit );
    
    
    return $credit;
}


function loadLocations(){
    $locations = json_decode( file_get_contents( __DIR__ . "/app/JackiePuppet/locations.json" ), false );
    return $locations;
}

function fix(){
    $songs =  json_decode( file_get_contents( __DIR__ . "/app/JackiePuppet/songs-old.json" ), false );

    $current_attribute = "locations";
    $current_attributes_array = [];

    $dates = [];
    // create locations array 
    foreach($songs as $song){
        
        foreach( $song->$current_attribute as $attribute ){

            // match these sections in a regex 
            // Sydney Airport (Sydney Australia) – September 15, 1975\
            $pattern = '/^([^(]+) \(([A-Za-z0-9 .\',-]+)\) [–-] ([A-Za-z]+) (\d{1,2}), (\d{4})$/';

            $patterns = [
                "parent-month-day-year"=>'/^([^(]+) \(([A-Za-z0-9 .\',-]+)\) [–-] ([A-Za-z]+) (\d{1,2}), (\d{4})$/',
                "parent-month-year"=>'/^([^(]+) \(([A-Za-z0-9 .\',-]+)\) [–-] ([A-Za-z]+) (\d{4})$/',
                "parent-year-range"=>'/^([^(]+) \(([A-Za-z0-9 .\',-]+)\) [–-] (\d{4}-\d{4})$/',
                "parent"=>'/^([^(]+) \(([A-Za-z0-9 .\',-]+)\)$/',
                "simple"=>'/^([^(]+)$/',
            ];
            
            $match=false;
     
            foreach($patterns as $key=>$pattern){
                preg_match($pattern, $attribute, $matches);
                
                
                if( count($matches) > 0 ){
                    
                    switch ($key ){
                        case "parent-month-day-year":
                        case "parent-month-year":
                        case "parent-year-range":
                        case "parent":
                            
                            // create the location if it doesn't exist 
                            $name = trim($matches[1]);
                            $slug = slugify( $matches[1] );
                            $parent = trim($matches[2]);
                            $parent_slug = slugify( $matches[2] );

                            if( !array_key_exists( $slug, $current_attributes_array ) ){
                                $current_attributes_array[$slug] = (object) [
                                    "name" => $name,
                                    "slug" => $slug,
                                    "count" => 0,
                                    "children" => [],
                                    "parent" => $parent_slug,
                                    "songs" => [ $song->slug ],
                                ];
                            }
                            else{
                                $current_attributes_array[$slug]->parent = $parent_slug;
                                if( !in_array( $song->slug, $current_attributes_array[$slug]->songs ) ){
                                    $current_attributes_array[$slug]->songs[] = $song->slug;
                                }
                            }
                            // create the parent location if it doesn't exist
                            if( !array_key_exists( $parent_slug, $current_attributes_array ) ){
                                $current_attributes_array[$parent_slug] = (object) [
                                    "name" => $parent,
                                    "slug" => $parent_slug,
                                    "count" => 0,
                                    "children" => [],
                                    "parent" => "",
                                    "songs" => [ $song->slug ],
                                ];
                            }
                            else{
                                if( !in_array( $slug, $current_attributes_array[$parent_slug]->children ) ){
                                    $current_attributes_array[$parent_slug]->children[] = $slug;
                                }
                                if( !in_array( $song->slug, $current_attributes_array[$parent_slug]->songs ) ){
                                    $current_attributes_array[$parent_slug]->songs[] = $song->slug;
                                }
                            }
                            break;
                    }
                    break;
                }
            } 

            
            continue;
        }

    }
    // sort by name
    usort( $current_attributes_array, function( $a, $b ){
        return strcmp( $a->name, $b->name );
    } );
    // write the locations json file
    file_put_contents( __DIR__ . "/app/JackiePuppet/locations.json" , json_encode( $current_attributes_array, JSON_PRETTY_PRINT ) );
    //file_put_contents( __DIR__ . "/app/JackiePuppet/dates.json" , json_encode( $dates, JSON_PRETTY_PRINT ) );
    //echo "done";
die;
}

    // write the people json file
    //file_put_contents( __DIR__ . "/app/JackiePuppet/songs.json" , json_encode( $songs, JSON_PRETTY_PRINT ) );
