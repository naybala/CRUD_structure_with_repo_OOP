<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;

class MakeRootLogicCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:rootLogicCommand {name}';

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

    private function filterName($names)
    {
        $position = strpos($names, '/');
        $name = substr($names, $position + 1);
        return $name;
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $answer = $this->ask('Enter your service container root folder name directory');
        if ($answer != " ") {
            $this->logicRun($answer);
        } else {
            $this->info("");
            $this->info("========================= Sorry!You can't use this features =========================");
            $this->info("");
        }
    }

    private function logicRun($pathName)
    {
        $projectName = $this->filterProjectName($this->argument('name'));
        $folderName = $this->filterFolderName($this->argument('name'));
        $name = $this->filterName($this->argument('name'));
        $controllerCommand = "{$projectName}.{$folderName}/{$name}Controller?path={$pathName}";
        $resourceCommand = "{$projectName}.{$folderName}/{$name}Resource?path={$pathName}";
        $serviceCommand = "{$projectName}.{$folderName}/{$name}Service?path={$pathName}";
        $requestCommand = "{$projectName}.{$folderName}/{$name}Request?path={$pathName}";
        $this->call("make:customController", [
            'name' => $controllerCommand,
        ]);
        $this->call("make:customResource", [
            'name' => $resourceCommand,
        ]);
        $this->call("make:customService", [
            'name' => $serviceCommand,
        ]);
        $this->call("make:customValidation", [
            'name' => $requestCommand,
        ]);
        $this->info("");
        $this->info("========================= Congratulation you unlock all Logic features =========================");
        $this->info("");
    }

}
