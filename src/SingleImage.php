<?php

namespace Encore\FileUpload;

use Encore\Admin\Form\Field;

class SingleImage extends Field
{
    protected $view = 'file-upload::single-image';

    protected static $css = [
        'vendor/laravel-admin-ext/file-upload/style.css',
    ];

    protected static $js = [
        'vendor/laravel-admin-ext/file-upload/plupload-2.1.2/js/plupload.full.min.js',
        'vendor/laravel-admin-ext/file-upload/upload.js',
    ];

    public function prepare($value)
    {
        return $value;
    }

    public function render()
    {
        $name = $this->formatName($this->column);
        $token = csrf_token();
        $this->script = <<<EOT
init_upload('{$name}_upload',false,'{$token}');
EOT;
        return parent::render();
    }
}
