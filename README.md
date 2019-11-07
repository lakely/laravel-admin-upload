#laravel-admin-fileUpdate extension
======

####Intro
Web direct upload, and can combine with upload manager system(if you application has).


####Install
```
composer require lakely/laravel-admin-upload
php artisan vendor:publish --tag=laravel-admin-upload
```

```
//添加上传路由与方法，自己去实现
$router->post('/file-upload', 'FileUploadController@fileUpload');
```

####Usage

```
$form->singleImage('logo', 'LOGO');

$form->multiImage('gallery', 'Gallery');
```

####Database tables and model
>`singleImage` saved as `string` and `multiImage` saved as `JSON` type, so
>firstly,you need to define the `casts` property
>secondly,if you DB field type is `varchar`, you need to define a `mutator`

######1.casts property

```
protected $casts = [
    'gallery' => 'array',
];
```

######2.Defining a mutator: setXXXXAttribute()

>if you database field is `Varchar`, you need the following. if `JSON`, do nothing

```
    public function setGalleryAttribute($value)
    {
        $this->attributes['gallery'] = json_encode($value);
    }
```
