<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class MakeModuleCommand extends Command
{
    ////////////////////////////////////////////////////////////////////////////
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make and module class';

    /**
     * Return the Singular Capitalize Name
     * @param $name
     * @return string
     */
    public function getSingularClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }

    //get custom stub from stubs Folder
    public function getStubPath()
    {
        return __DIR__ . '/../../../stubs/customModel.stub';
    }
    public function getProviderPath()
    {
        return __DIR__ . '/../../../stubs/customProvider.stub';
    }

    public function getRepositoryInterfacePath()
    {
        return __DIR__ . '/../../../stubs/customRepositoryInterface.stub';
    }

    /////////////////////////////////////////////////////////////

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

    private function filterModelName($names)
    {
        $position = strpos($names, '/');
        $modelName = substr($names, $position + 1);
        return $modelName;
    }

    //get Name Space And Class Name
    public function getStubVariables()
    {
        $projectName = $this->filterProjectName($this->getSingularClassName($this->argument('name')));
        $folderName = $this->filterFolderName($this->getSingularClassName($this->argument('name')));
        $modelName = $this->filterModelName($this->getSingularClassName($this->argument('name')));
        return [
            'NAMESPACE' => "$projectName\\Foundations\\Domain\\$folderName",
            'CLASS_NAME' => $modelName,
        ];
    }

    public function getStubProviderVariables()
    {
        $projectName = $this->filterProjectName($this->getSingularClassName($this->argument('name')));
        $folderName = $this->filterFolderName($this->getSingularClassName($this->argument('name')));
        $modelName = $this->filterModelName($this->getSingularClassName($this->argument('name')));
        $modelNameRepo = $modelName . "Repository";
        $modelNameRepoInterface = $modelName . "RepositoryInterface";
        return [
            'NAMESPACE' => "$projectName\\Foundations\\Domain\\$folderName\\Providers",
            'REPOSITORY_INTERFACE_PATH' => "$projectName\\Foundations\\Domain\\$folderName\\Repositories\\Eloquent\\$modelNameRepo",
            'REPOSITORY_PATH' => "$projectName\\Foundations\\Domain\\$folderName\\Repositories\\$modelNameRepoInterface",
            'CLASS_NAME' => $modelName,
        ];
    }

    public function getStubRepositoryInterfaceVariables()
    {
        $projectName = $this->filterProjectName($this->getSingularClassName($this->argument('name')));
        $folderName = $this->filterFolderName($this->getSingularClassName($this->argument('name')));
        $modelName = $this->filterModelName($this->getSingularClassName($this->argument('name')));
        return [
            'NAMESPACE' => "$projectName\\Foundations\\Domain\\$folderName\\Repositories",
            'CLASS_NAME' => $modelName,
        ];
    }

    /////////////////////////////////////////////////////////////

    /**
     * Get the stub path and the stub variables
     *
     * @return bool|mixed|string
     *
     */
    public function getSourceFile()
    {
        return $this->getStubContents($this->getStubPath(), $this->getStubVariables());
    }

    public function getProviderSourceFile()
    {
        return $this->getStubProviderContents($this->getProviderPath(), $this->getStubProviderVariables());
    }

    public function getRepositoryInterfaceSourceFile()
    {
        return $this->getStubRepositoryInterfaceContents($this->getRepositoryInterfacePath(), $this->getStubRepositoryInterfaceVariables());
    }

    /////////////////////////////////////////////////////////////
    //get ALl Content form Stub
    public function getStubContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);
        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$' . $search . '$', $replace, $contents);
        }
        return $contents;
    }

    public function getStubProviderContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);
        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$' . $search . '$', $replace, $contents);
        }
        return $contents;
    }

    public function getStubRepositoryInterfaceContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);
        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$' . $search . '$', $replace, $contents);
        }
        return $contents;
    }

    /////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////

    public function getSourceFilePath()
    {
        $folderName = $this->filterFolderName($this->getSingularClassName($this->argument('name')));
        $modelName = $this->filterModelName($this->getSingularClassName($this->argument('name')));
        return base_path("modules" . DIRECTORY_SEPARATOR . "Foundations" . DIRECTORY_SEPARATOR . "Domain" . DIRECTORY_SEPARATOR . $folderName) . DIRECTORY_SEPARATOR . $modelName . '.php';
    }

    public function getProviderFilePath()
    {
        $folderName = $this->filterFolderName($this->getSingularClassName($this->argument('name')));
        $modelName = $this->filterModelName($this->getSingularClassName($this->argument('name')));
        return base_path("modules" . DIRECTORY_SEPARATOR . "Foundations" . DIRECTORY_SEPARATOR . "Domain" . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . "Providers") . DIRECTORY_SEPARATOR . "Bind" . $modelName . "Provider.php";
    }

    public function getRepositoryInterfaceFilePath()
    {
        $folderName = $this->filterFolderName($this->getSingularClassName($this->argument('name')));
        $modelName = $this->filterModelName($this->getSingularClassName($this->argument('name')));
        return base_path("modules" . DIRECTORY_SEPARATOR . "Foundations" . DIRECTORY_SEPARATOR . "Domain" . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . "Repositories") . DIRECTORY_SEPARATOR . $modelName . "RepositoryInterface.php";
    }

    /////////////////////////////////////////////////////////////

    //Make Directory For custom Artisan
    protected $files;

    public function __construct(Filesystem $files)
    {
        ini_set('memory_limit', -1);
        parent::__construct();
        $this->files = $files;
    }
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
            $this->files->makeDirectory($path . DIRECTORY_SEPARATOR . "Providers", 0777, true, true);
            $this->files->makeDirectory($path . DIRECTORY_SEPARATOR . "Repositories", 0777, true, true);
        }
        return $path;
    }
    /////////////////////////////////////////////////////////////

    //Last Final Execute
    public function handle()
    {
        $path = $this->getSourceFilePath();
        $this->makeDirectory(dirname($path));
        $contents = $this->getSourceFile();

        $providerPath = $this->getProviderFilePath();
        $this->makeDirectory(dirname($providerPath));
        $providerContents = $this->getProviderSourceFile();

        $repositoryInterface = $this->getRepositoryInterfaceFilePath();
        $this->makeDirectory(dirname($repositoryInterface));
        $repositoryInterfaceContents = $this->getRepositoryInterfaceSourceFile();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->files->put($providerPath, $providerContents);
            $this->files->put($repositoryInterface, $repositoryInterfaceContents);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }
    }
    /////////////////////////////////////////////////////////////
}
