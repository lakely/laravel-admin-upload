<?php

namespace Lakely\LaravelAdminUpload;

use Encore\Admin\Extension;

class FileUpload extends Extension
{
    public $name = 'laravel-admin-upload';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';
}
