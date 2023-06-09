<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class MakeCustomValidation extends Command
{
    //php artisan make:customValidation Garment.Validaiton/DemoRequest
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:customValidation {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make and Validation class';

    public function getSingularClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }

    public function getStoreRequest()
    {
        return __DIR__ . '/../../../stubs/customStoreValidation.stub';
    }

    public function getUpdateRequest()
    {
        return __DIR__ . '/../../../stubs/customUpdateValidation.stub';
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

    private function filterRequestName($names)
    {
        $position = strpos($names, '/');
        $requestName = substr($names, $position + 1);
        return $requestName;
    }

    public function getStubStoreRequestVariables()
    {
        $projectName = $this->filterProjectName($this->getSingularClassName($this->argument('name')));
        $folderName = $this->filterFolderName($this->getSingularClassName($this->argument('name')));
        $requestName = $this->filterRequestName($this->getSingularClassName($this->argument('name')));
        $storeUpdateRequest = substr($requestName, 0, -7);
        return [
            'NAMESPACE' => "$projectName\\Web\\$folderName\\Validation",
            'FOLDER_NAME' => $folderName,
            'PROJECT_NAME' => $projectName,
            'CAPITAL' => $storeUpdateRequest,
        ];
    }

    public function getStoreRequestSourceFile()
    {
        return $this->getStubStoreRequestContents($this->getStoreRequest(), $this->getStubStoreRequestVariables());
    }

    public function getUpdateRequestSourceFile()
    {
        return $this->getStubUpdateRequestContents($this->getUpdateRequest(), $this->getStubStoreRequestVariables());
    }

    public function getStubStoreRequestContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);
        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$' . $search . '$', $replace, $contents);
        }
        return $contents;
    }

    public function getStubUpdateRequestContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);
        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$' . $search . '$', $replace, $contents);
        }
        return $contents;
    }

    public function getStoreRequestFilePath()
    {
        $folderName = $this->filterFolderName($this->getSingularClassName($this->argument('name')));
        $requestName = $this->filterRequestName($this->getSingularClassName($this->argument('name')));
        return base_path("modules\\Web\\$folderName\\Validation") . "\\" . "Store" . $requestName . ".php";
    }

    public function getUpdateRequestFilePath()
    {
        $folderName = $this->filterFolderName($this->getSingularClassName($this->argument('name')));
        $requestName = $this->filterRequestName($this->getSingularClassName($this->argument('name')));
        return base_path("modules\\Web\\$folderName\\Validation") . "\\" . "Update" . $requestName . ".php";
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
        $path = $this->getStoreRequestFilePath();
        $this->makeDirectory(dirname($path));
        $contents = $this->getStoreRequestSourceFile();

        $pathTwo = $this->getUpdateRequestFilePath();
        $this->makeDirectory(dirname($path));
        $contentTwo = $this->getUpdateRequestSourceFile();
        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->files->put($pathTwo, $contentTwo);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }
    }
}
