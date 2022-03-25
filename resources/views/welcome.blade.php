<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8"/>
    <title> Bienvenue sur Com'App || OLYMPE GROUP </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="email" content="#"/>
    <meta name="website" content="#"/>
    <link rel="shortcut icon" href="fontend/images/favicon.ico">
    <!-- Bootstrap -->
    <link href="fontend/css/bootstrap.min.css" rel="stylesheet" type="text/css"/> 
    <!-- Main Css -->
    <link href="fontend/css/style.css" rel="stylesheet" type="text/css" id="theme-opt"/>

</head>

<body>

<div id="preloader">
    <div id="status" align="center">
        <img src="fontend/images/logo-light.png" width="150px">
        <div class="spinner">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
        </div>
    </div>
</div>

<!-- Navbar STart -->
<header id="topnav" class="defaultscroll sticky">
    <div class="container">
        <!-- Logo container-->
        <div>
            <a class="logo" href="#">
                <img src="fontend/images/logo-light.png" class="l-dark" height="50" alt="">
                <img src="fontend/images/logo-light.png" class="l-light" height="70" alt="">
            </a>
        </div>
        <div class="buy-button">
        <!--<a href="{{ url('reservation') }}">
            <div class="btn btn-primary login-btn-primary">RESERVATION</div>
                <div class="btn btn-light login-btn-light">RESERVATION</div>
            </a>-->
			
			<?php
                         if (Auth::check()) {?>
                            <a href="{{ url('dashboard') }}">
                                 <div class="btn btn-primary login-btn-primary">Tableau de bord</div>
								 <div class="btn btn-light login-btn-light">Tableau de bord</div>
                            </a>
                           <?php } else {?>
                            <a href="{{ url('connexion') }}">
                                <div class="btn btn-primary login-btn-primary">MON ESPACE</div>
								<div class="btn btn-light login-btn-light">MON ESPACE</div>
                            </a>
                        <?php }?>
           


        </div><!--end login button-->
        <!-- End Logo container-->
        <div class="menu-extras">
            <div class="menu-item">
                <!-- Mobile menu toggle-->
                <a class="navbar-toggle">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </div>
        </div>

        <div id="navigation">

            <div class="buy-menu-btn d-none">
				<?php
                         if (Auth::check()) {?>
                            <a href="{{ url('dashboard') }} "class="btn btn-primary">Tableau de bord</a>
                           <?php } else {?>
                            <a href="{{ url('connexion') }}" class="btn btn-primary">MON ESPACE </a>
                        <?php }?>
               
            </div><!--end login button-->
        </div><!--end navigation-->
    </div><!--end container-->
</header><!--end header-->
<!-- Navbar End -->

<!-- Hero Start -->
<section class="bg-half-170 pb-0 bg-primary d-table w-100"
         style="background: url('fontend/images/bg2.png') center center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-6">
                <div class="title-heading">
                    <h4 class="text-white-50">OLYMPE GROUP</h4>
                    <h4 class="heading text-white title-dark">Mieux que le meilleur</h4> 
                    <div class="mt-4 pt-2">
                        <a href="#" target="blank" class="btn btn-light">Nos offres</a>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-lg-5 col-md-6 mt-5 mt-sm-0">
                <img src="fontend/images/logo-simple.png" class="img-fluid" alt="">
            </div>
        </div><!--end row-->
    </div> <!--end container-->
</section><!--end section-->
<!-- Hero End -->

<!-- Partners start -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">

        </div><!--end col-->
    </div><!--end row-->
