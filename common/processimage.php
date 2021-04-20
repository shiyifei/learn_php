<?php

class Processimage {
    
    function __construct(){
        
    }

    /**
     * 处理图片，不改变分辨率，减少图片文件大小
     * @param  [string] $src  原图路径
     * @param  [int] [图片质量]
     * @param  [string] $dest 目标图路径
     * @return [void]
     * @throws
     */
    public function tinyJpeg($src,$quality=60,$dest)
    {
    	$img = new Imagick();
		$img->readImage($src);
		$img->setImageCompression(Imagick::COMPRESSION_JPEG);
		$img->setImageCompressionQuality($quality);
		$img->stripImage();
		$img->writeImage($dest); 
		$img->clear();
    }

}
