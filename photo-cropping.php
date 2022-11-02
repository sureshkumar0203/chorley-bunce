<?php

class photo_cropping_manager {

    var $CROP_LEFT_TOP = 0;
    var $CROP_LEFT_BOTTOM = 1;
    var $CROP_RIGHT_TOP = 2;
    var $CROP_RIGHT_BOTTOM = 3;
    var $CROP_CENTER_TOP = 4;
    var $CROP_CENTER_BOTTOM = 5;
    var $CROP_MIDDLE_LEFT = 6;
    var $CROP_MIDDLE_RIGHT = 7;
    var $CROP_ALL = 8;
    var $quality = 100;

    function photo_cropping_manager($src, $target='', $width=0, $height=0, $left=0, $top=0) {
	$this->src = $src;
	if ($target) {
	    $this->target = $target;
	    copy($src, $target);
	    chmod($this->target, 0777);
	} else {
	    $this->target = $src;
	}
	$this->width = $width;
	$this->height = $height;
	$this->left = $left;
	$this->top = $top;
    }

    function set_quality($quality) {
	if ($quality) {
	    $this->quality = $quality;
	} else {
	    echo "<br>Thumbnail Manager Error: Quality is set to null.<hr>";
	}
    }

    function set_parameters($src, $target='', $width=0, $height=0, $left=0, $top=0) {
	if ($target) {
	    $this->target = $target;
	    copy($src, $target);
	    chmod($this->target, 0644);
	} else {
	    $this->target = $src;
	}
	$this->width = $width;
	$this->height = $height;
    }

    function set_target($target) {
	if ($target) {
	    $this->target = $target;
	    copy($this->src, $target);
	    chmod($this->target, 0644);
	} else {
	    $this->target = $this->src;
	}
	if (!$this->target) {
	    echo "<br>Thumbnail Manager Error: Target is set to null.<br>
					Both source and target can't be null.<hr>";
	}
    }

    function set_source($src) {
	$this->src = $src;
	if ($this->target) {
	    copy($this->src, $this->target);
	    chmod($this->target, 0644);
	}
	if (!$this->src) {
	    echo "<br>Thumbnail Manager Error: Source is set to null.<hr>";
	}
    }

    function set_files($src, $target) {
	$this->src = $src;
	if ($target) {
	    $this->target = $target;
	    copy($src, $target);
	    chmod($this->target, 0644);
	} else {
	    $this->target = $src;
	}
	if (!$this->target) {
	    echo "<br>Thumbnail Manager Error: Target is set to null.<br>
					Both source and target can't be null.<hr>";
	}
    }

    function set_dimension($width=0, $height=0) {
	$this->width = $width;
	$this->height = $height;
	if (!$this->width && !$this->height) {
	    echo "<br>Thumbnail Manager Error: Dimension is set to null.<br>
					Both width and height can't be null.<hr>";
	}
    }

    function set_area($left=0, $top=0, $right=0, $bottom=0) {
	$this->left = $left;
	$this->top = $top;
    }

    function get_exact_thumb($width=0, $height=0, $left=0, $top=0) {
	print "This function is not in used. Please use get_container_thumb()";
	exit;
	$this->height = $height ? $height : $this->height;
	$this->width = $height ? $width : $this->width;
	$this->left = $left ? $left : $this->left;
	$this->top = $top ? $top : $this->top;
	//return 	$this->height.'--'.$this->width;
	$cmd = "/usr/bin/mogrify -quality {$this->quality} -resize '{$this->width}x{$this->height}!+{$this->left}+{$this->top}'  {$this->target}";
	exec($cmd);
	chmod($this->target, 0644);
    }

    /////////////SET FUNCTION FOR LIBARY DEPENDENT ////////////////saswati
    function get_container_thumb($width=0, $height=0, $left=0, $top=0, $path="") {
	    $this->image_creation_gd($width, $height, $left, $top);	
    }

    ///////////////// Image /magic /////////////////////////////////
    function image_creation_im($width=0, $height=0, $left=0, $top=0, $path="/usr/bin/mogrify") {
	$this->height = $height ? $height : $this->height;
	$this->width = $height ? $width : $this->width;
	$this->left = $left ? $left : $this->left;
	$this->top = $top ? $top : $this->top;
	$cmd = "{$path} -quality {$this->quality} -resize '{$this->width}x{$this->height}>+{$this->left}+{$this->top}' {$this->target}";
	exec($cmd);
	chmod($this->target, 0644);
    }

