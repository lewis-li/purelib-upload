<?php
namespace PureLib\Upload;
class MimeType {
    protected static $mimetyps = array(
                    'txt' => 'text/plain',
                    'htm' => 'text/html',
                    'html' => 'text/html',
                    'php' => 'text/html',
                    'css' => 'text/css',
                    'js' 	=> 'application/javascript',
                    'json' => 'application/json',
                    'xml' => 'application/xml',
                    'swf' => 'application/x-shockwave-flash',
                    'flv' => 'video/x-flv',

                    // images
                    'png' => 'image/png',
                    'jpe' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'jpg' => 'image/jpeg',
                    'gif' => 'image/gif',
                    'bmp' => 'image/bmp',
                    'ico' => 'image/vnd.microsoft.icon',
                    'tiff' => 'image/tiff',
                    'tif' => 'image/tiff',
                    'svg' => 'image/svg+xml',
                    'svgz' => 'image/svg+xml',

                    // archives
                    'zip' => 'application/zip',
                    'rar' => 'application/x-rar-compressed',
                    'exe' => 'application/x-msdownload',
                    'msi' => 'application/x-msdownload',
                    'cab' => 'application/vnd.ms-cab-compressed',

                    // audio/video
                    'mp3' => 'audio/mpeg',
                    'qt' => 'video/quicktime',
                    'mov' => 'video/quicktime',

                    // adobe
                    'pdf' => 'application/pdf',
                    'psd' => 'image/vnd.adobe.photoshop',
                    'ai' => 'application/postscript',
                    'eps' => 'application/postscript',
                    'ps' => 'application/postscript',

                    // ms office
                    'doc' => 'application/msword',
                    'rtf' => 'application/rtf',
                    'xls' => 'application/vnd.ms-excel',
                    'ppt' => 'application/vnd.ms-powerpoint',

                    // open office
                    'odt' => 'application/vnd.oasis.opendocument.text',
                    'ods' => 'application/vnd.oasis.opendocument.spreadsheet',



                    'gif' => 'image/gif',
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'jpe' => 'image/jpeg',
                    'bmp' => 'image/bmp',
                    'png' => 'image/png',
                    'tif' => 'image/tiff',
                    'tiff' => 'image/tiff',
                    'pict' => 'image/x-pict',
                    'pic' => 'image/x-pict',
                    'pct' => 'image/x-pict',
                    'tif' => 'image/tiff',
                    'tiff' => 'image/tiff',
                    'psd' => 'image/x-photoshop',

                    'swf' => 'application/x-shockwave-flash',
                    'js' => 'application/x-javascrīpt',
                    'pdf' => 'application/pdf',
                    'ps' => 'application/postscrīpt',
                    'eps' => 'application/postscrīpt',
                    'ai' => 'application/postscrīpt',
                    'wmf' => 'application/x-msmetafile',

                    'css' => 'text/css',
                    'htm' => 'text/html',
                    'html' => 'text/html',
                    'txt' => 'text/plain',
                    'xml' => 'text/xml',
                    'wml' => 'text/wml',
                    'wbmp' => 'image/vnd.wap.wbmp',

                    'mid' => 'audio/midi',
                    'wav' => 'audio/wav',
                    'mp3' => 'audio/mpeg',
                    'mp2' => 'audio/mpeg',

                    'avi' => 'video/x-msvideo',
                    'mpeg' => 'video/mpeg',
                    'mpg' => 'video/mpeg',
                    'qt' => 'video/quicktime',
                    'mov' => 'video/quicktime',

                    'lha' => 'application/x-lha',
                    'lzh' => 'application/x-lha',
                    'z' => 'application/x-compress',
                    'gtar' => 'application/x-gtar',
                    'gz' => 'application/x-gzip',
                    'gzip' => 'application/x-gzip',
                    'tgz' => 'application/x-gzip',
                    'tar' => 'application/x-tar',
                    'bz2' => 'application/bzip2',
                    'zip' => 'application/zip',
                    'arj' => 'application/x-arj',
                    'rar' => 'application/x-rar-compressed',

                    'hqx' => 'application/mac-binhex40',
                    'sit' => 'application/x-stuffit',
                    'bin' => 'application/x-macbinary',

                    'uu' => 'text/x-uuencode',
                    'uue' => 'text/x-uuencode',

                    'latex'=> 'application/x-latex',
                    'ltx' => 'application/x-latex',
                    'tcl' => 'application/x-tcl',

                    'pgp' => 'application/pgp',
                    'asc' => 'application/pgp',
                    'exe' => 'application/x-msdownload',
                    'doc' => 'application/msword',
                    'rtf' => 'application/rtf',
                    'xls' => 'application/vnd.ms-excel',
                    'ppt' => 'application/vnd.ms-powerpoint',
                    'mdb' => 'application/x-msaccess',
                    'wri' => 'application/x-mswrite',
    );

    public static $extensionHexCode = array(
                    "FFD8FFE1"    => "jpg",
                    'FFD8FFE0'    => 'jpg',
                    "89504E47"    => "png",
                    "47494638"    => "gif",
                    "49492A00"    => "tif",
                    "424D"    => "bmp",
                    "41433130"    => "dwg",
                    "38425053"    => "psd",
                    "7B5C727466"    => "rtf",
                    "3C3F786D6C"    => "xml",
                    "68746D6C3E"    => "html",
                    "44656C69766572792D646174"    => "eml",
                    "CFAD12FEC5FD746F"    => "dbx",
                    "2142444E"    => "pst",
                    "D0CF11E0"    => "xls/doc",
                    "5374616E64617264204A"    => "mdb",
                    "FF575043"    => "wpd",
                    "252150532D41646F6265"    => "eps/ps",
                    "255044462D312E"    => "pdf",
                    "E3828596"    => "pwl",
                    "504B0304"    => "zip",
                    "52617221"    => "rar",
                    "57415645"    => "wav",
                    "41564920"    => "avi",
                    "2E7261FD"    => "ram",
                    "2E524D46"    => "rm",
                    "000001BA"    => "mpg",
                    "000001B3"    => "mpg",
                    "6D6F6F76"    => "mov",
                    "3026B2758E66CF11"    => "asf",
                    "4D546864"    => "mid",
    );

    public static function getExtensionByMimeType($mimetype) {
        if (($n=array_search($mimetype, self::$mimetyps)) !== false) {
            return $n;
        } else {
            return null;
        }
    }

    /**
     * 根据数据流头2个字节判断扩展名
     * @param string $stream
     */
    public static function getExtensionByStreamHeader($header) {
        $type = null;
        foreach (self::$extensionHexCode as $code=>$v)
        {
            $blen=strlen(pack("H*",$code)); //得到文件头标记字节数
            $tbin=substr($header,0,intval($blen)); ///需要比较文件头长度
            
            $t = strtolower(array_shift(unpack("H*",$tbin)));
            if($t===strtolower($code))
            {
                $type = $v;
                break;
            }
        }
        return $type;
    }

    /**
     * 根据文件头2个字节得到扩展名
     * @param string $file_name
     */
    public static function getExtensionByFileHeader($filename) {
        $h = fopen($filename, 'rb');
        $bin = fread($h, 15);
        $type = null;
        foreach (self::$extensionHexCode as $code=>$v)
        {
            $blen=strlen(pack("H*",$code)); //得到文件头标记字节数

            $tbin=substr($bin,0,intval($blen)); ///需要比较文件头长度
            $t = strtolower(array_shift(unpack("H*",$tbin)));
            if($t===strtolower($code))
            {
                $type = $v;
                break;
            }
        }
        return $type;
    }
}
