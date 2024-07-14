<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('make:interface {name}', function ($name) {
    $path = app_path("Interfaces/{$name}.php");

    if (File::exists($path)) {
        $this->error("Interface {$name} already exists!");
        return;
    }

    // Tạo thư mục nếu chưa tồn tại
    $directory = dirname($path);
    if (!File::isDirectory($directory)) {
        File::makeDirectory($directory, 0755, true);
    }

    // Nội dung của interface
    $stub = <<<EOT
<?php

namespace App\Interfaces;

interface {$name}
{
    //
}
EOT;

    File::put($path, $stub);

    $this->info("Interface {$name} created successfully.");
})->purpose('Create a new interface');

Artisan::command('make:service {name}', function ($name) {
    $path = app_path("Services/{$name}.php");

    if (File::exists($path)) {
        $this->error("Service {$name} already exists!");
        return;
    }

    $directory = dirname($path);
    if (!File::isDirectory($directory)) {
        File::makeDirectory($directory, 0755, true);
    }

    $stub = <<<EOT
<?php

namespace App\Services;

class {$name}
{
    //
}
EOT;

    File::put($path, $stub);

    $this->info("Service {$name} created successfully.");
})->purpose('Create a new service');

Artisan::command('make:repository {name}', function ($name) {
    $path = app_path("Repositories/{$name}.php");

    if (File::exists($path)) {
        $this->error("Repository {$name} already exists!");
        return;
    }

    $directory = dirname($path);
    if (!File::isDirectory($directory)) {
        File::makeDirectory($directory, 0755, true);
    }

    $stub = <<<EOT
<?php

namespace App\Repositories;

class {$name}
{
    //
}
EOT;

    File::put($path, $stub);

    $this->info("Repository {$name} created successfully.");
})->purpose('Create a new repository');