    ////////////////// GD Library //////////////////////////////
    //function gd_resizeimage($src,$target,$w,$h,$type=''){
    function image_creation_gd($width=0, $height=0, $left=0, $top=0) {
	ini_set("memory_limit", "120M");
	$this->height = $height ? $height : $this->height;
	$this->width = $height ? $width : $this->width;
	$this->left = $left ? $left : $this->left;
	$this->top = $top ? $top : $this->top;
	//$type = substr(strrchr($this->src,"."),1);
	$imgInfo = getimagesize($this->src);
	$left = 0;
	$top = 0;
	list($owidth, $oheight) = getimagesize($this->src);
	$ar_image = $owidth / $oheight;
	$ar_container = $this->width / $this->height;

	if ($owidth > $oheight && $ar_image > $ar_container) {
	    if ($owidth > $this->width) {
		$newwidth = $this->width;
	    } else {
		$newwidth = $owidth;
	    }
	    $newheight = ($newwidth / $owidth) * $oheight;
	} else {
	    if ($oheight > $this->height) {
		$newheight = $this->height;
	    } else {
		$newheight = $oheight;
	    }
	    $newwidth = ($newheight / $oheight) * $owidth;
	}
	$thumb = imagecreatetruecolor($newwidth, $newheight);

	//Added By prakash For Transparent Image Cropping
	switch ($imgInfo['mime']) {
	    case 'image/png':
		$type = "png";
		break;
	    case 'image/gif':
		$type = "gif";
		break;
	    case 'image/jpeg':
		$type = "jpeg";
		break;
	    case 'image/bmp':
		$type = "wbmp";
		break;
	}

	$transparent = imagecolorallocate($thumb, 255, 255, 255);
	imagefill($thumb, 0, 0, $transparent);
	imagecolortransparent($thumb, $transparent);

	$var = "imagecreatefrom" . $type;
	$source = $var($this->src);

	imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $owidth, $oheight);

