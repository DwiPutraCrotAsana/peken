<style type="text/css">
  .table thead,
.table th {text-align: center;}
</style>
</head>

<body class="skin-blue">
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">

            <a href="#" class="logo">
            
            <b>         EE</b>IS</a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->

                        <!-- Notifications: style can be found in dropdown.less -->

                           <li class="dropdown tasks-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i style="font-size: 18px;" class="fa fa-sliders"></i>
                                <?= $this->session->userdata('role_aktif') ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header" align="center" >Pilih Role..</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                    <?php
                                          foreach($this->session->userdata('role') as $role) {  
                                        ?>
                                        <li><!-- Task item -->
                                        
                                            <a href="#">
                                                <h3>
                                                   <li href=""  class="change" idK="<?php echo $role->id_role; ?>" ><strong><?php echo $role->nama_role ?> </strong></li> 
                                                   
                                                </h3>
                                                
                                            </a>
                                        </li><!-- end task item -->
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </li>
                                
                            </ul>
                        </li>
                        <!-- Tasks: style can be found in dropdown.less -->
                     
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/user.png') ?>" class="user-image" alt="User Image"/>
                                <span class="hidden-xs"> <?= $this->session->userdata('Nama_user') ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?php echo base_url('assets/AdminLTE-2.0.5/dist/img/user.png') ?>" class="img-circle" alt="User Image" />
                                    <p>
                                       <?= $this->session->userdata('Username') ?>
                                        
                                    </p>
                                </li>
                                <!-- Menu Body -->
                              
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?php echo base_url('index.php/Rbac/index'); ?>" class="btn btn-default btn-flat">Halaman Utama</a>
                                    </div>
                                    
                                    <div class="pull-right">
                                        <a href="<?php echo base_url('index.php/Login/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!--tambahkan custom js disini-->



<!-- jQuery 2.1.3 -->










        <!-- =============================================== -->