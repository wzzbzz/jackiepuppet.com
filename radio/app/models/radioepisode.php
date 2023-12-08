<?php


class RadioEpisode extends Episode
{

    public $data;

    protected $base_url;


    public function get_episode_number()
    {
        return $this->data['episodeNumber'];
    }

    public function get_title()
    {
        return $this->data['title'];
    }

    public function get_notes()
    {
        return $this->data['notes'];
    }

    public function get_songs()
    {
        return array_map( function($song_data) {
            return new Song($song_data);
        }, $this->data['songs']);
    }

    public function get_song($index)
    {
        return $this->get_songs()[$index];
    }

    public function validate_json($data)
    {
        if (isset($data['episodeNumber']) == false) {
            return false;
        }
        return true;
    }

    public function to_json()
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
