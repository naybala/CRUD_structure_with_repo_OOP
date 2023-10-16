<?php
namespace App\Console\Commands\MakeCommonCommand;

class MakeCommonCommand
{
    public function filterProjectName($names)
    {
        $projectPosition = strpos($names, '.');
        $projectName = substr($names, 0, $projectPosition);
        return $projectName;
    }

    public function filterFolderName($names)
    {
        $projectPosition = strpos($names, '.');
        $position = strpos($names, '/');
        $folderName = substr($names, 0, $position);
        $folderName = substr($folderName, $projectPosition + 1);
        return $folderName;
    }

    public function filterMainName($names)
    {
        $position = strpos($names, '/');
        $controllerPosition = strpos($names, '?');
        $controllerName = substr($names, 0, $controllerPosition);
        $controllerName = substr($controllerName, $position + 1);
        return $controllerName;
    }

    public function filterApiName($names)
    {
        $position = strpos($names, '=');
        $pathName = substr($names, $position + 1);
        return $pathName;
    }
}