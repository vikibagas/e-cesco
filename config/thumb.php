<?php
function UploadPhoto($fupload_name){
	//direktori gambar
	$vdir_upload = "../../dist/img/";
	$vfile_upload = $vdir_upload . $fupload_name;
	$imageType = $_FILES["fupload"]["type"];

	//Simpan gambar dalam ukuran sebenarnya
	move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);

	//identitas file asli
	switch($imageType) {
		case "image/gif":
			$im_src=imagecreatefromgif($vfile_upload); 
		break;
		case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$im_src=imagecreatefromjpeg($vfile_upload); 
		break;
		case "image/png":
		case "image/x-png":
			$im_src=imagecreatefrompng($vfile_upload); 
		break;
	}
  
	$src_width = imageSX($im_src);
	$src_height = imageSY($im_src);
	
	$thumb_width = 215;
	$thumb_height = 215;
	$original_aspect = $src_width / $src_height;
	$thumb_aspect = $thumb_width / $thumb_height;
	if ( $original_aspect >= $thumb_aspect ){
		$new_height = $thumb_height;
		$new_width = $src_width / ($src_height / $thumb_height);
	}
	else{
		$new_width = $thumb_width;
		$new_height = $src_height / ($src_width / $thumb_width);
	}
	$thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
	imagecopyresampled($thumb,
                       $im_src,
                       0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
                       0 - ($new_height - $thumb_height) / 2, // Center the image vertically
                       0, 0,
                       $new_width, $new_height,
                       $src_width, $src_height);
					   
	switch($imageType) {
		case "image/gif":
  			imagegif($thumb,$vdir_upload .$fupload_name);
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
  			imagejpeg($thumb,$vdir_upload .$fupload_name);
			break;
	    case "image/png":
		case "image/x-png":
  			imagepng($thumb,$vdir_upload .$fupload_name);
			break;
	}
	
	
	//Hapus gambar di memori komputer
	imagedestroy($im_src);
	imagedestroy($thumb);
}
?>