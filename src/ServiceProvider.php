<?php

namespace Rushing\YouTubeDl;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Str;

class ServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        /*
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('youtube-dl')
            ->hasConfigFile();
    }

    public function bootingPackage()
    {

    }
}
