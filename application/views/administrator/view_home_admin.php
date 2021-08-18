  <a style='color:#000' href='<?php echo base_url(); ?>administrator/listberita'>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-aqua"><i class="fa fa-book"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Berita</span>
        <?php $jmla = $this->Model_app->view('berita')->num_rows(); ?>
        <span class="info-box-number"><?php echo $jmla; ?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  </a>

  <a style='color:#000' href='<?php echo base_url(); ?>administrator/halamanbaru'>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-green"><i class="fa fa-file"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Halaman</span>
        <?php $jmlb = $this->Model_app->view('halamanstatis')->num_rows(); ?>
        <span class="info-box-number"><?php echo $jmlb; ?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  </a>

  <a style='color:#000' href='<?php echo base_url(); ?>administrator/agenda'>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-yellow"><i class="fa fa-files-o"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Event</span>
        <?php $jmlc = $this->Model_app->view('agenda')->num_rows(); ?>
        <span class="info-box-number"><?php echo $jmlc; ?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  </a>

  <a style='color:#000' href='<?php echo base_url(); ?>administrator/manajemenuser'>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Users</span>
        <?php $jmld = $this->Model_app->view('users')->num_rows(); ?>
        <span class="info-box-number"><?php echo $jmld; ?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  </a>

<section class="col-lg-7 connectedSortable">

  <div class='box'>
    <div class='box-header'>
      <h3 class='box-title'>Application Buttons</h3>
    </div>
    <div class='box-body'>
      <p>Silahkan klik menu pilihan yang berada di sebelah kiri untuk mengelola konten website anda 
          atau pilih ikon-ikon pada Control Panel di bawah ini : </p>
      <a href="<?php echo base_url(); ?>administrator/identitaswebsite" class='btn btn-app'><i class='fa fa-th'></i> Identitas</a>
      <a href="<?php echo base_url(); ?>administrator/menuwebsite" class='btn btn-app'><i class='fa fa-th-large'></i> Menu</a>
      <a href="<?php echo base_url(); ?>administrator/halamanbaru" class='btn btn-app'><i class='fa fa-file-text'></i> Halaman</a>
      <a href="<?php echo base_url(); ?>administrator/listberita" class='btn btn-app'><i class='fa fa-television'></i> Berita</a>
      <a href="<?php echo base_url(); ?>administrator/kategoriberita" class='btn btn-app'><i class='fa fa-bars'></i> Kategori</a>
      <a href="<?php echo base_url(); ?>administrator/tagberita" class='btn btn-app'><i class='fa fa-tag'></i> Tag Berita</a>
      <a href="<?php echo base_url(); ?>administrator/banner" class='btn btn-app'><i class='fa fa-file-image-o'></i> banner slide</a>
      <a href="<?php echo base_url(); ?>administrator/iklansidebar" class='btn btn-app'><i class='fa fa-file-image-o'></i> link bawah</a>
      <a href="<?php echo base_url(); ?>administrator/linkmenu" class='btn btn-app'><i class='fa fa-file-image-o'></i> link tengah</a>
      <a href="<?php echo base_url(); ?>administrator/templatewebsite" class='btn btn-app'><i class='fa fa-file'></i> Template</a>
      <a href="<?php echo base_url(); ?>administrator/agenda" class='btn btn-app'><i class='fa fa-calendar-minus-o'></i> Info Event</a>   
      <a href="<?php echo base_url(); ?>administrator/alamat" class='btn btn-app'><i class='fa fa-bed'></i> Alamat</a>
      <a href="<?php echo base_url(); ?>administrator/manajemenuser" class='btn btn-app'><i class='fa fa-users'></i> Users</a>
      <a href="<?php echo base_url(); ?>administrator/testimoni" class='btn btn-app'><i class='fa fa-users'></i> Testimoni</a>
    </div>
  </div>
</section><!-- /.Left col -->

<section class="col-lg-5 connectedSortable">
    <?php include "grafik.php"; ?>
</section><!-- right col -->
