<?php

namespace Encore\FileUpload;

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
            $this->loadViewsFrom($views, 'file-upload');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/file-upload')],
                'file-upload'
            );
        }

        Admin::booting(function () {
            Form::extend('singleImage', SingleImage::class);
            Form::extend('multiImage', MultiImage::class);
        });
    }
}
