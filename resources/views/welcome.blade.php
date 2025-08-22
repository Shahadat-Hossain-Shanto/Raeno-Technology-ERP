<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="it">

        <title>'বিক্রয়িক - Bikroyik' POS & Inventory Management System</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.dark\:text-gray-500{--tw-text-opacity:1;color:#6b7280;color:rgba(107,114,128,var(--tw-text-opacity))}}
        </style>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
        <link rel="icon" href="images/title-image.jpeg" type="image/icon type">

        <!-- bootstrap.min css -->
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
        <!-- Animate Css -->
        <link rel="stylesheet" href="{{ asset('plugins/animate-css/animate.css') }}">
        <!-- Icon Font css -->
        <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/fonts/Pe-icon-7-stroke.css') }}">
        <!-- Themify icon Css -->
        <link rel="stylesheet" href="{{ asset('plugins/themify/css/themify-icons.css') }}">
        <!-- Slick Carousel CSS -->
        <link rel="stylesheet" href="{{ asset('plugins/slick-carousel/slick/slick.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/slick-carousel/slick/slick-theme.css') }}">

        <!-- Main Stylesheet -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <script src="https://kit.fontawesome.com/4560d77c33.js" crossorigin="anonymous"></script>
    </head>
    <body class="antialiased">
        <!-- LOADER TEMPLATE -->
    <div id="page-loader">
        <div class="loader-icon fa fa-spin colored-border"></div>
    </div>
  

    <div class="logo-bar d-none d-md-block d-lg-block bg-light">
            <!-- @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/home') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                        
                    @endauth
                </div>
            @endif -->
            <div class="container">
                <div class="row">
                    <div class="col-lg-20">
                        <div class="logo d-none d-lg-block">
                            <!-- <a class="navbar-brand js-scroll-trigger" href="index.html">
                                <img src="images/bikroyik_logo.png" alt="bikroyik_logo" width="80" height="65">
                            </a> -->
                        </div>
                    </div>

                    <div class="col-lg-8 justify-content-end ml-lg-auto d-flex col-12 col-md-12 justify-content-md-center">
                        <div class="top-info-block d-inline-flex">
                            <div class="icon-block">
                                <i class="ti-mobile"></i>
                            </div>
                            <div class="info-block">
                                <h5 class="font-weight-500">+8801916574623</h5>
                                <h5>কল করুন </h5>
                            </div>
                        </div>

                        <div class="top-info-block d-inline-flex">
                            <div class="icon-block">
                                <i class="ti-email"></i>
                            </div>
                            <div class="info-block">
                                <h5 class="font-weight-500">info@bikroyik.com</h5>
                                <h5>ইমেইল পাঠান</h5>
                            </div>
                        </div>
                        <div class="top-info-block d-inline-flex">
                            <div class="icon-block">
                                <i class="ti-time"></i>
                            </div>
                            <div class="info-block">
                                <h5 class="font-weight-500">Sat-Thu 9:00AM-7.00PM </h5>
                                <h5>শুক্রবার অফিস বন্ধ</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <!-- NAVBAR
    ================================================= -->
   <div class="main-navigation" id="mainmenu-area">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary main-nav navbar-togglable rounded-radius">

                <a class="navbar-brand js-scroll-trigger" href="index.html">
                    <img src="images/bikroyik_logo.png" alt="bikroyik_logo" width="85" height="65">
                </a>
                <!-- Toggler -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fa fa-bars"></span>
                </button>

                <!-- Collapse -->
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <!-- Links -->
                    <ul class="navbar-nav ">
                        

                         <li class="nav-item ">
                            <a href="{{ url('/')}}" class="nav-link js-scroll-trigger">
                                হোমপেজ 
                            </a>
                        </li>
                        
                         <li class="nav-item ">
                            <a href="service.html" class="nav-link js-scroll-trigger">
                                ফিচার 
                            </a>
                        </li>
                        
                                                <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarWelcome" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                ব্যবসার ধরন
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarWelcome">
                                <a class="dropdown-item " href="#">
                                    রেস্তোরাঁ  
                                </a>
                                <a class="dropdown-item " href="#">
                                    ওষুধ দোকান  
                                </a>
                                <a class="dropdown-item " href="#">
                                    কাপড়ের দোকান
                                </a>
                                <a class="dropdown-item " href="#">
                                    মুদি দোকান 
                                </a>
                            </div>
                        </li>
                        
                        <li class="nav-item ">
                            <a href="#" class="nav-link js-scroll-trigger">
                                প্যাকেজ   
                            </a>
                        </li>
                       
                        <li class="nav-item ">
                            <a href="#" class="nav-link js-scroll-trigger">
                                টিউটোরিয়াল 
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a href="#" class="nav-link js-scroll-trigger">
                                বিক্রয়িকের গল্প
                            </a>
                        </li>
                    </ul>

                    <!-- <ul class="ml-lg-auto list-unstyled m-0">
                        <li><a href="#" class="btn btn-white btn-square">LogIn</a></li>
                    </ul> -->
                </div>
                <!-- / .navbar-collapse -->
            </nav>
        </div>
        <!-- / .container -->
    </div>



    <!-- HERO
    ================================================== -->
    
    <section class="banner-area py-7">
        <!-- Content -->
        <div class="container">
            <div class="row  align-items-center">
                <div class="col-md-12 col-lg-7 text-center text-lg-left">
                    <div class="main-banner">
                        <!-- Heading -->
                        <h1 class="display-4 mb-0 font-weight-normal" style="color:rgb(31, 158, 103);">
                            <b> বিক্রয়িক ই-শপ </b>   
                        </h1>
                        <h4 class="display-4 mb-5" style="color:rgb(31, 158, 103);">
                            <span style="font-size: 75%;"> সর্বাধিক ফিচার সমৃদ্ধ</span> <span style="font-size: 75%;"><br> ব্যবসার সেরা  সফটওয়্যার </span>   
                        </h4>

                        <!-- Subheading -->
                        <p class="lead mb-4">
                            আমাদের ম্যানেজমেন্ট সফ্টওয়্যার, “বিক্রয়িক ই-শপ ”- এর মাধ্যমে আপনার কর্মীদের দক্ষতা, উৎপাদনশীলতা এবং গ্রাহক সেবা দ্রুত  বাড়াতে পারেন। আমাদের সফ্টওয়্যার আপনার কাজের প্রক্রিয়াকে স্বয়ংক্রিয় করতে এবং ব্যবসার উন্নয়ন করতে সহায়তা করে।
                        </p>

                        <!-- Button -->
                        <p class="mb-0">
                            <a href="{{ url('/login') }}" target="_blank" class="btn btn-primary btn-circled" style="font-size: 80%;">
                                লাইভ ডেমো 
                            </a>
                            <a href="#" target="_blank" class="btn btn-primary btn-circled ml-2" style="font-size: 80%;">
                                এখনই কিনুন 
                            </a>
                        </p>

                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-block">
                    <div class="banner-img-block">
                        <img src="images/pharmacy_bikroyik_index.jpeg" alt="banner-img" class="img-fluid">
                    </div>
                </div>
            </div>
            <!-- / .row -->
        </div>
        <!-- / .container -->
    </section>

    <section class="section" id="service">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-12 pl-4 text-lg-center pt-5">
                    <div class="service-heading">
                        <h1>এক নজরে <span style="color: #1f9e67">বিক্রয়িক ই-শপ</span> - এর ফিচার সমূহ: </h1>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="service-block media">
                        
                        <div class="service-inner-content media-body text-center">
                            <div class="service-icon-new" style="margin-left: 125px;">
                                <i class="fas fa-warehouse fa-2x"></i>
                            </div>
                            <h4 class="pt-3">ইনভেন্টরি ম্যানেজমেন্ট</h4>
                            <p style="font-size: 14px;">খরচ নিয়ন্ত্রন এবং কার্যকারিতার সাথে পরিচালনা করতে, আপনার ইনভেন্টরির সম্পূর্ণ নিয়ন্ত্রণ পেতে এবং অপচয়ের কারণে ক্ষতি কমাতে অনায়াসে আপনার ইনভেন্টরি পরিচালনা করুন। 
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="service-block media">
                        
                        <div class="service-inner-content media-body text-center">
                            <div class="service-icon-new" style="margin-left: 125px;">
                                <i class="fas fa-info fa-2x"></i>
                            </div>
                            <h4 class="pt-3">পণ্যের তথ্য</h4>
                            <p style="font-size: 14px;"> প্যাকের আকার এবং ওজন সহ আমাদের সিস্টেমের সাথে সমস্ত পণ্যগুলি সুনির্দিষ্টভাবে এবং নিরাপদে পরিচালনা করুন। এই সিস্টেমটি শুধুমাত্র অনুমোদিত ব্যবহারকারীদের জন্য পণ্যের তথ্য, ডেটা সুরক্ষিত এবং অ্যাক্সেসযোগ্য রাখতে সাহায্য করে।</p>
                        </div>
                    </div>
                </div>
              
                <div class="col-lg-4 col-md-6">
                    <div class="service-block media">
                        
                        <div class="service-inner-content media-body text-center">
                            <div class="service-icon-new" style="margin-left: 125px;">
                                <i class="fas fa-truck fa-2x"></i>
                            </div>
                            <h4 class="pt-3">সরবরাহকারী বা বিক্রেতার তথ্য</h4>
                            <p style="font-size: 14px;">পণ্য সরবরাহকারীর সঠিক তথ্য ,নির্দিষ্ট সময়ে আপনার প্রয়োজনীয় সঠিক পণ্যগুলি পেতে আপনার পণ্য সরবরাহকারীর বিক্রেতার সাথে যোগাযোগ করুন।</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-block media">
                        
                        <div class="service-inner-content media-body text-center">
                            <div class="service-icon-new" style="margin-left: 125px;">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <h4 class="pt-3">গ্রাহক ব্যবস্থাপনা</h4>
                            <p style="font-size: 14px;">আপনার গ্রাহক এর তথ্য সংগ্রহ, লয়াল্টি গ্রাহক যাচাই করে সর্বোচ্চ গ্রাহক সেবা প্রদান করুন।</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-block media">
                        
                        <div class="service-inner-content media-body text-center">
                            <div class="service-icon-new" style="margin-left: 125px;">
                                <i class="fas fa-reply fa-2x"></i>
                            </div>
                            <h4 class="pt-3">সরবরাহকারীর কাছে পণ্য ফেরত</h4>
                            <p style="font-size: 14px;">আপনার পণ্য ফেরত দেওয়ার প্রয়োজন হলে, মাত্র কয়েকটি ক্লিকে সেই আইটেমটির সরবরাহকারীকে সহজেই খুঁজে ফেলুন।</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-block media">
                        
                        <div class="service-inner-content media-body text-center">
                            <div class="service-icon-new" style="margin-left: 125px;">
                                <i class="fas fa-barcode fa-2x"></i>
                            </div>
                            <h4 class="pt-3">বারকোড সিস্টেম</h4>
                            <p style="font-size: 14px;">পণ্যের কম্পিউটারাইজ লেবেলিং, দাম- স্ক্যানার এর সাহায্যে আমাদের সিস্টেমের মাধ্যমে প্রিন্ট করুন।</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-block media">
                        
                        <div class="service-inner-content media-body text-center">
                            <div class="service-icon-new" style="margin-left: 125px;">
                                <i class="fas fa-house-damage fa-2x"></i>
                            </div>
                            <h4 class="pt-3">ক্ষতি এবং হারানো পণ্য সামঞ্জস্য</h4>
                            <p style="font-size: 14px;">ক্ষতি এড়াতে আপনার ক্ষতিগ্রস্ত এবং হারিয়ে যাওয়া পণ্য নিয়ন্ত্রণ করুন।</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-block media">
                        
                        <div class="service-inner-content media-body text-center">
                            <div class="service-icon-new" style="margin-left: 125px;">
                                <i class="fas fa-recycle fa-2x"></i>
                            </div>
                            <h4 class="pt-3">পণ্য বিনিময়</h4>
                            <p style="font-size: 14px;">ইনভেন্টরি সামঞ্জস্য করতে আপনার বিনিময় পণ্য রেকর্ড রাখুন।</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-block media">
                        
                        <div class="service-inner-content media-body text-center">
                            <div class="service-icon-new" style="margin-left: 125px;">
                                <i class="fas fa-file-contract fa-2x"></i>
                            </div>
                            <h4 class="pt-3">রিপোর্ট</h4>
                            <p style="font-size: 14px;">রিয়েল-টাইম রিপোর্টিং ক্ষমতা সহ কর্মযোগ্য সেটিংস পান। আপনার প্রয়োজন অনুসারে রিপোর্ট তৈরি করুন। </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-block media">
                        
                        <div class="service-inner-content media-body text-center">
                            <div class="service-icon-new" style="margin-left: 125px;">
                                <i class="fas fa-percent fa-2x"></i>
                            </div>
                            <h4 class="pt-3">ডিসকাউন্ট ব্যবস্থাপনা</h4>
                            <p style="font-size: 14px;">পুনরাবৃত্ত গ্রাহক পেতে এবং আরও  ব্যবসার উন্নয়নে ডিসকাউন্ট সহায়তা করে। আমাদের পোস সিস্টেমের মাধ্যমে একাধিক ডিসকাউন্ট এবং কুপন ব্যবহার সহজ।</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-block media">
                        
                        <div class="service-inner-content media-body text-center">
                            <div class="service-icon-new" style="margin-left: 125px;">
                                <i class="fas fa-cash-register fa-2x"></i>
                            </div>
                            <h4 class="pt-3">একাধিক কাউন্টারের মাধ্যমে বিক্রয়</h4>
                            <p style="font-size: 14px;">একটি দোকানে একাধিক কাউন্টার তৈরি করতে পারেন এবং তাদের মাধ্যমে আপনার পণ্য বিক্রি/রক্ষণাবেক্ষণ করতে পারেন।</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="service-block media">
                        
                        <div class="service-inner-content media-body text-center">
                            <div class="service-icon-new" style="margin-left: 125px;">
                                <i class="fas fa-store fa-2x"></i>
                            </div>
                            <h4 class="pt-3">একাধিক স্টোর ব্যবস্থাপনা</h4>
                            <p style="font-size: 14px;">একাধিক স্টোর নিয়ন্ত্রন করতে পারেন। একাধিক স্টোরে একাধিক কর্মচারী এবং কাউন্টার তৈরি করুন। </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-block media">
                        
                        <div class="service-inner-content media-body text-center">
                            <div class="service-icon-new" style="margin-left: 125px;">
                                <i class="fas fa-th-large fa-2x"></i>
                            </div>
                            <h4 class="pt-3">অন্যান্য</h4>
                            <p style="font-size: 14px;">এছাড়াও আমাদের রয়েছে আরো অসংখ্য ফিচার। লাইভ ডেমো দেখতে এখনই যোগাযোগ করুন আমাদের সাথে। </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-lg-12 text-center">
                    <p class="pl-3">Want to know more about this? <a href="#">Contact us</a></p>
                </div>
            </div> -->
        </div>
    </section>


    

    <footer class="section " id="footer">
        <div class="overlay footer-overlay"></div>
        <!--Content -->
        <div class="container">
            <div class="row justify-content-start">
                <div class="col-lg-4 col-sm-12">
                    <div class="footer-widget">
                        <!-- Brand -->
                        <a href="#" class="footer-brand text-white">
                            <b>বিক্রয়িক</b>
                        </a>
                        <p>বিক্রয়িক পয়েন্ট অফ সেলস (POS)- A SaaS based Sales and Inventory Management Software.</p>
                    </div>
                </div>

                <div class="col-lg-3 ml-lg-auto col-sm-12">
                    <div class="footer-widget">
                        <h3>Account</h3>
                        <!-- Links -->
                        <ul class="footer-links ">
                            <li>
                                <a href="#">
                                    Terms and conditions
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Privacy policy
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Affiliate services
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Help and support
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Frequently Asked Question
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="col-lg-2 col-sm-6">
                    <div class="footer-widget">
                        <h3>About</h3>
                        <!-- Links -->
                        <ul class="footer-links ">
                            <li>
                                <a href="#">
                                    Services
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    About Us
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Pricing
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Products Shop
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    Contact
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-2 col-sm-6">
                    <div class="footer-widget">
                        <h3>Socials</h3>
                        <!-- Links -->
                        <ul class="list-unstyled footer-links">
                            <li><a href="https://www.facebook.com/%E0%A6%AC%E0%A6%BF%E0%A6%95%E0%A7%8D%E0%A6%B0%E0%A6%AF%E0%A6%BC%E0%A6%BF%E0%A6%95-105130662075214"><i class="fab fa-facebook-f"></i>Facebook</a></li>
                            <li>
                                <a href="#"><i class="fab fa-twitter"></i>Twitter
                            </a></li>
                            <li><a href="#"><i class="fab fa-pinterest-p"></i>Pinterest
                            </a></li>
                            <li><a href="#"><i class="fab fa-linkedin"></i>linkedin
                            </a></li>
                            <li><a href="#"><i class="fab fa-youtube"></i>YouTube
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- / .row -->


            <div class="row text-right pt-5">
                <div class="col-lg-12">
                    <!-- Copyright -->
                    <p class="footer-copy ">
                        &copy; Copyright <span class="current-year">বিক্রয়িক </span> All rights reserved.
                    </p>
                </div>
            </div>
            <!-- / .row -->
        </div>
        <!-- / .container -->
    </footer>


    <!--  Page Scroll to Top  -->

    <!-- <a class="scroll-to-top js-scroll-trigger" href="#top-header">
        <i class="fa fa-angle-up"></i>
    </a> -->

        
        <!-- Essential Scripts -->
        <!-- ===================================== -->


        <!-- Main jQuery -->
        <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 3.1 -->
        <script src="{{ asset('plugins/bootstrap/js/popper.min.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <!-- Slick Slider -->
        <script src="{{ asset('plugins/slick-carousel/slick/slick.min.js') }}"></script>
        <!-- <script src="js/jquery.easing.1.3.js"></script> -->
        <!-- Map Js -->
        <script src="{{ asset('plugins/google-map/gmap3.min.js') }}"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwIQh7LGryQdDDi-A603lR8NqiF3R_ycA"></script>

        <!-- <script src="js/form/contact.js"></script> -->
        <script src="{{ asset('js/theme.js') }}"></script>
    </body>
</html>
