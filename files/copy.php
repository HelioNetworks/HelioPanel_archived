<?php

// This script will copy a file requested by the user
// This script relies on the input of another form

// Asign the submitted data to variables
$path = $_POST['path'];
$from = $_POST['from'];
$dest = $_POST['dest'];

$original = $path.''.$from;
$destinat = $dest.''.$from;

// Perform the copy
if (!copy($original, $destinat)) {
    echo "Error: Failed to copy file";
}

// Redirect to the file manager
header('location:../files.php?path='.$dest);

?>