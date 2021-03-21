<!--

=========================================================
* Now UI Dashboard - v1.5.0
=========================================================

* Product Page: https://www.creative-tim.com/product/now-ui-dashboard
* Copyright 2019 Creative Tim (http://www.creative-tim.com)

* Designed by www.invisionapp.com Coded by www.creative-tim.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{env('STORAGE_PATH')}}assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="{{env('STORAGE_PATH')}}assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    ROYAL APP - ADMIN PORTAL
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <!-- CSS Files -->
  <link href="{{env('STORAGE_PATH')}}assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="{{env('STORAGE_PATH')}}assets/css/now-ui-dashboard.css?v=1.5.0" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="{{env('STORAGE_PATH')}}assets/demo/demo.css" rel="stylesheet" />
</head>

<body class="login-layout">
    @yield('content')
  <!--   Core JS Files   -->
  <script src="{{env('STORAGE_PATH')}}assets/js/core/jquery.min.js"></script>
  <script src="{{env('STORAGE_PATH')}}assets/js/core/popper.min.js"></script>
  <script src="{{env('STORAGE_PATH')}}assets/js/core/bootstrap.min.js"></script>
  <script src="{{env('STORAGE_PATH')}}assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chart JS -->
  <script src="{{env('STORAGE_PATH')}}assets/js/plugins/chartjs.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="{{env('STORAGE_PATH')}}assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{env('STORAGE_PATH')}}assets/js/now-ui-dashboard.min.js?v=1.5.0" type="text/javascript"></script><!-- Now Ui Dashboard DEMO methods, don't include it in your project! -->
  <script src="{{env('STORAGE_PATH')}}assets/demo/demo.js"></script>
</body>

</html>