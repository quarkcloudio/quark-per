<?php

require(__DIR__.'/../helpers.php');

$resourceAssets = __DIR__.'/../../public';

$params = getopt('path:');

if($params['path']) {
    $path = $params['path'];
    $assets = __DIR__.'/../../../../../' . $path;
} else {
    $path = 'public/admin';
    $assets = __DIR__.'/../../../../../' . $path;

    if(!realpath($assets)) {
        mkdir($assets);
    }
}

$dirs = get_folder_dirs($resourceAssets);
$files = get_folder_files($resourceAssets);

if(is_array($dirs)) {
    foreach ($dirs as $key => $value) {
        $dirPath = $resourceAssets.'/'.$value;
        copy_dir_to_folder($dirPath, $assets);
    }
}

if(is_array($files)) {
    foreach ($files as $key => $value) {
        $filePath = $resourceAssets.'/'.$value;
        copy_file_to_folder($filePath, $assets);
    }
}

print_r('Publish the resources to [ ' . $path . ' ] successed!');