<?php
if($u['warehouse'] == 'Y'){
    // Ambil nilai module dari $_GET dengan aman (mencegah undefined index warning)
    $module = isset($_GET['module']) ? $_GET['module'] : '';
    
    // Daftar module yang membuat menu Warehouse menjadi 'active'
    $warehouse_modules = [
        'rak', 
        'booking-warehouse', 
        'barang-nonaktif', 
        'barang', 
        'report-warehouse', 
        'pemakaian-rak', 
        'keluar-barang-belum-tercatat'
    ];
    
    $is_active = in_array($module, $warehouse_modules) ? 'active' : '';
?>
    <li class="treeview <?php echo $is_active; ?>">
        <a href="#"><i class="fa fa-clone"></i> <span>Warehouse</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li class="<?php echo ($module == 'rak' ? 'active' : ''); ?>"><a href="?module=rak"><i class="fa fa-circle-o"></i>Data Rak</a></li>
            <li class="<?php echo ($module == 'barang' ? 'active' : ''); ?>"><a href="?module=barang"><i class="fa fa-circle-o"></i> Data Barang</a></li>
            <li class="<?php echo ($module == 'barang-nonaktif' ? 'active' : ''); ?>"><a href="?module=barang-nonaktif"><i class="fa fa-circle-o"></i> Data Barang Non Aktif</a></li>
            <li class="<?php echo ($module == 'report-warehouse' ? 'active' : ''); ?>"><a href="?module=report-warehouse"><i class="fa fa-circle-o"></i> Report Warehouse</a></li>
            <li class="<?php echo ($module == 'booking-warehouse' ? 'active' : ''); ?>"><a href="?module=booking-warehouse"><i class="fa fa-circle-o"></i> Booking AWB Warehouse</a></li>
            <li class="<?php echo ($module == 'pemakaian-rak' ? 'active' : ''); ?>"><a href="?module=pemakaian-rak"><i class="fa fa-circle-o"></i> Data Pemakaian Rak</a></li>
            <li class="<?php echo ($module == 'keluar-barang-belum-tercatat' ? 'active' : ''); ?>"><a href="?module=keluar-barang-belum-tercatat"><i class="fa fa-circle-o"></i> Data belum tercatat
                <span class="pull-right-container">
                  <small class="label pull-right bg-red">
                    <?php
                        $q = mysqli_query($con, 'SELECT COUNT(*) as datakosong FROM warehouse_history WHERE id_pengiriman IS NULL AND awb IS NULL and out_in = "out" and created_date > "2025-01-01" ');
                        $data = mysqli_fetch_assoc($q);
                        echo $data['datakosong'];
                    ?>
                  </small>                
                </span>
            </a></li>
        </ul>
    </li>
<?php
}
?>