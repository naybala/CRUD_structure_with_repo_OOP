Notes *
@Base Repo and Common folders should ready@
@All the command should be enter the root of the project@
php artisan list

Step One
========================
php artisan stub:publish

------------------------------------------------------------------------------------------------------------------------

Step Two
==========================================
php artisan make:command MakeModuleCommand
php artisan make:command MakeRepositoryCommand
php artisan make:command MakeControllerCommand
php artisan make:command MakeServiceCommand
php artisan make:command MakeValidationCommand

copy parts of file my git hub "https://github.com/naybala/CRUD_structure_with_repo_OOP"

------------------------------------------------------------------------------------------------------------------------

Step Three
=========================================== total 9 files
crate new stub file name "customModel.sub"
crate new stub file name "customProvider.sub"
crate new stub file name "customRepository.sub"
create new stub file name "customRepositoryInterface.stub"

create new stub file name "customController.stub"
create new stub file name "customService.stub"
create new stub file name "customResource.stub"

create new stub file name "customStoreValidation.stub"
create new stub file name "customUpdateValidation.stub"

copy part of file my git hub "https://github.com/naybala/CRUD_structure_with_repo_OOP"

------------------------------------------------------------------------------------------------------------------------

==============================//// File Creation With Command ////=========================================
Project Name = Garment;
Folder Name = Demos;
Model Name = Demo;

-------------------------------------------------------------------------------------------------------------------------
All Repo and Logic Will Create Automatically template(Root Command One)
-------------------------------------------------------------------------------------------------------------------------
php artisan make:rootCommand Garment.Demos/Demo

(if process will include the path "Api\Dashboard")
-------------------------------------------------------------------------------------------------------------------------

-------------------------------------------------------------------------------------------------------------------------
All Logic Will Create Automatically template (Root Command Two)
-------------------------------------------------------------------------------------------------------------------------
php artisan make:rootLogicCommand Garment.Demos/Demo

(The process should include the path "Api\Dashboard")
-------------------------------------------------------------------------------------------------------------------------




