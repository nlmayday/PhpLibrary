<?php
class Image
{
    public static function compress($src, $quality = 90)
    {
        list ($width, $height, $type) = getimagesize($src);
        if ($type !== 2) {
            return true;
        }
        $img = imagecreatefromjpeg($src);
        imagecopyresampled($img, $img, 0, 0, 0, 0, $width, $height, $width, $height);
        if (imagejpeg($img, $src, $quality)) {
            imagedestroy($img);
            return true;
        }
        return false;
    }

    public static function watermarkByImage($fromPath, $watermarkPath, $toPath = '', $xAlign = 'left', $yAlign = 'bottom', $quality = 90)
    {
        $xOffset = $yOffset = $xPos = $yPos = 10; // 偏移10像素
        if (! $img = imagecreatefromjpeg($fromPath))
            return false;
        $waterArray = array(
            1 => 'gif',
            2 => 'jpeg',
            3 => 'png'
        );
        list ($waterWidth, $waterHeight, $waterType) = getimagesize($watermarkPath);
        $createFunc = 'imagecreatefrom' . $waterArray[$waterType];
        if (! $imgWater = $createFunc($watermarkPath))
            exit('The watermark image is not a real ' . $waterArray[$waterType] . ', please CONVERT the image.');
        list ($imgWidth, $imgHeight) = getimagesize($fromPath);
        if ($xAlign == 'middle') {
            $xPos = $imgWidth / 2 - $waterWidth / 2 + $xOffset;
        }
        if ($xAlign == 'left') {
            $xPos = 0 + $xOffset;
        }
        if ($xAlign == 'right') {
            $xPos = $imgWidth - $waterWidth - $xOffset;
        }
        if ($yAlign == 'middle') {
            $yPos = $imgHeight / 2 - $waterHeight / 2 + $yOffset;
        }
        if ($yAlign == 'top') {
            $yPos = 0 + $yOffset;
        }
        if ($yAlign == 'bottom') {
            $yPos = $imgHeight - $waterHeight - $yOffset;
        }
        
        $cut = imagecreatetruecolor($waterWidth, $waterHeight);
        imagecopy($cut, $img, 0, 0, $xPos, $yPos, $waterWidth, $waterHeight);
        imagecopy($cut, $imgWater, 0, 0, 0, 0, $waterWidth, $waterHeight);
        imagecopymerge($img, $cut, $xPos, $yPos, 0, 0, $waterWidth, $waterHeight, $quality);
        if (imagejpeg($img, ($toPath ? $toPath : $fromPath), $quality)) {
            imagedestroy($img);
            return true;
        }
        return false;
    }
}
