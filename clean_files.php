<?php
// Hapus file QRCode yg lbh dr 3 bulan
$files = glob('reportsnew/code/*.png', GLOB_BRACE);
foreach($files as $file) {
    $age = time()-filemtime($file);
    if ($age > (24 * 3600) * 90) {
        @unlink($file);
    }
}

/// Pindahin foto penerima yg lbh dr 3 bulan
$destination_path = '/home/cesco/tmp/backup-ecesco/'.date('Ymd').'/';
$files = glob('reportsnew/delivery_photo/*.jpg', GLOB_BRACE);
$x = 0;
foreach($files as $file) {
    $age = time()-filemtime($file);
    if ($age > (24 * 3600) * 90) {
		
		if ($x == 0){
			$x++;
			if (!is_dir($destination_path)) {
				$oldmask = umask(0);
				@mkdir($destination_path, 0777, true);
				umask($oldmask);
			}
		}
		
        @rename($file, $destination_path . pathinfo($file, PATHINFO_BASENAME));
    }
}

?>