#laravel-admin-fileUpdate extension
======

####Intro
Web direct upload, and can combine with upload manager system(if you application has).


####Install
```
composer require laravel-admin-ext/file-upload
php artisan vendor:publish --provider=Encore\FileUpload\FileUploadServiceProvider
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

####`[choice]`Combine with you uplodaManager system

This is not necessary, if your are idle bored.
In `FileUploadController@fileUpload'` function, it has already save the upload file/image info, like this:

```
$bool = $this->getStorage()->put($fileFullPath, $newFile->getEncoded());
if (!$bool) {
    throw new GeneralException('文件上传失败');
}

$upload = Upload::query()->create([
    'disk' => $this->getDisk(),
    'original_name' => $originalName ?? '',
    'path' => $fileFullPath,
    'ext' => $fileExt,
    'url' => $this->getStorage()->url($fileFullPath),
    'width' => $newFile->width() ?? null,
    'height' => $newFile->height() ?? null,
    'size' => $fileSize,
    'status' => 0,
]);
```

######1.Form submit data example

>If there has `uploads` talbe in your laravel system. The following array `keys` is the `id` of uploads table

>>`singelImage` submit field, like this:

```
logo_id: "31",
logo: "https://liuy-test.oss-cn-beijing.aliyuncs.com/images/201906/27/a1719d4f3791f3f88eb4b2446160a4f9.jpg",
```

>>`multiImage` like this:

```
gallery: {
  1: "http://your-web-site.com/03b3e50e68ea73160099d199fe9e02ed.jpg",
  2: "http://your-web-site.com/03b3e50e68ea73160099d199fe9e02ed.jpg",
  3: "http://your-web-site.com/03b3e50e68ea73160099d199fe9e02ed.jpg"
},
```

######2.process in your laravel-admin from callback

```
$form->saved(function (Form $form) {
    $model = $form->model();

    //single type relation with `uploads` table
    if ($form->logo && $form->logo_id) {
        $oldLogo = $model->uploads()
            ->where('sub_type', 'logo')
            ->where('status', Upload::STATUS_NORMAL)
            ->first();

        $newUploadId = $form->logo_id;
        if ($oldLogo && $oldLogo->id != $newUploadId) {
            $oldLogo->status = Upload::STATUS_UNUSED;
            $oldLogo->save();
        }

        if (!$oldLogo || $oldLogo->id != $newUploadId) {
            Upload::query()->where('id', $newUploadId)->update([
                'uploadable_type' => 'shops',
                'uploadable_id'   => $form->model()->id,
                'sub_type'        => 'logo'
            ]);
        }
    }

    //multi type relation with `uploads` table
    if ($form->gallery) {
        $oldGalleryIds = $model->uploads()
            ->where('sub_type', 'gallery')
            ->where('status', Upload::STATUS_NORMAL)
            ->pluck('id');

        $unuseUploadIds = $oldGalleryIds->diff(array_keys($form->gallery));

        Upload::query()->whereIn('id', $unuseUploadIds)->update([
            'status' => Upload::STATUS_UNUSED,
        ]);

        Upload::query()->whereIn('id', array_keys($form->gallery))->update([
            'uploadable_type' => 'shops',
            'uploadable_id' => $form->model()->id,
            'sub_type' => 'gallery'
        ]);
    }
});

```
