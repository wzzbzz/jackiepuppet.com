<?php

class Episode
{

    public $data;

    protected $base_url;

    public function __construct($app, $data)
    {   
        if ($this->validate_json($data)) {
            $this->data = $data;
            $this->data['base_url'] = $app->get_base_url(). "/episodes/" . $data['episodeNumber'] . "/";
        } else {
            pre($data);
            pre(debug_backtrace());
            throw new Exception("Invalid JSON data");
        }

        $this->base_url = $app->get_base_url();
    }

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
