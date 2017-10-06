<?php
/**
 * Created by PhpStorm.
 * User: 潘兴杨
 * Date: 2017/10/6
 * Time: 17:55
 */
 
    class Image{
        private $image;
        private $info;
        private $bili;
		//构造函数
        function __construct($src)
        {
           $info = getimagesize($src);
           $this->info = array(
               'width' =>$info[0],
               'height'=>$info[1],
               'type'  =>image_type_to_extension($info[2],false),
               'mime'  =>$info['mime']
           );
           $this->bili = $this->info['width']/$this->info['height'];
           $str = "imagecreatefrom{$this->info['type']}";
           $this->image = $str($src);

        }

//图片水印
        function shuiyin($src){

            $info = getimagesize($src);
            $type = image_type_to_extension($info[2],false);
            $str = "imagecreatefrom{$type}";
            $shuiyinimage = $str($src);
            imagecopymerge($this->image,$shuiyinimage,0,0,0,0,$info[0],$info[1],30);
            imagedestroy($shuiyinimage);
        }
//缩略图		
        function imageThumd($width){
            $thumdimage = imagecreatetruecolor($width,$width/$this->bili);
            imagecopyresampled($thumdimage,$this->image,0,0,0,0,$width,$width/$this->bili,$this->info['width'],$this->info['height']);
            imagedestroy($this->image);
            $this->image = $thumdimage;
        }
//文字水印
        function wenzi($src,$content,$red,$green,$blue){
            $col = imagecolorallocatealpha($this->image,$red,$green,$blue,50);
            imagettftext($this->image,50,0,30,80,$col,$src,$content);
        }
//在web中显示		
        function show(){
            header("Content-type:".$this->info['mime']);
            imagejpeg($this->image);

        }
//保存图片		
        function save(){
            imagejpeg($this->image,'./image/new'.'.'.$this->info['type']);

        }
//销毁图片
        function __destruct()
        {
            // TODO: Implement __destruct() method.
            imagedestroy($this->image);
        }

    }
    $image = new Image('./image/myimage.jpg');
    $image->shuiyin('./image/001.jpg');


    $image->wenzi('./image/msyh.ttf','hello',255,255,255);

    $image->imageThumd(500);

    $image->show();