	imagejpeg($thumb, $this->target, $this->quality);
    }

    //////////////////////////End of library dependent function//////////

    function get_cropped_thumb($type=8, $width=0, $height=0) {
	//print_r($this);exit;	
	$this->height = $height ? $height : $this->height;
	$this->width = $width ? $width : $this->width;
	list($owidth, $oheight, $otype, $oattr) = getimagesize($this->target);
	$thumb_ratio = $this->width / $this->height;
	$image_ratio = $owidth / $oheight;
	if ($image_ratio > $thumb_ratio) {
	    $geometry_height = $this->height;
	} else {
	    $geometry_width = $this->width;
	}
	if (!( $geometry_height + $geometry_width)) {
	    return;
	}
	$cmd = "/usr/bin/mogrify -quality {$this->quality} -resize '{$geometry_width}x{$geometry_height}>' {$this->target}";
	error_log('File :' . __FILE__ . ' Line : ' . __LINE__ . $cmd);
	exec($cmd);
	$extra_width = 0;
	$extra_height = 0;
	list($cwidth, $cheight, $ctype, $cattr) = getimagesize($this->target);
	if ($geometry_width) {  // meaning that the image has to be cropped vertically)
	    $extra_height = ($cheight - $this->height);
	} else {
	    $extra_width = ($cwidth - $this->width);
	}

	$shave_width_left = 0;
	$shave_width_right = 0;
	$shave_height_top = 0;
	$shave_height_bottom = 0;
	switch ($type) {
	    case $this->CROP_ALL:
		if ($extra_width) {
		    if ($extra_width % 2) {
			$extra_width++;
			$shave_width_left = $extra_width / 2;
			$shave_width_right = $shave_width_left - 1;
		    } else {
			$shave_width_left = $shave_width_right = $extra_width / 2;
		    }
		}
		if ($extra_height) {
		    if ($extra_height % 2) {
			$extra_height++;
			$shave_height_top = $extra_height / 2;
			$shave_height_bottom = $shave_height_top - 1;
		    } else {
			$shave_height_top = $shave_height_bottom = $extra_height / 2;
		    }
		}
		break;
	    case $this->CROP_LEFT_TOP:
		$shave_width_left = $extra_width;
		$shave_height_top = $extra_height;
		break;
	    case $this->CROP_LEFT_BOTTOM:
		$shave_width_left = $extra_width;
		$shave_height_bottom = $extra_height;
		break;
	    case $this->CROP_RIGHT_TOP:
		$shave_width_right = $extra_width;
		$shave_height_top = $extra_height;
		break;
	    case $this->CROP_RIGHT_BOTTOM:
		$shave_width_right = $extra_width;
		$shave_height_bottom = $extra_height;
		break;
	    case $this->CROP_CENTER_TOP:
		if ($extra_width) {
		    if ($extra_width % 2) {
			$extra_width++;
			$shave_width_left = $extra_width / 2;
			$shave_width_right = $shave_width_left - 1;
		    } else {
			$shave_width_left = $shave_width_right = $extra_width / 2;
		    }
		}
		$shave_height_top = $extra_height;
		break;

	    case $this->CROP_CENTER_BOTTOM:
		if ($extra_width) {
		    if ($extra_width % 2) {
			$extra_width++;
			$shave_width_left = $extra_width / 2;
			$shave_width_right = $shave_width_left - 1;
		    } else {
			$shave_width_left = $shave_width_right = $extra_width / 2;
		    }
		}
		$shave_height_bottom = $extra_height;
		break;
	    case $this->CROP_MIDDLE_LEFT:
		$shave_width_left = $extra_width;
		if ($extra_height) {
		    if ($extra_height % 2) {
			$extra_height++;
			$shave_height_top = $extra_height / 2;
			$shave_height_bottom = $shave_height_top - 1;
		    } else {
			$shave_height_top = $shave_height_bottom = $extra_height / 2;
		    }
		}

		break;
	    case $this->CROP_MIDDLE_RIGHT:
		$shave_width_right = $extra_width;
		if ($extra_height) {
		    if ($extra_height % 2) {
			$extra_height++;
			$shave_height_top = $extra_height / 2;
			$shave_height_bottom = $shave_height_top - 1;
		    } else {
			$shave_height_top = $shave_height_bottom = $extra_height / 2;
		    }
		}

		break;
	    default:
		if ($extra_width) {
		    if ($extra_width % 2) {
			$extra_width++;
			$shave_width_left = $extra_width / 2;
			$shave_width_right = $shave_width_left - 1;
		    } else {
			$shave_width_left = $shave_width_right = $extra_width / 2;
		    }
		}
		if ($extra_height) {
		    if ($extra_height % 2) {
			$extra_height++;
			$shave_height_top = $extra_height / 2;
			$shave_height_bottom = $shave_height_top - 1;
		    } else {
			$shave_height_top = $shave_height_bottom = $extra_height / 2;
		    }
		}
	}
	$ccmd = "/usr/bin/mogrify -quality {$this->quality} -crop '+{$shave_width_left}-{$shave_height_bottom}'  -crop '-{$shave_width_right}+{$shave_height_top}'  {$this->target}";
	//usr/bin/mogrify -quality 70 -crop '+38-0' -crop '-0+0' /var/www/html/ppl/image/thumb/126_11_Picture_4.png
	//print "Type : $type : extra_width : $extra_width extra_height $extra_height CCMD $ccmd";exit;

	exec($ccmd);

	chmod($this->target, 0644);
    }

};
#####################################################
######################CONVERT_mE#####################
####################################################
//THIS FUNCTION IS USED TO REMOVE SPECIAL CHARACTER
function convert_me($str) 
{    
    $str = preg_replace("/[^\w\d\.\-]/","-",$str);
    return $str;
}

