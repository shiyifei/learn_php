<?php
class Test_gd {

	public function __construct()
	{
		if(extension_loaded('gd')) {
		    // echo '你可以使用gd<br>';
		    /*foreach(gd_info() as $cate=>$value) {
		        echo "$cate: $value<br>";
		    }*/
		}else {
		    echo '你没有安装gd扩展';
			return;
		}
	}

	public function gdInfo()
	{
		var_dump(gd_info());
	}

	/**
	 * 生成一个png图片，上面字体为楷体
	 * @param  [string] $text 中文文字
	 * @return [void]   
	 */
	public function createPng($text)
	{		
		// Set the content-type
		 header ( 'Content-Type: image/png' );

		 // Create the image
		 $im  =  imagecreatetruecolor ( 120 ,  30 );

		 // Create some colors
		 $blue  =  imagecolorallocate ( $im ,  105 ,  158 ,  195 );
		 $grey  =  imagecolorallocate ( $im ,  128 ,  128 ,  128 );  //表示阴影效果
		 $white  =  imagecolorallocate ( $im ,  255 ,  255 ,  255 );
		 imagefilledrectangle ( $im ,  0 ,  0 ,  120 ,  29 ,  $blue );
		 
		 // Replace path by your own font path
		 //$font  =  '/usr/share/fonts/truetype/ttf-dejavu/DejaVuSans.ttf' ;
		 $font  = '/usr/share/fonts/truetype/cwtex/cwkai.ttf';//楷体

		 $len = mb_strlen($text);
		 $posX = (imagesx($im)-20*$len) / 2 - 3*($len-1) - $len*0.5; //4.4表示字间距

		 // Add some shadow to the text
		 //imagettftext ( $im ,  20 ,  0 ,  $posX+1 ,  24 ,  $grey ,  $font ,  $text );

		 // Add the text
		 imagettftext ( $im ,  20 ,  0 ,  $posX ,  23 ,  $white ,  $font ,  $text );

		 // Using imagepng() results in clearer text compared with imagejpeg()
		 imagepng ( $im );
		 imagedestroy ( $im );
	}

	/**
	 * 降低图片质量，减小文件体积
	 * @return [void]
	 */
	public function tinyImage()
	{
		$sImage = '/home/www/medicine/SPH00000972/主图1.jpg';
		$tImage = '/home/www/data/SPH00000972_1.jpg';

		$im = imagecreatefromjpeg($sImage);
		imagejpeg($im,$tImage,50); //quality setting to 50%
		imagedestroy($im);
		echo "complete<br/>";
	}
	/**
	 * 使用imagick库降低图片质量，缩小文件体积
	 * @return [void]
	 */
	public function minImage()
	{
		$sImage = '/home/www/medicine/SPH00000972/主图1.jpg';
		$tImage = '/home/www/data/SPH00000972_11.jpg';

		include('processimage.php');
		$processor = new Processimage();
		$processor->tinyJpeg($sImage,50,$tImage);
		echo "min jpge file by imagick<br/>";
	}


}

$gdTest = new Test_gd();

$text = $_GET['text'];
$gdTest->createPng($text);


//$gdTest->gdInfo();

//$gdTest->tinyImage();
//$gdTest->minImage();
	
?>