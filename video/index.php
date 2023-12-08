<?php
//display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "functions.php";

// // if there's no app name in the get, do a 404
// if( !isset($_GET['app']) ){
//     header("HTTP/1.0 404 Not Found");
//     die;
// }

// if the app name is not 'radio', do an error page. 
// if( $_GET['app'] != 'radio' ){
//     header("HTTP/1.0 404 Not Found");
//     die;
// }

// get the app name from the get

include 'app/app.php';

$app = new VideoApp('slim-tv');

if(!empty($_GET)){
  
    $episode_index = $_GET["episode"];
    $song_index = $_GET["song"] -1;

    $episode = $app->get_episode_number_from_index($episode_index); // there are missing episodes, so we need to get the episode number from the index
    $song = $song_index; // the song index is zero based, but the url is 1 based. 

}
else{
    $episode = "";
    $song = "";
}

$og_description = $app->og_description();
$og_image = $app->og_image();
$og_url = $app->og_url();
$og_title = $app->og_title();

?><!DOCTYPE html>
<html>
<head>
    <title>Sonic Twist Radio</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- give me some og tags -->
    <meta property="og:title" content="<?= $og_title;?>">
    <meta property="og:description" content="<?= $og_description;?>">
    <meta property="og:image" content="<?= $og_image; ?>">
    <meta property="og:url" content="<?= $og_url;?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Sonic Twist Radio">
    <meta property="og:locale" content="en_US">

    <link rel="stylesheet" type="text/css" href="/sonic-twist-radio/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        var episodes;
        var current_episode;

        // document load event
        $(document).ready(function() {

           // remove the episode and song from the url
            window.history.pushState("", "", "/sonic-twist-radio/"); 
            
            // load episode from ajax endpoint: /api/episode 
            $.ajax({
                url: "/sonic-twist-radio/api/episodes",
                dataType: "json",
                success: function(data) {

                    episodes = data;
                    current_episode = '<?php echo $episode; ?>';
                    current_song = '<?php echo $song; ?>';
                
                    var select = $("header nav #episodes select");
                                    
                    // loop through the episodes
                    $.each(episodes, function(index, episode) {
                        // create an option element
                        var option = $("<option></option>");
                        // set the option value to the episode number
                        option.attr("value", episode.episodeNumber);
                        // set the option text to the episode title
                        option.html(episode.title);
                        // append the option to the select element
                        select.append(option);
                    });

                    // calculate the total songs from the data 
                    var total_songs = 0;
                    $.each(episodes, function(index, episode) {
                        total_songs += episode.songs.length;
                    });
                    $("#total_songs").html(total_songs);

                    // if the episode and song are set in the url, play that song
                    if( current_episode != "" && current_song != "" ){


                        console.log( "episode: " + current_episode + " song: " + current_song );

                        // find the index of the episode in the episodes array with the episode number
                        current_episode_index = episodes.findIndex( episode => episode.episodeNumber == current_episode );
                        console.log( "current_episode_index: " + current_episode_index );
                        
                        // set the episode
                        select.prop("selectedIndex", current_episode_index);
                        select.change();
                        // set the song
                        $("#song_list ul li[data-index='" + current_song + "']").click();
                    }
                    else{
                    // select a random episode to start
                    var random_episode_index = Math.floor(Math.random() * episodes.length);
                    select.prop("selectedIndex", random_episode_index);
                    select.change();

                    // select a random song to start
                    var random_song_index = Math.floor(Math.random() * $("#song_list ul li").length);
                    $("#song_list ul li[data-index='" + random_song_index + "']").click();
                    }

                }
            });

            $("#song_list ul").on("click", "li", function() {
                var index = $(this).data("index");
                var song = episodes[current_episode].songs[index];

                $("#song_list ul li").removeClass("active");
                $(this).addClass("active");
                
                // set the title and anything else we have available for it.
                $("#song_title").html(song.title);
                $("#song_notes").html(song.notes.ai_description);
                $("#song_lyrics").html( displayLyrics(song.lyrics) );

                $("#audio_player source").attr("src", episodes[current_episode].base_url + song.file);
                $("#audio_player")[0].load();
                // play on load
                $("#audio_player")[0].play();

                // change the url and the page title
                var episode_number = episodes[current_episode].episodeNumber;
                var song_number = index + 1;
                var title = episodes[current_episode].title + ": " + song.title;
                document.title = title;

            });

            // when a song ends, play the next song
            $("#audio_player").on("ended", function() {


                // if the shuffle checkbox is checked, play a random song
                if ($("#shuffle").prop("checked")) {
                    // pick a random episode
                    var random_episode_index = Math.floor(Math.random() * episodes.length);
                    $("header nav select").prop("selectedIndex", random_episode_index);
                    $("header nav select").change();
                    // pick a random song
                    var random_song_index = Math.floor(Math.random() * $("#song_list ul li").length);
                    $("#song_list ul li[data-index='" + random_song_index + "']").click();
                    return;
                }

                // if the repeat checkbox is checked, play the same song again
                if ($("#repeat").prop("checked")) {
                    $("#audio_player")[0].play();
                    return;
                }

                var current_song_index = $("#song_list ul li.active").data("index");
                var next_song_index = current_song_index + 1;
                var next_song = $("#song_list ul li[data-index='" + next_song_index + "']");
                if (next_song.length > 0) {
                    next_song.click();
                }
                else {
                    
                    // load the next episode
                    var current_episode_index = $("header nav select").prop("selectedIndex");
                    // if there is not a next episode, wrap it back to the first episode
                    var next_episode_index = current_episode_index + 1;
                    if (next_episode_index >= episodes.length) {
                        next_episode_index = 0;
                    }
                    $("header nav select").prop("selectedIndex", next_episode_index);
                    $("header nav select").change();

                }
            });

            // on shuffle click, clear the repeat checkbox
            $("#shuffle").on("click", function() {
                $("#repeat").prop("checked", false);
            });

            // on repeat click, clear the shuffle checkbox
            $("#repeat").on("click", function() {
                $("#shuffle").prop("checked", false);
            });

            // on copy link click, copy the link to the clipboard
            $("#copy_link").on("click", function() {
                // get the base url, and add the episode and song
                var episode_number = episodes[current_episode].episodeNumber;
                var song_number = $("#song_list ul li.active").data("index") + 1;
                var url = window.location.origin + "/sonic-twist-radio/" + episode_number + "/" + song_number;

                // copy url to clipboard
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(url).select();
                document.execCommand("copy");
                $temp.remove();

                
                
                $("#notification").html("Link copied to clipboard");
                setTimeout(function() {
                    $("#notification").html("");
                }, 1000);
            });



            // on nav select change, change the episode
            $("header nav select").on("change", function() {
                // which one was clicked - not the value.
                    
                current_episode = $(this).prop("selectedIndex");
            
                var episode = episodes[current_episode];

                $("#episode_title").html(episode.title);
                $("#audio_player source").attr("src", episode.base_url + episode.songs[0].file);
                $("#audio_player")[0].load();
                $("#song_list ul").html("");
                $.each(episode.songs, function(index, song) {
                    var active = (index == 0) ? "active" : "";
                    $("#song_list ul").append("<li class='" + active + "' data-index = '" + index + "'>" + song.title + "</li>");
                });
                $("#song_list ul li").first().click();

            });
            
            $("#lyrics_toggle").click(function() {
                toggleLyrics();
            });

            // the search will be an autocomplete that searches for a song title within all episodes
            $("#search input").on("keyup", function() {
                $("#search_results").show();

                var search_term = $(this).val();
                var results = [];
                $.each(episodes, function(index, episode) {
                    $.each(episode.songs, function(index, song) {
                        if (song.title.toLowerCase().indexOf(search_term.toLowerCase()) > -1) {
                            results.push(song);
                        }
                    });
                });
                // put the selections in a select element after the search input. 
                $("#search_results").html("");
                $.each(results, function(index, song) {
                    $("#search_results").append("<option value='" + song.title + "' data-episode='" + song.episode + "' data-song='" + song.index + "'>" + song.title + "</option>");
                });
                
                
            });


            
        });
        
        function displayLyrics( lyrics ){
            var html = "";
            // lyrics are in arrays of arrays;  the first array is the verse, the second is the line
            $.each(lyrics, function(verse_index, verse) {
                html += "<div class='verse'>";
                $.each(verse, function(line_index, line) {
                    html += "<div class='line'>" + line + "</div>";
                });
                html += "</div>";
            });
            return html;
        }

        function toggleLyrics() {
            if( $("#song_lyrics").is(":visible") ){
                $("#lyrics_toggle").html("Show Lyrics");
                $("#song_lyrics").hide();
                return;
            }
            $("#lyrics_toggle").html("Hide Lyrics");
            $("#song_lyrics").show();
        }

    </script>
