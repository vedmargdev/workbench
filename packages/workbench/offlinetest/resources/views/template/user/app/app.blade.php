<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>@yield('title')</title>
	
	{{-- <base href="{{ route('user') }}" data-url="{{ url('/') }}"> --}}
	<meta name="author" content="{{ config('app.name') }}" />
	
	  <!-- CSRF Token for AJAX requests -->
	  <meta name="csrf-token" content="{{ csrf_token() }}">

	{{-- <link rel="shortcut icon" href="{{ cdn_url('images/fav-icon.png') }}"/> --}}
	

	@env('production')
	@else
		<meta name="robots" content="noindex,nofollow">
	@endif
   <!-- Supplier Css Link -->
  
    <!-- Font Awesome Link -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->

    <!-- Bootsrap Cdn link -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

   <!-- Tags Input CSS -->
   {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css"> --}}
    <!-- TinyMCE CSS -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
	<script type="text/javascript" src="https://d1mz44slphi9ww.cloudfront.net/assets/js/tinymce.min.js"></script>
	{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js"></script> --}}

	{{-- <link rel="stylesheet preload" type="text/css" href="{{ cdn_url('assets/public/css/static.min.css') }}" as="css" /> 
	<link rel="stylesheet preload" type="text/css" href="{{ asset('css/all.css?v='.time()) }}" /> --}}
	<link rel="stylesheet preload" type="text/css" href="{{ asset('css/inventory.css?v='.time()) }}" />
	{{-- <link rel="stylesheet" href="{{ asset('css/supplier.css') }}">
	<link rel="stylesheet" href="{{ asset('css/addProducts.css') }}">
	<link rel="stylesheet" href="{{asset('css/library-management.css')}}">
	<link rel="stylesheet" href="{{asset('css/gatepass.css')}}"> --}}


	<link rel="stylesheet" href="https://vedmarg.s3.ap-south-1.amazonaws.com/assets/public/css/static.min.css">
	<link rel="stylesheet" href="https://vedmarg.s3.ap-south-1.amazonaws.com/assets/public/css/all.min.css">
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


	
	{{-- @yield('app-css') --}}
	 
</head>

<body>
	
	<?php
		$permissions = false;
		$sidebar = false;
		$canUserSearchStudent = false;
		$canChangeSession = false;
		$canClearCache = false;
   		$session = '';
   		$school_name = '';
   		$school_logo = false;
   		$avtar = false;
		$agent = new Jenssegers\Agent\Agent();
	?>


	<div class="theme-layout dashboard user" id="scrollup">
		
		<header class="stick-top forsticky gradient">
			<div class="main-header" style="">
				<div class="container head-container">
					<div class="row">
						<div class="col-lg-2 col-md-2">
							<div class="logo">
								<a class="nav-link mobile-toggle-sidebar" href="javascript:void(0)">
									
								</a>
								{{-- <a class="navbar-brand" href="{{ route('user') }}">
									@if( $school_logo )
										<img src="{{ asset_url(getThumbnailImage($school_logo)) }}" alt="{{ $school_name ?? '' }}" />
									@else
										<img class="logo-placeholder" src="{{ school_placeholder_logo() }}" alt="{{ $school_name ?? '' }}">
									@endif
									<span class="ss-name">{{ $school_name ?? '' }}</span>
								</a> --}}
							</div>
						</div>

						<div class="col-lg-10 col-md-10">
							<nav class="nav">
								<ul class="navbar-nav navbar-left">
									<li>
										{{-- <a class="nav-link toggle-sidebar" href="javascript:void(0)">
											<img src="{{ asset_url('images/icons/angle-left-white.png') }}">
										</a> --}}
									</li>
								</ul>
								<ul class="navbar-nav navbar-right">
									@if( 7181 == Auth::id() )
										<?php $totalNotice = App\Models\NoticeUser::where('user_id', Auth::id())->where('is_read', 0)->count(); ?>
										<li class="message--counter-li">
											<a href="{{ route('user.notices') }}">
												<span>
													<i class="fas fa-envelope"></i>
												</span>
												<span class="counter--msg {{ $totalNotice ? 'active' : '' }}">{{ $totalNotice ?? '' }}</span>
											</a>
										</li>
									@endif

									@if( $canUserSearchStudent )
										<li class="nav--search">
											<div class="mobile--search-btn-toggle">
												<img src="{{ asset_url('images/icons/search-black.png') }}">
											</div>
											<div class="nav-search--box">
												<input type="text" name="nav--search--field" placeholder="Search student by name, father name, admission no or mobile no....">
												<div class="mobile--search-close-toggle">
													<span class="icon">
														<img src="{{ asset_url('images/icons/close.png') }}">
													</span>
												</div>
											</div>
											<div class="searched-result-dropdown"></div>
										</li>
									@else
										<li></li>
									@endif
									

									{{-- <li class="nav-user--profile">
										<a class="nav-link {{ $agent->isMobile() ? 'mobile-toggle-sidebar' : 'nav-user-profile' }}" href="javascript:void(0)">
											@if( $avtar )
												<img class="user-image" src="{{ asset_url(getSmallImage($avtar)) }}" alt="{{ Auth::user()->first_name ?? '' }}" />
											@else
												<img class="user-image" src="{{ user_placeholder_logo() }}" alt="{{ Auth::user()->first_name ?? '' }}">
											@endif
										</a>

										<ul class="dropdown-menu-user">

											<li class="pb-2 user--name--d">
												<div class="text-dark text-center">
													Hi, {{ Auth::user()->first_name ?? '' }}
												</div>
											</li>


											<li>
												<a href="{{ Route::has('logout') ? route('logout') : '#' }}">
								                    
													<img src="{{ asset_url('images/icons/logout.png') }}">
								                     Logout
								                </a>
											</li>
										</ul>
									</li> --}}
								</ul>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</header>

		<section class="dashboard user user-section">
			<div class="block pt-0">
				<div class="container">
					 <div class="row gw-profile-sidebar">
					 	@include('offlinetest::template.user.app.sidebar')

					 	<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12 section--content--container">
							
							@yield('content')

						</div>
					</div>
				</div>
			</div>
		</section>
		<input type="hidden" id="cdnUrl" value="{{ cdn_url() }}">
	</div>


	<div class="spinner-loader">
		<span class="loader-sm">
			<img src="{{ asset_url('images/loader.gif') }}">
		</span>
	</div>

	<div id="popupWrapper"></div>

	{{ csrf_field() }}

	<canvas id='myCanvas' style="position: absolute;left: 16%;top: 80px;z-index: -1;"></canvas>

	
	@include('offlinetest::template.user.app.footerjs')

</body>
</html>