<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;

class MakeRootCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:rootCommand {name}';

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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projectName = $this->filterProjectName($this->argument('name'));
        $folderName = $this->filterFolderName($this->argument('name'));
        $name = $this->filterName($this->argument('name'));
        $moduleRepoCommand = "$projectName.$folderName/$name";
        $controllerCommand = "{$projectName}.{$folderName}/{$name}Controller";
        $serviceCommand = "{$projectName}.{$folderName}/{$name}Service";
        $requestCommand = "{$projectName}.{$folderName}/{$name}Request";
        $this->call("make:module", [
            'name' => $moduleRepoCommand,
        ]);
        $this->call("make:repo", [
            'name' => $moduleRepoCommand,
        ]);
        $this->call("make:customController", [
            'name' => $controllerCommand,
        ]);
        $this->call("make:customService", [
            'name' => $serviceCommand,
        ]);
        $this->call("make:customValidation", [
            'name' => $requestCommand,
        ]);
    }
}
