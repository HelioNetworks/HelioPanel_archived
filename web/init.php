<?php

@session_start();

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
	header("location:login.php");
	die();
}

function installHook($username, $password, $redirect = 'http://central.heliopanel.heliohost.org')
{
    $url = 'http://heliopanel.heliohost.org/install/autoinstall.php?';
    $query = http_build_query(array(
        'username' => $username,
        'password' => $password,
        'redirect' => $redirect,
    ));

    header('location:'.$url.$query);
    die();
}

// Include the configuration
require __DIR__.'/FileRepository.php';
require __DIR__.'/ConfigManager.php';

$username = $_SESSION['username'];
$configManager = new ConfigManager();
$config = $configManager->getConfig();
$currentConfig = $config[$username];
$fileRepository = new FileRepository($currentConfig['hook_php'], $currentConfig['hook_auth']);


//TODO: Remove this (hook.php should do this)
// Get the user's home directory
if (file_exists('/home/'.$username)) {
    $homedir = '/home/'.$username;
} elseif (file_exists('/home1/'.$username)) {
    $homedir = '/home1/'.$username;
} else {
	die ('Fatal Error: Cannot find home directory!');
}