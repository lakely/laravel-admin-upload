<?php

namespace Lakely\LaravelAdminUpload;

use Encore\Admin\Form\Field;

class SingleImage extends Field
{
    protected $view = 'laravel-admin-upload::single-image';

    protected static $css = [
        'vendor/lakely/laravel-admin-upload/style.css',
    ];

    protected static $js = [
        'vendor/lakely/laravel-admin-upload/plupload-2.1.2/js/plupload.full.min.js',
        'vendor/lakely/laravel-admin-upload/upload.js',
    ];

    public function prepare($value)
    {
        return $value;
    }

    public function render()
    {
        $id = $this->formatId($this->column);
        $name = $this->formatName($this->column);
        $token = csrf_token();
        $this->script = <<<EOT
init_upload('{$id}_upload', '{$name}', false,'{$token}');
EOT;
        return parent::render();
    }
}
