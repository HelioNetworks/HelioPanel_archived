<?php

$authKey = '%authKey%';

if($_GET['auth'] !== $authKey) {
    echo '403 Unauthorized';
    die();
}

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
                        'file' => $file,
                        'type' => $type,
                    );
                }
            }
            closedir($handle);
        }

        echo serialize($files);
}