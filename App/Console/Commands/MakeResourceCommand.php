<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class MakeResourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:customResource {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function getSingularClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }

    public function getResource()
    {
        return __DIR__ . '/../../../stubs/customResource.stub';
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

    private function filterResourceName($names)
    {
        $position = strpos($names, '/');
        $resourcePosition = strpos($names, '?');
        $resourceName = substr($names, 0, $resourcePosition);
        $resourceName = substr($resourceName, $position + 1);
        return $resourceName;
    }

    private function filterApiName($names)
    {
        $position = strpos($names, '=');
        $pathName = substr($names, $position + 1);
        return $pathName;
    }

    public function getStubResourceVariables()
    {
        $projectName = $this->filterProjectName($this->getSingularClassName($this->argument('name')));
        $folderName = $this->filterFolderName($this->getSingularClassName($this->argument('name')));
        $serviceName = $this->filterResourceName($this->getSingularClassName($this->argument('name')));
        $pathName = $this->filterApiName($this->getSingularClassName($this->argument('name')));
        $service = substr($serviceName, 0, -8);
        $capital = $service;
        return [
            //namespace Garment\Web\Sizes\Services;
            'NAMESPACE' => "$projectName\\$pathName\\$folderName\\Resources",
            'ClASS' => $capital,
        ];
    }

    public function getResourceSourceFile()
    {
        return $this->getStubResourceContents($this->getResource(), $this->getStubResourceVariables());
    }

    public function getStubResourceContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);
        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$' . $search . '$', $replace, $contents);
        }
        return $contents;
    }

    public function getResourceFilePath()
    {
        $folderName = $this->filterFolderName($this->getSingularClassName($this->argument('name')));
        $pathName = $this->filterApiName($this->getSingularClassName($this->argument('name')));
        $resourceName = $this->filterResourceName($this->getSingularClassName($this->argument('name')));
        return base_path("modules\\$pathName\\$folderName\\Resources") . "\\" . $resourceName . ".php";
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
        $path = $this->getResourceFilePath();
        $this->makeDirectory(dirname($path));
        $contents = $this->getResourceSourceFile();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }
    }
}
