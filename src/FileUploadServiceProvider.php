<?php

namespace Lakely\LaravelAdminUpload;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Illuminate\Support\ServiceProvider;

class FileUploadServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(FileUpload $extension)
    {
        if (! FileUpload::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'laravel-admin-upload');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/lakely/laravel-admin-upload')],
                'laravel-admin-upload'
            );
        }

        Admin::booting(function () {
            Form::extend('singleImage', SingleImage::class);
            Form::extend('multiImage', MultiImage::class);
        });
    }
}
