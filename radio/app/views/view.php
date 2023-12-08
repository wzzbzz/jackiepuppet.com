<?php

class View{
    protected $app;
    public function __construct( $app ){
        $this->app = $app;
    }
    public function render(){
        $this->header();
        $this->body();
        $this->footer();
    }

    public function header(){
        $this->doctype();
        $this->head();
    }

    public function doctype(){
        ?><!DOCTYPE html>
        <html>
        <?php
    }

    public function head(){
        ?><head><?php
        $this->title();
        $this->meta();
        $this->css();
        $this->js();
        ?></head><?php
    }

    public function title(){
        ?><title>Sonic Twist Radio</title><?php
    }

    public function meta(){
        ?><meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:title" content="<?= $og_title;?>">
        <meta property="og:description" content="<?= $og_description;?>">
        <meta property="og:image" content="<?= $og_image; ?>">
        <meta property="og:url" content="<?= $og_url;?>">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Sonic Twist Radio">
        <meta property="og:locale" content="en_US">
        <?php
    }

    public function css(){
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
        <!-- link local stylesheet -->
        <link rel="stylesheet" href="radio/style.css">
        <?php
    }

    public function js(){
        ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- link local javascript -->
        <!-- <script src="radio/script.js"></script> -->
        <?php
    }

    public function body(){
        ?><body><?php
        $this->nav();
        $this->main();
        $this->content_footer();
        ?></body><?php
    }

    public function nav(){

    }

    public function main(){

    }

    public function content_footer(){}

    public function footer(){
        ?></html><?php
    }

}

