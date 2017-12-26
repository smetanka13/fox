<?php

class Main {

    public static function setTitle($str) {
        $GLOBALS['$_TITLE'] = $str;
    }

    public static function getFileExt($name) {
        $tmp = explode('.', $name);
        $count = count($tmp);
        if($count <= 1) return FALSE;
        return $tmp[$count - 1];
    }

    public static function saveResized($size, $align, $target, $original, $quad = FALSE) {

    	/* ALIGN

    		0 - vertial
    		1 - horizontal

    	*/

        if(!file_exists($original))
            throw new InvalidArgumentException("Input file does not exist.");

        $ext = self::getFileExt($target);

        switch ($ext) {
            case 'jpg':
    			$image_save_func = 'imagejpeg';
    			break;

    		case 'jpeg':
    			$image_save_func = 'imagejpeg';
    			break;

    		case 'png':
    			$image_save_func = 'imagepng';
    			break;

            default:
                throw new InvalidArgumentException("$ext is not supported.");

    	}

    	switch (exif_imagetype($original)) {
    		case IMAGETYPE_JPEG:
    			$image_create_func = 'imagecreatefromjpeg';
    			break;

    		case IMAGETYPE_PNG:
    			$image_create_func = 'imagecreatefrompng';
    			break;

            default:
                throw new InvalidArgumentException("Original's file extention is not supported.");
    	}

    	$img = $image_create_func($original);
    	list($width, $height) = getimagesize($original);

        $orig_offset_x = 0;
        $orig_offset_y = 0;

        if(!$quad) {
            if($align) {
                $p = $height / $width;
        		$new_width = $size;
        		$new_height = $new_width * $p;
        	} else {
                $p = $width / $height;
        		$new_height = $size;
        		$new_width = $new_height * $p;
        	}
        } else {
            $new_height = $size;
            $new_width = $size;

            if($width > $height) {
                $orig_offset_y = 0;
                $orig_offset_x = ($width - $height) / 2;
                $width = $height;
            } else {
                $orig_offset_y = ($height - $width) / 2;
                $orig_offset_x = 0;
                $height = $width;
            }
        }

    	$tmp = imagecreatetruecolor($new_width, $new_height);
    	imagecopyresampled(
            $tmp, $img,
            0, 0,
            $orig_offset_x,
            $orig_offset_y,
            $new_width,
            $new_height,
            $width,
            $height
        );

    	if(file_exists($target))
    		unlink($target);

    	if(!$image_save_func($tmp, "$target"))
            throw new RuntimeException("Could't save resized image.");

    }

    public static function lookSame ($arr, $subj) {
        for($i = 0; isset($arr[$i]); $i++) {
            if($arr[$i] == $subj) {
                return true;
                break;
            }
        }
        return false;
    }

    public static function generateKey($len = 16) {
        $str = "";
        $arr = [
            'A', 'I', 'Q', 'X',
            'B', 'J', 'R', 'Y',
            'C', 'K', 'S', 'Z',
            'D', 'L', 'T', '0',
            'E', 'M', 'U', '1',
            'F', 'N', 'V', '2',
            'G', 'O', 'W', '3',
            'H', 'P', 'X', '4',
            '5', '6', '7', '8',
            '9'
        ];
        $arr_len = count($arr);
        for($i = 0; $i < $len; $i++) {
            $str .= $arr[rand(0, ($arr_len-1))];
        }
        return $str;
    }

    public static function query($data) {
        $result = mysqli_query($GLOBALS['DB'], $data);
        if(!$result) {
            throw new RuntimeException(mysqli_error($GLOBALS['DB']));
        }
        return $result;
    }

    public static function select($data, $array = FALSE) {
        $result = mysqli_query($GLOBALS['DB'], $data);

        if(!$result) {
            throw new RuntimeException(mysqli_error($GLOBALS['DB']));
        }
        if(!$array)
            $answer = mysqli_fetch_assoc($result);
        else {
            $answer = [];
            while(($row = mysqli_fetch_assoc($result)) != FALSE) {
                $answer[] = $row;
            }
        }

        return $answer;
    }
}
