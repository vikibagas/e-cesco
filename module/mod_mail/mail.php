
  <?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../../application/login.php');
}
else{
	$aksi	= "module/mod_mail/mail_action.php";
		
	$act = !empty($_GET['act']) ? $_GET['act'] : '';

	switch($act){
			
			default:
			echo'
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Mailbox
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mailbox</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="main.php?module=mail&act=new_message" class="btn btn-primary btn-block margin-bottom">New Message</a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            ';
            $query_in = mysqli_query($con,"select pengirim,x_userclient(pengirim) as nama_pengirim,penerima,  subject,dibaca,created_date from mail where penerima=9999 and dibaca='N' order by created_date desc limit 15 ");
                  $rowcount = mysqli_num_rows($query_in);

            echo '
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="main.php?module=mail"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right">'.$rowcount.'</span></a>
              	</li>
                <li><a href="main.php?module=mail&act=sent"><i class="fa fa-envelope-o"></i> Sent  </a></li>
                
                
                
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Inbox</h3>

              <!--<div class="box-tools pull-right">
                <div class="has-feedback">
                  <input type="text" class="form-control input-sm" placeholder="Search Mail">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
               /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
               
                </button>
               
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                <button type="submit" form="deletemessage" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                <!--  <div class="pull-right">
                  1-50/200
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                  </div>
                  
                </div>
               /.pull-right -->
              </div>
              <div class="table-responsive mailbox-messages">
              <form action="'.$aksi.'?module=mail&act=delete-all" id="deletemessage" method="POST"></form> 
                <table id="mailbox" class="table table-hover">
                <thead>
                      <tr>
                          <td> Action                      </td>
                          <td> To                       </td>
                          <td> Subject                       </td>
                          <td> Date                       </td>
                      </tr>
                  </thead>
                  <tbody>
                  ';
                  $query_inbox = mysqli_query($con,"select id,pengirim_client,fullname_pengirim,x_userclient(pengirim_client) as nama_pengirim,penerima,  subject,dibaca,created_date,pesan_bebas from mail where penerima=9999 order by created_date desc limit 15 ");
                  $count = mysqli_num_rows($query_inbox);
                  
                  if($count >0){
                  		while($q=mysqli_fetch_assoc($query_inbox)){

                        if($q['pesan_bebas'] == 'Y'){
                          $pengirim = $q['fullname_pengirim'];
                        }else{
                          $pengirim = $q['nama_pengirim'];
                        }
		                  echo '
		                  <tr  '.($q['dibaca'] == 'N' ? ' class="warning"' : '').'>
		                    <td >
		                    	<input type="checkbox" form="deletemessage" name="selectmail[]" value="'.$q['id'].'">
				                  <a type="button" href="module/mod_mail/mail_action.php?module=mail&act=delete&id='.$q['id'].'" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a>
				                  <a href="main.php?module=mail&act=new_message&reply_subject=RE: '.$q['subject'].'&penerima='.$q['pengirim_client'].'"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button></a>
				                  
				                
		                    </td>
		                    
		                    <td class="mailbox-name"><a href="main.php?module=mail&act=read&id='.$q['id'].'">'.$pengirim.'</a></td>
		                    <td class="mailbox-subject"><b>'.$q['subject'].'
		                    </td>
		                    
		                    <td class="mailbox-date">'.$q['created_date'].'</td>
		                  </tr>';
		                 }	
                  }
                  else{
                  		echo '<tr><td>Belum ada Pesan</td></tr>';
                  }                  
                  echo '               
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
             
            </div>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  
  <!-- /.content-wrapper -->

  ';
  break;

  case "sent":

  	echo '<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Mailbox
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mailbox</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="main.php?module=mail&act=new_message" class="btn btn-primary btn-block margin-bottom">New Message</a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            ';
            $query_inbox = mysqli_query($con,"select pengirim,x_userclient(pengirim) as nama_pengirim,penerima,  subject,dibaca,created_date from mail where penerima=$u[id_user] and dibaca='N' order by created_date desc limit 15 ");
                  $rowcount = mysqli_num_rows($query_inbox);

            echo '
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="main.php?module=mail"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right">'.$rowcount.'</span></a>
              	</li>
                <li class="active"><a href="main.php?module=mail&act=sent"><i class="fa fa-envelope-o"></i> Sent  </a></li>
                
                
                
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Sent</h3>

              <!--<div class="box-tools pull-right">
                <div class="has-feedback">
                  <input type="text" class="form-control input-sm" placeholder="Search Mail">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
               /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
               
                </button>
               
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                <button type="button" form="deletemessage" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                <!--  <div class="pull-right">
                  1-50/200
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                  </div>
                  
                </div>
               /.pull-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <table id="mailbox" class="table table-hover">
                  <thead>
                      <tr>
                          <td> Action                      </td>
                          <td> To                       </td>
                          <td> Subject                       </td>
                          <td> Date                       </td>
                      </tr>
                  </thead>
                  <tbody>
                  ';
                  $query_sent = mysqli_query($con,"select penerima, x_userclient(penerima_client) as nama_penerima, subject,dibaca,created_date from mail where pengirim=$u[id_user] order by created_date desc limit 15 ");
                  $rowcount = mysqli_num_rows($query_sent);
                  if($rowcount >0){
                  		while($q=mysqli_fetch_assoc($query_sent)){
		                  echo '
		                  <tr>
		                    <td >
		                    	
				                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
				                  
				                  
				                
		                    </td>
		                    
		                    <td class="mailbox-name"><a href="read-mail.html">'.$q['nama_penerima'].'</a></td>
		                    <td class="mailbox-subject"><b>'.$q['subject'].'
		                    </td>
		                    
		                    <td class="mailbox-date">'.$q['created_date'].'</td>
		                  </tr>';
		                 }	
                  }
                  else{
                  		echo '<tr><td>Belum ada Pesan</td></tr>';
                  }                  
                  echo '               
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
             
            </div>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  
  <!-- /.content-wrapper -->';

  break;

  //habis sent

  case "new_message":

  	echo '
  		<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Mailbox
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mailbox</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="main.php?module=mail" class="btn btn-primary btn-block margin-bottom">Back to Inbox</a>

          <div class="box box-solid">
           <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            ';
            $query_inbox = mysqli_query($con,"select pengirim,x_userclient(pengirim) as nama_pengirim,penerima,  subject,dibaca,created_date from mail where penerima=$u[id_user] and dibaca='N' order by created_date desc limit 15 ");
                  $rowcount = mysqli_num_rows($query_inbox);

            echo '
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="main.php?module=mail"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right">'.$rowcount.'</span></a>
              	</li>
                <li><a href="main.php?module=mail&act=sent"><i class="fa fa-envelope-o"></i> Sent  </a></li>
                
                
                
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Compose New Message</h3>
            </div>
            <!-- /.box-header -->

            <form method="post" action="'.$aksi.'?module=mail&act=create"  autocomplete="off" enctype="multipart/form-data">
            <div class="box-body">
              <div class="form-group">
							
							
								<select name="penerima" id="id_kustomerin" data-placeholder="To..." class="form-control select2" required>';
								$result = mysqli_query($con,"select id_user,fullname,x_namakustomer(id_kustomer) as nama_perusahaan,email from user_client where blocked='N' order by fullname");
								echo '<option value="">Select...</option>';
								while ($row = mysqli_fetch_assoc($result)) {
									echo '<option '.($row['id_user']== $_GET['penerima'] ? 'selected' : '' ).' value="' . $row['id_user'] . '">' . $row['fullname'] .', '. $row['nama_perusahaan'].' / '.$row['email'].'</option>';
								}
								echo'
								</select>
								
							
						</div>
              <div class="form-group">
                <input class="form-control" placeholder="Subject:" name="subject" value="'.$_GET['reply_subject'].'">
              </div>
              <div class="form-group">
                  ';
                  if ($_GET['reply_subject']){
                      echo '
                    <textarea id="compose-textarea" class="form-control" name="pesan" style="height: 300px">
                       Dear Customer, <br><br>
                       Terima Kasih atas email nya.<br><br>                       
                       Status AWB tersebut adalah...<br><br>

                       Terima Kasih<br>
                       '.$u['namalengkap'].'

                    </textarea>';


                  }else{
                     echo '
                    <textarea id="compose-textarea" class="form-control" name="pesan" style="height: 300px">
                       Dear Customer, <br><br>
                       Terima Kasih atas email nya.<br><br>                       
                       
                       ........<br><br>

                       Terima Kasih<br>
                       '.$u['namalengkap'].'<br>'
                        .$u['username'].
                       '

                    </textarea>';
                  }

                  echo '
                    <textarea id="compose-textarea" class="form-control" name="pesan" style="height: 300px"></textarea>';

                    echo '
              </div>
              ';
              /*<div class="form-group">
                <div class="btn btn-default btn-file">
                  <i class="fa fa-paperclip"></i> Attachment
                  <input type="file" name="attachment">
                </div>
                <p class="help-block">Max. 2MB</p>
              </div>*/
              echo '
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-right">
                
                <button type="submit" id="sendbutton" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
              </div>
              
            </div>
            </form>
            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  	';
  break;

  case "read":

  $query_read = mysqli_query($con,"select a.*,x_namakustomer(b.id_kustomer) as namakustomer,b.fullname,b.email from mail a left join user_client b on a.pengirim_client=b.id_user where id=".$_GET['id']." ");
  $r = mysqli_fetch_assoc($query_read);

  //echo "select a.*,x_namakustomer(b.id_kustomer) as namakustomer from mail a left join user_client b on a.pengirim_client=b.id_user where id=".$_GET['id']." ";

    echo '
      <section class="content-header">
      <h1>
        Mailbox
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mailbox</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="compose.html" class="btn btn-primary btn-block margin-bottom">Compose</a>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            ';
            $query_in = mysqli_query($con,"select pengirim,x_userclient(pengirim) as nama_pengirim,penerima,  subject,dibaca,created_date from mail where penerima=9999 and dibaca='N' order by created_date desc limit 15 ");
                  $rowcount = mysqli_num_rows($query_in);

            echo '
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="main.php?module=mail"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right">'.$rowcount.'</span></a>
                </li>
                <li><a href="main.php?module=mail&act=sent"><i class="fa fa-envelope-o"></i> Sent  </a></li>
                
                
                
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
         
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Read Mail</h3>

              <div class="box-tools pull-right">
                
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-read-info">
                <h3>'.$r['subject'].'</h3>
                <h5>'.$r['email'].' ('.$r['fullname'].', '.$r['namakustomer'].')
                  <span class="mailbox-read-time pull-right">'.$r['created_date'].'</span></h5>
              </div>
              <!-- /.mailbox-read-info -->
              <div class="mailbox-controls with-border text-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Delete">
                    <i class="fa fa-trash-o"></i></button>
                  <a href="main.php?module=mail&act=new_message&reply_subject=RE: '.$r['subject'].'&penerima='.$r['pengirim_client'].'"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button></a>
                  
                </div>
                <!-- /.btn-group -->
                
              </div>
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
                '.$r['pesan'].'

                

                
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->';
            /*
            <div class="box-footer">
              <ul class="mailbox-attachments clearfix">
                <li>
                  <span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> Sep2014-report.pdf</a>
                        <span class="mailbox-attachment-size">
                          1,245 KB
                          <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
                <li>
                  <span class="mailbox-attachment-icon"><i class="fa fa-file-word-o"></i></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> App Description.docx</a>
                        <span class="mailbox-attachment-size">
                          1,245 KB
                          <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
                <li>
                  <span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo1.png" alt="Attachment"></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> photo1.png</a>
                        <span class="mailbox-attachment-size">
                          2.67 MB
                          <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
                <li>
                  <span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo2.png" alt="Attachment"></span>

                  <div class="mailbox-attachment-info">
                    <a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> photo2.png</a>
                        <span class="mailbox-attachment-size">
                          1.9 MB
                          <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                        </span>
                  </div>
                </li>
              </ul>
            </div>*/
            echo '
            <!-- /.box-footer -->
            <div class="box-footer">
              <div class="pull-right">
                <a href="main.php?module=mail&act=new_message&reply_subject=RE: '.$r['subject'].'&penerima='.$r['pengirim_client'].'"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button></a>
                
              </div>
              <button type="button" class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</button>
              
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    ';

  break;
  //habis new messege

	}

}
?>