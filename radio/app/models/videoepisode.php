<?php


class VideoEpisode extends Episode
{

    public $data;

    protected $base_url;

    public function get_videos()
    {
        return array_map( function($video_data) {
            return new Video($video_data);
        }, $this->data['videos']);
    }

    public function get_video($index)
    {
        return $this->get_videos()[$index];
    }


}