</head>
<body>
    <header>
        <nav>
            <!-- <div id="search">
                <input type="text" placeholder="Search">
                <select id="search_results"></select>
            </div> -->
            <!-- provide markup for a search bar-->
            <div id="episodes">
                <select></select>
            </div>
        </nav>
    </header>
    <main>
        <section>
            <!-- <img src="images/sonic-twist-radio-logo.png" alt="Sonic Twist Radio"> -->
            
            <div id="stats">
                <p><span id="total_songs"></span> song demos from the world of Jackie Puppet</p>
                <p>Browse by episode, or click shuffle and let it run!</p>
            </div>
        </section>
        <section>
            <h2 id="episode_title"></h2>
            <p id="ai_episode_description"></p>
        </section>
        <section>
            <div id="song">
                <div id="song_title"></div>
                <div id="song_notes"></div>
                </div>
            </div>
            <audio id="audio_player" controls>
                <source src="" type="audio/mpeg">
            </audio>
            <div id="lyrics container">
                <button id="lyrics_toggle">Hide Lyrics</button>
                <div id="song_lyrics"></div>
            </div>
            <div id="controls">
                <div><input type="checkbox" id="shuffle" checked > Shuffle</div>
                <div><input type="checkbox" id="repeat"> Repeat</div>
                <div><button id="copy_link">Copy Link</button><span id="notification"></div>
            
            </div>
        </section>
        <section>
            <div id="song_list">
                <h2>Song List</h2>
                <ul>
                    <li></li>
                </ul>
            </div>
        </section>

    </main>
    <footer>
        <p>All Songs, Words, Melodies, Performances, Arrangementsm and &copy;: Brad Kleiman</p>
    </footer>
</body>
</html>
