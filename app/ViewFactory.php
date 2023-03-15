<?php


class ViewFactory
{
    public static function create($uri)
    {
        $uri = trim($uri, '/');
        $uri = explode('/', $uri);
        $controller = $uri[0];
        $action = $uri[1];
        $controller = ucfirst($controller);
        $controller = "app\\{$controller}Controller";
        $controller = new $controller;
        $controller->$action();
    }
}