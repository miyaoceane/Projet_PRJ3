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
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
