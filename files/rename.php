<?php

// This script will rename a file requested by the user
// This script relies on the input of another form

// Asign the submitted data to variables
$path = $_POST['path'];
$from = $_POST['from'];
$dest = $_POST['dest'];

// Perform the rename
rename("".$path."".$from."", "".$path."".$dest."");

// Redirect to the file manager
header('location:../files.php?path='.$path);

?>