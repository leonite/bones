<?php
ini_set('session.save_path', '/tmp/');
 session_start();
  $letters = '123456789';

  $caplen = 6;
  $width = 150; $height = 30;
  $font = 'font/comic.ttf';
  $fontsize = 30;

  header('Content-type: image/png');

  $im = imagecreatetruecolor($width, $height);
  imagesavealpha($im, true);
  $bg = imagecolorallocatealpha($im, 0, 0, 0, 127);
  imagefill($im, 0, 0, $bg);
  
  putenv( 'GDFONTPATH=' . realpath('.') );

  $captcha = '';
  for ($i = 0; $i < $caplen; $i++)
  {
	$fontsize = rand(15,30);
    $captcha .= $letters[ rand(0, strlen($letters)-1) ];
    $x = ($width - 30) / $caplen * $i + 10;
    $x = rand($x, $x+4);
    $y = $height - ( ($height - $fontsize) / 2 );

   //$curcolor = imagecolorallocate( $im, rand(200, 255), rand(200, 255), rand(200, 255) );


   // $curcolor = $curcolor. imagecolorallocate( $im, rand(42*($i), 42*($i+1)),rand(42*(caplen-$i-1), 42*(caplen-$i)), rand(42*($i), 42*($i+1)));
	$curcolor = imagecolorallocate( $im, rand(0, 50),rand(0, 50), rand(0, 50));
	
    $angle = rand(-10, 10);
    imagettftext($im, $fontsize, $angle, $x, $y, $curcolor, $font, $captcha[$i]);
	if($i>2)
	{
	$color = imagecolorallocate($im, rand(0, 50), rand(0, 50), rand(0, 150));
	imageSetThickness($im, 2);
	imageLine($im, 0, rand(0,$height), $width, rand(0,$height), $color);
	}
  }
	

  $_SESSION['captcha'] = $captcha;

  imagepng($im);
  imagedestroy($im);

?>