 <?php 
	$iden = $this->Model_utama->view_where('identitas',array('id_identitas' => 1))->row_array();
	$alamat = $this->Model_utama->view_where('mod_alamat',array('id_alamat' => 1))->row_array();
	$link = $this->Model_utama->view('pasangiklan');
?>
    <div class="container">
        <div class="row">
           
            <div class="col-md-4">
               <h5 class="mb-sm">LOKASI</h5>
            	<iframe width="100%" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="<?php echo "$iden[maps]"; ?>"></iframe>

            </div>
			<div class="col-md-4">
                <h5 class="mb-sm" style="padding-left:15px;">LINK TERKAIT</h5>
				<ul class="list list-icons list-icons-sm" style="padding-left:15px;">
					<?php
						foreach($link->result_array() as $row){
							echo "<li><i class='fa fa-caret-right'></i> <a href='$row[url]'>$row[judul]</a></li>";
						}
					?>
					
				</ul>
				
                
            </div>
			<div class="col-md-4">
                <h5 class="mb-sm">Contact Us</h5>
                <span class="phone"><i class="fa fa-phone"></i>   <?php echo $iden['no_telp']; ?></span>
                <?php
				echo $alamat["alamat"];
				?>
                
                <ul class="social-icons mt-xl">
                        <li>
                            <a class="sc-1" href="https://facebook.com/mindh.killer.anthem" target="_blank"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li>
                            <a class="sc-2" href="https://twitter.com/@l_gami" target="_blank"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li>
                            <a class="sc-11" href="https://instagram.com/l_gami" target="_blank"><i class="fa fa-instagram"></i></a>
                        </li>
                        <li>
                            <a class="sc-4" href="https://github.com/mindhalig" target="_blank"><i class="fa fa-github"></i></a>
                        </li>
                        <li>
                            <a class="sc-4" href="https://www.linkedin.com/in/mindhalig" target="_blank"><i class="fa fa-linkedin"></i></a>
                        </li>
                </ul>

            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-11">
                    <p>Di buat dengan <span style="color: #e25555;">&#9829;</span> di kendal © <?php echo Date('Y'); ?>  </p>
                   
                </div>
            </div>
        </div>
    </div>
