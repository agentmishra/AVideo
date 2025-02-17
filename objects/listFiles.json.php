<?php
global $global, $config;
if (!isset($global['systemRootPath'])) {
    require_once '../videos/configuration.php';
}
header('Content-Type: application/json');

if (!User::canUpload() || !empty($advancedCustom->doNotShowImportMP4Button)) {
    return false;
}
$global['allowed'] = ['mp4'];
$files = [];
if (!empty($_POST['path'])) {
    $path = $_POST['path'];
    if (substr($path, -1) !== '/') {
        $path .= "/";
    }

    if (file_exists($path)) {
        $extn = implode(",*.", $global['allowed']);
        $extnLower = strtolower($extn);
        $extnUpper = strtoupper($extn);
        $filesStr = "{*." . $extn . ",*" . $extnLower . ",*" . $extnUpper . "}";

        //echo $files;
        $video_array = glob($path . $filesStr, GLOB_BRACE);

        $id = 0;
        foreach ($video_array as $key => $value) {
            $path_parts = pathinfo($value);
            $obj = new stdClass();
            $obj->id = $id++;
            $obj->path = mb_convert_encoding($value, 'UTF-8');
            $obj->name = mb_convert_encoding($path_parts['basename'], 'UTF-8');
            $files[] = $obj;
        }
    }
}
echo json_encode($files);
