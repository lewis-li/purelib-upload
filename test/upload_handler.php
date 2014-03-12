<!DOCTYPE html>
<html>
<head>
<meta charset=UTF-8>
<title>Insert title here</title>
</head>
<body>
	<?php
	use PureLib\Upload\Upload;


	require_once __DIR__.'/../Upload.php';
	require_once __DIR__.'/../FileStorer.php';
	require_once __DIR__.'/../FileInfo.php';


	Upload::setScene('global', array(
	'dir' => 'assets',
	));

	Upload::setScene('qrcode', array(
	'dir' => 'qrcode',
	));

	Upload::setScene('richtexteditor', array(
	'dir' => 'up',
	));

	Upload::upload('test1', array(
	'scene' => 'global',
	'verify'=>array(
	'notempty' => true,
	'type' => 'image',
	'size' => 200000,
	),

	'success' => function ($fileinfo){
	foreach ($fileinfo as $f) {
	    var_dump(
	    $f->getPath(),
	    $f->getRealPath(),
	    $f->getPathname(),
	    $f->getRelativePath(true),

	    $f->getFileName(),
	    $f->getBaseName(),


	    $f->getExtension(),
	    $f->getSize(),
	    $f->getMimeType(),
	    $f->getDimensions()
	    );
	}
	},
	'error' => function ($err){
	var_dump($err);
	},
	));

	Upload::upload('test2', array(
	'scene' => 'qrcode',
	'verify'=>array(
	'notempty'=>true,
	'type' => 'image',
	'mime_type' => 'image/jpeg',
	),
	'success' => function ($fileinfo) {
	var_dump($fileinfo);
	},
	'error' => function ($err){
	    var_dump($err);
	}

	));
	?>
</body>
</html>
