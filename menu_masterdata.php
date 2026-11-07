<?php
if($u['level']=='admin' || $u['level']=='finance' || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='inbound' || $u['level']=='operational' || $u['tambahan_admin']==1 && $u['menu_pengurangan'] != 'finance' ){
	if($_GET['module']=='country' || $_GET['module']=='destination' || $_GET['module']=='branch' || $_GET['module']=='branch' || $_GET['module']=='customer' || $_GET['module']=='users' || $_GET['module']=='agent-outbound' || $_GET['module']=='agent-inbound' || $_GET['module']=='tarif-luarnegeri' || $_GET['module']=='tarif-urgent' || $_GET['module']=='tarif-regular' || $_GET['module']=='tarif-intercity' || $_GET['module']=='traffic' || $_GET['module']=='traffic-mobile')
	{
		echo'
		<li class="treeview active">
			<a href="#"><i class="fa fa-clone"></i> <span>Master Data</span> <i class="fa fa-angle-left pull-right"></i></a>
			<ul class="treeview-menu">
			<li ';if($_GET['module']=='country')echo'class="active"';echo'><a href="?module=country"><i class="fa fa-circle-o"></i> Negara</a></li>';
			if($_GET['module']=='destination'){
				echo'
				<li class="active"><a href="?module=destination"><i class="fa fa-circle-o"></i> Kota</a></li>';
			}else{
				echo'
				<li><a href="?module=destination"><i class="fa fa-circle-o"></i> Kota</a></li>';
			}
			if($_GET['module']=='branch'){
				echo'
				<li class="active"><a href="?module=branch"><i class="fa fa-circle-o"></i> Branch/Agent</a></li>';
			}else{
				echo'
				<li><a href="?module=branch"><i class="fa fa-circle-o"></i> Branch/Agent</a></li>';
			}
			if($_GET['module']=='customer'){
				echo'	
				<li class="active"><a href="?module=customer"><i class="fa fa-circle-o"></i> Customer</a></li>';
			}else{
				echo'	
				<li><a href="?module=customer"><i class="fa fa-circle-o"></i> Customer</a></li>';
			}
			if($_GET['module']=='users'){
				echo'	
				<li class="active"><a href="?module=users"><i class="fa fa-circle-o"></i> Users</a></li>';
			}else{
				echo'	
				<li><a href="?module=users"><i class="fa fa-circle-o"></i> Users</a></li>';
			}
			if($_GET['module']=='rate'){
				echo'
				<li class="active"><a href="?module=rate"><i class="fa fa-circle-o"></i> Rate Customer</a></li>';
			}else{
				echo'
				<li><a href="?module=rate"><i class="fa fa-circle-o"></i> Rate Customer</a></li>';
			}
			if($_GET['module']=='vendor'){
				echo'
				<li class="active"><a href="?module=vendor"><i class="fa fa-circle-o"></i> Vendor</a></li>';
			}else{
				echo'
				<li><a href="?module=vendor"><i class="fa fa-circle-o"></i> vendor</a></li>';
			}
			if($_GET['module']=='traffic'){
				echo'
				<li class="active"><a href="?module=traffic"><i class="fa fa-circle-o"></i> Traffic</a></li>';
			}else{
				echo'
				<li><a href="?module=traffic"><i class="fa fa-circle-o"></i> Traffic</a></li>';
			}
			if($_GET['module']=='traffic-mobile'){
				echo'
				<li class="active"><a href="?module=traffic-mobile"><i class="fa fa-circle-o"></i> Traffic Mobile</a></li>';
			}else{
				echo'
				<li><a href="?module=traffic-mobile"><i class="fa fa-circle-o"></i> Traffic Mobile</a></li>';
			}
			if($_GET['module']=='agent-outbound' || $_GET['module']=='agent-inbound' || $_GET['module']=='agent-cross'){
				echo'	
				<li class="treeview active"><a href="#"><i class="fa fa-circle-o"></i> Pricelist Agent <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">';
					if($_GET['module']=='agent-outbound'){
						echo'
						<li class="active"><a href="?module=agent-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>';
					}else{
						echo'
						<li><a href="?module=agent-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>';
					}
					if($_GET['module']=='agent-inbound'){
						echo'
						<li class="active"><a href="?module=agent-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>';
					}
					if($_GET['module']=='agent-cross'){
						echo'
						<li class="active"><a href="?module=agent-cross"><i class="fa fa-circle-o"></i> Cross</a></li>';
					}else{
						echo'
						<li><a href="?module=agent-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>';
					}
					echo'
					</ul>
				</li>';
				echo'
				<li class="treeview ';if($_GET['module']=='tarif-luarnegeri' || $_GET['module']=='tarif-urgent' || $_GET['module']=='tarif-regular' || $_GET['module']=='tarif-intercity'){echo'active';}echo'"><a href="#"><i class="fa fa-circle-o"></i> Tarif Kiriman <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">
						<li ';if($_GET['module']=='tarif-luarnegeri'){echo'class="active"';}echo'><a href="?module=tarif-luarnegeri"><i class="fa fa-circle-o"></i> Luar Negeri</a></li>
						<li ';if($_GET['module']=='tarif-urgent'){echo'class="active"';}echo'><a href="?module=tarif-urgent"><i class="fa fa-circle-o"></i> Top Urgent & Urgent</a></li>
						<li ';if($_GET['module']=='tarif-regular'){echo'class="active"';}echo'><a href="?module=tarif-regular"><i class="fa fa-circle-o"></i> Regular</a></li>
						<li ';if($_GET['module']=='tarif-intercity'){echo'class="active"';}echo'><a href="?module=tarif-intercity"><i class="fa fa-circle-o"></i> Intercity</a></li>
					</ul>
				</li>';
			}else{
				echo'	
				<li class="treeview"><a href="#"><i class="fa fa-circle-o"></i> Pricelist Agent <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">
						<li><a href="?module=agent-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>
						<li><a href="?module=agent-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>
						<li><a href="?module=agent-cross"><i class="fa fa-circle-o"></i> Cross</a></li>
					</ul>
				</li>
				<li class="treeview"><a href="#"><i class="fa fa-circle-o"></i> Tarif Kiriman <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">
						<li><a href="?module=tarif-luarnegeri"><i class="fa fa-circle-o"></i> Luar Negeri</a></li>
						<li><a href="?module=tarif-urgent"><i class="fa fa-circle-o"></i> Top Urgent & Urgent</a></li>
						<li><a href="?module=tarif-regular"><i class="fa fa-circle-o"></i> Regular</a></li>
						<li><a href="?module=tarif-intercity"><i class="fa fa-circle-o"></i> Intercity</a></li>
					</ul>
				</li>
				';
			}
			echo'
			</ul>
			
		</li>';
		
	}else{
		echo'
		<li class="treeview">
			<a href="#"><i class="fa fa-clone"></i> <span>Master Data</span> <i class="fa fa-angle-left pull-right"></i></a>
			<ul class="treeview-menu">
			'; if($u['level']=='admin' || $u['tambahan_admin']=='1' || $u['tambahan_admin']==2 ){
				echo '
				<li><a href="?module=country"><i class="fa fa-circle-o"></i> Negara</a></li>
				<li><a href="?module=destination"><i class="fa fa-circle-o"></i> Kota</a></li>
				<li><a href="?module=branch"><i class="fa fa-circle-o"></i> Branch/Agent</a></li>
				<li><a href="?module=customer"><i class="fa fa-circle-o"></i> Customer</a></li>
				<li><a href="?module=users"><i class="fa fa-circle-o"></i> Users</a></li>
				<li><a href="?module=rate"><i class="fa fa-circle-o"></i> Rate Customer</a></li>
				';
				}
				if ($u['level']=='admin' || $u['tambahan_admin']=='1' || $u['tambahan_admin']==2 || $u['level']=='network' || $u['level']=='admin_network' || $u['level']=='inbound' || $u['level']=='operational') {
				echo '
				<li><a href="?module=vendor"><i class="fa fa-circle-o"></i> Vendor</a></li>
				<li><a href="?module=traffic"><i class="fa fa-circle-o"></i> Traffic</a></li>
				<li><a href="?module=traffic-mobile"><i class="fa fa-circle-o"></i> Traffic Mobile</a></li>
				';
				}
				echo '
				<li><a href="?module=branch"><i class="fa fa-circle-o"></i> Branch/Agent</a></li>
				<li class="treeview"><a href="#"><i class="fa fa-circle-o"></i> Pricelist Agent <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">
						<li><a href="?module=agent-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>
						<li><a href="?module=agent-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>
						<li><a href="?module=agent-cross"><i class="fa fa-circle-o"></i> Cross</a></li>
					</ul>
				</li>
				<li class="treeview"><a href="#"><i class="fa fa-circle-o"></i> SLA <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">
						<li><a href="?module=sla-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>
						<li><a href="?module=sla-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>
						<li><a href="?module=sla-cross"><i class="fa fa-circle-o"></i> Cross City</a></li>
					</ul>
				</li>

			</ul>
		</li>';
	}
}
?>