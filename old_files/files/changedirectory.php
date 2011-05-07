<?php

// This script will redirect the user to the requested directory
// and display a listing where further actions can be performed.

if ($_POST['directory'] == '/' || $_POST['directory'] == '') {
	
	// Calculate home directory name
	$filename = '/home/'.$_SESSION['username'];

	if (file_exists($filename)) {
  		$homedir = '/home/'.$_SESSION['username'];
	} else {
	    $homedir = '/home1/'.$_SESSION['username'];
	}
	
	$directory = $homedir;
}else{
	$directory = $_POST['directory'];
}

// Redirect to the file listing
header('location:../files.php?path='.$directory.'/');