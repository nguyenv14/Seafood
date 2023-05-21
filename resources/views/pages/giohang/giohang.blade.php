@extends('pages.page_layout')
@section('web_content')
    <link rel="stylesheet" href="{{ asset('public/fontend/assets/css/cart.css') }}">
    <div id="content-cart">
        <div class="wrap cf">
            <div class="heading cf">
                <h1>My Cart</h1>
                <a href="{{ URL::to('/') }}" class="continue">Continue Shopping</a>
            </div>
            <div class="cart">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Tổng giá mỗi loại</th>

                            <th class="delete-all" style="cursor: pointer; color: #fff;"><i class="fas fa-trash-alt"></i>
                                Delete
                                all</th>
                        </tr>
                    </thead>
                    <tbody class="tbody loading-cart">

                    </tbody>
                </table>

            </div>
            <div class="title-hoa-don" style="">
               
            </div>
            <div class="hoa-don">

                <div class="thanh-toan">
                    <h1>Thông tin nhận hàng</h1>
                    <div class="input-items">
                        <input type="text" name="shipping_name" id="" placeholder="Tên Người Đặt Hàng" value="<?php if(session()->get('shipping') != null){$shipping = session()->get('shipping');echo$shipping['shipping_name'];} else if(isset($customer)) {echo $customer->customer_name;}?>">
                    </div>
                    <div class="input-items">
                        <input type="" name="shipping_phone" id="" placeholder="Số Điện Thoại"value="<?php if(session()->get('shipping') != null){$shipping = session()->get('shipping');echo$shipping['shipping_phone'];} else if(isset($customer)) {echo $customer->customer_phone;}?>">
                    </div>
                    <div class="input-items">
                        <input type="email" name="shipping_email" id="" placeholder="Email"
                            value="<?php if(session()->get('shipping') != null){$shipping = session()->get('shipping');echo$shipping['shipping_email'];} else if(isset($customer)) {echo $customer->customer_email;}?>">   
                    </div>
                    <form hidden>
                        @csrf
                    </form>
                    <?php
                    if (session()->get('fee') != null) {
                        $fee = session()->get('fee');
                    } else {
                        $fee = null;
                    }
                    ?>
                    <div class="input-items">
                        {{-- <label for="">Chọn Tỉnh Thành Phố</label> --}}
                        <select class="form-control choose  city" name="city" id="city">
                            <?php
                                if($fee != null){
                            ?>
                            <option value="{{ $fee['fee_id_city'] }}">{{ $fee['fee_name_city'] }}</option>
                            <?php
                                }else{
                                    ?>
                            <option value="">---Chọn Tỉnh Thành Phố---</option>
                            <?php
                                }
                            ?>

                            @foreach ($cities as $key => $city)
                                <option value="{{ $city->matp }}">{{ $city->name_city }}</option>
                            @endforeach
                        </select>

                        </select>
                    </div>
                    <div class="input-items">
                        {{-- <label for="">Chọn Quận Huyện</label> --}}
                        <select class="form-control choose  province" name="province" id="province">
                            <?php
                                if($fee != null){
                            ?>
                            <option value="{{ $fee['fee_id_province'] }}">{{ $fee['fee_name_province'] }}</option>
                            <?php
                                }else{
                                    ?>
                            <option value="">---Chọn Quận Huyện---</option>
                            <?php
                                }
                            ?>

                        </select>
                    </div>
                    <div class="input-items">
                        {{-- <label for="">Chọn Xã Phường Thị Trấn</label> --}}
                        <select class="form-control wards caculate_fee" name="wards" id="wards">
                            <?php
                            if($fee != null){
                        ?>
                            <option value="{{ $fee['fee_id_wards'] }}">{{ $fee['fee_name_wards'] }}</option>
                            <?php
                            }else{
                                ?>
                            <option value="">---Chọn Xã Phường---</option>
                            <?php
                            }
                        ?>

                        </select>
                    </div>
                    <div class="input-items">
                        <input type="text" name=" shipping_home_number" id="" placeholder="Số Nhà (Nếu Có)" value="">
                    </div>
                </div>
                <div class="sale-hoadon">
                    @if (session()->get('customer_id'))
                        
                    <div class="code-sale">
                        <h2>Nhập mã giảm giá</h2>
                        <input type="text" class="code-flashsale" value="<?php if(session()->get('coupon-cart') != null){$coupon_cart = session()->get('coupon-cart');echo $coupon_cart->coupon_name_code;}?>"> 
                        <button class="btn-code">Áp dụng</button>
                        
                        <div class="coupon-box">
                            
                        </div>
                        
                        {{-- <hr> --}}
                    </div>
                    @endif
                    <div class="tong-tien">
                        <table class="table-price">

                        </table>

                        <div class="btn-thanh-toan">
                            <button id="btn-payment" class="btn-deliver"><i class="fas fa-shopping-cart"></i> Đặt
                                hàng</button>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>

    <script>
        load_detail_cart();
        load_payment();
        load_coupon();
        /* Load Bảng Sản Phẩm */
        function load_detail_cart() {
            $.ajax({
                url: '{{ url('/cart/load-detail-cart') }}',
                method: 'get',
                data: {

                },
                success: function(data) {
                    $('.loading-cart').html(data);
                    load_payment();
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        }
        /* Load Bảng Mã Giảm Giá */
        function load_coupon() {
            $.ajax({
                url: '{{ url('/cart/load-coupon') }}',
                method: 'get',
                data: {},
                success: function(data) {
                    $('.coupon-box').html(data);
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        }
        /* Load Bảng Giá */
        function load_payment() {
            $.ajax({
                url: '{{ url('/cart/load-payment') }}',
                method: 'get',
                data: {},
                success: function(data) {
                    $('.table-price').html(data);

                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        }
        /*  Áp Dụng Mã Giảm Giá*/
        $('.sale-hoadon .code-sale').on('click', '.btn-code', function() {
            var input = $('.code-flashsale').val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: '{{ url('/cart/check-coupon') }}',
                method: 'get',
                data: {
                    input: input,
                    _token: _token,
                },
                success: function(data) {
                    if(data == 'trùng'){
                            message_toastr('warning', "Mã Giảm Giá Này Bạn Đã Sử Dụng Rồi!!")
                        }
                        else if (data == 'error') {
                            message_toastr("warning", "Mã Giảm Giá Không Tồn Tại !");
                        } else if(data == 'success'){
                            // $('.coupon-box').html(data);
                            load_coupon();
                            load_payment();
                            message_toastr("success", "Đã Áp Dụng Mã Giảm Giá !");
                        }else if(data = 'không'){
                            message_toastr('warning', 'Mã Giảm Giá Đã Hết!');
                        }   
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        });
        /* Gỡ Mã Giảm Giá */
        $('.coupon-box').on('click', '.fa-circle-xmark', function() {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: '{{ url('/cart/delete-coupon') }}',
                method: 'POST',
                data: {
                    _token: _token,
                },
                success: function(data) {
                    if (data == 'success') {
                        load_coupon();
                        load_payment();
                        message_toastr("success", "Đã Gỡ Mã Giảm Giá !");
                    }
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        })


        /* Xóa 1 Sản Phẩm */
        $('tbody').on('click', '.fa-trash-alt', function() {
            var cart_id = $(this).data("cart_id");
            var _token = $('input[name="_token"]').val();
            message_toastr("info",
                'Bạn Muốn Xóa Sản Phẩm Này Không ? <br /><br/><button type="button" class="btn btn-primary btn-delete-cart">Xóa</button> ',
                'Xác Nhận');

            $(document).on('click', '.btn-delete-cart', function() {
                $.ajax({
                    url: '{{ url('/cart/delete-cart') }}',
                    method: 'POST',
                    data: {
                        cart_id: cart_id,
                        _token: _token,
                    },
                    success: function(data) {
                        load_detail_cart();
                        message_toastr("success", 'Xóa Sản Phẩm Thành Công!');
                    },
                    error: function() {
                        alert("Bug Huhu :<<");
                    }
                })

            })
        })

        /* Xóa Toàn Bộ Sản Phẩm */
        $('.cart-table').on('click', '.delete-all', function() {
            if (confirm("Bạn có muốn xóa toàn bộ sản phẩm trong giỏ hàng?")) {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: '{{ url('/cart/delete-all-cart') }}',
                    method: 'POST',
                    data: {
                        _token: _token,
                    },
                    success: function(data) {
                        load_detail_cart();
                        message_toastr("success", "Xóa Toàn Bộ Sản Phẩm Thành Công !");
                    },
                    error: function() {
                        alert("Bug Huhu :<<");
                    }
                })
            }
        })


        /* Cập Nhật Số Lượng */
        $('.tbody').on('change', '.changequantity', function() {
            var cart_id = $(this).data("cart_product_id");
            var quantity = $(this).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: '{{ url('/cart/update-all-cart') }}',
                method: 'POST',
                data: {
                    cart_id: cart_id,
                    quantity: quantity,
                    _token: _token,
                },
                success: function(data) {
                    message_toastr("success", "Số Lượng Sản Phẫm Đã Được Cập Nhật !");
                    load_detail_cart();
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        })
    </script>
    {{-- Lấy Quận Huyện - Xã Phường --}}
    <script>
        $('.choose').change(function() {
            var action = $(this).attr('id'); /* Lấy Thuộc Tính Của ID */
            var ma_id = $(this).val();
            var _token = $('input[name="_token"]').val();
            var result = '';

            if (action == 'city') {
                result = 'province';
            } else {
                result = 'wards';
            }
            $.ajax({
                url: '{{ url('admin/delivery/select-delivery') }}',
                method: 'POST',
                data: {
                    action: action,
                    ma_id: ma_id,
                    _token: _token,

                },
                success: function(data) {
                    $('#' + result).html(data);
                },
                error: function() {
                    alert("Nhân Ơi Fix Bug Huhu :<");
                },
            });
        });
        $('.caculate_fee').change(function() {
            var id_city = $("#city").val();
            var id_province = $("#province").val();
            var id_wards = $("#wards").val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: '{{ url('cart/caculate-fee') }}',
                method: 'POST',
                data: {
                    id_city: id_city,
                    id_province: id_province,
                    id_wards: id_wards,
                    _token: _token,

                },
                success: function(data) {
                    message_toastr("success", "Đã Tính Phí Vận Chuyển !");
                    load_payment();
                },
                error: function() {
                    alert("Nhân Ơi Fix Bug Huhu :<");
                },
            });
        });
    </script>

    <script>
        $('#btn-payment').click(function() {
            // var customer_id = '{{ Session::get('customer_id') }}';
            // if (customer_id == '') {
            //     message_toastr("warning", "Hãy Đăng Nhập Để Tiến Hành Đặt Hàng!");
            //     $("#overlay").css({
            //         "display": "block"
            //     });
            //     $(".fromlogin").css({
            //         "display": "block"
            //     });
            // }

            var shipping_name = $("input[name='shipping_name']").val();
            var shipping_phone = $("input[name='shipping_phone']").val();
            var shipping_email = $("input[name='shipping_email']").val();
            var  shipping_home_number =  $("input[name=' shipping_home_number']").val();
            var id_wards = $("#wards").val();
            
            var price_all_product = '{{ Session::get('price_all_product') }}';
            var _token = $('input[name="_token"]').val();

            var check_error = 0;
            if(shipping_name == ''|| shipping_phone == '' || shipping_email == ''){
                message_toastr("warning", "Vui Lòng Nhập Đầy Đủ Thông Tin Vận Chuyển!");
                check_error++;
            }    
            if(price_all_product == '0'){
                message_toastr("warning", "Vui Lòng Thêm Sản Phẩm Vào Giỏ Hàng!");
                check_error++;
            }
            if(id_wards == ''){
                 message_toastr("warning", "Vui Lòng Chọn Nơi Vận Chuyển!");
                 check_error++;
            }
            if(check_error == 0){
                $.ajax({
                url: '{{ url('cart/confirm-cart') }}',
                method: 'POST',
                data: {
                    shipping_name: shipping_name,
                    shipping_phone: shipping_phone,
                    shipping_email: shipping_email,
                    shipping_home_number: shipping_home_number,
                    _token: _token,

                },
                success: function(data) {
                    if(data == "true"){
                        window.location="{{ url('/thanh-toan') }}";
                    }     
                },
                error: function() {
                    alert("Nhân Ơi Fix Bug Huhu :<");
                },
            });
            }
        });
    </script>
@endsection
