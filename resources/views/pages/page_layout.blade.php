<!DOCTYPE html>
<html lang="vn">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $meta['title'] }}</title>

    <!-- SEO -->
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="robots" content="INDEX,FOLLOW">
    <link rel="canonical" href="{{ $meta['canonical'] }}">
    <meta name="author" content="Nhân Sợ Code Và Nguyên Báo Thủ">
    <link REL="SHORTCUT ICON" href="">


    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="{{ $meta['canonical'] }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $meta['sitename'] }}">
    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:image" content="{{ $meta['image'] }}">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:url" content="{{ $meta['canonical'] }}">
    <meta name="twitter:type" content="website">
    <meta name="twitter:site_name" content="{{ $meta['sitename'] }}">
    <meta name="twitter:title" content="{{ $meta['title'] }}">
    <meta name="twitter:description" content="{{ $meta['description'] }}">
    <meta name="twitter:image" content="{{ $meta['image'] }}">

    <!-- END SEO -->


    <link rel="stylesheet" href="{{ asset('public/fontend/assets/css/trangchu.css') }}">
    <link rel="stylesheet" href="{{ asset('public/fontend/assets/css/resTrangchu.css') }}">
    <link rel="stylesheet" href="{{ asset('public/fontend/assets/owlcarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/fontend/assets/owlcarousel/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    {{-- jquery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
        integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- jquery UI --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"
        integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- jquery CSS UI --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css"
        integrity="sha512-ELV+xyi8IhEApPS/pSj66+Jiw+sOT1Mqkzlh8ExXihe4zfqbWkxPRi8wptXIO9g73FSlhmquFlUOuMSoXz5IRw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Toastr Css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    {{-- Js Toast  --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {{-- Thông Báo Toastr --}}
    <script>
        function message_toastr(type, title, content) {
            Command: toastr[type](title, content)
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        }

        function message(type, content) {
            Command: toastr[type](content)
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        }
    </script>

</head>

<style>
    @font-face {
        font-family: nhanf;
        src: url({{ asset('public/fontend/assets/fonts/Mt-Regular.otf') }});
        font-display: swap;
    }
    .Fix {}
</style>

