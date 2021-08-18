

<div style="margin-bottom:80px">
	<?php include "slide.php"; ?>
</div><!-- /.container -->


<div class="container">
<div class="row">
<div class="col-md-12 center">
<div class="heading heading-border heading-middle-border heading-middle-border-center" id="produk">
<?php
$parentlinksatu = $this->Model_utama->view_where_ordering_limit('link', array('id_parent' => 0, 'groupname' => 'satu'), 'id_link',  'DESC', 0,1);
	foreach ($parentlinksatu->result_array() as $x) {
		$parent1 = $x['id_link'];
		echo "<h1><strong>$x[nama_menu]</strong></h1>";
	}
?>

</div>
</div>
</div>
<div class="row mt-md mb-xl">
<?php
$linksatu = $this->Model_utama->view_where_ordering_limit('link', array('id_parent' => $parent1), 'id_link',  'ASC', 0,3);
	foreach ($linksatu->result_array() as $x1) {
	?>
	<div class="col-md-4">
	<div class="thumb-info custom-thumb-info-4">
	<div class="thumb-info-wrapper" loading="lazy"><img src="<?php echo base_url() . "asset/foto_link/" . $x1['gambar'];?>" loading="lazy" class="img-responsive" /></div>
	<div class="thumb-info-caption custom-box-shadow">
	<div class="thumb-info-caption-text">
	<h4 class="text-center"><a href="<?php echo $x1['link']; ?>" class="text-color-dark"> <?php echo $x1['nama_menu']; ?></a></h4>
	<p class="justify"><?php echo $x1['deskripsi']; ?></p>
	</div>
	</div>
	</div>
	</div>
	<?php		
	}
?>



</div>
</div>
<section class="section m-none">
<div class="container" id="tentang">
	<div class="row">
		<div class="col-md-12 center">
			<h4>Bila ada yang ingin ditanyakan dulu atau ingin di diskusikan</h4>
			<h2>Silakan Hubungi <strong>Kami</strong></h2>
		</div>
	</div>
	<div class="featured-boxes featured-boxes-flat">
		<div class="row">
			<div class="col-md-3 col-sm-6">
				<div class="featured-box featured-box-primary featured-box-effect-2" style="height: 206.2px;">
					<div class="box-content">
						<i class="icon-featured fa fa-envelope"></i>
						<h4>Email</h4>
						<a href="mailto:kontak.dhalig@gmail.com" class="btn btn-tertiary mr-xs mb-lg">Klik Disini</a>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6">
				<div class="featured-box featured-box-secondary featured-box-effect-2" style="height: 206.2px;">
					<div class="box-content">
						<i class="icon-featured fa fa-whatsapp"></i>
						<h4>whatsapp</h4>
						<a href="https://api.whatsapp.com/send?phone=6282324283418&text=Hi.. Saya ingin bertanya tentang" class="btn btn-tertiary mr-xs mb-lg">Klik Disini</a>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6">
				<div class="featured-box featured-box-tertiary featured-box-effect-2" style="height: 206.2px;">
					<div class="box-content">
						<i class="icon-featured fa fa-telegram"></i>
						<h4>Telegram</h4>
						<a href="https://t.me/L_gami" class="btn btn-tertiary mr-xs mb-lg">Klik Disini</a>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6">
				<div class="featured-box featured-box-quaternary featured-box-effect-2" style="height: 206.2px;">
					<div class="box-content">
						<i class="icon-featured fa fa-facebook"></i>
						<h4>Facebook</h4>
						<a href="https://facebook.com/mindh.killer.anthem" class="btn btn-tertiary mr-xs mb-lg">Klik Disini</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
