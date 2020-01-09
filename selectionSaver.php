<?php

    $selection = json_encode( $_POST['ids']);
    if ($selection == 'null'){
        $selection = "[]";
    }
    file_put_contents("selection.json", $selection);

?>