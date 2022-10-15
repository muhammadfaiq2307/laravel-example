<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Requirements
1. PHP 8.1.3

## Deployment Locally (using artisan)
1. `$composer install`
2. `$cp .env.example .env`
3. Modify .env
4. `$php artisan key:generate`
5. `$php artisan migrate`
6. `npm install`
7. `npm run dev`
8. `php artisan storage:link`
9. `php artisan serve`

## Deployment on Server (with apache2)
1. `$git clone`
2. `$composer install`
3. `$cp .env.example .env`
4. Modify .env
5. `$php artisan key:generate`
6. `$php artisan migrate`
7. `npm install`
8. `npm run dev`
9. setup sites-available & sites-enabled on apache2
10. `$sudo service apache2 restart`

## Authentication

- Use [Laravel Breeze](https://laravel.com/docs/9.x/starter-kits#laravel-breeze)
- For LDAP Authentication, the flow will be check the password against LDAP's password first, if correct, update the password on the local database then do a normal authentication
- For Token-based API Authentication, use [Laravel Sanctum](https://laravel.com/docs/9.x/sanctum). Request parameters needed are email, password, app_name. Returned token can be used in header as Authorization Bearer

## Odoo Connection
- Use JsonRPC (see Boards on why JsonRPC is picked over XML-RPC), code refer to [Refact-be Odoo](https://github.com/refact-be/odoo)
- Library is on app/Libraries/OdooConnection.php
- Controller example is on app/Http/Controllers/OdooController.php

## Database Starter Data, Changes, and Structure
- For any changes on database, instead of directly modify the database, use [Migrations](https://laravel.com/docs/9.x/migrations)
- For any starter data on database (e.g.: starting main menu and/or roles) that need to be inserted on first deploy, instead of database backup-restore, use [Seeders](https://laravel.com/docs/9.x/seeding). In this app there are already pre-made seeders, for AuthItem, and MainMenu, both under database/seeders folder
1. php artisan db:seed
- Tables in database can be grouped into several logical scheme. For example Auth, System, Admin Scheme; Or by entity like Paragon, Parama, Pharmacore Scheme. Migration example can be found in database/migrations/2022_03_21_062146_create_system_schema.php

## User Role Management
- Use [Middleware](https://laravel.com/docs/9.x/middleware) to check if User has the right role to access specific menu, can be used by appending `->middleware(['access.role.menu:X']);` in the routes/web.php with X is the menu's primary key.

## Design Pattern
- Use [Service](https://martinfowler.com/eaaCatalog/serviceLayer.html) and [Repository](https://martinfowler.com/eaaCatalog/repository.html) Pattern. [Example 1](https://dev.to/jsf00/implement-crud-with-laravel-service-repository-pattern-1dkl), [Example 2](https://www.twilio.com/blog/repository-pattern-in-laravel-application)
- TLDR: User hit the endpoint, request arrives at Controller, Controller send the request without any modification to Service, Service modify the request data (if needed) and do any business logic, Service will ask Repository when it has to do something with the database, Repository (which has been injected by Model) will do any database/query/eloquent related things and send the needed data back to Service and will be sent to Controller to be presented to User.
- The purpose is to decouple each layer for it to be easier on Unit Test (_need more references/articles_)
- On each layer also can be implemented a superclass that will be extended from any subclasses so there will be no duplicated function. For example app/Repositories/System/BaseSystemRepository.php is the superclass for all repository files under app/Repositories/System
- **IF** it feels too overkill, Repository layer can be skipped and Service layer can do a direct call to Model. If this pattern is used, all queries will be moved to each Model to be called from Service. _p.s. be careful to not create a [God Object/God Class/God Model](https://en.wikipedia.org/wiki/God_object)_

## Exception Handling
- For any checks that will instantly stop the process if it doesnt meet the requirement, instead of doing this

```php
if (1 != 2){
    return response()->json('Process failed, exiting!', 400);
}
```
Create a custom [Exception](https://laravel.com/docs/9.x/errors) and throw it whenever its needed. For example, the created exception can be checked in app/Exceptions/CustomInvalidException.php. And to use it, do
```php
use App\Exceptions\CustomInvalidException;
...
if(1 != 2){
    throw new CustomInvalidException('Process failed, exiting!');
}
```
With this, if any changes needed for the output, only change lines inside app/Exceptions/CustomInvalidException.php without having to modify every error return json. Reference: [reference 1](https://medium.com/@sgandhi132/how-to-validate-an-api-request-in-laravel-35b46470ba88)

## Validation
- Instead of doing manual validation, for example
```php
public function store(Request $request){
    if(!isset($request->name)){
        return response()->json('name is required', 400);
    }
}
```
or in-function validation
```php
use Validator;
use Illuminate\Validation\ValidationException;
...
public function store(Request $request){
    $validator = Validator::make($data,[
			'name'   => 'required',
        ]);
        if ($validator->fails()){
            return response()->json('name is required', 400);
        }
}

```
[Form Request Validation](https://laravel.com/docs/9.x/validation#form-request-validation) can be used, for example in app/Http/Requests/StoreUserRequest.php, and use it by
```php
use App\Http\Requests\StoreUserRequest;
...
function store(StoreUserRequest $request){
        $validatedRequest = $request->validated();
        $result = //do store logic with $validatedRequest
        return response()->json($result,200);
    }
```
Have a read on [Validation Rules](https://laravel.com/docs/9.x/validation#available-validation-rules) too, just in case the rules are already provided by Laravel.

## File Structure
Similar with the default installation, with addition of:
- app/Repositories
- app/Services
- app/Http/Requests

## Activity Log
Logger can be implemented in order to track what has been changed by which user and when was the time of the change. Package [Spatie Laravel-activitylog](https://spatie.be/docs/laravel-activitylog/v4/introduction) will be used. Usage example of logging when there is data change can be found in app/Models/SystemAuthItem.php
```php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
...
use LogsActivity;
...
public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logFillable();
    }
```
Any changes (create, update, delete) on the said model will be logged, and the log will be saved in public.activity_log table.

Example log saved for creating a model
```
id          : 1
log_name    : default
description : created
subject_type: App\Models\SystemAuthItem
subject_id  : 8
causer_type : App\Models\User
causer_id   : 1
properties  : {"attributes":{"name":"deliveryman","type":1,"description":"deliveryman","rule_name":null,"data":null,"created_at":"2022-04-18T02:17:24.000000Z" "updated_at":"2022-04-18T02:17:24.created_at: 000000Z","created_by":"Admin","updated_by":null}}
event       : created
```

## Task Scheduling (cron)
For scheduled task, usually we create the code then register it in cron configuration [crontab](https://man7.org/linux/man-pages/man5/crontab.5.html) on the server. 

This will be hard to maintain over time because the scheduler is not in source control and we need to SSH into the server to view or make changes cron entries. Let alone having multiple server for multiple environment. Also the infamous mistype of `e` to `r` [crontab -r deletes cron entries without asking for confirmation](https://bugs.launchpad.net/ubuntu/+source/cron/+bug/1451286).

[Laravel Task Scheduling](https://laravel.com/docs/9.x/scheduling) will be used to replace on-server cron configuration. To use this feature, we can write the scheduled code as [Commands](https://laravel.com/docs/9.x/artisan), then the commands can be called from app/Console/Kernel.php by schedule (hourly, minutes, weekly, or custom cron * * * * *). By using this, the only cron configuration we need to write in the server is only 
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
with the rest of the scheduled tasks are on source control.

Example on this repository:
- app/Console/Kernel.php - for scheduled task
- app/Console/Commands/ExportCsvUser.php - for the task itself

## Testing
Laravel is built with [Testing](https://laravel.com/docs/9.x/testing), and support for PHPUnit is included and already set up to be used.
1. Prerequisites

- phpunit.xml. 
- .env.testing with separate db for testing purposes. 
- Create [Factories](https://laravel.com/docs/9.x/database-testing#generating-factories) for each tested Model. 

2. Creating Tests

- `php artisan make:test AuthItemTest --unit` for unit testing. 

3. Running Tests
```bash
php artisan test --env=testing tests/Unit/
php artisan test --env=testing tests/Feature/
```

Code Example can be found in:
- phpunit.xml (for phpunit xml config)
- .env.testing (for testing environment)
- database/factories/SystemAuthItemFactory.php (for auth item factory)
- tests/Unit/AuthItemTest.php (for auth item basic crud tests scenario)

Example Test Result on this repository:
```bash
   PASS  Tests\Unit\AuthItemTest
  ✓ get all endpoint can be accessed
  ✓ get one endpoint can be accessed
  ✓ auth item can be created
  ✓ auth item can be updated
  ✓ auth item can be deleted

   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\AuthSanctumTest
  ✓ authenticate endpoint return sanctum token

   PASS  Tests\Feature\Auth\AuthenticationTest
  ✓ login screen can be rendered
  ✓ users can authenticate using the login screen
  ✓ users can not authenticate with invalid password

   PASS  Tests\Feature\Auth\EmailVerificationTest
  ✓ email verification screen can be rendered
  ✓ email can be verified
  ✓ email is not verified with invalid hash

   PASS  Tests\Feature\Auth\PasswordConfirmationTest
  ✓ confirm password screen can be rendered
  ✓ password can be confirmed
  ✓ password is not confirmed with invalid password

   PASS  Tests\Feature\Auth\PasswordResetTest
  ✓ reset password link screen can be rendered
  ✓ reset password link can be requested
  ✓ reset password screen can be rendered
  ✓ password can be reset with valid token

   PASS  Tests\Feature\Auth\RegistrationTest
  ✓ registration screen can be rendered
  ✓ new users can register

   PASS  Tests\Feature\AuthMiddlewareTest
  ✓ access endpoint missing authorization header
  ✓ access endpoint invalid authorization header
  ✓ access endpoint valid authorization header

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response

  Tests:  26 passed
  Time:   1.71s
```

## Dockerize
By containerizing our application, when it comes to deployment especially in a new server we only need to install Docker and Docker Compose, it eliminates the need to install and setup other required package, e.g.: php, nginx, postgresql. Docker Container also help with package version, so our server can host 2 different app with different version (e.g.: php7 and php5) at the same time.

Will be using [Docker](https://docs.docker.com/engine/) and [Docker Compose](https://docs.docker.com/compose/)

Reference: [Article 1](https://www.digitalocean.com/community/tutorials/how-to-containerize-a-laravel-application-for-development-with-docker-compose-on-ubuntu-18-04), [Article 2](https://www.digitalocean.com/community/tutorials/how-to-set-up-laravel-nginx-and-mysql-with-docker-compose)

Prerequisites: have docker and docker compose [installed](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-compose-on-ubuntu-20-04)

Steps:
1. modify `DB_HOST` on .env to `db`
2. `docker compose build`
3. `docker compose up -d`
4. if database hasn't been created, do `docker compose exec db bash` then `psql -U postgres -h localhost -d postgres` and `create database laravel_santafe`
5. Do all steps in **Deployment** except step 9 by prepending `docker compose exec app` to each commands, or just start bash with `docker compose exec app bash` and do normal setup
6. App deployed

Production deployment references: [Article 1](https://www.docker.com/blog/how-to-deploy-on-remote-docker-hosts-with-docker-compose/), [Article 2](https://itnext.io/a-beginners-guide-to-deploying-a-docker-application-to-production-using-docker-compose-de1feccd2893)

## Unexpected Error Handling
In __Exception Handling__ Section, we handle any _expected error_, such as updating data with non-existent primary key, by detecting the error, or catch from exception threw by Laravel, and send back the modified error message to user.
For _unexpected error_, Laravel also already prepare a __500 | SERVER ERROR__ page. Beside this, the error need to be logged in order to reproduce it in testing server.
To do this, we will be using [Microsoft's Webhook](https://docs.microsoft.com/en-us/microsoftteams/platform/webhooks-and-connectors/what-are-webhooks-and-connectors) to Teams Channel.

This message will be sent in real time to Teams Channel to ease the debug step after.
<p align="center"><img src="https://testing-nizar.s3.ap-southeast-1.amazonaws.com/santafe/webhook-message.png"></p>

Steps:
1. Create a Team in Microsoft Teams
2. Create a non-private Channel in the just created Team
3. Use Incoming Webhook from Channel's Connector and copy the Webhook Link
4. Save the link in application, and use it in Exception. For this app, it is placed in app/Exceptions/Handler.php (default general error handler)
5. If any exception happens, Webhook message will be triggerred automatically.


## Laravel Template Engine
Laravel uses Blade as Template Engine ([Views](https://laravel.com/docs/9.x/views), and [Blade Templates](https://laravel.com/docs/9.x/blade))