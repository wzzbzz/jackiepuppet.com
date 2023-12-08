<?php

function pre() {
    $args = func_get_args();
    $backtrace = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 );
    echo '<pre>';
    echo $backtrace[0]['file'] . ':' . $backtrace[0]['line'] . PHP_EOL;
    foreach( $args as $arg ){
        var_dump( $arg );
    }
    echo '</pre>';
}