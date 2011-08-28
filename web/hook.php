<?php

error_reporting(0);

$authKey = '%authKey%';

if($_GET['auth'] !== $authKey) {
    echo '403 Unauthorized';
    die();
}

$homeDir = __DIR__.'/../';
$_GET['source'] = $homeDir.$_GET['source'];
$_GET['dest'] = $homeDir.$_GET['dest'];

switch ($_GET['action']) {
    case 'rename':
        rename($_GET['source'], $_GET['dest']);
        break;
    case 'copy':
        copy($_GET['source'], $_GET['dest']);
        break;
    case 'touch':
        file_put_contents($_GET['source'], '');
        break;
    case 'save':
        file_put_contents($_GET['source'], $_POST['data']);
        break;
    case 'get':
        echo file_get_contents($_GET['source']);
        break;
    case 'mkdir':
        mkdir($_GET['source']);
        break;
    case 'ls':
        $files = array();
        if ($handle = opendir($_GET['source'])) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if(is_file($_GET['source'].''.$file.'')) {
                        $type = 'file';
                    }else{
                        $type = 'folder';
                    }

                    $files[] = array(
                        'path' => $_GET['source'].$file,
                        'name' => $file,
                        'type' => $type,
                    );
                }
            }
            closedir($handle);
        }

        echo serialize($files);
        break;
    case 'rm':
        unlink($_GET['source']);
        break;
    default:
        echo '600 Not Implemented';
        break;
}