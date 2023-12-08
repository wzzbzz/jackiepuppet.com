<?php

//preformat print_r and give line number and file name which called it

use JWS\PersistantData;

function pre( $data ){
    $backtrace = debug_backtrace();
    $file = $backtrace[0]['file'];
    $line = $backtrace[0]['line'];
    echo "<pre>";
    echo "File: $file<br>";
    echo "Line: $line<br>";
    var_dump( $data );
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
    $songs = json_decode( file_get_contents( "app/JackiePuppet/data-files/songs.json" ), false );
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
        case "settings":
            $settings = new \JackiePuppet\Settings(loadSettings());
            $settings->renderPage();
            break;
        case "setting":
            $settings = new \JackiePuppet\Settings(loadSettings());
            $setting = $settings->find( $slug );
            $setting->renderPage();
            break;
        case "theme":
            $themes = new \JackiePuppet\Themes(loadThemes());
            $theme = $themes->find( $slug );
            $theme->renderPage();
            break;
        case "themes":
            $themes = new \JackiePuppet\Themes(loadThemes());
            $themes->renderPage();
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
    $characters = json_decode( file_get_contents( __DIR__."/app/JackiePuppet/data-files/characters.json" ), false );
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


function loadPeople(){
    $people = json_decode( file_get_contents( __DIR__ . "/app/JackiePuppet/data-files/people.json" ), false );
    return $people;
}

function loadLocations(){
    $locations = json_decode( file_get_contents( __DIR__ . "/app/JackiePuppet/data-files/locations.json" ), false );
    return $locations;
}

function loadSettings(){
    $settings = json_decode( file_get_contents( __DIR__ . "/app/JackiePuppet/data-files/settings.json" ), false );
    return $settings;
}

function loadThemes(){
    $themes = json_decode( file_get_contents( __DIR__ . "/app/JackiePuppet/data-files/themes.json" ), false );
    return $themes;
}

function loadDataFile( $file ){
    $data = json_decode( file_get_contents( __DIR__ . "/app/JackiePuppet/data-files/" . $file . ".json" ), false );
    return $data;
}

function test(){
    switch( $_GET["test"] ){
        case "add":
            testAddItem();
            break;
        case "delete":
            testDeleteItem();
            break;
        case "update":
            testUpdateItem();
            break;
        case "migrate":
            testMigrate();
            break;
        default:
            echo "no test";
            break;
    }
}

function testMigrate(){
    include __DIR__ . "/app/JackiePuppet/Migration.php";
}

function testDeleteItem(){
    $songData = new \JackiePuppet\SongsData();

    $item = (object) [
        "title" => "test",
        "slug" => "test",
        "credits" => [
            (object) [
                "role" => "test",
                "credits" => [
                    "b-kleiman"
                ]
            ]

        ]
    ];

    
    if( $songData->itemExists( $item->slug ) ){
        $songData->removeItem( $item->slug );
        $songData->save();
        echo "item deleted";
    }
    else{
        echo "item does not exist, no more now.";
    }
    
    

    echo "test";

    die;
}


function testUpdateItem(){
    $songData = new \JackiePuppet\SongsData();

    $item = (object) [
        "title" => "test_update",
        "slug" => "test",
        "credits" => [
            (object) [
                "role" => "test",
                "credits" => [
                    "j-williams"
                ]
            ]

        ]
    ];

    
    if( $songData->itemExists( $item->slug ) ){
        $songData->updateItem( $item->slug, $item );
        $songData->save();
        echo "item updated";
    }
    else{
        echo "item does not exist, no more now.";
    }
    
    

    echo "test";

    die;
}

function testAddItem(){
    $songData = new \JackiePuppet\SongsData();

    $item = (object) [
        "title" => "test",
        "slug" => "test",
        "credits" => [
            (object) [
                "role" => "test",
                "credits" => [
                    "b-kleiman"
                ]
            ]

        ]
    ];

    
    if( ! $songData->itemExists( $item->slug ) ){
        $songData->addItem( $item );
        $songData->save();
        echo "item added";
    }
    else{
        echo "item exists, no more now.";
    }
    
    

    echo "test";

    die;
}

function fix(){
    
    $method = $_GET['method'];
    switch( $method ){
        case "fix-songs":
            fixSongs();
            break;
        case "fix-characters":
            fixCharacters();
            break;
        case "fix-locations":
            fixLocations();
            break;
        case "fix-settings":
            fixSettings();
            break;
        case "fix-themes":
            fixThemes();
            break;
        case 'fix-people':
            fixPeople();
            break;
        case 'add-songs':
            addSongsFromTextFile();
            break;
        case "default":
            echo "no method selected";
            die;
            break;
    }
}

function fixSongs(){
return;
    // open songtitles.txt  and read it into an array
    $songsData = new \JackiePuppet\SongsData();
    $songsData->load();

    // re-key the array by song slug
    $songs = [];
    foreach( $songsData->data as $song ){
        $songs[] = $song;
    }
    $songsData->data = $songs;
    $songsData->save();
    die;

    //$songtitles = file( __DIR__ . "/app/JackiePuppet/songtitles.txt" );
    //pre($songtitles);
    die;
}

function fixPeople(){
    $people = new \JackiePuppet\PeopleData();

    $songs = new \JackiePuppet\SongsData();

    foreach( $people->data as $key=>$person ){
        $person->name = trim( $person->name );
        pre($people->data);
        die;
        $people->data[$key] = $person;
continue;
        foreach( $song->credits as $credit ){
            foreach( $credit->credits as $person ){
                pre($person);
                die;
            }
        }
    }
    pre($peopl>data);
die;
} 

function addSongsFromTextFile(){
    $fh = fopen( __DIR__ . "/app/JackiePuppet/data-files/new-songs.txt", "r" );

    // read the file line by line
    $text = "";

    $songs = new \JackiePuppet\SongsData();
    $people = new \JackiePuppet\PeopleData();
    $characters = new \JackiePuppet\CharactersData();
    $locations = new \JackiePuppet\LocationsData();
    $settings = new \JackiePuppet\SettingsData();
    $themes = new \JackiePuppet\ThemesData();
    
    
    while( ! feof( $fh ) ){

        $song = new \stdClass();
        
        $song_credits = fgets( $fh );
        $song->title = titleFromTitleCreditLine( $song_credits );
        $song->slug = slugify( $song->title );

        $song->credits = creditsFromTitleCreditLine( $song_credits );
        
        $characters = fgets( $fh );
        $song->characters = parseCharacterLine( $characters );

        $locations = fgets( $fh );
        $song->locations = str_replace( "Locations: ", "", $locations );
        
        $settings = fgets( $fh );
        $song->settings = str_replace( "Settings: ", "", $settings );

        $themes = fgets( $fh );
        $song->themes = str_replace( "Themes: ", "", $themes );

        pre($song);
        
    }
    
    pre($songs);
    die;

}

function titleFromTitleCreditLine( $title_credit_line){
    $pattern = '/^Song: (.*) \(.*\)$/';
    preg_match( $pattern, $title_credit_line, $matches );
    if( count( $matches ) > 0 ){
        return $matches[1];
    }
    else{
        return false;
    }

}

function creditsFromTitleCreditLine( $title_credit_line ){
    $pattern = '/^Song: .* \((.*)\)$/';
    preg_match( $pattern, $title_credit_line, $matches );
    if( count( $matches ) > 0 ){
        $credits = explode( ",", $matches[1] );
        $credits = array_map( function( $credit ){
            return parse_credit(trim( $credit ));
        }, $credits );
        return $credits;
    }
    else{
        return false;
    }
}

function parseCharacterLine( $character_line ){
    $pattern = '/^Characters: (.*)$/';
    preg_match( $pattern, $character_line, $matches );
    if( count( $matches ) > 0 ){
        
        $characters = explode( ",", $matches[1] );

        foreach( $characters as $key => $character ){
            $character = parseCharacter( trim( $character ) );
            $characters[$key] = parseCharacter( $character );
        }
        pre($characters);
        return $characters;
    }
    else{
        return false;
    }
}

// character can contain more than one character, if there are parenthesis
function parseCharacter( $character ){
    $patterns = [
        "compound"=>'/^([^(]+)\(([A-Za-z0-9 .\',-]+)\)/',
        "simple"=>'/^([^(]+)$/',
    ];
    foreach( $patterns as $key => $pattern){
        preg_match( $pattern, $character, $matches );
        if( count( $matches ) > 0 ){
            switch( $key ){
                case "compound":
                    pre($character);
                    die;
                    $character = new \stdClass();
                    $character->name = $matches[1];
                    return [
                        "name" => $matches[1],
                        "sub_characters" => explode( ",", $matches[2] )
                    ];
                    break;
                case "simple":
                    $character = new \stdClass();
                    $character->name = $matches[1];
                    $character->slug = slugify( $character->name );
                    return [ $character ];
                    break;
            }
        }
    }
}
function fixCharacters(){}
function fixThemes(){
    return;
    $songs = json_decode( file_get_contents( __DIR__ . "/app/JackiePuppet/data-files/songs-old.json" ), false );
    $themes = [];

    $new_song_themes = [];

    // create themes array
    foreach( $songs as $song ){
        $new_song_themes[$song->slug] = [];
        foreach( $song->themes as $theme ){
            $patterns = [
                "hierarchy"=>'/^([^(]+)\(([A-Za-z0-9 .\',-]+)\)/',
                "simple"=>'/^([^(]+)$/',
            ];

            $keys = [];
            $matches = [];

            foreach( $patterns as $key=>$pattern ){
                preg_match( $pattern, $theme, $matches );
                if( count( $matches ) > 0 ){
                    switch( $key ){
                        case "hierarchy":
                            
                            $theme = trim( $matches[1] );
                            $slug = slugify($theme);
                            if( ! isset( $themes[$theme] ) ){
                                $themes[$slug] = (object) [
                                        "name"=> $theme,
                                        "slug"=> slugify($theme),
                                        "description"=> "",
                                        "songs"=> [ $song->slug ],
                                        "parent"=> ""
                                ];
                            }
                            else{
                                $themes[$theme]->songs[] = $song->slug;
                            }

                            $new_song_themes[$song->slug][] = $slug;

                            $children = explode( ",", $matches[2] );
                            foreach( $children as $child ){
                                $child = trim( $child );
                                $childSlug = slugify($child);
                                if( ! isset( $themes[$childSlug] ) ){
                                    $themes[$childSlug] = (object) [
                                            "name"=> $child,
                                            "slug"=> slugify($child),
                                            "description"=> "",
                                            "songs"=> [ $song->slug ],
                                            "parent"=> $slug
                                    ];
                                }
                                else{
                                    $themes[$childSlug]->songs[] = $song->slug;
                                }

                                $new_song_themes[$song->slug][] = $childSlug;
                            }


                            // get the children;
                            break;
                        case "simple":
                            $theme = trim( $matches[1] );
                            $slug = slugify($theme);
                            if( ! isset( $themes[$theme] ) ){
                                $themes[$slug] = (object) [
                                        "name"=> $theme,
                                        "slug"=> slugify($theme),
                                        "description"=> "",
                                        "songs"=> [ $song->slug ],
                                        "parent"=> ""
                                ];
                            }
                            else{
                                $themes[$theme]->songs[] = $song->slug;
                            }

                            $new_song_themes[$song->slug][] = $slug;
                            break;
                    }
                break;
               }

            }
            
        }
        
    }

    usort( $themes, function( $a, $b ){
        return strcmp( $a->name, $b->name );
    } );

    $songs = json_decode( file_get_contents( __DIR__ . "/app/JackiePuppet/data-files/songs.json" ), false );

    foreach( $songs as $song ){
        $song->themes = $new_song_themes[$song->slug];
    }
   
    file_put_contents( __DIR__ . "/app/JackiePuppet/data-files/songs.json", json_encode( $songs, JSON_PRETTY_PRINT ) );
    file_put_contents( __DIR__ . "/app/JackiePuppet/data-files/themes.json", json_encode( $themes, JSON_PRETTY_PRINT ) );
    die;

}


function fixSettings(){
    $songs = json_decode( file_get_contents( __DIR__ . "/app/JackiePuppet/data-files/songs-old.json" ), false );
    $settings = [];

    // create settings array
    foreach($songs as $song){
        foreach( $song->settings as $setting ){

            $setting = trim($setting);

            $patterns = [
                "parent"=>'/^([^(]+) \(([A-Za-z0-9 .\',-]+)\)$/',
                "simple"=>'/^([^(]+)$/',
            ];

            $matches = [];
            foreach( $patterns as $key=>$pattern ){
                preg_match( $pattern, $setting, $matches );
                if( count($matches) > 0 ){
                    $matches[] = $key;
                }

            }
            pre($matches);
            die;
            $slug = slugify($setting);
            if( ! isset( $settings[$setting] ) ){
                $settings[$slug] = (object) [
                        "name"=> $setting,
                        "slug"=> slugify($setting),
                        "description"=> "",
                        "image"=> "",
                        "songs"=> [ $song->slug ]
                ];
            }
            else{
                $settings[$setting]->songs[] = $song->slug;
            }
            
        }
    }

    usort ( $settings , function($a, $b){
        return strcmp($a->name, $b->name);
    });

    foreach( $settings as $setting ){
        $setting->songs = array_unique( $setting->songs );
    }

    file_put_contents( __DIR__ . "/app/JackiePuppet/data-files/settings.json", json_encode($settings, JSON_PRETTY_PRINT) );

    $songs = json_decode( file_get_contents( __DIR__ . "/app/JackiePuppet/data-files/songs.json" ), false );
    
    foreach( $songs as $song ){
        $song->settings = [];
        foreach($settings as $setting){
            if( in_array( $song->slug, $setting->songs ) ){
                $song->settings[] = $setting->slug;
            }
        }

    }

    file_put_contents( __DIR__ . "/app/JackiePuppet/data-files/songs.json", json_encode($songs, JSON_PRETTY_PRINT) );
    
    die;
}

function fixLocations(){

    $songs =  json_decode( file_get_contents( __DIR__ . "/app/JackiePuppet/data-files/songs-old.json" ), false );

    $current_attribute = "locations";
    $current_attributes_array = [];
    $song_locations_array = [];

    $dates = [];
    // create locations array 
    foreach($songs as $song){
        
        $song_locations_array[$song->slug] = [];

        foreach( $song->$current_attribute as $attribute ){

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

                            // create the location if it doesn't exist
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

                            if( !in_array( $parent_slug, $song_locations_array[$song->slug] )) {
                                $song_locations_array[$song->slug][]=$parent_slug;

                            }
                            if( !in_array( $slug, $song_locations_array[$song->slug] )){
                                $song_locations_array[$song->slug][]=$slug;
                            }

                            
                            break;
                        case "simple":
                            $name = trim($matches[1]);
                            $slug = slugify( $matches[1] );

                            if( !array_key_exists( $slug, $current_attributes_array ) ){
                                $current_attributes_array[$slug] = (object) [
                                    "name" => $name,
                                    "slug" => $slug,
                                    "count" => 0,
                                    "children" => [],
                                    "parent" => "",
                                    "songs" => [ $song->slug ],
                                ];
                            }
                            else{
                                if( !in_array( $song->slug, $current_attributes_array[$slug]->songs ) ){
                                    $current_attributes_array[$slug]->songs[] = $song->slug;
                                }
                            }
                            if( !in_array( $slug, $song_locations_array[$song->slug] )){
                                $song_locations_array[$song->slug][]=$slug;
                            }
                            break;
                    }
                    break;
                }
            } 
            

        }

    }

    $songs = json_decode( file_get_contents( __DIR__ . "/app/JackiePuppet/data-files/songs.json" ) );
    pre($songs);
    foreach($songs as $song){
        $song->locations = $song_locations_array[$song->slug];
    }


    // sort by name
    usort( $current_attributes_array, function( $a, $b ){
        return strcmp( $a->name, $b->name );
    } );

    
    // write the locations json file
    //file_put_contents( __DIR__ . "/app/JackiePuppet/locations.json" , json_encode( $current_attributes_array, JSON_PRETTY_PRINT ) );
    //file_put_contents( __DIR__ . "/app/JackiePuppet/dates/dates.json" , json_encode( $dates, JSON_PRETTY_PRINT ) );
   //pre($songs);
    //file_put_contents( __DIR__ . "/app/JackiePuppet/data-files/songs.json" , json_encode( $songs, JSON_PRETTY_PRINT ) );
    //echo "done";
die;
}

    // write the people json file
    //file_put_contents( __DIR__ . "/app/JackiePuppet/songs.json" , json_encode( $songs, JSON_PRETTY_PRINT ) );
