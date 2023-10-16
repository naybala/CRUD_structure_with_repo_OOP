<?php

namespace App\Console\Commands;

use App\Console\Commands\MakeCommonCommand\MakeCommonCommand;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class MakeControllerCommand extends Command
{
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

    public function getStubControllerVariables()
    {
        $projectName = $this->commonCommand->filterProjectName($this->getSingularClassName($this->argument('name')));
        $folderName = $this->commonCommand->filterFolderName($this->getSingularClassName($this->argument('name')));
        $controllerName = $this->commonCommand->filterMainName($this->getSingularClassName($this->argument('name')));
        $pathName = $this->commonCommand->filterApiName($this->getSingularClassName($this->argument('name')));
        $controller = substr($controllerName, 0, -10);
        $capital = $controller;
        $controller = lcfirst($capital);
        return [
            'NAMESPACE' => "$projectName\\$pathName\\$folderName\\Controllers",
            'CLASS_NAME' => $controllerName,
            'FOLDER_NAME' => $folderName,
            'PROJECT_NAME' => $projectName,
            'PATH_NAME' => $pathName,
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
        $folderName = $this->commonCommand->filterFolderName($this->getSingularClassName($this->argument('name')));
        $controllerName = $this->commonCommand->filterMainName($this->getSingularClassName($this->argument('name')));
        $pathName = $this->commonCommand->filterApiName($this->getSingularClassName($this->argument('name')));
        return base_path("modules" . DIRECTORY_SEPARATOR . $pathName . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . "Controllers") . DIRECTORY_SEPARATOR . $controllerName . ".php";
    }

    //Make Directory For custom Artisan
    protected $files;

    public function __construct(
        Filesystem $files,
        private MakeCommonCommand $commonCommand,
    ) {
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