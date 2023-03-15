<?php
// display all errors 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "functions.php";

// get the slug from the server variable
$path = $_SERVER['REQUEST_URI'];

$songs =  json_decode( file_get_contents( __DIR__ . "/app/JackiePuppet/songs.json" ), false );

// adjust the credits to be slugs, with roles if they exist
foreach( $songs as $song ){
    
    foreach( $song->credits as $song_key => $credit_line ){
    
    //    $song->settings = array_map("trim",$song->locations);
        
    }
    

}





// write the people json file
//file_put_contents( __DIR__ . "/app/JackiePuppet/songs.json" , json_encode( $songs, JSON_PRETTY_PRINT ) );

?>
<!-- mobile first boiler plate with bootstrap -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jackie Puppet Songs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <style>
        /* font family */
        body {
            font-family: 'Roboto', sans-serif;
        }
        body {
            background-color: black;
            color: white;
        }

        pre{
            background-color: black;
            color: white;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* nav */
        .nav {
            background-color: black;
            color: white;
            padding: 10px;
        }

        .nav ul {
            list-style: none;
            display: flex;
            justify-content: space-between;
            margin: 0;
            padding: 0;
            width:100%;
        }

        
    </style>
</head>
<body>
    <!-- nav with login and register buttons --> 
    <div class="container">
    <div class="nav">
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/login/">Login</a></li>
            <li><a href="/register/">Register</a></li>
            <li><a href="/songs/">Songs</a></li>
            <li><a href="/people/">People</a></li>
            <li><a href="/characters/">Characters</a></li>
            <li><a href="/locations/">Locations</a></li>
            <li><a href="/settings/">Settings</a></li>
            <li><a href="/themes/">Themes</a></li>
        </ul>
    </div>

        <?php chooseRoute( $path ); ?>
    </div>
</body>
</html>