#####################################################
######################BMP@GD#########################
####################################################
//this function is used to convert bmp image to jpg for image croping
function bmp2gd($src, $jpgFile, $dest = false) {
	/*	 * * create a temp file ** */
	$dest = $dest ? $dest : tempnam("/tmp", "GD");
	/*	 * * try to open the file for reading ** */
	if (!($src_f = fopen($src, "rb"))) {
		return false;
	}

	/*	 * * try to open the destination file for writing ** */
	if (!($dest_f = fopen($dest, "wb"))) {
		return false;
	}

	/*	 * * grab the header ** */
	$header = unpack("vtype/Vsize/v2reserved/Voffset", fread($src_f, 14));

	/*	 * * grab the rest of the image ** */
	$info = unpack("Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vimportant", fread($src_f, 40));

	/*	 * * extract the header and info into varibles ** */
	extract($info);
	extract($header);

	/*	 * * check for BMP signature ** */
	if ($type != 0x4D42) {
		return false;
	}

	/*	 * * set the pallete ** */
	$palette_size = $offset - 54;
	$ncolor = $palette_size / 4;
	$gd_header = "";

	/*	 * * true-color vs. palette ** */
	$gd_header .= ( $palette_size == 0) ? "\xFF\xFE" : "\xFF\xFF";
	$gd_header .= pack("n2", $width, $height);
	$gd_header .= ( $palette_size == 0) ? "\x01" : "\x00";
	if ($palette_size) {
		$gd_header .= pack("n", $ncolor);
	}

	/*	 * * we do not allow transparency ** */
	$gd_header .= "\xFF\xFF\xFF\xFF";

	/*	 * * write the destination headers ** */
	fwrite($dest_f, $gd_header);

	/*	 * * if we have a valid palette ** */
	if ($palette_size) {
		/*		 * * read the palette ** */
		$palette = fread($src_f, $palette_size);
		/*		 * * begin the gd palette ** */
		$gd_palette = "";
		$j = 0;
		/*		 * * loop of the palette ** */
		while ($j < $palette_size) {
			$b = $palette{$j++};
			$g = $palette{$j++};
			$r = $palette{$j++};
			$a = $palette{$j++};
			/*			 * * assemble the gd palette ** */
			$gd_palette .= "$r$g$b$a";
		}
		/*		 * * finish the palette ** */
		$gd_palette .= str_repeat("\x00\x00\x00\x00", 256 - $ncolor);
		/*		 * * write the gd palette ** */
		fwrite($dest_f, $gd_palette);
	}

	/*	 * * scan line size and alignment ** */
	$scan_line_size = (($bits * $width) + 7) >> 3;
	$scan_line_align = ($scan_line_size & 0x03) ? 4 - ($scan_line_size & 0x03) : 0;

	/*	 * * this is where the work is done ** */
	for ($i = 0, $l = $height - 1; $i < $height; $i++, $l--) {
		/*		 * * create scan lines starting from bottom ** */
		fseek($src_f, $offset + (($scan_line_size + $scan_line_align) * $l));
		$scan_line = fread($src_f, $scan_line_size);
		if ($bits == 24) {
			$gd_scan_line = "";
			$j = 0;
			while ($j < $scan_line_size) {
				$b = $scan_line{$j++};
				$g = $scan_line{$j++};
				$r = $scan_line{$j++};
				$gd_scan_line .= "\x00$r$g$b";
			}
		} elseif ($bits == 8) {
			$gd_scan_line = $scan_line;
		} elseif ($bits == 4) {
			$gd_scan_line = "";
			$j = 0;
			while ($j < $scan_line_size) {
				$byte = ord($scan_line{$j++});
				$p1 = chr($byte >> 4);
				$p2 = chr($byte & 0x0F);
				$gd_scan_line .= "$p1$p2";
			}
			$gd_scan_line = substr($gd_scan_line, 0, $width);
		} elseif ($bits == 1) {
			$gd_scan_line = "";
			$j = 0;
			while ($j < $scan_line_size) {
				$byte = ord($scan_line{$j++});
				$p1 = chr((int) (($byte & 0x80) != 0));
				$p2 = chr((int) (($byte & 0x40) != 0));
				$p3 = chr((int) (($byte & 0x20) != 0));
				$p4 = chr((int) (($byte & 0x10) != 0));
				$p5 = chr((int) (($byte & 0x08) != 0));
				$p6 = chr((int) (($byte & 0x04) != 0));
				$p7 = chr((int) (($byte & 0x02) != 0));
				$p8 = chr((int) (($byte & 0x01) != 0));
				$gd_scan_line .= "$p1$p2$p3$p4$p5$p6$p7$p8";
			}
			/*			 * * put the gd scan lines together ** */
			$gd_scan_line = substr($gd_scan_line, 0, $width);
		}
		/*		 * * write the gd scan lines ** */
		fwrite($dest_f, $gd_scan_line);
	}
	/*	 * * close the source file ** */
	fclose($src_f);
	/*	 * * close the destination file ** */
	fclose($dest_f);

	/*	 * * convert to gd ** */
	$img = imagecreatefromgd($dest);

	/*	 * * Unlink Original Fle ** */
	unlink($src);
	/*	 * * Unlink Temporary Fle ** */
	unlink($dest);

	/*	 * * write the new jpeg image ** */
	imagejpeg($img, $jpgFile);

	return true;
}


