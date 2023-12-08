<?php

class VideoApp{

    protected $base_dir;
    protected $base_url;

    private $channel;
    private $sequence;
    private $episode;

    public function __construct( $path = 'video' ){
        $this->base_dir = $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
        $this->base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . '/' . $path;
    }

    public function run(){
        // if there is no channel, then it is the radio home page.

        if ($this->is_home_page()){
            $this->home();
            return;
        }
    
        $this->channel();
        
    }

    public function is_home_page(){
        if( isset( $_GET['channel'] ) == false ){
            return true;
        }
        return false;
    }

    public function home(){
        $view = new Homepage( $this );
        $view->render();
    }

    public function get_sequence(){
        // the sequence are in the sequence folder in the base dir
        $sequence_dir = $this->base_dir . '/sequence';
        // loop through the sequence directory, these are the sequence folders.  Sort them alphabetically
        $sequence = array();
        foreach (scandir($sequence_dir) as $sequence_folder) {
            // ignore the . and .. directories
            if ( $sequence_folder == '.' || $sequence_folder == '..' ) {
                continue;
            }
            // get the sequence json file
            $sequence_json_file = $sequence_dir . '/' . $sequence_folder . '/sequence.json';
            // get the sequence json data
            $sequence_data = json_decode(file_get_contents($sequence_json_file), true);
            // create a new sequence object
            $sequence = new Sequence($this, $sequence_data);
            // add the sequence to the sequence array
            $sequence[$sequence_folder] = $sequence;
        }

        pre($sequence);
        die;

    }

    public function channel(){
        $view = new ChannelPage( $this );
    }

    public function get_base_dir(){
        return $this->base_dir;
    }

    public function get_base_url(){
        return $this->base_url;
    }

    public function episodes_dir(){
        return $this->base_dir . '/episodes';
    }

    public function get_latest_episode_number(){
        $episodes = $this->get_episodes();
        $latest_episode = end($episodes);
        return $latest_episode->get_episode_number();
    }

    public function get_episodes(){
        // loop through the episodes directory, these are the episode folders.  Sort them numerically. 
        $episodes = array();
        foreach (scandir($this->episodes_dir()) as $episode_dir) {
            // ignore the . and .. directories
            if ( is_numeric($episode_dir) == false) {
                continue;
            }
            // get the episode number from the directory name
            $episode_number = intval($episode_dir);
            // get the episode json file
            $episode_json_file = $this->episodes_dir() . '/' . $episode_dir . '/songs.json';
            // get the episode json data
            $episode_data = json_decode(file_get_contents($episode_json_file), true);
            // create a new episode object
            $episode = new Episode($this, $episode_data);
            // add the episode to the episodes array
            $episodes[$episode_number] = $episode;
        }
        
        // sort the keys numerically
        ksort($episodes);

        return $episodes;
    }
    
    public function get_title(){
        return "The Sonic Twist";
    }

    public function get_episode($episode_number){
        if( 'latest' == $episode_number ){
            $episodes = $this->get_episodes();
            
            $latest_episode = end($episodes);
            
            return $latest_episode;
        }
        else{
            $episodes = $this->get_episodes();
            
            if( isset( $episodes[$episode_number] ) == false ){
                throw new Exception("Episode not found");
            }
            return $episodes[$episode_number];
        }
    }

    public function get_episode_number_from_index( $index ){
        $episodes = $this->get_episodes();
        return $episodes[$index]->get_episode_number();
    }

    public function og_description(){
        // check the get, and get the episode 
        if( isset( $_GET['episode'] ) ){
            $episode = $this->get_episode( $_GET['episode'] );
            if( isset($_GET['song'])){
                $song = $episode->get_song( $_GET['song'] - 1  );
                return $song->get_ai_description();
            }
            return $episode->get_ai_description();
        }
        else{    
            $episode = $this->get_episode( 'latest' );
            return "Demos and songs from the Sonic Twist Collection";
        }
        
    }

    public function og_title(){
        // check the get, and get the episode 
        if( isset( $_GET['episode'] ) ){
            $episode = $this->get_episode( $_GET['episode'] );
            if( isset($_GET['song'])){
                $song = $episode->get_song( $_GET['song'] - 1  );
                return $episode->get_title() . ": " .  $song->get_title();
            }
            return $episode->get_title();
        }
        else{    
            $episode = $this->get_episode( 'latest' );
            return "Sonic Twist Radio.";
        }
        
    }
    public function og_image(){
        return "";
        // check the get, and get the episode \
        if( isset( $_GET['episode'] ) ){
            $episode = $this->get_episode( $_GET['episode'] );
            
        }
        else{
            $episode = $this->get_episode( 'latest' );
        }
        return $episode->get_song(0)->get_image_url();
    }

    public function og_url(){

        return "";
    }

    
}
require_once __DIR__ . '/models/models.php';
require_once __DIR__ . '/views/views.php';