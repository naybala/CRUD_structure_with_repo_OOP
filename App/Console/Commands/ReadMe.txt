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

copy part of file my git hub "https://github.com/naybala"
------------------------------------------------------------------------------------------------------------------------
Step Three
===========================================
crate new stub file name "customModel.sub"
crate new stub file name "customProvider.sub"
crate new stub file name "customRepository.sub"
create new stub file name "customRepositoryInterface.stub"

create new stub file name "customController.stub"
create new stub file name "customService.stub"

create new stub file name "customStoreValidation.stub"
create new stub file name "customUpdateValidation.stub"

copy part of file my git hub "https://github.com/naybala"

//// File Creation With Command ////
Project Name = Garment;
Folder Name = Demos;
Model Name = Demo;
Controller Name = DemoController;
Service Name = DemoService;

-------------------------------------------------------------------------------------------------------------------------
(first Cmd) php artisan make:module Garment.Demos/Demo
After hit this cmd the result will be modules/Foundations/Domain/...   the necessary folders and files will created.
-------------------------------------------------------------------------------------------------------------------------


-------------------------------------------------------------------------------------------------------------------------
(second Cmd) php artisan make:repo Garment.Demos/Demo
After hit this cmd the result will be modules/Foundations/Domain/../Repositories/Eloquent/..   the new file will created.
-------------------------------------------------------------------------------------------------------------------------

-------------------------------------------------------------------------------------------------------------------------
(Third Cmd) php artisan make:customController Garment.Demos/DemoController
After hit this cmd the result will be modules/Web/../Controllers/..   the new Controller will created.
-------------------------------------------------------------------------------------------------------------------------

-------------------------------------------------------------------------------------------------------------------------
(Fourth Cmd) php artisan make:customService Garment.Demos/DemoService
After hit this cmd the result will be modules/Web/../Services/..   the new Service will created.
-------------------------------------------------------------------------------------------------------------------------

-------------------------------------------------------------------------------------------------------------------------
(Fifth Cmd) php artisan make:customValidation Garment.Demos/DemoRequest
After hit this cmd the result will be modules/Web/../Validation/..   the new form validation store and update will created.
-------------------------------------------------------------------------------------------------------------------------




