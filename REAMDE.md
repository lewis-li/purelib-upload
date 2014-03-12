##设置使用场景:
```php
	/**
     * 设置场景
     * @param string $name 场景名称
     * @param array $config 场景配置,可设置项与upload方法$config参数相同
     */
	Upload::setScene('global', array(
		'base_path' => '/www',
		'dir' => 'assets/up',
	));
```


##上传API:
```php
    /** 上传文件
     * 原型 Upload::upload($name, array $config);
     *
     * @param $name 表单上传项的名称
     * @param $config 配置,有如下配置项:
     *   - scene 指定场景 ,如果配置了该项，则指定的场景的配置信息将会与$config合并（如有相同的配置，$config的配置将覆盖场景配置）
     *   - base_path 基本路径
     *   - dir 文件保存路径（相对于base_path）
     *   - verify 验证，有如下可选规则:
     *       -# notempty 不可为空
     *       -# size 大小限制(以字节为单位)
     *       -# type 文件类型(仅实现图像类型的判断),可选值为:
     *       -# mime_type
     *       -# 回调
     *   - success 上传成功后执行的回调函数,传入参数为文件信息对象(SplFileInfo扩展, @see \SplFileInfo)
     *       除了SplFileInfo的方法:
     *       -# getPath() 获得文件的路径(不含文件名)
     *       -# getRealPath() 获得文件的路径(不含文件名)
     *       -# getFileName() 获得文件名
     *       -# getBaseName() 获得文件名(可以设置是否排除后缀)
     *       -# getExtension() 获得文件的扩展名
     *       -# getSize() 获得文件大小
     *       还有如下扩展方法:
     *       -# getMimeType() 获得文件MIME类型
     *       -# getDimensions() 如果文件是图像类型,获得图片的宽度和高度信息
     *   - error 上传失败后执行的回调函数,传入参数为错误信息
     * @return false | fileinfo文件信息对象
     */
```

 - 单文件上传使用方法:

```php
Upload::upload('field_name', array(
    //设置基础路径和保存的目录或通过指定场景来获得base_path,dir等配置信息
    'base_path' => '/www',
	'dir'=>'assets',
	
	//设置验证规则
    'verify'=>array(
        'notempty' => true,
        'type' => 'image',
        'size' => 200000,
    ),
    
    //设置上传成功后的回调
    'success' => function ($fileinfo){
	    var_dump(
            $fileinfo->getExtension(),
            $fileinfo->getSize(),
            $fileinfo->getPath(),
            $fileinfo->getMimeType(),
            $fileinfo->getDimensions()
	    );
    },
    //设置上传失败的回调
    'error' => function ($err){
        var_dump($err);
    },
));
```

 - 多文件上传使用方法:

```php
Upload::upload('field_name', array(
    //设置基础路径和保存的目录或通过指定场景来获得base_path,dir等配置信息
    'base_path' => '/www',
		'dir'=>'assets',
    //设置验证规则
    'verify'=>array(
        'notempty' => true,
        'type' => 'image',
        'size' => 200000,
    ),
    //设置上传成功后的回调。注意，此时的$fileinfo为数组
    'success' => function ($fileinfo){
		foreach ($fileinfo as $f) {
    		var_dump(
                $f->getExtension(),
                $f->getSize(),
                $f->getPath(),
                $f->getMimeType(),
                $f->getDimensions()
    	    );
		}
    },
    //设置上传失败的回调
    'error' => function ($err){
        var_dump($err);
    },
));
```