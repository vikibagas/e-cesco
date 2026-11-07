<?php
if($_GET['module']=='booking' || $_GET['module']=='bookingin' || $_GET['module']=='pickup' || $_GET['module']=='checklist' || $_GET['module']=='manifest-pickup' || $_GET['module']=='manifest' || $_GET['module']=='outbound' || $_GET['module']=='delivery' || $_GET['module']=='airwaybill' || $_GET['module']=='report-airwaybill-by-date'){
	echo'
	<li class="treeview active">
		<a href="#"><i class="fa fa-desktop"></i> <span>Application</span> <i class="fa fa-angle-left pull-right"></i></a>
		<ul class="treeview-menu">';
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='domex' || $u['level']=='network' || $u['menu_tambahan']=='booking'){
			if($_GET['module']=='booking' || $_GET['module']=='api'  || $u['finance']){
				echo'
				<li class="treeview active">
					<a href="#"><i class="fa fa-circle-o"></i> <span>Booking</span> <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">';
					if($_GET['module']=='booking'){
						echo'
						<li class="active"><a href="?module=booking"><i class="fa fa-circle-o"></i> Outbound</a></li>';
					}else{
						echo'
						<li><a href="?module=booking"><i class="fa fa-circle-o"></i> Outbound</a></li>';
					}
					if($_GET['module']=='bookingin'){
						echo'
						<li class="active"><a href="?module=bookingin"><i class="fa fa-circle-o"></i> Inbound</a></li>';
					}else{
						echo'
						<li><a href="?module=bookingin"><i class="fa fa-circle-o"></i> Inbound</a></li>';
					}
					echo'
					</ul>
				</li>';
			}else{
				echo'
				<li class="treeview">
					<a href="#"><i class="fa fa-circle-o"></i> <span>Booking</span> <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu">
						<li><a href="?module=booking"><i class="fa fa-circle-o"></i> Outbound</a></li>
						<li><a href="?module=bookingin"><i class="fa fa-circle-o"></i> Inbound</a></li>
					</ul>
				</li>';
			}
		}
		if($_GET['module']=='pickup'){
			echo'
			<li class="active"><a href="?module=pickup"><i class="fa fa-circle-o"></i> Pick Up</a></li>';
		}else{
			echo'
			<li><a href="?module=pickup"><i class="fa fa-circle-o"></i> Pick Up</a></li>';
		}
		if($_GET['module']=='checklist' || $_GET['module']=='manifest-pickup' || $_GET['module']=='manifest' || $_GET['module']=='outbound'){
			echo'
			<li class="treeview active">
				<a href="#"><i class="fa fa-circle-o"></i> <span>Manifest</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">';
				if($_GET['module']=='checklist'){
					echo'
					<li class="active"><a href="?module=checklist"><i class="fa fa-circle-o"></i> Checklist</a></li>';
				}else{
					echo'
					<li><a href="?module=checklist"><i class="fa fa-circle-o"></i> Checklist</a></li>';
				}
				if($u['level']=='admin' || $u['level']=='operator'  || $u['finance']){
					if($_GET['module']=='manifest-pickup'){
						echo'
						<li class="active"><a href="?module=manifest-pickup&act=create"><i class="fa fa-circle-o"></i> Pickup</a></li>';
					}else{
						echo'
						<li><a href="?module=manifest-pickup&act=create"><i class="fa fa-circle-o"></i> Pickup</a></li>';
					}
				}
				if($_GET['module']=='manifest'){
					echo'
					<li class="active"><a href="?module=manifest"><i class="fa fa-circle-o"></i> Delivery</a></li>';
				}else{
					echo'
					<li><a href="?module=manifest"><i class="fa fa-circle-o"></i> Delivery</a></li>';
				}
				echo'
				</ul>
			</li>';
		}else{
			echo'
			<li class="treeview">
				<a href="#"><i class="fa fa-circle-o"></i> <span>Manifest</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li><a href="?module=checklist"><i class="fa fa-circle-o"></i> Checklist</a></li>';
					if($u['level']=='admin' || $u['level']=='operator'  || $u['finance']){
						echo'<li><a href="?module=manifest-pickup&act=create"><i class="fa fa-circle-o"></i> Pickup</a></li>';
					}
					echo'
					<li><a href="?module=manifest"><i class="fa fa-circle-o"></i> Delivery</a></li>
				</ul>
			</li>';
		}
		if($_GET['module']=='delivery'){
			echo'
			<li class="active"><a href="?module=delivery"><i class="fa fa-circle-o"></i> Delivery</a></li>';
		}else{
			echo'
			<li><a href="?module=delivery"><i class="fa fa-circle-o"></i> Delivery</a></li>';
		}
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['finance'] || $u['sales']){
			if($_GET['module']=='airwaybill'){
				echo'
				<li class="active"><a href="?module=airwaybill"><i class="fa fa-circle-o"></i> Airwaybill</a></li>';
			}else{
				echo'
				<li><a href="?module=airwaybill"><i class="fa fa-circle-o"></i> Airwaybill</a></li>';
			}
		}
		echo'
		</ul>
	</li>';
}else{
	echo'
	<li class="treeview">
		<a href="#"><i class="fa fa-desktop"></i> <span>Application</span> <i class="fa fa-angle-left pull-right"></i></a>
		<ul class="treeview-menu">';
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['menu_tambahan']=='booking' || $u['menu_tambahan']=='api_awb'  || $u['finance']){
			echo'
			<li class="treeview">
				<a href="#"><i class="fa fa-circle-o"></i> <span>Booking</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li><a href="?module=booking"><i class="fa fa-circle-o"></i> Outbounddd</a></li>
					<li><a href="?module=bookingin"><i class="fa fa-circle-o"></i> Inbound</a></li>
				</ul>
			</li>';
		}
		echo'
			<li><a href="?module=pickup"><i class="fa fa-circle-o"></i> Pick Up</a></li>
			<li class="treeview">
				<a href="#"><i class="fa fa-circle-o"></i> <span>Manifest</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">
					<li><a href="?module=checklist"><i class="fa fa-circle-o"></i> Checklist</a></li>';
					if($u['level']=='admin' || $u['level']=='operator'){
						echo'<li><a href="?module=manifest-pickup&act=create"><i class="fa fa-circle-o"></i> Pickup</a></li>';
					}
					echo'
					<li><a href="?module=manifest"><i class="fa fa-circle-o"></i> Delivery</a></li>
				</ul>
			</li>
			<li><a href="?module=delivery"><i class="fa fa-circle-o"></i> Delivery</a></li>';
			if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network'|| $u['finance'] || $u['sales']){
				echo'
				<li><a href="?module=airwaybill"><i class="fa fa-circle-o"></i> Airwaybill</a></li>';
			}
			echo'
		</ul>
	</li>';
}
?>