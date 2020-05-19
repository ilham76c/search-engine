<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Kelola Berita</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Admin</b>DLH</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>    
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>        
        
        <!-- MENU 'Kelola Konten' -->  
        <li class="active">          
          <a href="#"><i class="fa fa-file-text-o"></i>Kelola Tesaurus</a>            
        </li>                

        <li class="header">LABELS</li>
        <li><a href="<?php echo site_url('dlh/logout');?>"><i class="fa fa-sign-out text-red"></i><span>Log out</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">    
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">                        
            <!-- /.box-header -->
            <div class="box-body">
              <div class="box-header with-border">

                <h3 class="box-title">Table Data Berita</h3>
                <div class="box-tools pull-right">
                
                  <a href="tesaurus/form" class="btn btn-primary btn-sm"><i class="fa fa-plus">&nbsp;</i>Tambah Berita</a>
                </div>
              </div>
             
              <div class="box box-info">

                <div class="box-body">
                  
                  <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead class="">
                      <tr>
                        <th>No</th>
                        <th>Kata</th>
                        <th>Gugus Kata</th>                        
                        <th style="width: 110px;">#</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $no = 1;
                        foreach ($tesaurus as $row) :?>                      
                          <tr>
                            <td><?= $no; ?></td>
                            <td><?= $row->kata; ?></td>                                                        
                            <td><?= $row->gugus_kata; ?></td>
                            <td>
                              <!-- <a href="<?php //echo site_url('admin/form/edit?id='.$row->id.''); ?>">Edit</a>  -->
                              <?=anchor("tesaurus/edit?id=".$row->id,"<i class='fa fa-edit'></i>Edit",array('onclick' => "return confirm('Edit data?')"))?>
                              &nbsp;|&nbsp;
                              <!-- <a href="<?php //echo site_url('admin/hapusBerita/'.$row->id.''); ?>">Delete</a> -->
                              <?=anchor("tesaurus/delete?id=".$row->id,"<i class='fa fa-remove'></i>Delete",array('onclick' => "return confirm('Hapus data?')"))?>
                            </td>
     
                          </tr>                                                    
                      <?php
                        $no++;
                        endforeach; ?>
                    </tbody>
                    
                    <tfoot>
                      <tr>
                        <th>No</th>
                        <th>Kata</th>
                        <th>Gugus Kata</th>                        
                        <th>#</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>            
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.13
    </div>
    <strong>Copyright &copy; 2014-2019 <a href="#">ilham76c</a>.</strong> All rights
    reserved.
  </footer>

  
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>/assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url(); ?>/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(); ?>/assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>/assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>/assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(); ?>/assets/dist/js/demo.js"></script>
<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable(
      {
        'aoColumnDefs': [{
        'bSortable': false,
        'aTargets': [2,3] /* 1st one, start by the right */
        }]
      }
    )
  })
</script>
</body>
</html>
