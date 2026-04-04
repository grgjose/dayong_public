<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Dayong Providers Inc. | {{ $header_title }}</title>
    <!-- Site Icon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/logo.ico') }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('admin_lte/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('admin_lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('admin_lte/dist/css/adminlte.min.css')}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin_lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css'); }}">
    <link rel="stylesheet" href="{{ asset('admin_lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css'); }}">
    <link rel="stylesheet" href="{{ asset('admin_lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css'); }}">
    <!-- Toast -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
    <!-- Chosen (For Select Multiple UI) -->
    <link rel="stylesheet" href="{{ asset('admin_lte/chosen/chosen.css'); }}">
    <link rel="stylesheet" href="{{ asset('admin_lte/chosen/chosen.min.css'); }}">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css'); }}">

    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
  </head>

  <style>
    
    .fill {
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden
    }

    .fill img {
      flex-shrink: 0;
      min-width: 100%;
      min-height: 100%
    }

    .chosen-container-single .chosen-single {
        border-radius: 3px;
        height: calc(2rem + 2px);
        padding-top: 5px;
        font-size: 15px;
    }

    #container3 {
      height: 500px;
      min-width: 310px;
      max-width: 800px;
      margin: 0 auto;
    }

    .loading {
      margin-top: 10em;
      text-align: center;
      color: gray;
    }
    
  </style>

  <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

    <div class="wrapper">

      <!-- Preloader -->
      @isset($greet_icon)
        <div class="preloader flex-column justify-content-center align-items-center">
          <img class="animation__wobble" src="{{asset('img/logo.png')}}" alt="Logo" height="120" width="120">
        </div>
      @endisset

      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="/dashboard" class="nav-link">Home</a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="/profile" class="nav-link">My Profile</a>
          </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <!-- Navbar Search>
          <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
              <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
              <form class="form-inline">
                <div class="input-group input-group-sm">
                  <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                  <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                      <i class="fas fa-search"></i>
                    </button>
                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </li -->

          <!-- Messages Dropdown Menu -->
          <li class="nav-item dropdown" style="display: none;">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="far fa-comments"></i>
              <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <a href="#" class="dropdown-item">
                <!-- Message Start -->
                <div class="media">
                  <img src="{{asset('admin_lte/dist/img/user1-128x128.jpg')}}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                  <div class="media-body">
                    <h3 class="dropdown-item-title">
                      Brad Diesel
                      <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                    </h3>
                    <p class="text-sm">Call me whenever you can...</p>
                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                  </div>
                </div>
                <!-- Message End -->
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <!-- Message Start -->
                <div class="media">
                  <img src="{{asset('admin_lte/dist/img/user8-128x128.jpg')}}" alt="User Avatar" class="img-size-50 img-circle mr-3">
                  <div class="media-body">
                    <h3 class="dropdown-item-title">
                      John Pierce
                      <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                    </h3>
                    <p class="text-sm">I got your message bro</p>
                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                  </div>
                </div>
                <!-- Message End -->
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <!-- Message Start -->
                <div class="media">
                  <img src="{{asset('admin_lte/dist/img/user3-128x128.jpg')}}" alt="User Avatar" class="img-size-50 img-circle mr-3">
                  <div class="media-body">
                    <h3 class="dropdown-item-title">
                      Nora Silvester
                      <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                    </h3>
                    <p class="text-sm">The subject goes here</p>
                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                  </div>
                </div>
                <!-- Message End -->
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
          </li>
          <!-- Notifications Dropdown Menu -->
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="far fa-bell"></i>
              <!-- span class="badge badge-warning navbar-badge">15</span -->
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <span class="dropdown-item dropdown-header">0 Notifications</span>
              <div class="dropdown-divider"></div>
              <!-- a href="#" class="dropdown-item">
                <i class="fas fa-envelope mr-2"></i> 4 new messages
                <span class="float-right text-muted text-sm">3 mins</span>
              </a -->
              <!-- div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="fas fa-users mr-2"></i> 8 friend requests
                <span class="float-right text-muted text-sm">12 hours</span>
              </a-->
              <!-- div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="fas fa-file mr-2"></i> 3 new reports
                <span class="float-right text-muted text-sm">2 days</span>
              </a -->
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
              <i class="fas fa-expand-arrows-alt"></i>
            </a>
          </li>
          <!--li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
              <i class="fas fa-th-large"></i>
            </a>
          </li-->
        </ul>
      </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="/" class="brand-link">
          <img src="{{asset('img/logo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
          <span class="brand-text font-weight-light">DAYONG APP</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image fill">
              <img src="{{asset('storage/profile_pic/'.$my_user["profile_pic"])}}" 
                   onerror="this.onerror=null;this.src='{{ asset('storage/profile_pic/default.jpg') }}';"
                   class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
              <a href="/profile" class="d-block">{{ $my_user->fname.' '.$my_user->lname; }}</a>
            </div>
          </div>

          <!-- SidebarSearch Form -->
          <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
              <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-sidebar">
                  <i class="fas fa-search fa-fw"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <!-- Add icons to the links using the .nav-icon class
                  with font-awesome or any other icon font library -->
              <li class="nav-item">
                <a href="/dashboard" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>
                    Dashboard
                    <!-- span class="badge badge-info right">2</!-->
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/entries" class="nav-link">
                  <i class="nav-icon fas fa-coins"></i>
                  <p>
                    Collection
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/new-sales" class="nav-link">
                  <i class="nav-icon fas fa-comment-dollar"></i>
                  <p>
                    New Sales
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/members" class="nav-link">
                  <i class="nav-icon fas fa-users-cog"></i>
                  <p>
                    Members
                  </p>
                </a>
              </li>
              <!-- li class="nav-item">
                <a href="/audit" class="nav-link disabled">
                  <i class="nav-icon fas fa-book"></i>
                  <p>
                    Audit (Disabled Temporarily)
                  </p>
                </a>
              </li -->
              <li class="nav-item">
                <a href="/fidelity" class="nav-link">
                  <i class="nav-icon fas fa-hand-holding-usd"></i>
                  <p>
                    Fidelity
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/reports" class="nav-link">
                  <i class="nav-icon fas fa-flag"></i>
                  <p>
                    Reports
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/attendance" class="nav-link">
                  <i class="nav-icon fas fa-address-book"></i>
                  <p>
                    Attendance Tracker
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link" onclick="$('#logoutForm').submit();">
                  <i class="nav-icon fas fa-power-off"></i>
                  <p>
                    Logout
                  </p>
                </a>
              </li>
              <form action="/logout" method="post" id="logoutForm" style="display: none;">
                @csrf
              </form>
              @if($my_user->usertype == 1)
                <li class="nav-header">ADDITIONAL SETTINGS</li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-cogs"></i>
                    <p>
                      SETTINGS
                      <i class="fas fa-angle-left right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item tab-2">
                      <a href="/branch" class="nav-link">
                        <i class="fas fa-code-branch nav-icon"></i>
                        <p>Branches</p>
                      </a>
                    </li>
                    <li class="nav-item tab-2">
                      <a href="/program" class="nav-link">
                        <i class="fas fa-medal nav-icon"></i>
                        <p>Programs</p>
                      </a>
                    </li>
                    <li class="nav-item tab-2">
                      <a href="/user-accounts" class="nav-link">
                        <i class="fas fa-user-friends nav-icon"></i>
                        <p>Users</p>
                      </a>
                    </li>
                    <li class="nav-item tab-2">
                      <a href="/matrix" class="nav-link">
                        <i class="fas fa-percent nav-icon"></i>
                        <p>Incentives Matrix</p>
                      </a>
                    </li>
                    <li class="nav-item tab-2">
                      <a href="/excel-collection" class="nav-link">
                        <i class="fas fa-donate nav-icon"></i>
                        <p>Excel Collection</p>
                      </a>
                    </li>
                    <li class="nav-item tab-2">
                      <a href="/excel-new-sales" class="nav-link">
                        <i class="fas fa-user-cog nav-icon"></i>
                        <p>Excel New Sales</p>
                      </a>
                    </li>
                  </ul>
                </li>
              @endif
            </ul>
          </nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">{{ $header_title; }}</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">{{ $header_title; }}</li>
                </ol>
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main Content -->
        @include($subview)
        
      </div>
      <!-- /.content-wrapper -->

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
      </aside>
      <!-- /.control-sidebar -->

      <!-- Main Footer -->
      <footer class="main-footer">
        <strong>Copyright &copy; 2023-2024 <a href="/">Dayong Providers Inc.</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
          <b>Version</b> 1.0.0
        </div>
      </footer>
    </div>

    @include('plus.scripts')

  </body>
</html>
