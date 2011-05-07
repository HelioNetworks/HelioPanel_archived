<?php

// This script will delete a file requested by the user
// This script relies on the input of another webpage

// Asign the submitted data to variables
$path = $_GET['path'];
$file = $_GET['file'];

// Perform delete
$filename = $path.''.$file;
unlink($filename);

// Redirect to the file manager
header('location:../files.php?path='.$path);

?>