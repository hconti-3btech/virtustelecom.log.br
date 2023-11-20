<?php

    //echo Date('Y-m-d H:i:s');

    $array = [
        0 => ['e','b','c','d','e'],
        1 => ['b','b','c','d','e'],
        3 => ['d','b','c','d','e'],
        2 => ['c','b','c','d','e'],
        
    ];

    unset($array[2]);

    for ($i=0; $i < count($array); $i++) { 
        
        sort($array);
        // BEGIN: Debugging
        echo "<pre>";
        print_r($array);
        echo "</pre>";
        // END: Debugging

    }       


?>