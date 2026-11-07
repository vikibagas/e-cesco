<?php
//id user 8 bu sri
if($u['level'] == 'admin' || $u['level'] == 'inbound' || $u['level'] == 'network' || $u['level'] == 'admin_network'  || $u['id_user'] == 8 ){
?>
	<li class="treeview <?php echo ($_GET['module'] == 'kpi-karyawan' || $_GET['module'] == 'kpi' || $_GET['module'] == 'kpi-cabang' || $_GET['module'] == 'kpi-distribution-team' || $_GET['module'] == 'kpi-customer' || $_GET['module'] == 'kpi-vendor' ? 'active' : ''); ?>">
		<a href="#"><i class="fa fa-clone"></i> <span>KPI</span> <i class="fa fa-angle-left pull-right"></i></a>
		<ul class="treeview-menu">

			<?php if($u['level'] == 'admin' ){ ?>
				<li class="<?php echo ($_GET['module'] == 'kpi-cabang' ? 'active' : ''); ?>"><a href="?module=kpi-cabang"><i class="fa fa-circle-o"></i> KPI Cabang/agent</a></li>
				<li class="<?php echo ($_GET['module'] == 'kpi-vendor' ? 'active' : ''); ?>"><a href="?module=kpi-vendor"><i class="fa fa-circle-o"></i> KPI vendor</a></li>
				<li  class="<?php echo ($_GET['module'] == 'kpi-karyawan' ? 'active' : ''); ?>"><a href="?module=kpi-karyawan"><i class="fa fa-circle-o"></i>KPI Karyawan</a></li>
			
				<li class="<?php echo ($_GET['module'] == 'kpi-distribution-team' ? 'active' : ''); ?>"><a href="?module=kpi-distribution-team"><i class="fa fa-circle-o"></i> KPI Distribution team</a></li>
				<li class="<?php echo ($_GET['module'] == 'kpi-customer' ? 'active' : ''); ?>"><a href="?module=kpi-customer"><i class="fa fa-circle-o"></i> KPI Customer</a></li>

				
				
				<li class="<?php echo ($_GET['module'] == 'kpi' ? 'active' : ''); ?>"><a href="?module=kpi"><i class="fa fa-circle-o"></i> Kelola KPI indikator</a></li>

			<?php }else{ ?>
			
				<li class="<?php echo ($_GET['module'] == 'kpi-cabang' ? 'active' : ''); ?>"><a href="?module=kpi-cabang"><i class="fa fa-circle-o"></i> KPI Cabang/agent</a></li>
				<li class="<?php echo ($_GET['module'] == 'kpi-vendor' ? 'active' : ''); ?>"><a href="?module=kpi-vendor"><i class="fa fa-circle-o"></i> KPI vendor</a></li>
			<?php } ?>
			
		</ul>
</li>
<?php
}
?>