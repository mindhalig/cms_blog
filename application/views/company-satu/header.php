<header id="header" class="header-no-border-bottom" data-plugin-options="{'stickyEnabled': true, 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': true, 'stickyStartAt': 140, 'stickySetTop': '-140px', 'stickyChangeLogo': false}">
    <div class="header-body">
        <div class="header-container container">
            <div class="header-row">
                <div class="header-column">
                    <div class="header-logo"> 
                    
						<?php 
							echo "Kontak Dhalig" ;
						?>
                            <font color="red"><i class="fa fa-code"></i></font><br>
                            <marquee scrollamount="3"><font color="red"><b> IT Software And Development </b></font></marquee></br>                           
                           
						
                    </div>
                </div>
				<div class="header-column">
                    <div class="header-search">
                        <form id="searchForm" action="<?php echo base_url(); ?>berita/index/" method="POST">
                            <div class="input-group">
                                <input type="text" class="form-control" name="kata" placeholder="Search..." required="" aria-required="true">
                                <span class="input-group-btn">
                                    <button name="cari" class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="header-container header-nav header-nav-center header-nav-bar header-nav-bar-primary">

            <button class="btn header-btn-collapse-nav" data-toggle="collapse" data-target=".header-nav-main">
                <i class="fa fa-bars"></i>
            </button>
            <div class="header-nav-main header-nav-main-light header-nav-main-effect-1 header-nav-main-sub-effect-1 collapse" style="padding-top:3px;">
                <nav>
                    <ul class="nav nav-pills" id="mainNav">
                        <li class="">
                            <a href="<?php echo base_url(); ?>">
                                <i class="fa fa-home" style="font-size:25px;"></i>
                            </a>
                        </li>
						        <?php include "main_menu.php"; ?>
                        <li>
                                                                              
                                <a href="http://localhost/sekartama/#produk" class="hvr-rectangle-out scroll">Apps</a>
                        </li>
                        <li>
                                <a href="http://localhost/sekartama/#tentang" class="hvr-rectangle-out scroll">Hubungi Kami</a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
    </div>
</header>