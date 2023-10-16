<?php

namespace App\Console\Commands;

use App\Console\Commands\MakeCommonCommand\MakeCommonCommand;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class MakeServiceCommand extends Command
{
    //get customService.stub
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:customService {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make and Service class';

    public function getSingularClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }

    public function getService()
    {
        return __DIR__ . '/../../../stubs/customService.stub';
    }

    public function getStubServiceVariables()
    {
        $projectName = $this->commonCommand->filterProjectName($this->getSingularClassName($this->argument('name')));
        $folderName = $this->commonCommand->filterFolderName($this->getSingularClassName($this->argument('name')));
        $serviceName = $this->commonCommand->filterMainName($this->getSingularClassName($this->argument('name')));
        $pathName = $this->commonCommand->filterApiName($this->getSingularClassName($this->argument('name')));
        $service = substr($serviceName, 0, -7);
        $capital = $service;
        $serviceCamel = lcfirst($capital);

        return [
            //namespace Garment\Web\Sizes\Services;
            'NAMESPACE' => "$projectName\\$pathName\\$folderName\\Services",
            'CLASS_NAME' => $serviceName,
            'FOLDER_NAME' => $folderName,
            'PROJECT_NAME' => $projectName,
            'PATH_NAME' => $pathName,
            'CAMEL_CASE' => $serviceCamel,
            'CAPITAL' => $capital,
        ];
    }

    public function getServiceSourceFile()
    {
        return $this->getStubServiceContents($this->getService(), $this->getStubServiceVariables());
    }

    public function getStubServiceContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);
        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace('$' . $search . '$', $replace, $contents);
        }
        return $contents;
    }

    public function getServiceFilePath()
    {
        $folderName = $this->commonCommand->filterFolderName($this->getSingularClassName($this->argument('name')));
        $serviceName = $this->commonCommand->filterMainName($this->getSingularClassName($this->argument('name')));
        $pathName = $this->commonCommand->filterApiName($this->getSingularClassName($this->argument('name')));
        return base_path("modules" . DIRECTORY_SEPARATOR . $pathName . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . "Services") . DIRECTORY_SEPARATOR . $serviceName . ".php";
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
        $path = $this->getServiceFilePath();
        $this->makeDirectory(dirname($path));
        $contents = $this->getServiceSourceFile();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }
    }
}