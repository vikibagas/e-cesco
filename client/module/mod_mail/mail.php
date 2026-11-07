
  <?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['usernamex']) AND empty($_SESSION['passwordx'])){
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
            $query_in = mysqli_query($con,"select pengirim,x_userclient(pengirim) as nama_pengirim,penerima,  subject,dibaca,created_date from mail where penerima_client=$u[id_user] and dibaca='N' order by created_date desc limit 15 ");
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
                  <tbody>
                  ';
                  $query_inbox = mysqli_query($con,"select id,pengirim,x_user(pengirim) as nama_pengirim,penerima,  subject,dibaca,created_date from mail where penerima_client=$u[id_user] order by created_date desc limit 15 ");
                  $count = mysqli_num_rows($query_inbox);
                  
                  if($count >0){
                  		while($q=mysqli_fetch_assoc($query_inbox)){
		                  echo '
		                  <tr  '.($q['dibaca'] == 'N' ? ' class="warning"' : '').'>
		                    <td >
		                    	
				                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
				                  <a href="main.php?module=mail&act=new_message&reply_subject=RE: '.$q['subject'].'&penerima='.$q['pengirim'].'"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button></a>
				                  
				                
		                    </td>
		                    
		                    <td class="mailbox-name"><a href="main.php?module=mail&act=read&id='.$q['id'].'">Customer Care</a></td>
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
            $query_inbox = mysqli_query($con,"select pengirim,x_userclient(pengirim) as nama_pengirim,penerima,  subject,dibaca,created_date from mail where penerima_client=$u[id_user] and dibaca='N' order by created_date desc limit 15 ");
                  $rowcount = mysqli_num_rows($query_inbox);

            echo '
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li ><a href="main.php?module=mail"><i class="fa fa-inbox"></i> Inbox
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
                  <tbody>
                  ';
                  $query_sent = mysqli_query($con,"id,select penerima, x_user(penerima) as nama_penerima, subject,dibaca,created_date from mail where pengirim_client=$u[id_user] order by created_date desc limit 15 ");
                  $rowcount = mysqli_num_rows($query_sent);
                  if($rowcount >0){
                  		while($q=mysqli_fetch_assoc($query_sent)){
		                  echo '
		                  <tr>
		                    <td >
		                    	
				                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
				                  
				                  
				                
		                    </td>
		                    
		                    <td class="mailbox-name"><a href="main.php?module=mail&act=read&id='.$q['id'].'">Customer care</a></td>
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
            $query_inbox = mysqli_query($con,"select pengirim,x_user(pengirim) as nama_pengirim,penerima,  subject,dibaca,created_date from mail where penerima_client=$u[id_user] and dibaca='N' order by created_date desc limit 15 ");
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

            ';
              $query_tanyaawb = mysqli_query($con,"select airwaybill_kode from pengiriman where id_pengiriman =".$_GET['id']." ");
                  $qt = mysqli_fetch_assoc($query_tanyaawb);
            echo '

            <form method="post" action="'.$aksi.'?module=mail&act=create"  autocomplete="off" enctype="multipart/form-data">
            <div class="box-body">
              <div class="form-group">
							 
               <div class="form-group">
               <label >From </label> <small>pastikan email anda sudah benar(kami akan membalas jawaban ke alamat email anda). </small> <a href="main.php?module=profile">ubah email</a>
                <input class="form-control" placeholder="Emailku" name="from" value="'.$u['email'].'" readonly>
              </div>
							 <label >To </label> 
								<select name="penerima" id="id_kustomerin" data-placeholder="To..." class="form-control select2" required >';
								
																	
                  echo '<option '.($row['id_user']== $_GET['penerima'] ? 'selected' : '' ).' value="3"> Customer Service customercare@cescologistics.com </option>';
								
								echo'
								</select>
								
							
						</div>
            <label >Subject </label> 
              <div class="form-group">
                ';
                  if (isset($_GET['reply_subject'])){
                      echo '<input class="form-control" placeholder="Subject:" name="subject" value="'.$_GET['reply_subject'].'">';
                  }
                  elseif(isset($_GET['id'])){
                      echo '<input class="form-control" placeholder="Subject:" name="subject" value="Tanya status paket '.$qt['airwaybill_kode'].'">';
                  }
                
                  echo '
              </div>
              <div class="form-group">
              <label >Message </label> 
                    <textarea id="compose-textarea-client" class="form-control" name="pesan" style="height: 300px">';if(isset($_GET['id'])){
                            echo 'Dear rekan team Cesco Logistics..<br><br> 
                            Saya ingin menanyakan status paket dengan no awb '.$qt['airwaybill_kode'].'<br><br>

                            Terimakasih <br>
                            '.ucwords($u[fullname]).'<br>
                            '.$u['username']



                            ;

                            }else{
                                echo 'Dear rekan team Cesco Logistics..<br><br> 
                            Saya ingin menanyakan........<br><br>

                            Terimakasih <br>
                            '.ucwords($u[fullname]).'<br>
                            '.$u['username']



                            ;                              
                            }

                            echo '
                    </textarea>
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
                
                <button type="submit" class="btn btn-primary" id="sendbutton"><i class="fa fa-envelope-o"></i> Send</button>
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

  //habis new messege

  case "read":

  $query_read = mysqli_query($con,"select * from mail where id=".$_GET['id']." ");
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
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="mailbox.html"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right">12</span></a></li>
                <li><a href="#"><i class="fa fa-envelope-o"></i> Sent</a></li>
                
                
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
                <h5> customercare@cesco-logistics.com (Customer Care Cesco Logistics)
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