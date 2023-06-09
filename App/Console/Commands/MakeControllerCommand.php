<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class MakeControllerCommand extends Command
{
    //php artisan make:customController Garment.Demos/DemoController
    //                           ProjectName.FolderName/ControllerName
    //
    //get customController.stub
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:customController {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make and Controller class';

    public function getSingularClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }

    public function getController()
    {
        return __DIR__ . '/../../../stubs/customController.stub';
    }

    private function filterProjectName($names)
    {
        $projectPosition = strpos($names, '.');
        $projectName = substr($names, 0, $projectPosition);
        return $projectName;
    }

    private function filterFolderName($names)
    {
        $projectPosition = strpos($names, '.');
        $position = strpos($names, '/');
        $folderName = substr($names, 0, $position);
        $folderName = substr($folderName, $projectPosition + 1);
        return $folderName;
    }

    private function filterControllerName($names)
    {
        $position = strpos($names, '/');
        $controllerName = substr($names, $position + 1);
        return $controllerName;
    }

    public function getStubControllerVariables()
    {
        $projectName = $this->filterProjectName($this->getSingularClassName($this->argument('name')));
        $folderName = $this->filterFolderName($this->getSingularClassName($this->argument('name')));
        $controllerName = $this->filterControllerName($this->getSingularClassName($this->argument('name')));
        $controller = substr($controllerName, 0, -10);
        $capital = $controller;
        $controller = lcfirst($capital);
        return [
            'NAMESPACE' => "$projectName\\Web\\$folderName\\Controllers",
            'CLASS_NAME' => $controllerName,
            'FOLDER_NAME' => $folderName,
            'PROJECT_NAME' => $projectName,
            'CAMEL_CASE' => $controller,
            'CAPITAL' => $capital,
        ];
    }

    public function getControllerSourceFile()
    {
        return $this->getStubControllerContents($this->getController(), $this->getStubControllerVariables());
    }

    public function getStubControllerContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);
        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$' . $search . '$', $replace, $contents);
        }
        return $contents;
    }

    public function getControllerFilePath()
    {
        $folderName = $this->filterFolderName($this->getSingularClassName($this->argument('name')));
        $controllerName = $this->filterControllerName($this->getSingularClassName($this->argument('name')));
        return base_path("modules\\Web\\$folderName\\Controllers") . "\\" . $controllerName . ".php";
    }

    //Make Directory For custom Artisan
    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }
        return $path;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $path = $this->getControllerFilePath();
        $this->makeDirectory(dirname($path));
        $contents = $this->getControllerSourceFile();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }
    }
}
