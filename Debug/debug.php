<?php
function debug($var, $mode = 1) {
    echo '<div style="background: orange;padding: 5px;">';
    $trace = debug_backtrace();
    $trace = array_shift($trace);
    echo "Debug demandé dans le fichier : $trace[file] à la ligne $trace[line].<hr>";
    
    if ($mode === 1) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    } else {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
    
    echo '</div>';
};
?>