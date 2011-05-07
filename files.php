<?php

///// HelioPanel /////
//// File Manager ////

// Turn off all error reporting
error_reporting(0);

// Include the header
require 'style/header.php';

// If no page was requested, include the homepage
if ($_GET['act'] == NULL) {
require 'files/pages/home.php';
}

// If a rename was requested, ask for the destination
if ($_GET['act'] == 'rename') {
require 'files/pages/rename.php';
}

// If a move was requested, ask for the destination
if ($_GET['act'] == 'move') {
require 'files/pages/move.php';
}

// Include the footer
require 'style/footer.php';
?>