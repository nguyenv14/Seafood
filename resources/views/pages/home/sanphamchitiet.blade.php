@extends('pages.page_layout')
@section('web_content')
    <link rel="stylesheet" href="{{ asset('public/fontend/assets/css/sanphamchitiet.css') }}">
    <style>
        a {
            text-decoration: none;
        }

        .date {
            font-size: 11px
        }

        .comment-text {
            font-size: 12px
        }

        .fs-12 {
            font-size: 12px
        }

        .shadow-none {
            box-shadow: none
        }

        .name {
            color: #007bff
        }

        .cursor:hover {
            color: blue
        }

        .cursor {
            cursor: pointer
        }

        .textarea {
            resize: none
        }
    </style>
    <style>
        .box-cmt {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .comment_title {
            width: 1130px;
            padding: 5px;
        }

        .comment_content {
            margin-top: 5px;
            width: 1130px;
            padding: 5px;
        }

        .custom-btn {
            width: 130px;
            height: 40px;
            color: #fff;
            border-radius: 5px;
            padding: 10px 25px;
            font-family: 'Lato', sans-serif;
            font-weight: 500;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: inline-block;
            box-shadow: inset 2px 2px 2px 0px rgba(255, 255, 255, .5),
                7px 7px 20px 0px rgba(0, 0, 0, .1),
                4px 4px 5px 0px rgba(0, 0, 0, .1);
            outline: none;
        }

        /* 11 */
        .btn-11 {
            border: none;
            background: rgb(251, 33, 117);
            background: linear-gradient(0deg, rgba(251, 33, 117, 1) 0%, rgba(234, 76, 137, 1) 100%);
            color: #fff;
            overflow: hidden;
        }

        .btn-11:hover {
            text-decoration: none;
            color: #fff;
        }

        .btn-11:before {
            position: absolute;
            content: '';
            display: inline-block;
            top: -180px;
            left: 0;
            width: 30px;
            height: 100%;
            background-color: #fff;
            animation: shiny-btn1 3s ease-in-out infinite;
        }

        .btn-11:hover {
            opacity: .7;
        }

        .btn-11:active {
            box-shadow: 4px 4px 6px 0 rgba(255, 255, 255, .3),
                -4px -4px 6px 0 rgba(116, 125, 136, .2),
                inset -4px -4px 6px 0 rgba(255, 255, 255, .2),
                inset 4px 4px 6px 0 rgba(0, 0, 0, .2);
        }


        @-webkit-keyframes shiny-btn1 {
            0% {
                -webkit-transform: scale(0) rotate(45deg);
                opacity: 0;
            }

            80% {
                -webkit-transform: scale(0) rotate(45deg);
                opacity: 0.5;
            }

            81% {
                -webkit-transform: scale(4) rotate(45deg);
                opacity: 1;
            }

            100% {
                -webkit-transform: scale(50) rotate(45deg);
                opacity: 0;
            }
        }
    </style>

    <div class="menumin">
        <div class="menumin-box">
            <ul class="menumin-box-ul">
                <li class="menumin-box-li">Sản Phẩm</li>
                @if ($flashsale_product != null)
                    <li class="menumin-box-li"><i class="fa-solid fa-angle-right"></i></li>
                    <li class="menumin-box-li">Flash sale</li>
                @endif
                <li class="menumin-box-li"><i class="fa-solid fa-angle-right"></i></li>
                <li class="menumin-box-li">{{ $products->product->category->category_name }}</li>
                <li class="menumin-box-li"> <i class="fa-solid fa-angle-right"></i></li>
                <li class="menumin-box-li">{{ $products->product->product_name }}</li>
            </ul>
        </div>
    </div>
    <div class="MeliaDanangBeachResort">
        <div class="MeliaDanangBeachResort_Box">
            <div class="MeliaDanangBeachResort-Title">
                <span>{{ $products->product->product_name }}</span>
            </div>
            <div class="MeliaDanangBeachResort-Star-Type-Box">
                <div style="margin-top: 10px; margin-bottom: 10px" class="MeliaDanangBeachResort-Type">
                    <span>{{ $products->product->category->category_name }}</span>
                </div>
            </div>

            <div class="MeliaDanangBeachResort-img-Box">
                <div class="MeliaDanangBeachResort-img MeliaDanangBeachResort-video ">
                    <img width="592px" height="366px" style="object-fit: cover;border-radius: 8px;"
                        src="{{ URL::to('public/fontend/assets/img/product/' . $products->product->product_image) }}"
                        alt="">
                    {{-- <video type="video/mp4" autoplay="" muted="" loop="" width="592px" height="366px"
                    src="assets/img/thongtinkhachsan/7324_MYTOUR.mp4"></video> --}}

                </div>
                <?php
                $temp = 0;
                ?>
                @foreach ($all_gallery_product as $key => $gal_pro)
                    <div id="show-detail-img{{ $key }}" class="MeliaDanangBeachResort-img">
                        <div style="margin-top:5px ">
                            <img width="284px" height="160px" style="object-fit: cover;border-radius: 8px;"
                                src="{{ URL::to('public/fontend/assets/img/product/' . $gal_pro->gallery_product_image) }}"
                                alt="{{ $gal_pro->gallery_product_name }}">
                        </div>
                    </div>
                    <?php
                    $temp++;
                    if ($temp == 4) {
                        break;
                    }
                    ?>
                @endforeach

            </div>
            <div class="cart-content">
                <div class="cart-content-left">
                    <div class="info_product">
                        <div class="content-box-bottom-bigbox">
                            <div class="content-bottom-bigbox-box">
                                <div class="content-bottom-bigbox-title">
                                    <span>Thông tin chi tiết</span>
                                </div>
                                <div class="content-bottom-bigbox-infobank">
                                    <div class="content-bottom-bigbox-infobank-left">
                                        <?php
                                        $product_unit = '';
                                        switch ($products->product->product_unit) {
                                            case '0':
                                                $product_unit = 'Con';
                                                break;
                                            case '1':
                                                $product_unit = 'Phần';
                                                break;
                                            case '2':
                                                $product_unit = 'khay';
                                                break;
                                            case '3':
                                                $product_unit = 'Túi';
                                                break;
                                            case '4':
                                                $product_unit = 'Kg';
                                                break;
                                            case '5':
                                                $product_unit = 'Gam';
                                                break;
                                            case '6':
                                                $product_unit = 'Combo';
                                                break;
                                            default:
                                                $product_unit = 'Bug Rùi :<';
                                                break;
                                        }
                                        ?>
                                        <span>Quy cách:</span>
                                    </div>
                                    <div class="content-bottom-bigbox-infobank-right">
                                        <span>{{ $products->product->product_unit_sold . ' ' . $product_unit }}</span>
                                    </div>
                                </div>
                                <div class="content-bottom-bigbox-infobank">
                                    <div class="content-bottom-bigbox-infobank-left">
                                        <span>Tình trạng:</span>
                                    </div>
                                    <div class="content-bottom-bigbox-infobank-right">
                                        <span>{{ $products->product_details_deliveryway }}</span>
                                    </div>
                                </div>
                                <div class="content-bottom-bigbox-infobank">
                                    <div class="content-bottom-bigbox-infobank-left">
                                        <span>Xuất xứ:</span>
                                    </div>
                                    <div class="content-bottom-bigbox-infobank-right">
                                        <span>{{ $products->product_details_origin }}</span>
                                    </div>
                                </div>
                                <div style="border-bottom: 1px solid;border-color:#cbd5e0; padding-bottom: 16px;"
                                    class="content-bottom-bigbox-infobank">
                                    <div class="content-bottom-bigbox-infobank-left">
                                        <span>Món ăn ngon:</span>
                                    </div>
                                    <div class="content-bottom-bigbox-infobank-right">
                                        <span>{{ $products->product_details_delicious_foods }}</span>
                                    </div>
                                </div>
                                <div class="content-bottom-bigbox-infobank">
                                    <div class="content-bottom-bigbox-infobank-left">
                                        <span>Giá:</span>
                                    </div>
                                    @if ($flashsale_product != null)
                                        <div id="price-nguyen" class="content-bottom-bigbox-infobank-right">
                                            <span>{{ number_format($flashsale_product->flashsale_product_price, 0, ',', '.') . ' ' . 'đ' }}</span>
                                        </div>
                                    @else
                                        <div id="price-nguyen" class="content-bottom-bigbox-infobank-right">
                                            <span>{{ number_format($products->product->product_price, 0, ',', '.') . ' ' . 'đ' }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="content-bottom-bigbox-infobank">
                                    <div class="content-bottom-bigbox-infobank-left">
                                        <span>Số lượng:</span>
                                    </div>
                                    <div class="content-bottom-bigbox-infobank-right">
                                        <span>
                                            <div class="buttons_added">
                                                <input class="minus is-form" type="button" value="-">
                                                <input aria-label="quantity" class="input-qty" max="99" min="1"
                                                    name="" type="number" value="1">
                                                <input class="plus is-form" type="button" value="+">
                                            </div>

                                        </span>
                                    </div>
                                </div>
                                <div class="content-bottom-bigbox-infobank">
                                    <div class="content-bottom-bigbox-infobank-left">

                                    </div>
                                    <div class="content-bottom-bigbox-infobank-right">
                                        <div style="margin-top: 5px;">
                                            <span><button class="btn-cart" data-product_id="{{ $products->product->product_id }}"><i class="fa-solid fa-cart-shopping"></i><span
                                                        style="margin-left: 3px;">Thêm Giỏ Hàng</span></button></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cart-content-right">
                    <div class="hotline_box">
                        <div class="hotline_box_text">
                            <div class="hotline_text">Tư Vấn Đặt Hàng</div>
                        </div>
                        <div class="hotline_bottom">
                            <div class="hotline_img">

                                <img style="object-fit: cover;width: 40px;height: 40px;"
                                    src=" {{ asset('public/fontend/assets/img/icon/hotline-icon.webp') }}" alt="">
                            </div>
                            <div class="hotline_text_box">
                                <div class="hotline_text_phone">
                                    <span>19001009</span>
                                </div>
                                <div class="hotline_text_text">
                                    <span>(8h-21h từ T2-Chủ Nhật)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hotline_box_bottom">

                        <div class="hotline_bottom_vip">
                            <div class="hotline_img">

                                <img style="object-fit: cover;width: 35px;height: 35px;"
                                    src="{{ asset('public/fontend/assets/img/icon/thuonghieu.webp') }}" alt="">
                            </div>
                            <div class="hotline_text_box">
                                <div class="hotline_text_phone_vip">
                                    <span>Thương Hiệu Hàng Đầu</span>
                                </div>
                                <div class="hotline_text_text_vip">
                                    <span>Bản Lẻ Hải Sản</span>
                                </div>
                            </div>
                        </div>

                        <div class="hotline_bottom_vip">
                            <div class="hotline_img">

                                <img style="object-fit: cover;width: 35px;height: 35px;"
                                    src=" {{ asset('public/fontend/assets/img/icon/doitra.webp') }}" alt="">
                            </div>
                            <div class="hotline_text_box">
                                <div class="hotline_text_phone_vip">
                                    <span>Đổi Trả Miễn Phí Và Tận Nhà</span>
                                </div>
                                <div class="hotline_text_text_vip">
                                    <span>Nhanh & Miễn Phí</span>
                                </div>
                            </div>
                        </div>

                        <div class="hotline_bottom_vip">
                            <div class="hotline_img">
                                <img style="object-fit: cover;width: 35px;height: 35px;"
                                    src=" {{ asset('public/fontend/assets/img/icon/giaohang.webp') }}" alt="">
                            </div>
                            <div class="hotline_text_box">
                                <div class="hotline_text_phone_vip">
                                    <span>Hơn 300 Sản Phẩm Từ Hải Sản</span>
                                </div>
                                <div class="hotline_text_text_vip">
                                    <span>Phong Phú, An Toàn, Chất Lượng</span>
                                </div>
                            </div>
                        </div>

                        <div class="hotline_bottom_vip" style="text-overflow: ellipsis">
                            <div class="hotline_img">
                                <img style="object-fit: cover;width: 35px;height: 35px;"
                                    src=" {{ asset('public/fontend/assets/img/icon/hon300.webp') }}" alt="">
                            </div>
                            <div class="hotline_text_box">
                                <div class="hotline_text_phone_vip">
                                    <span>Giao Hàng Tận Nhà 2H</span>
                                </div>
                                <div class="hotline_text_text_vip">
                                    <span>Hóa Đơn Từ 150.000đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="hotline_box_bottom">
                    <div class="img-store">
                         
                        <img style="object-fit: cover; width: 234px;" src="{{ asset('public/fontend/assets/img/icon/nhqa-icon.webp') }}" alt="">
                    </div>
                    <div>
                        <div class="store_text_title">
                            Nhà Hàng Và Mua Sĩ
                        </div>
                        <div class="store_text">
                            Liên Hệ Qua Số 19001099
                        </div>
                        <div class="store_text">
                            Để Được Tư Vấn Và Báo Giá Tốt
                        </div>
                    </div>
                </div>  --}}

                </div>
            </div>
        </div>
    </div>

    <div class="ShowImgHotel">
        <div class="ShowImgHotel-Box">
            <div class="ShowImgHotel-js owl-carousel owl-theme">
                @foreach ($all_gallery_product as $gal_pro)
                    <div class="item">
                        <img width="1100px" height="628px" style="object-fit: cover;border-radius: 8px;"
                            src="{{ URL::to('public/fontend/assets/img/product/' . $gal_pro->gallery_product_image) }}"
                            alt="{{ $gal_pro->gallery_product_name }}">
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <div id="chooserooms" class="chooserooms">
        <div class="chooseroomsbox">
            <div class="chooseroomsbox-Title">
                <span>Mô Tả Sản Phẩm</span>
            </div>
            <div class="chooseroomsbox-boxcontent">
                <div class="chooseroomsbox-boxcontent-top">
                    <i class="fa-solid fa-star"></i>
                    <span style="margin-left: 5px ; margin-top: 2px;">Tên Sản Phẩm</span>
                </div>
                <div class="chooseroomsbox-boxcontent-bottom">
                    @foreach ($all_gallery_product as $gal_pro)
                        <div style="display: flex; justify-items: center;justify-content: center;align-content: center;align-items: center;padding: 20px"
                            class="flashsalehotel_img-box">
                            <img class="flashsalehotel_img" width="500" height="auto" style="object-fit: cover;"
                                src="{{ URL::to('public/fontend/assets/img/product/' . $gal_pro->gallery_product_image) }}"
                                alt="">
                        </div>
                        @if ($gal_pro->gallery_product_content != 'Ảnh này chưa có nội dung !')
                            <div
                                style="display: flex; justify-items: center;justify-content: center;align-content: center;align-items: center;padding: 20px 20px 40px 20px ; margin-left:10% ;margin-right:10% ">
                                <span style="text-align: center">{{ $gal_pro->gallery_product_content }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="Danhgia">
        <div class="Danhgiabox">
            <div class="DanhgiaBox-Title_Text">
                <div class="Danhgia-Title">
                    <span>Đánh giá</span>
                </div>
                <div class="Danhgia-Text">
                    <span>100% đánh giá từ khách hàng mua hải sản trên Thế Giới Hải Sản</span>
                </div>
            </div>
            <div class="imgusers">
                <div class="imgusers-box">
                    <div class="imgusers-box-left">
                        {{-- <div class="imgusers-box-left-text">
                        <span>Ảnh người dùng đánh giá</span>
                    </div> --}}
                        <div class="imgusers-box-left-img">
                            {{-- <div class="imgusers-box-left-img-item">
                            <img width="56px" height="56px" style="border-radius: 5px;object-fit: cover ;"
                                src="assets/img/datphong/anhnguoidung1.jpg" alt="">
                        </div>
                        <div class="imgusers-box-left-img-item">
                            <img width="56px" height="56px" style="border-radius: 5px;object-fit: cover ;"
                                src="assets/img/datphong/anhnguoidung2.jpg" alt="">
                        </div>
                        <div class="imgusers-box-left-img-item">
                            <img width="56px" height="56px" style="border-radius: 5px;object-fit: cover ;"
                                src="assets/img/datphong/anhnguoidung3.jpg" alt="">
                        </div>
                        <div class="imgusers-box-left-img-item">
                            <img width="56px" height="56px" style="border-radius: 5px;object-fit: cover ;"
                                src="assets/img/datphong/anhnguoidung4.jpg" alt="">
                        </div>
                        <div class="imgusers-box-left-img-item">
                            <img width="56px" height="56px" style="border-radius: 5px;object-fit: cover ;"
                                src="assets/img/datphong/anhnguoidung5.jpg" alt="">
                        </div> --}}
                        </div>
                    </div>
                    <div class="imgusers-box-right">
                        <div class="imgusers-box-right-box">
                            <ul class="imgusers-box-right-box-ul">
                                <li class="imgusers-box-right-box-li">Mới nhất</li>
                                <li class="imgusers-box-right-box-li">Cũ nhất</li>
                                <li class="imgusers-box-right-box-li">Điểm cao nhất</li>
                                <li class="imgusers-box-right-box-li">Điểm thấp nhất</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="load_comment">

            </div>

            <a href="#" class="seemore">
                <div class="seemore-btn">
                    <span>Xem thêm đánh giá</span>
                </div>
            </a>


            @if (Session::get('customer_id') != null)
                <?php
                $result = explode(' ', Session::get('customer_name'));
                $result = end($result);
                ?>
                {{-- <div>
                    <div class="d-flex justify-content-center row p-4">
                        <div class="col-md-12">
                            <div class="d-flex flex-column comment-section">
                                <div class="p-2">
                                    <div class="d-flex flex-row align-items-start">
                                        <div class="userswrite-boxone-imgusers">
                                            <div class="userswrite-boxone-imgusers-element">
                                                <span>{{ $result[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div>
                                                <input class="form-control ml-1 shadow-none comment_title" type="text"
                                                    placeholder="Tiêu Đề">
                                            </div>
                                            <textarea rows="4" class="form-control ml-1 shadow-none textarea mt-2 comment_content" placeholder="Nội Dung"></textarea>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-right">
                                        <button id="comment_post" class="btn btn-primary btn-sm shadow-none"
                                            type="button">Đăng Bình
                                            Luận</button>
                                        <button class="btn btn-outline-primary btn-sm ml-1 shadow-none"
                                            type="button">Quay
                                            Lại</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}



                <div class="box-cmt">
                    <div>
                        <div class="userswrite-boxone-imgusers">
                            <div class="userswrite-boxone-imgusers-element">
                                <span>{{ $result[0] }}</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-left: 10px">
                        <div class="box-input">
                            <div>
                                <input class="comment_title" type="text" placeholder="Tiêu Đề">
                            </div>
                            <textarea rows="4" class="comment_content" placeholder="Nội Dung"></textarea>
                        </div>
                        <div>
                            <button id="comment_post" class="custom-btn btn-11" type="button">Bình
                                Luận</button>
                            <button class="custom-btn btn-11" type="button">Quay
                                Lại</button>
                        </div>
                    </div>
            @endif


        </div>
    </div>

    <div class="contentflashsale">
        <div class="contentboxflashsale">
            <div class="flashsaletop-content">
                <div class="flashsaletop-left">
                    <div class="flashsaletop-left-img">
                        <h2>Sản Phẩm Cùng Danh Mục</h2>
                    </div>
                </div>
            </div>

        </div>

        <div class="flashsalehotel-card-box">
            <div class="flashsalehotel flashsalehotel-js owl-carousel owl-theme">
                @foreach ($all_product_by_category as $key => $product)
                    <div class="flashsalehotel_boxcontent flashsalehotel_boxcontent_hover item">
                        <div class="flashsalehotel_boxcontent_img_text">
                            <a
                                href="{{ URL::to('/san-pham/san-pham-chi-tiet-flash-sale?product_id=' . $product->product_id) }}">
                                <div class="flashsalehotel_img-box">
                                    <img class="flashsalehotel_img" width="284px" height="160px"
                                        style="object-fit: cover;"
                                        src="{{ URL::to('public/fontend/assets/img/product/' . $product->product_image) }}"
                                        alt="">
                                </div>
                                <div class="flashsalehotel_text_sale">
                                    <div class="flashsalehotel_text-title">
                                        {{ $product->product_name }}
                                    </div>
                            </a>
                            <div class="flashsalehotel_place">
                                <div>
                                    <i class="fa-solid fa-certificate"></i>
                                    {{ $product->category->category_name }}
                                </div>
                            </div>
                            <div class="flashsalehotel_text-evaluate">
                                <div class="flashsalehotel_text-evaluate-icon">
                                    <i class="fa-solid fa-star"></i>8.5
                                </div>
                                <div class="flashsalehotel_text-evaluate-text">
                                    Tuyệt vời <span style=" color:#4a5568;">(425 đánh giá)</span>
                                </div>
                            </div>
                            <div class="flashsalehotel_text-time">
                                Trạng Thái Như Thế Nào ?
                            </div>
                            <div class="flashsalehotel_text-box-price">
                                <div class="product_price_sale">

                                </div>
                                <div style="display: flex;">
                                    <div class="flashsalehotel_text-box-price-two">
                                        <span>{{ number_format($product->product_price, '0', ',', '.') . 'đ' }}</span>
                                    </div>
                                    <div class="flashsalehotel_text-box-price-one">
                                        <span>/</span>
                                    </div>
                                    <div class="flashsalehotel_text-box-price-one">
                                        <?php
                                        $product_unit = '';
                                        switch ($product->product_unit) {
                                            case '0':
                                                $product_unit = 'Con';
                                                break;
                                            case '1':
                                                $product_unit = 'Phần';
                                                break;
                                            case '2':
                                                $product_unit = 'khay';
                                                break;
                                            case '3':
                                                $product_unit = 'Túi';
                                                break;
                                            case '4':
                                                $product_unit = 'Kg';
                                                break;
                                            case '5':
                                                $product_unit = 'Gam';
                                                break;
                                            case '6':
                                                $product_unit = 'Combo';
                                                break;
                                            default:
                                                $product_unit = 'Bug Rùi :<';
                                                break;
                                        }
                                        ?>
                                        <span>{{ $product->product_unit_sold . ' ' . $product_unit }}</span>
                                    </div>
                                </div>
                                <div class="flashsalehotel_text-box-price-three bordernhay">
                                    <div style="margin-left: 8px;" class="flashsalehotel_text-box-price-three-l chunhay">
                                        <div class="cart-hover">
                                            <i class="fa-solid fa-heart"></i>
                                            <span style="font-size: 14px;">Yêu Thích</span>
                                        </div>
                                    </div>
                                    <div class="flashsalehotel_text-box-price-three-r chunhay">
                                        <div class="cart-hover">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                            <span style="font-size: 14px;">Đặt Hàng</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            @endforeach
        </div>
    </div>

    <div style="margin-top:120px ; padding-bottom: 100px">
        <?php
        $recentlyviewed = session()->get('recentlyviewed');
        ?>
        @if ($recentlyviewed != null)
            <div class="recentlyviewed">
                <div class="recentlyviewed_box">
                    <div class="recentlyviewed_title">
                        <span class="recentlyviewed_title">Xem Gần Đây</span>
                    </div>
                    <div class="recentlyviewed_boxcontent-boxslider">
                        <div class="recentlyviewed_boxcontent owl-carousel owl-theme">
                            @foreach ($recentlyviewed as $value)
                                <a href="{{ URL::to('/san-pham/san-pham-chi-tiet?product_id=' . $value['product_id']) }}">
                                    <div class="recentlyviewed_boxcontent-item item">
                                        <div class="recentlyviewed_boxcontent-item-img-box">
                                            <img class="recentlyviewed_boxcontent-item-img" width="178px" height="133px"
                                                style="object-fit: cover;"
                                                src="{{ URL::to('public/fontend/assets/img/product/' . $value['product_image']) }}"
                                                alt="">
                                        </div>
                                        <div class="recentlyviewed_boxcontent-item-title">
                                            <span>{{ $value['product_name'] }}</span>
                                        </div>
                                        <div class="recentlyviewed_boxcontent-item-star">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <div class="recentlyviewed_boxcontent-item-place">
                                            <i class="fa-solid fa-location-dot"></i>
                                            <span>{{ $value['category_name'] }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- Số Lượng Ở Trang Chi Tiết Sản Phẩm --}}
    <script>
        $('input.input-qty').each(function() {
            var $this = $(this),
                qty = $this.parent().find('.is-form'),
                min = Number($this.attr('min')),
                max = Number($this.attr('max'))
            if (min == 0) {
                var d = 0
            } else d = min
            $(qty).on('click', function() {
                if ($(this).hasClass('minus')) {
                    if (d > min) d += -1
                } else if ($(this).hasClass('plus')) {
                    var x = Number($this.val()) + 1
                    if (x <= max) d += 1
                }
                $this.attr('value', d).val(d)
            })
        })
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

          /* Thêm Sản Phẩm Vào Giỏ Hàng */
          $(document).on("click", '.btn-cart', function() {
            var product_id = $(this).data('product_id');
            var product_qty = $('.input-qty').val();
            var _token = $('meta[name="csrf-token"]').attr('content');

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

    </script>

    <script>
        $('#show-detail-img3').click(function() {
            $('.ShowImgHotel').show();
            $('#overlay').show();
        });

        $('#overlay').click(function() {
            $('.ShowImgHotel').hide();
        });
    </script>

    <script>
        loading_comment();
        // Loading All Comment By ID Product 
        function loading_comment() {
            var product_id = {{ $products->product->product_id }};
            $.ajax({
                url: '{{ url('/san-pham/tai-binh-luan') }}',
                method: 'get',
                data: {
                    product_id: product_id,
                },
                success: function(data) {
                    $('.load_comment').html(data);
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        }

        $('#comment_post').click(function() {

            var comment_title = $('.comment_title').val();
            var comment_content = $('.comment_content').val();
            var _token = $('meta[name="csrf-token"]').attr('content');
            var product_id = {{ $products->product->product_id }};

            $.ajax({
                url: '{{ url('/san-pham/dang-binh-luan') }}',
                method: 'POST',
                data: {
                    comment_content: comment_content,
                    comment_title: comment_title,
                    product_id: product_id,
                    _token: _token,
                },
                success: function(data) {
                    loading_comment();
                    message_toastr("success", "Bình Luận Của Bạn Đang Chờ Xét Duyệt !",
                        "Bình Luận Thành Công !");
                    $('.comment_content').val('');
                    $('.comment_title').val('');
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })

        });
    </script>
@endsection