<section class="section section-no-background m-none">
	<div class="container">
		<div class="row">
			

		<div class="col-md-4" data-wow-delay=".4s">
			<h2 class="mb-lg">Info <strong>Event</strong> Teknologi</h2>
			<?php
			 foreach ($agenda->result_array() as $h) {
			?>
					<div class="recent-posts">
						<article class="post">
							<div class="post-meta">
								<span><i class="fa fa-calendar"></i>
								<?php $tgl_mulai   = tgl_indo($h['tgl_mulai']);
									  $tgl_posting = tgl_indo($h['tgl_posting']);
								?>
								<strong>Posting</strong> <?php echo $tgl_posting?>
								<br>(acara dimulai <?php echo $tgl_mulai ?>)</span>
							</div>
							<h5><a href="<?php echo base_url() . "agenda/detail/" . $h['tema_seo']; ?>"><?php echo $h['tema']; ?></a></h5>
						</article>
					</div>
			<?php
			 }
			?>
		</div>
										

		<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 welcome padding-left-none padding-bottom-40 scroll_effect fadeInUp">
			<h2 class="margin-bottom-25 margin-top-none"><strong>Reviews</strong></h2>
			<div id="myCarousel" class="carousel slide" data-ride="carousel">
				<ol class="carousel-indicators">
				<?php
				$bannerhome = $this->Model_utama->view_ordering_limit('testimoni','id_testimoni','ASC',0,10);         
				$no = 0;
				foreach ($bannerhome->result_array() as $ban1){ 
					if($no == 0){
					?>
						<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
					<?php
					}else{
					?>
						<li data-target="#myCarousel" data-slide-to="<?php echo $no; ?>" class=""></li>
					<?php
					}
					$no++;
				}
				?>
				</ol>
				<!-- Wrapper for slides -->
				<div class="carousel-inner">
				<?php
				$bintang = "<i class ='fa fa-star'></i>";
				
				$no = 0;				
				foreach ($bannerhome->result_array() as $ban2){ 
					if($no == 0){
					?>
						 <div class="item active">
							 <font color="red"><?php echo $ban2['nama_testimoni'];?></font>
							 (<?php echo $ban2['pekerjaan_testimoni'];?>)<br>
							  <?php for ($i=0;$i<5; $i++){
									echo "<i class ='fa fa-starr fa-star'></i>";
							   }?>
							 <?php echo $ban2['deskripsi_testimoni'];?></div>
					<?php
					
					}else{
					?>
						<div class="item ">
							<font color="red"><?php echo $ban2['nama_testimoni'];?></font>
							(<?php echo $ban2['pekerjaan_testimoni'];?>)<br>
							 <?php for ($i=0;$i<4; $i++){
									echo "<i class ='fa fa-starr fa-star'></i>";
							   }
							echo $bintang;
							echo $ban2['deskripsi_testimoni'];?></div>
					<?php
				
				
					}
					$no++;
					
				}
				
				?>	   
				</div>
				<!-- Left and right controls --> <a class="left carousel-control" href="#myCarousel" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> <span class="sr-only">Previous</span> </a> <a class="right carousel-control" href="#myCarousel" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> <span class="sr-only">Next</span> </a>
			</div>
		</div>
		</div>
	</div>
</section>

<div class="clearfix"></div>

<div class="container">

    <div class="row">
        <div class="col-md-12">
            <h2><strong>LATEST</strong> NEWS</h2>
        </div>
    </div>
    <div class="row mt-lg">
            <?php 
            $pilihan = $this->Model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.aktif' => 'Y','status' => 'Y'),'id_berita','DESC',0,4);
            foreach ($pilihan->result_array() as $row) { 
            $tgl = tgl_indo($row['tanggal']);
             echo "<div class='col-md-3'>";
			 echo"
                <div class='recent-posts mt-md mb-lg'>
                    <article class='post left'>
                        <div class='post-meta'>
                            <span><i class='fa fa-calendar'></i> $tgl </span>
                        </div>
                        <h5><a class='text-dark' href='".base_url()."berita/detail/$row[judul_seo]'>$row[judul]</a></h5>
                    </article>
                </div>
            </div>";                            
                       
            }
        ?>

    </div>

</div>                    <!-- /#myCarousel -->

	