</section><!--end section-->
<!-- Partners End -->
 
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <h4 class="title mb-4">Les Services de <span class="text-primary font-weight-bold">OLYMPE GROUP</span>
                    </h4>
                    <p class="text-muted para-desc mb-0 mx-auto">Informatiques, Electroniques, Import et Export, divers ...
                        <br> <span class="text-primary font-weight-bold"></span> Nous développons pour vous, des prestations dédiées à tout type de projet.</p>
                </div>
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-3 col-md-4 mt-4 pt-2">
                <div
                    class="card features fea-primary rounded p-4 bg-light text-center position-relative overflow-hidden border-0">
                            <span class="h1 icon2 text-primary">
                                <img src="fontend/images/cpu.svg" class="iconini" alt="">
                                <!--<i class="uil uil-chart-line"></i>-->
                            </span>
                    <div class="card-body p-0 content">
                        <h5>Electroniques</h5>
                        <p class="para text-muted mb-0">Services Electroniques.</p>
                    </div>
                    <span class="big-icon text-center">
                                <i class="uil uil-chart-line"></i>
                            </span>
                </div>
            </div><!--end col-->

            <div class="col-lg-3 col-md-4 mt-4 pt-2">
                <div
                    class="card features fea-primary rounded p-4 bg-light text-center position-relative overflow-hidden border-0">
                            <span class="h1 icon2 text-primary">
                                  <img src="fontend/images/laptop.svg" class="iconini" alt="">
                            </span>
                    <div class="card-body p-0 content">
                        <h5>Informatiques </h5>
                        <p class="para text-muted mb-0">Services Informatiques. </p>
                    </div>
                    <span class="big-icon text-center">
                                <i class="uil uil-crosshairs"></i>
                            </span>
                </div>
            </div><!--end col-->

            <div class="col-lg-3 col-md-4 mt-4 pt-2">
                <div
                    class="card features fea-primary rounded p-4 bg-light text-center position-relative overflow-hidden border-0">
                            <span class="h1 icon2 text-primary">
                                <img src="fontend/images/data-transfer.svg" class="iconini" alt="">
                            </span>
                    <div class="card-body p-0 content">
                        <h5>Import et Export</h5>
                        <p class="para text-muted mb-0">Services d'Import et Export.</p>
                    </div>
                    <span class="big-icon text-center">
                                <i class="uil uil-airplay"></i>
                            </span>
                </div>
            </div><!--end col-->

            <div class="col-lg-3 col-md-4 mt-4 pt-2">
                <div
                    class="card features fea-primary rounded p-4 bg-light text-center position-relative overflow-hidden border-0">
                            <span class="h1 icon2 text-primary">
                                <img src="fontend/images/idea.svg" class="iconini" alt="">
                            </span>
                    <div class="card-body p-0 content">
                        <h5>Divers</h5>
                        <p class="para text-muted mb-0">Autres services..</p>
                    </div>
                    <span class="big-icon text-center">
                                <i class="uil uil-rocket"></i>
                            </span>
                </div>
            </div><!--end col-->
 
        </div><!--end row-->
        <br> <br> 
    </div><!--end container-->  


<!-- Footer Start -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-12 mb-0 mb-md-4 pb-0 pb-md-2">
                <a href="#" class="logo-footer">
                    <img src="fontend/images/logo-light.png" height="100" alt="">
                </a> 
                <p>YOPOUGON CITE-VERTE, VILLA 647<br>
                    Boite postale : 18 BP 2547 ABIDJAN 18<br>
                    N° RCCM : CI-ABJ-2020-B-14351<br>
                    N° CC : 2043334 Y<br>
                    Tél. : <a href="tel:+(225) 07 07 67 86 86">+(225) 07 07 67 86 86</a><br>
                    Email :&nbsp;<a href="mailto:info@olympegroup.ci">info@olympegroup.ci</a></p>
                <ul class="list-unstyled social-icon social mb-0 mt-4">
                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i data-feather="facebook"
                                                                                                 class="fea icon-sm fea-social"></i></a>
                    </li>
                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i
                                data-feather="instagram" class="fea icon-sm fea-social"></i></a></li>
                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i data-feather="twitter"
                                                                                                 class="fea icon-sm fea-social"></i></a>
                    </li>
                    <li class="list-inline-item"><a href="javascript:void(0)" class="rounded"><i data-feather="linkedin"
                                                                                                 class="fea icon-sm fea-social"></i></a>
                    </li>
                </ul><!--end icon-->
            </div><!--end col-->

            <div class="col-lg-4 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <h4 class="text-light footer-head">Nos prestations</h4>
                <ul class="list-unstyled footer-list mt-4">
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right mr-1"></i> Prestations et Services Electroniques</a></li>
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right mr-1"></i> Prestations et Services Informatiques </a>  </li>
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right mr-1"></i> Import et Export</a> </li>
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right mr-1"></i> Divers</a></li>
                </ul>
            </div><!--end col-->

             

            <div class="col-lg-4 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <h4 class="text-light footer-head"> Abonnez Vous</h4>
                <p class="mt-4">Recevez nos annonces .</p>
                <form>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="foot-subscribe form-group position-relative">
                                <label>Écrivez votre email <span class="text-danger">*</span></label>
                                <i data-feather="mail" class="fea icon-sm icons"></i>
                                <input type="email" name="email" id="emailsubscribe" class="form-control pl-5 rounded"
                                       placeholder="Votre email : " required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <input type="submit" id="submitsubscribe" name="send" class="btn btn-soft-primary btn-block"
                                   value="Valider">
                        </div>
                    </div>
                </form>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</footer><!--end footer-->
<footer class="footer footer-bar">
    <div class="container text-center">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="text-sm-left">
                    <p class="mb-0">© 2021 Com'App - OLYMPE GROUP - tous droits réservés.</p>
                </div>
            </div><!--end col-->

            
        </div><!--end row-->
    </div><!--end container-->
</footer><!--end footer-->
<!-- Footer End -->


<!-- Back to top -->
<a href="#" class="btn btn-icon btn-soft-primary back-to-top"><i data-feather="arrow-up" class="icons"></i></a>
<!-- Back to top -->


<!-- javascript -->
<script src="fontend/js/jquery-3.5.1.min.js"></script>
<script src="fontend/js/bootstrap.bundle.min.js"></script>
<!-- Icons -->
<script src="fontend/js/feather.min.js"></script>
<!-- Main Js -->
<script src="fontend/js/app.js"></script>
</body>
</html>
