			<div class="container">
                <div class="row" style="padding-top:15px;"> 
                    <div class="col-md-9">
                        <div class="blog-post single-post">
						<?php 
						foreach ($beritakategori->result_array() as $row) {   
						$tgl = tgl_indo($row['tanggal']);
						$isi_berita = strip_tags($row['isi_berita']); 
						$isi = substr($isi_berita,0,120); 
						$isi = substr($isi_berita,0,strrpos($isi," "));
						?>
                            <article class="post post-medium" style="padding-bottom: 10px;">
								<div class="row">
										<div class="col-md-4">
											
										</div>
										<div class="col-md-7">

											<div class="post-content">
											<span><i class="fa fa-calendar"></i> <?php echo $tgl;?> </span>
												<h4><a href="<?php echo base_url() ?>berita/detail/<?php echo $row['judul_seo']?>"><?php echo $row['judul']; ?></a></h4>
												<p><?php echo $isi;?></p>

											</div>
										</div>
										

									<div class="col-md-11">
										<div class="post-meta">
											
											<a href="<?php echo base_url() ?>berita/detail/<?php echo $row['judul_seo']?>" class="btn btn-xs btn-primary pull-right">Read more...</a>
										</div>
									</div>
								</div>

							</article>
						<?php
						}
						?>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <aside class="siderbar">
							<h4 class="heading-primary">Latest News</h4>
                            <ul class="nav nav-list mb-xlg">
							<?php
							$pilihan = $this->Model_utama->view_join_two('berita','users','kategori','username','id_kategori',array('berita.aktif' => 'Y','status' => 'Y'),'id_berita','DESC',0,8);
							foreach ($pilihan->result_array() as $row) {   
                                    echo "<li><a href='../../berita/detail/$row[judul_seo]'>$row[judul]</a></li>";
                            }
							?>
                            </ul>
                            <hr />
							
                            <h4 class="heading-primary">Categories</h4>
							<ul class="nav nav-list mb-xlg">
								<?php
								foreach ($kategori->result_array() as $kat){
								?>
									<li><a href="../../kategori/detail/<?php echo $kat['kategori_seo']; ?>"><?php echo $kat['nama_kategori']; ?></a></li>
								<?php
								}
								?>
                            </ul>
                            <hr />
                            
                       
                        </aside>
                    </div>
                </div>
            </div>
       



