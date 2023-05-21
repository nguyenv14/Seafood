@extends('pages.page_layout')
@section('web_content')
    <div>
        <div class="contentflashsale">
            <div class="contentboxflashsale">
                <div class="flashsaletop-content">
                    <div class="flashsaletop-left">
                        <div class="flashsaletop-left-img">
                            <img style="object-fit:cover ;" width="198px" height="44px"
                                src="{{ asset('public/fontend/assets/img/icon/icon_flashSale_home_white_new.png') }}"
                                alt="">
                        </div>
                        <div class="flashsaletop-left-text chunhay">
                            Chương trình sẽ diễn ra trong : 2 ngày
                        </div>
                    </div>
                </div>
                <div class="flashsaletop-right-content">
                    <div class="flashsaletop-right res-flashsaletop-right-top-1213">
                        <div class="flashsaletop-right-top ">
                            12:00-13:00
                        </div>
                        <div class="flashsaletop-right-bottom">
                            Đã kết thúc
                        </div>
                    </div>
                    <div class="flashsaletop-right res-flashsaletop-right-top-902">
                        <div class="flashsaletop-right-top">
                            15:00-16:00
                        </div>
                        <div class="flashsaletop-right-bottom">
                            Đã kết thúc
                        </div>
                    </div>
                    <div class="flashsaletop-right">
                        <div class="flashsaletop-right-top">
                            21:00-22:00
                        </div>
                        <div class="flashsaletop-right-bottom">
                            Sắp diễn ra
                        </div>
                    </div>
                    <div class="flashsaletop-right">
                        <div class="flashsaletop-right-top">
                            09:00-10:00
                        </div>
                        <div class="flashsaletop-right-bottom">
                            11/5
                        </div>
                    </div>
                    <div class="flashsaletop-right res-flashsaletop-right">
                        <div class="flashsaletop-right-top">
                            12:00-13:00
                        </div>
                        <div class="flashsaletop-right-bottom">
                            11/5
                        </div>
                    </div>
                </div>
            </div>
            <div class="flashsalehotel-card-box">
                <div class="flashsalehotel flashsalehotel-js owl-carousel owl-theme">
                    @foreach ($flashsale as $key => $flashsale)
                        <div class="flashsalehotel_boxcontent flashsalehotel_boxcontent_hover item">
                            <div class="flashsalehotel_boxcontent_img_text">
                                <a
                                    href="{{ URL::to('/san-pham/san-pham-chi-tiet?product_id=' . $flashsale->product_id) }}">
                                    <div class="flashsalehotel_img-box">
                                        <img class="flashsalehotel_img" width="300px" height="200px"
                                            style="object-fit: cover;"
                                            src="{{ URL::to('public/fontend/assets/img/product/' . $flashsale->product->product_image) }}"
                                            alt="">
                                    </div>
                                    <div class="flashsalehotel_text_sale">
                                        <div class="flashsalehotel_text-title">
                                            {{ $flashsale->product->product_name }}
                                        </div>
                                </a>
                                <div class="flashsalehotel_place">
                                    <div>
                                        <i class="fa-solid fa-certificate"></i>
                                        {{ $flashsale->product->category->category_name }}
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
                                        <span>{{ number_format($flashsale->product->product_price, '0', ',', '.') . 'đ' }}</span>
                                    </div>
                                    <div style="display: flex;">
                                        <div class="flashsalehotel_text-box-price-two">
                                            <span>{{ number_format($flashsale->flashsale_product_price, '0', ',', '.') . 'đ' }}</span>
                                        </div>
                                        <div class="flashsalehotel_text-box-price-one">
                                            <span>/</span>
                                        </div>
                                        <div class="flashsalehotel_text-box-price-one">
                                            <?php
                                            $product_unit = '';
                                            switch ($flashsale->product->product_unit) {
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
                                            <span>{{ $flashsale->product->product_unit_sold . ' ' . $product_unit }}</span>
                                        </div>
                                    </div>
                                    <div class="flashsalehotel_text-box-price-three bordernhay">
                                        <div style="margin-left: 8px;"
                                            class="flashsalehotel_text-box-price-three-l chunhay">
                                            <div class="cart-hover">
                                                <i class="fa-solid fa-heart"></i>
                                                <span style="font-size: 14px;">Yêu Thích</span>
                                            </div>
                                        </div>
                                        <div class="flashsalehotel_text-box-price-three-r chunhay">
                                            <div class="cart-hover">
                                                <i class="fa-solid fa-cart-shopping"></i>
                                                <span style="font-size: 14px;"
                                                    data-product_id="{{ $flashsale->product->product_id }}"
                                                    class="button_cart">Đặt Hàng</span>
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
    </div>


    <div>
        <div class="hottelcodesale">
            <div class="hottelcodesale_box">
                <div class="hottelcodesale_box-title">
                    <span class="hottelcodesale_box-title">Mã giảm giá cho bạn</span>
                </div>
                <div class="hottelcodesale_box-content">
                    @foreach ($coupons as $coupon)
                        <div class="hottelcodesale_box-content-left">
                            <div class="hottelcodesale_box-content-left-content1">
                                <div class="hottelcodesale_box-content-left-content1-text">
                                    <span>Nhập mã </span>
                                </div>
                                <div class="hottelcodesale_box-content-left-content1-block">
                                    <span>{{ $coupon->coupon_name_code }}</span>
                                </div>
                            </div>
                            <div class="hottelcodesale_box-content-left-content2">
                                <div class="hottelcodesale_box-content-left-content2-l">
                                    <span>{{ $coupon->coupon_desc }}</span>
                                </div>
                                <div class="hottelcodesale_box-content-left-content2-r">
                                    <span class="hottelcodesale_box-content-left-content2-r">Điều kiện và thể lệ chương
                                        trình</span>
                                </div>
                            </div>
                            <div class="hottelcodesale_box-content-left-content3">
                                <span class="hottelcodesale_box-content-left-content3">Chỉ còn
                                    {{ $coupon->coupon_qty_code }}
                                    mã | Nhập mã trước khi
                                    thanh toán</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="contentflashsale" style="height: auto;padding-bottom: 130px;border-radius: 8px;margin-left: 20px;margin-right: 20px;">
            <div class="contentboxflashsale">
                <div class="flashsaletop-content">
                    <div class="flashsaletop-left">
                        <div class="hottelpricesbox-contenttop-title">
                            <span class="hottelpricesbox-contenttop-title">Sản phẩm bán chạy</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flashsalehotel-card-box">
                <div class="flashsalehotel flashsalehotel-js owl-carousel owl-theme">
                    @foreach ($best_sale_product as $key => $product)
                        <div class="flashsalehotel_boxcontent flashsalehotel_boxcontent_hover item">
                            <div class="flashsalehotel_boxcontent_img_text">
                                <a href="{{ URL::to('/san-pham/san-pham-chi-tiet?product_id='.$product->product_id) }}">
                                    <div class="flashsalehotel_img-box">
                                        <img class="flashsalehotel_img" width="300px" height="200px"
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
                                            @php
                                                $product_unit = '';
                                                switch ($flashsale->product->product_unit) {
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
                                            @endphp
                                            <span>{{ $flashsale->product->product_unit_sold . ' ' . $product_unit }}</span>
                                        </div>
                                    </div>
                                    <div class="flashsalehotel_text-box-price-three bordernhay">
                                        <div style="margin-left: 8px;"
                                            class="flashsalehotel_text-box-price-three-l chunhay">
                                            <div class="cart-hover">
                                                <i class="fa-solid fa-heart"></i>
                                                <span style="font-size: 14px;">Yêu Thích</span>
                                            </div>
                                        </div>
                                        <div class="flashsalehotel_text-box-price-three-r chunhay">
                                            <div class="cart-hover">
                                                <i class="fa-solid fa-cart-shopping"></i>
                                                <span style="font-size: 14px;"
                                                    data-product_id="{{ $product->product_id }}" class="button_cart">Đặt
                                                    Hàng</span>
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
    </div>

    <div style="margin-top: 20px">
        <div class="contentflashsale" style="height: auto;padding-bottom: 130px;border-radius: 8px;margin-left: 20px;margin-right: 20px;">
            <div class="contentboxflashsale">
                <div class="flashsaletop-content">
                    <div class="flashsaletop-left">
                        <div class="hottelpricesbox-contenttop-title">
                            <span class="hottelpricesbox-contenttop-title">Sản phẩm đang thịnh hành</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flashsalehotel-card-box">
                <div class="flashsalehotel flashsalehotel-js owl-carousel owl-theme">
                    @foreach ($viewer_product as $key => $product)
                        <div class="flashsalehotel_boxcontent flashsalehotel_boxcontent_hover item">
                            <div class="flashsalehotel_boxcontent_img_text">
                                <a href="{{ URL::to('/san-pham/san-pham-chi-tiet?product_id='.$product->product_id) }}">
                                    <div class="flashsalehotel_img-box">
                                        <img class="flashsalehotel_img" width="300px" height="200px"
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
                                            @php
                                                $product_unit = '';
                                                switch ($flashsale->product->product_unit) {
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
                                            @endphp
                                            <span>{{ $flashsale->product->product_unit_sold . ' ' . $product_unit }}</span>
                                        </div>
                                    </div>
                                    <div class="flashsalehotel_text-box-price-three bordernhay">
                                        <div style="margin-left: 8px;"
                                            class="flashsalehotel_text-box-price-three-l chunhay">
                                            <div class="cart-hover">
                                                <i class="fa-solid fa-heart"></i>
                                                <span style="font-size: 14px;">Yêu Thích</span>
                                            </div>
                                        </div>
                                        <div class="flashsalehotel_text-box-price-three-r chunhay">
                                            <div class="cart-hover">
                                                <i class="fa-solid fa-cart-shopping"></i>
                                                <span style="font-size: 14px;"
                                                    data-product_id="{{ $product->product_id }}" class="button_cart">Đặt
                                                    Hàng</span>
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
    </div>


    <div style="margin-top: 20px">
        <div class="contentflashsale" style="height: auto;padding-bottom: 130px;border-radius: 8px;margin-left: 20px;margin-right: 20px;">
            <div class="contentboxflashsale">
                <div class="flashsaletop-content">
                    <div class="flashsaletop-left">
                        <div class="hottelpricesbox-contenttop-title">
                            <span class="hottelpricesbox-contenttop-title">Sản phẩm mới</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flashsalehotel-card-box">
                <div class="flashsalehotel flashsalehotel-js owl-carousel owl-theme">
                    @foreach ($new_product as $key => $product)
                        <div class="flashsalehotel_boxcontent flashsalehotel_boxcontent_hover item">
                            <div class="flashsalehotel_boxcontent_img_text">
                                <a href="{{ URL::to('/san-pham/san-pham-chi-tiet?product_id='.$product->product_id) }}">
                                    <div class="flashsalehotel_img-box">
                                        <img class="flashsalehotel_img" width="300px" height="200px"
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
                                            @php
                                                $product_unit = '';
                                                switch ($flashsale->product->product_unit) {
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
                                            @endphp
                                            <span>{{ $flashsale->product->product_unit_sold . ' ' . $product_unit }}</span>
                                        </div>
                                    </div>
                                    <div class="flashsalehotel_text-box-price-three bordernhay">
                                        <div style="margin-left: 8px;"
                                            class="flashsalehotel_text-box-price-three-l chunhay">
                                            <div class="cart-hover">
                                                <i class="fa-solid fa-heart"></i>
                                                <span style="font-size: 14px;">Yêu Thích</span>
                                            </div>
                                        </div>
                                        <div class="flashsalehotel_text-box-price-three-r chunhay">
                                            <div class="cart-hover">
                                                <i class="fa-solid fa-cart-shopping"></i>
                                                <span style="font-size: 14px;"
                                                    data-product_id="{{ $product->product_id }}" class="button_cart">Đặt
                                                    Hàng</span>
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
    </div>


    <div class="hottelprices">
        <div class="hottelpricesbox">
            <div class="hottelpricesbox-contenttop">
                <div class="hottelpricesbox-contenttop-title">
                    <span class="hottelpricesbox-contenttop-title">Sản phẩm giá tốt chỉ có trên Thế Giới Hải Sản</span>
                </div>
                <div class="hottelpricesbox-contenttop-img">
                    <img width="auto" height="86px" style="object-fit: cover;"
                        src="{{ asset('public/fontend/assets/img/banner-right.png') }}" alt="">
                </div>
            </div>
            <div class="hottelpricesbox-contentbottom">
                <div class="hottelpricesbox-contentbottom-layout">
                    @foreach ($best_price_product as $key => $product)
                        <div class="hottelpricesbox-contentbottom-layout-item">
                            <div class="hottelpricesbox-boxcontent_img_text">
                                <a href="{{ URL::to('san-pham/san-pham-chi-tiet?product_id=' . $product->product_id) }}">
                                    <div class="hottelpricesbox-img_{{ $key + 1 }}">
                                        <div class="hottelpricesbox-img_box_top" style="margin-left: 10px">
                                            <div class="hottelpricesbox-love">
                                                <i class="fa-solid fa-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hottelpricesbox-text">
                                        <div class="hottelpricesbox-text-title">
                                            {{ $product->product_name }}
                                        </div>
                                </a>
                                <div class="hottelpricesbox-place">
                                    <div>
                                        <i class="fa-solid fa-certificate"></i>
                                        {{ $product->category->category_name }}
                                    </div>
                                </div>
                                <div class="hottelpricesbox-evaluate">
                                    <div class="hottelpricesbox-text-evaluate-icon">
                                        <i class="fa-solid fa-star"></i>8.5
                                    </div>
                                    <div class="hottelpricesbox-text-evaluate-text">
                                        Tuyệt vời <span style=" color:#4a5568;">(573 đánh giá)</span>
                                    </div>
                                </div>
                                <div class="hottelpricesbox-text-time">
                                    Vừa đặt cách đây vài ngày trước
                                </div>
                                <div class="flashsalehotel_text-box-price">
                                    <div style="display: flex;margin-top: 35px">
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
                                        <div style="margin-left: 8px;"
                                            class="flashsalehotel_text-box-price-three-l chunhay">
                                            <div class="cart-hover">
                                                <i class="fa-solid fa-heart"></i>
                                                <span style="font-size: 14px;">Yêu Thích</span>
                                            </div>
                                        </div>
                                        <div class="flashsalehotel_text-box-price-three-r chunhay">
                                            <div class="cart-hover">
                                                <i class="fa-solid fa-cart-shopping"></i>
                                                <span style="font-size: 14px;"
                                                    data-product_id="{{ $product->product_id }}" class="button_cart">Đặt
                                                    Hàng</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <style>
                    .hottelpricesbox-img_{{ $key + 1 }} {
                        width: 284px;
                        height: 160px;
                        background-image: url({{ asset('public/fontend/assets/img/product/' . $product->product_image) }});
                        background-size: 100%;
                        border-radius: 10px;
                        object-fit: cover;
                    }

                    .fix {}
                </style>
                @endforeach
            </div>
        </div>
    </div>
    </div>


    @php
        $recentlyviewed = session()->get('recentlyviewed');
    @endphp
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
    <div class="inspiration">
        <div class="inspiration-box">
            <div class="inspiration-box-top">
                <div class="inspiration-box-top-title">
                    <span>Có Thể Bạn Đã Biết</span>
                </div>
                <div class="inspiration-box-top-text">
                    <span>Bí quyết nấu ăn, chia sẽ kinh nghiệm và những câu chuyện thú vị đang chờ đón bạn</span>
                </div>
            </div>
            <div class="inspiration-box-bottom">
                <div class="inspiration-box-bottom-left">
                    <a href="">
                        <div class="inspiration-box-bottom-left-zoom">
                            <div class="inspiration-box-bottom-left-img">
                                <img width="586px" height="380px" style="object-fit: cover;border-radius: 8px;"
                                    src=" {{ URL::to('public/fontend/assets/img/News/anh-hai-san.webp') }}"
                                    alt="">
                            </div>
                        </div>
                        <div class="inspiration-box-bottom-left-text">
                            <span>5 cách ướp hải sản nướng đậm vị, thơm lừng không phải ai cũng biết</span>
                        </div>
                    </a>
                </div>
                <div class="inspiration-box-bottom-right">
                    <a href="">
                        <div class="inspiration-box-bottom-right-item">
                            <div class="inspiration-box-bottom-right-item-zoom">
                                <div class="inspiration-box-bottom-right-item-img">
                                    <img width="285px" height="152px" style="object-fit: cover;border-radius: 8px;"
                                        src="assets/img/News/cach-chon-cua-ghe-nhieu-thit-it-ai-biet1600240042.jpeg"
                                        alt="">
                                </div>
                            </div>
                            <div class="inspiration-box-bottom-right-item-text">
                                <span>Sashimi tôm hùm Alaska ngon tuyệt hảo</span>
                            </div>
                        </div>
                    </a>
                    <a href="">
                        <div class="inspiration-box-bottom-right-item">
                            <div class="inspiration-box-bottom-right-item-zoom">
                                <div class="inspiration-box-bottom-right-item-img">
                                    <img width="285px" height="152px" style="object-fit: cover;border-radius: 8px;"
                                        src="assets/img/News/nhung-luu-y-khi-so-che-hai-san1600223467.jpg" alt="">
                                </div>
                            </div>
                            <div class="inspiration-box-bottom-right-item-text">
                                <span>Trổ tài làm món gỏi bao tử cá ngừ chiêu đãi cả nhà ngày cuối tuần</span>
                            </div>
                        </div>
                    </a>
                    <a href="">
                        <div class="inspiration-box-bottom-right-item">
                            <div class="inspiration-box-bottom-right-item-zoom">
                                <div class="inspiration-box-bottom-right-item-img">
                                    <img width="285px" height="152px" style="object-fit: cover;border-radius: 8px;"
                                        src="assets/img/News/cach-chon-hai-san-ngon1600223450.jpg" alt="">
                                </div>
                            </div>
                            <div class="inspiration-box-bottom-right-item-text">
                                <span>Bào ngư om bông cải xanh</span>
                            </div>
                        </div>
                    </a>
                    <a href="">
                        <div class="inspiration-box-bottom-right-item">
                            <div class="inspiration-box-bottom-right-item-zoom">
                                <div class="inspiration-box-bottom-right-item-img">
                                    <img width="285px" height="152px" style="object-fit: cover;border-radius: 8px;"
                                        src="assets/img/News/top-6-mon-an-che-bien-tu-tom-hum1600223476.jpg"
                                        alt="">
                                </div>
                            </div>
                            <div class="inspiration-box-bottom-right-item-text">
                                <span>Bỏ túi ngay cách làm bạch tuộc xào dứa thơm ngon lạ miệng</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        /* Loading Thông Báo Số Lượng Trên Giỏ Hàng */
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
        $(document).on("click", '#btn-gocart', function() {
            window.location.href = "{{ url('DoAnCNWeb/cart') }}";
        });
    </script>
@endsection
