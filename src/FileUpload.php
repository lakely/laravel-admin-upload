<?php

namespace Encore\FileUpload;

use Encore\Admin\Extension;

class FileUpload extends Extension
{
    public $name = 'file-upload';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';
}
