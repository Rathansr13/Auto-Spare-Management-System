<?php
 $db = mysqli_connect('localhost', 'root', 'root') or
        die ('Unable to connect. Check connection properly.');
        mysqli_select_db($db, 'spare_parts' ) or die(mysqli_error($db));
?>