<body class="preloading">
    <div class="load">
        <img src="{{ asset('public/fontend/assets/img/loader.gif') }}" alt="">
    </div>
    <?php
    if(session()->get('message')!=null){
       $message = session()->get('message');
       $type = $message['type'];
       $content = $message['content'];
    ?>
    <script>
        message_toastr("{{ $type }}", "{{ $content }}");
    </script>
    <?php
    session()->forget('message');
    }
    ?>
    <div class="header">
        <div class="videoContainer">
            <video type="video/mp4" autoplay="" muted="" loop="">
                <source src="{{ asset('public/fontend/assets/video/video-backgroud.mp4') }}" />
                Your browser does not support the video tag.
            </video>

            <div class="ContentInVideo">

                <nav class="navbar">

                    <div class="navbar-logo">
                        <a class="navbar-item-link" href="trangchu.html"> <img
                                style="width: 130px; height: 45px;object-fit: cover;"
                                src="{{ asset('public/fontend/assets/img/config/' . $config_logo_web->config_image) }}"
                                alt="">
                        </a>
                    </div>

                    <ul class="navbar-list navbar-list--left">
                        <li class="navbar-item res-navbar-item-589">
                            <a class="navbar-item-link" href="">
                                <i class="fa-solid fa-house"></i>
                            </a>
                            <a class="navbar-item-link res-navbar-item-589" href="{{ URL::To('/') }}"><span>Trang
                                    Chủ</span></a>
                        </li>
                        <li class="navbar-item">
                            <a class="navbar-item-link res-navbar-item-589" href="{{ url('/cua-hang') }}"><span>Thế Giới Hải
                                    Sản</span></a>
                        </li>
                        <li class="navbar-item res-navbar-item-1230">
                            <a class="navbar-item-link" href=""><span>Hệ Thống Cửa Hàng</span></a>
                        </li>
                        <li class="navbar-item res-navbar-item-1297">
                            <a class="navbar-item-link" href=""><span>Thông Tin & Sự Kiện</span></a>
                        </li>
                    </ul>

                    <ul class="navbar-list navbar-list--right">
                        <li class="navbar-item res-navbar-item-768">
                            @if(session()->get('customer_id') != null)
                            <a class="navbar-item-link" href="{{ url('user/order?customer_id='.session()->get('customer_id').'') }}">
                                <i class="fa-solid fa-bus"></i>
                            </a>
                            @else
                            <a class="navbar-item-link" href="{{ url('user/order?customer_id=-1') }}">
                                <i class="fa-solid fa-bus"></i>
                            </a>
                            @endif
                        </li>

                        <li class="navbar-item res-navbar-item-768">
                            <a class="navbar-item-link" id="cart_shopping" href="{{ URL::TO('cart/') }}">
                                <?php
                                    if (Session::get('cart')) {
                                        $cart = Session::get('cart');
                                        $countcart = count($cart);
                                ?>
                                <i class="fa-solid fa-cart-shopping" style="position: absolute;"></i>
                                <span
                                    style="padding: 3px 4px ; background-color: #FF3366; color: #fff ; border-radius: 8px ; font-size: 10px ; position: relative;bottom:-3px;left: 9px;">{{ $countcart }}</span>
                                <?php
                                    }else{
                                        ?>
                                <i class="fa-solid fa-cart-shopping"></i>
                                <?php       
                                    } 
                                    ?>
                            </a>
                        </li>
                        <li class="navbar-item res-navbar-item-589">
                            <label style="cursor: pointer;" for="Notification-input" class="navbar-item-link">
                                <i class="fa-solid fa-bell"></i>
                            </label>
                        </li>
                        @if (Session::get('customer_id') != null)
                        <label class="navbar-item">
                            <a href="{{ url('user/order?customer_id='.session()->get('customer_id').'') }}" >
                                <label class="navbar-item-link">
                                    <i class="fa-solid fa-user"></i>
                                </label>
                                <label class="navbar-item-link">
                                    {{ session()->get('customer_name') }}
                                </label>
                            </a>
                        </label>
                            <a href="{{ URL::to('user/logout') }}">
                                <label class="navbar-item">
                                    <label class="navbar-item-link">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                    </label>
                                    <label class="navbar-item-link">
                                        Đăng Xuất
                                    </label>
                                </label>
                            </a>
                        @else
                            <label for="nav-login-logout" class="navbar-item">
                                <label for="nav-login-logout" class="navbar-item-link">
                                    <i class="fa-solid fa-user"></i>
                                </label>
                                <label for="nav-login-logout" class="navbar-item-link">
                                    <i class="fa-solid fa-caret-down"></i>
                                </label>
                            </label>
                        @endif

                        <li class="navbar-item ">
                            <label style="cursor: pointer;" for="nav-input" class="navbar-item-link">
                                <i class="fa-solid fa-bars"></i>
                            </label>
                        </li>
                    </ul>

                    <input type="checkbox" hidden class="Notification-input-select" name=""
                        id="Notification-input">
                    <label class="nooverlay-Notification" for="Notification-input">
                    </label>
                    <label for="Notification-input" class="Notification">
                        <div class="Notification-img">
                            <img src="assets/img/trangchu/icon_notification_empty.svg" alt="">
                        </div>
                        <div class="Notification-text">
                            <span>Không có thông báo nào!</span>
                        </div>
                    </label>

                    <input type="checkbox" hidden class="nav-login-logout-select" name=""
                        id="nav-login-logout">
                    <label class="nooverlay-login-logout" for="nav-login-logout">
                    </label>
                    <div class="nav-login-logout">
                        <div class="nav-login-logout-box">
                            <label id="dangnhap" for="input-fromlogin" class="nav-login-logout-box-item">
                                <span class="nav-login-logout-box-text">Đăng Nhập</span>
                            </label>
                            <label id="dangky" for="input-fromsignup" class="nav-login-logout-box-item">
                                <span class="nav-login-logout-box-text">Đăng Ký</span>
                            </label>
                        </div>
                    </div>

                    @include('pages.Login_Register.register')
                    @include('pages.Login_Register.verycode')
                    @include('pages.Login_Register.recoverypw')
                    @include('pages.Login_Register.code_confirmation')
                    @include('pages.Login_Register.confirmpassword')
                    @include('pages.Login_Register.login')

                    <input type="checkbox" hidden class="nav-input-select" name="" id="nav-input">
                    <label for="nav-input" class="nav-overlay">
                    </label>
                    <div class="nav-menu">
                        <div class="nav-menu-box">
                            <label for="nav-input" class="nav-menu-close">
                                <i class="nav-menu-close fa-solid fa-xmark"></i>
                            </label>
                            <ul>
                                <li class="nav-menu-item"><i style="color: #00b6f3;"
                                        class="fa-solid fa-house"></i><span class="nav-menu-item-text">Trang
                                        Chủ</span></li>
                                <li class="nav-menu-item nav-menu-item-boder"><i style="color: #00b6f3;"
                                        class="fa-solid fa-heart"></i><span class="nav-menu-item-text">Yêu
                                        Thích</span>
                                </li>
                                <li class="nav-menu-item"><i style="color: #ffc043;"
                                        class="fa-solid fa-hotel"></i><span class="nav-menu-item-text">Khách
                                        Sạn</span></li>
                                <li class="nav-menu-item"><i style="color: #ff2890;"
                                        class="fa-solid fa-plane"></i><span class="nav-menu-item-text">Vé Máy
                                        Bay</span></li>
                                <li class="nav-menu-item"><i style="color: #ff2890;"
                                        class="fa-solid fa-hand-holding-heart"></i><span
                                        class="nav-menu-item-text">The
                                        Memories</span></li>
                                <li class="nav-menu-item"><i style="color: #00b6f3;"
                                        class="fa-solid fa-briefcase"></i><span class="nav-menu-item-text">Tour & Sự
                                        Kiện</span></li>
                                <li class="nav-menu-item nav-menu-item-boder"><i style="color: #48bb78;"
                                        class="fa-solid fa-book"></i><span class="nav-menu-item-text">Cẩm Năng Du
                                        Lịch</span></li>
                                <li class="nav-menu-item"><i style="color: #00b6f3;"
                                        class="fa-solid fa-briefcase"></i><span class="nav-menu-item-text">Tuyển
                                        Dụng</span></li>
                                <li class="nav-menu-item"><i style="color: #00b6f3;"
                                        class="fa-solid fa-headphones-simple"></i><span class="nav-menu-item-text">Hỗ
                                        Trợ</span></li>
                                <li class="nav-menu-item nav-menu-item-boder"><i style="color: #00b6f3;"
                                        class="fa-solid fa-sack-dollar"></i><span class="nav-menu-item-text">Trở Thành
                                        Đối Tác Liên Kết</span></li>
                                <li class="nav-menu-item"><i style="color: #00b6f3;"
                                        class="fa-solid fa-handshake"></i><span class="nav-menu-item-text">Hợp Tác Với
                                        Chúng Tôi</span></li>
                                <li class="nav-menu-item"><i style="color: #00b6f3;"
                                        class="fa-solid fa-mobile"></i><span class="nav-menu-item-text">Tải Ứng Dụng
                                        MyHotel</span></li>
                                <li class="nav-menu-item"><i style="color: #00b6f3;"
                                        class="fa-solid fa-share-nodes"></i><span class="nav-menu-item-text">Giới
                                        Thiệu
                                        Bạn Bè</span></li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <div class="BoxSearch">

                    <div class="BoxSearch-title">
                        <ul class="BoxSearch-list">
                            <li class="BoxSearch-item">
                                <span class="BoxSearch-link" href=""><i
                                        class="fa-solid fa-fish-fins"></i></span>
                                <span class="BoxSearch-link" href="">Tìm Kiếm Tên, Danh Mục, Loại Hải
                                    Sản</span>
                            </li>
                        </ul>
                    </div>

                    <div class="BoxSearch-Bottom">
                        <div class="BoxSearch-Bottom-One">
                            <div class="BoxSearch-Bottom-One-Title">
                                Nhập dô để tìm kiếm!
                            </div>
                            <form class="BoxSearch-Bottom-One-Input">
                                <input class="BoxSearch-Bottom-One-input-size" type="text"
                                    placeholder="Ngao, Sò, Ốc, Cua, Ghẹ, Tôm Hùm,...">
                                <div class="inputsearchhotel">

                                </div>
                            </form>
                        </div>
                        <a href="thongtinkhachsan.html">
                            <div class="BoxSearch-Bottom-BtnSrearch">
                                <div class="BoxSearch-Bottom-BtnSrearch-Box">
                                    <i class="fa-solid fa-magnifying-glass BoxSearch-Bottom-BtnSrearch-Box-Icon"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="slider">
        <div class="slider-box">
            <div class="slider-js owl-carousel owl-theme">
                @foreach ($slider as $item)
                    <div class="item">
                        <img width="465px" height="195px" style="object-fit: cover;border-radius: 8px;"
                            src="{{ asset('public/fontend/assets/img/slider/' . $item->slider_image) }}"
                            alt="{{ $item->slider_desc }}">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="container-1">
        <div class="boxcontent">

            @foreach ($config_slogan_web as $slogan_image)
                <div class="boxcontent-layout">
                    <div class="boxcontent-img">

                        <img src="{{ asset('public/fontend/assets/img/config/' . $slogan_image->config_image) }}"
                            alt="">
                    </div>
                    <div class="boxcontent-text">
                        <div class="boxcontent-text-one">
                            {{ $slogan_image->config_title }}
                        </div>
                        <div class="boxcontent-text-two">
                            {{ $slogan_image->config_content }}
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    @yield('web_content')

    <div class="infohotel">
        <div class="BoxNhapSDT">
            <div class="BoxNhapSDT-left">
                <div class="BoxNhapSDT-left-img">
                    <img src="{{ URL::to('public/fontend/assets/img/icon_mail_red.svg') }}" alt="">
                </div>
                <div class="BoxNhapSDT-left-text">
                    <div class="BoxNhapSDT-left-text-title">
                        <span>Bạn muốn tiết kiện 50% khi đặt hải sản ?</span>
                    </div>
                    <div class="BoxNhapSDT-left-text-content">
                        <span>Nhập số điện thoại để Thế Giới Hải Sản có thể gửi đến bạn những chương trình khuyến mại
                            mới nhất!</span>
                    </div>
                </div>
            </div>
            <div class="BoxNhapSDT-right">
                <div class="BoxNhapSDT-right-Groupinput">
                    <div class="BoxNhapSDT-right-input">
                        <input type="text" placeholder="Nhập số điện thoại">
                    </div>
                    <label class="BoxNhapSDT-right-lable" for="">
                        <span>Đăng ký</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="infohotel-box">
            <div class="infohotel_logo">
                <img width="185px" height="55px" style="object-fit: cover;"
                    src="{{ asset('public/fontend/assets/img/config/' . $config_logo_web->config_image) }}"
                    alt="">
            </div>
            <div class="infohotel_content">
                <div class="infohotel_content_box">
                    <div class="infohotel_content_title">
                        {{ $company_config->company_name }}
                    </div>
                    <div class="infohotel_content_text">
                        Tổng đài chăm sóc: {{ $company_config->company_hostline }}
                        <br>
                        Email: {{ $company_config->company_mail }}
                        <br>
                        Văn phòng Đà Nẵng: {{ $company_config->company_address }}
                        <br>
                    </div>
                </div>
                <div class="infohotel_content_box">
                    <div class="infohotel_content_title">
                        Chính sách & Quy định
                    </div>
                    <div class="infohotel_content_text">
                        Điều khoản và điều kiện <br>
                        Quy định về thanh toán <br>
                        Chính sách bảo mật thông tin <br>
                        Quy chế hoạt động <br>
                        Quy trình giải quyết tranh chấp, khiếu nại <br>
                    </div>
                </div>
                <div class="infohotel_content_box">
                    <div class="infohotel_content_title">
                        Khách hàng và đối tác
                    </div>
                    <div class="infohotel_content_text">
                        Đăng nhập HMS <br>
                        Tuyển dụng <br>
                        Liên hệ <br>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-content">
            <div class="footer-content">
                {{ $company_config->company_slogan }}
            </div>
            <div class="footer-content">
                @foreach ($config_brand_web as $brand)
                    <a href="{{ $brand->config_content }}" target="_blank"><img width="100px" height="70px"
                            style="border-radius: 8px;object-fit: cover; margin:15px"
                            src="{{ asset('public/fontend/assets/img/config/' . $brand->config_image) }}"
                            alt=""></a>
                @endforeach
            </div>
            <div class="footer-content">
                {{ $company_config->company_copyright }}
            </div>
        </div>
    </div>
    <script src=" {{ asset('public/fontend/assets/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.1/TweenMax.min.js"></script>
    <script src=" {{ asset('public/fontend/assets/js/trangchu.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"
        integrity="sha512-Eak/29OTpb36LLo2r47IpVzPBLXnAMPAVypbSZiZ4Qkf8p/7S/XRG5xp7OKWPPYfJT6metI+IORkR5G8F900+g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src=" {{ asset('public/fontend/assets/js/main.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        new WOW().init();
    </script>

    <!-- Messenger Plugin chat Code -->
    <div id="fb-root"></div>

    <!-- Your Plugin chat code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>
    <script>
        
    </script>
    <script>
        var chatbox = document.getElementById('fb-customer-chat');
        chatbox.setAttribute("page_id", "106752932206746");
        chatbox.setAttribute("attribution", "biz_inbox");
    </script>

    <!-- Your SDK code | Js Của Chát FB-->
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                xfbml: true,
                version: 'v15.0'
            });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>



    {{-- Validator Trang Đăng Ký --}}
    <script>
        
        Validator({
            form: '.fromlogin',
            errorSelector: '.form-message',
            rules: [
                Validator.isRequired('#name_login', 'Vui lòng nhập tên tài khoản hoặc email'),
                Validator.isRequired('#pass_login', 'Vui lòng nhập mật khẩu của bạn'),
                Validator.minLength('#pass_login', 6),
            ]
        });

        $('.fromlogin-btn').click(function() {
            if ($('.form-message').text() != '') {
                $(".fromlogin").submit(function(e) {
                    e.preventDefault();
                });
            }
        })

        Validator({
            form: '#form-register',
            errorSelector: '.form-message',
            rules: [
                Validator.isRequired('#fullname', 'Vui lòng nhập tên đầy đủ của bạn'),
                Validator.isRequired('#email', 'Vui lòng nhập email của bạn'),
                Validator.isRequired('#phonenumber', 'Vui lòng nhập số điện thoại của bạn'),
                Validator.isEmail('#email'),
                Validator.minLength('#password', 6),
                Validator.minLength('#phonenumber', 10),
                Validator.maxLength('#phonenumber', 10),
                Validator.isRequired('#password_confirmation'),
                Validator.isConfirmed('#password_confirmation', function() {
                    return document.querySelector('#form-register #password').value;
                }, 'Mật khẩu nhập lại không chính xác')
            ]
        });

        Validator({
            form: '.form-vecovery-password',
            errorSelector: '.form-message',
            rules: [
                Validator.isRequired('#EmailorAccountOld', 'Vui lòng nhập tên tài khoản hoặc email'),
            ]
        });

        Validator({
            form: '#form_verycode',
            errorSelector: '.form-message',
            rules: [
                Validator.minLength('#very_code', 8),
            ]
        });

        Validator({
            form: '#password_confir',
            errorSelector: '.form-message',
            rules: [
                Validator.minLength('#newpass', 6),
                Validator.isRequired('#newpassconfir'),
                Validator.isConfirmed('#newpassconfir', function() {
                    return document.querySelector('#password_confir #newpass').value;
                }, 'Mật khẩu nhập lại không chính xác')

            ]
        });

        Validator({
            form: '#code_confirmation',
            errorSelector: '.form-message',
            rules: [
                Validator.minLength('#very_code_rc', 8),
            ]
        });
        
      
    </script>

    <script>
        $('#overlay').click(function() {

            $("#form-register").css({
                "display": "none"
            });
            $("#from-verycode").css({
                "display": "none"
            });
            $("#overlay").css({
                "display": "none"
            });
            $(".fromlogin").css({
                "display": "none"
            });
            $(".form-vecovery-password").css({
                "display": "none"
            });
            $(".code_confirmation").css({
                "display": "none"
            });
            $(".password_confirmation").css({
                "display": "none"
            });

            $('#ShowImgHotel').hide();

            $('.form-message').html('');

            document.getElementById('name_login').value = '';
            document.getElementById('pass_login').value = '';

            document.getElementById('fullname').value = '';
            document.getElementById('email').value = '';
            document.getElementById('phonenumber').value = '';
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';

            document.getElementById('EmailorAccountOld').value = '';
            
            document.getElementById('very_code_rc').value = '';
            document.getElementById('very_code').value = '';

            document.getElementById('newpass').value = '';
            document.getElementById('newpassconfir').value = '';
            

        });
        $('.close-box').click(function() {

            $("#form-register").css({
                "display": "none"
            });
            $(".from-verycode").css({
                "display": "none"
            });
            $("#overlay").css({
                "display": "none"
            });
            $(".fromlogin").css({
                "display": "none"
            });
            $(".form-vecovery-password").css({
                "display": "none"
            });
            $(".code_confirmation").css({
                "display": "none"
            });
            $(".password_confirmation").css({
                "display": "none"
            });

            $('.form-message').html('');

            document.getElementById('name_login').value = '';
            document.getElementById('pass_login').value = '';

            document.getElementById('fullname').value = '';
            document.getElementById('email').value = '';
            document.getElementById('phonenumber').value = '';
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';

            document.getElementById('EmailorAccountOld').value = '';
            
            document.getElementById('very_code_rc').value = '';
            document.getElementById('very_code').value = '';

            document.getElementById('newpass').value = '';
            document.getElementById('newpassconfir').value = '';
            
        });

        $('#dangnhap').click(function() {
            $(".fromlogin").css({
                "display": "block"
            });
            $("#overlay").css({
                "display": "block"
            });
        });
        $('#dangky').click(function() {
            $("#form-register").css({
                "display": "block"
            });
            $("#overlay").css({
                "display": "block"
            });
        });
        $('#loginaccount').click(function() {
            $("#form-register").css({
                "display": "none"
            });
            $(".fromlogin").css({
                "display": "block"
            });
        });
        $('#registeraccount').click(function() {
            $("#form-register").css({
                "display": "block"
            });
            $(".fromlogin").css({
                "display": "none"
            });
        });
        $('#recoverypassaccount').click(function() {
            $(".form-vecovery-password").css({
                "display": "block"
            });
            $(".fromlogin").css({
                "display": "none"
            });
        });
    </script>

<script>
    load_cart();
    function load_cart() {
            $.ajax({
                url: '{{ url('/cart/message-cart') }}',
                method: 'get',
                data: {

                },
                success: function(data) {
                    $('#cart_shopping').html(data);
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        }
    $(document).on("click", '.button_cart', function() {
            var product_id = $(this).data('product_id');
            var _token = $('input[name="_token"]').val();
            var product_qty = 1;
            $.ajax({
                url: '{{ url('/cart/save-cart') }}',
                method: 'POST',
                data: {
                    _token: _token,
                    product_id: product_id,
                    product_qty:product_qty,
                },
                success: function(data) {
                    load_cart();
                    if (data == 'error') {
                        message_toastr("info", "Đã tồn tại sản phẩm trong giỏ hàng!!!", "Thông báo");
                    } else {
                        message_toastr("success",
                            'Bạn đã thêm sản phẩm vào giỏ hàng!!! <br /><br/><button id="btn-gocart" type="button" class="btn btn-primary">Đi Đến Giỏ Hàng</button> ',
                            'Thành công');
                    }
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        });
        $(document).on("click", '#btn-gocart', function() {
            window.location.href = "https://sepnguyenvanhanbro.thegioihaisan.laravel.vn/DoAnCNWeb/cart";
        });
</script>


    {{-- <script>
        $(".action").on("click", function() {
            var list_id = [];
            $.each($("input[name='category']:checked"), function() {
                list_id.push($(this).val());

            });
            $.ajax({
                url: '{{ url('/trang-chu/show-product-category') }}',
                method: 'get',
                data: {
                    list_id: list_id,
                },
                success: function(data) {
                    $('.hottelpricesbox-contentbottom-layout').html(data);
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        });



        $("#maxprice").on("click", function() {

            $.ajax({
                url: '{{ url('/trang-chu/sort-max-price-product') }}',
                method: 'get',
                data: {},
                success: function(data) {
                    $('.hottelpricesbox-contentbottom-layout').html(data);
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        });
        $("#minprice").on("click", function() {
            $.ajax({
                url: '{{ url('/trang-chu/sort-min-price-product') }}',
                method: 'get',
                data: {},
                success: function(data) {
                    //  alert(data);
                    $('.hottelpricesbox-contentbottom-layout').html(data);
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        });

        $("#newproduct").on("click", function() {
            $.ajax({
                url: '{{ url('/trang-chu/sort-newproduct') }}',
                method: 'get',
                data: {

                },
                success: function(data) {
                    //  alert(data);
                    $('.hottelpricesbox-contentbottom-layout').html(data);
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        });
    </script> --}}



    {{-- Voice --}}
    {{-- <script>
        $('.filter_content').on('keyup', '#searchbyvoice', function() {
            // $("#searchbyvoice").change(function(){
            var keysearch = $(this).val();
            //var _token =  $("input[name='_token']").val();
            $.ajax({
                url: '{{ url('/trang-chu/search-by-voice') }}',
                method: 'get',
                data: {
                    keysearch: keysearch,
                },
                success: function(data) {
                    $('.hottelpricesbox-contentbottom-layout').html(data);
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })

        });
    </script> --}}



    {{-- Đăng Nhập --}}
    {{-- <script>
        $('#btn-login-submit').click(function(){
           var customer_name = $("input[name='customer_name']").val();
           var customer_password = $("input[name='customer_password']").val();
           var _token = $('meta[name="csrf-token"]').attr('content'); 
           
           $.ajax({
                url: '{{ url('user/login-customer') }}',
                method: 'POST',
                data: {
                    customer_name: customer_name,
                    customer_password: customer_password,
                    _token: _token,
                },
                success: function(data) {
                    if(data == 'error'){
                        message_toastr("warning", "Tên Tài Khoản Hoặc Mật Khẩu Không Chính Xác !", "Cảnh Báo !");
                    }else{
                        message_toastr("success", "Đăng Nhập Thành Công !", "Thành Công !");
                       /* Chưa Xong */
                    }
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })


        });
    </script> --}}


</body>

</html>
