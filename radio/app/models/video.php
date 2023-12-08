<?php

class Song extends Clip
{

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function get_credits()
    {
        return $this->data['lyrics'];
    }

    public function get_notes()
    {
        return $this->data['notes'];
    }

    public function get_ai_description()
    {
        return $this->data['notes']['ai_description'];
    }

    

    public function get_notes_html()
    {
        $notes = $this->get_notes();
        $html = '<div class="notes">';
        $html .= '<div class="ai_description">' . $notes->ai_description . '</div>';
        $html .= '</div>';
        return $html;
    }

    public function get_html()
    {
        $html = '<div class="song">';
        $html .= '<div class="title">' . $this->get_title() . '</div>';
        $html .= '<div class="file">' . $this->get_file() . '</div>';
        $html .= $this->get_lyrics_html();
        $html .= $this->get_notes_html();
        $html .= '</div>';
        return $html;
    }

}
