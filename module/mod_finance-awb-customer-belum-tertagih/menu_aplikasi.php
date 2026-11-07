<?php
if($_GET['module']=='booking' || $_GET['module']=='bookingin' || $_GET['module']=='pickup' || $_GET['module']=='checklist' || $_GET['module']=='manifest-pickup' || $_GET['module']=='manifest-outbound' || $_GET['module']=='manifest-inbound' || $_GET['module']=='manifest' || $_GET['module']=='delivery' || $_GET['module']=='airwaybill'){
	echo'
	<li class="treeview active">
		<a href="#"><i class="fa fa-desktop"></i> <span>Application</span> <i class="fa fa-angle-left pull-right"></i></a>
		<ul class="treeview-menu">';
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='logistics'){
			if($_GET['module']=='booking' || $_GET['module']=='bookingin'){
				echo'
				<li class="active">
					<a href="?module=bookingin"><i class="fa fa-circle-o"></i> <span>Booking</span></a>
				</li>';
			}else{
				echo'
				<li><a href="?module=bookingin"><i class="fa fa-circle-o"></i> <span>Booking</span></a></li>';
			}
		}
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='intercity' || $u['level']=='agent' || $u['level']=='driver' || $u['level']=='kurir' || $u['level']=='domex' || $u['level']=='logistics'){
			if($_GET['module']=='pickup'){
				echo'
				<li class="active"><a href="?module=pickup"><i class="fa fa-circle-o"></i> Pick Up</a></li>';
			}else{
				echo'
				<li><a href="?module=pickup"><i class="fa fa-circle-o"></i> Pick Up</a></li>';
			}
		}
		if($_GET['module']=='checklist' || $_GET['module']=='manifest-pickup' || $_GET['module']=='manifest-inbound' || $_GET['module']=='manifest-outbound' || $_GET['module']=='manifest'){
			if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='agent' || $u['level']=='intercity' || $u['level']=='domex'){
			echo'
			<li class="treeview active">
				<a href="#"><i class="fa fa-circle-o"></i> <span>Manifest</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">';
				if($u['level']=='admin'){
					if($_GET['module']=='checklist'){
						echo'
						<li class="active"><a href="?module=checklist"><i class="fa fa-circle-o"></i> Checklist</a></li>';
					}else{
						echo'
						<li><a href="?module=checklist"><i class="fa fa-circle-o"></i> Checklist</a></li>';
					}
				}
				if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='agent' || $u['level']=='intercity' || $u['level']=='domex'){
					if($_GET['module']=='manifest-pickup'){
						echo'
						<li class="active"><a href="?module=manifest-pickup"><i class="fa fa-circle-o"></i> Pickup</a></li>';
					}else{
						echo'
						<li><a href="?module=manifest-pickup"><i class="fa fa-circle-o"></i> Pickup</a></li>';
					}
				}
				if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='domex' || $u['level']=='agent'){
					if($_GET['module']=='manifest-outbound'){
						echo'<li class="active"><a href="?module=manifest-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>';
					}else{
						echo'<li><a href="?module=manifest-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>';
					}
				}
				if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='agent'){
					if($_GET['module']=='manifest-inbound'){
						echo'<li class="active"><a href="?module=manifest-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>';
					}else{
						echo'<li><a href="?module=manifest-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>';
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
			}
		}else{
			if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='agent' || $u['level']=='intercity' || $u['level']=='domex'){
			echo'
			<li class="treeview">
				<a href="#"><i class="fa fa-circle-o"></i> <span>Manifest</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">';
					if($u['level']=='admin'){
						echo'<li><a href="?module=checklist"><i class="fa fa-circle-o"></i> Checklist</a></li>';
					}
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='agent' || $u['level']=='intercity' || $u['level']=='domex'){
						echo'<li><a href="?module=manifest-pickup"><i class="fa fa-circle-o"></i> Pickup</a></li>';
					}
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='domex' || $u['level']=='agent'){
						echo'<li><a href="?module=manifest-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>';
					}
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='agent'){
						echo'<li><a href="?module=manifest-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>';
					}
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='agent' || $u['level']=='intercity' || $u['level']=='domex'){
						echo'<li><a href="?module=manifest"><i class="fa fa-circle-o"></i> Delivery</a></li>';
					}
					echo'
				</ul>
			</li>';
			}
		}
		if($_GET['module']=='delivery'){
			echo'
			<li class="active"><a href="?module=delivery"><i class="fa fa-circle-o"></i> Delivery</a></li>';
		}else{
			echo'
			<li><a href="?module=delivery"><i class="fa fa-circle-o"></i> Delivery</a></li>';
		}
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='intercity' || $u['level']=='finance' || $u['level']=='domex'){
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
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='logistics'){
			echo'
			<li><a href="?module=bookingin"><i class="fa fa-circle-o"></i> Booking</a></li>';
		}
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='intercity' || $u['level']=='agent' || $u['level']=='driver' || $u['level']=='kurir' || $u['level']=='domex' || $u['level']=='logistics'){
			echo'
			<li><a href="?module=pickup"><i class="fa fa-circle-o"></i> Pick Up</a></li>';
		}
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='agent' || $u['level']=='intercity' || $u['level']=='domex'){
		echo'
			<li class="treeview">
				<a href="#"><i class="fa fa-circle-o"></i> <span>Manifest</span> <i class="fa fa-angle-left pull-right"></i></a>
				<ul class="treeview-menu">';
					if($u['level']=='admin'){
						echo'<li><a href="?module=checklist"><i class="fa fa-circle-o"></i> Checklist</a></li>';
					}
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='agent' || $u['level']=='intercity' || $u['level']=='domex'){
						echo'<li><a href="?module=manifest-pickup"><i class="fa fa-circle-o"></i> Pickup</a></li>';
					}
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='domex' || $u['level']=='agent'){
						echo'<li><a href="?module=manifest-outbound"><i class="fa fa-circle-o"></i> Outbound</a></li>';
					}
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='agent'){
						echo'<li><a href="?module=manifest-inbound"><i class="fa fa-circle-o"></i> Inbound</a></li>';
					}
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='agent'){
						echo'<li><a href="?module=manifest"><i class="fa fa-circle-o"></i> Delivery</a></li>';
					}
					echo'
				</ul>
			</li>';
		}
		echo'
			<li><a href="?module=delivery"><i class="fa fa-circle-o"></i> Delivery</a></li>';
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='intercity' || $u['level']=='domex'){
			echo'
			<li><a href="?module=airwaybill"><i class="fa fa-circle-o"></i> Airwaybill</a></li>';
		}
		echo'
		</ul>
	</li>';
}
?>