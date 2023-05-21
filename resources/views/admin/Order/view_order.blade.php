@extends('admin.admin_layout')
@section('admin_content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-clipboard-outline"></i>
            </span> Quản Lý Đơn Hàng
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="mdi mdi-clipboard-outline"></i>
                    <span><?php
                    $today = date('d/m/Y');
                    echo $today;
                    ?></span>
                </li>
            </ul>
        </nav>
    </div>


    <?php
    $mesage = Session::get('mesage');
    if ($mesage) {
        echo $mesage;
        Session::put('mesage', null);
    }
    ?>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div style="display: flex;justify-content: space-between">
                    <div class="card-title col-sm-9">Thông Tin Khách Hàng Đăng Nhập</div>
                    <div class="col-sm-3">
                    </div>
                </div>
                @if($customer != null)
                <table style="margin-top:20px " class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#ID Khách Hàng</th>
                            <th>Tên Khách Hàng</th>
                            <th>Số Điện Thoại</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td>{{ $customer->customer_id }}</td>
                        <td>{{ $customer->customer_name }}</td>
                        <td>{{ $customer->customer_sdt }}</td>
                    </tbody>

                </table>
                @else
                <h6>Không Có Thông Tin - Khách Hàng Đặt Hàng Trực Tiếp Trên Hệ Thống Không Thông Qua Đăng Nhập !</h6>
                @endif
               
            </div>
        </div>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div style="display: flex;justify-content: space-between">
                    <div class="card-title col-sm-9">Thông Tin Người Đặt Hàng</div>
                    <div class="col-sm-3">
                    </div>
                </div>
                <table style="margin-top:20px " class="table table-bordered">
                    <tr>
                        <th>Tên Người Đặt Hàng</th>
                        <td>{{ $shipping->shipping_name }}</td>
                    </tr>
                    <tr>
                        <th>Số Điện Thoại</th>
                        <td>{{ $shipping->shipping_phone }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $shipping->shipping_email }}</td>
                    </tr>
                    <tr>
                        <th>Địa Chỉ</th>
                        <td>{{ $shipping->shipping_address }}</td>
                    </tr>
                    <tr>
                        <th>Ghi Chú</th>
                        <td>{{ $shipping->shipping_notes }}</td>
                    </tr>
                    <tr>
                        <th>Yêu Cầu Đặc Biệt</th>
                        <td>
                            @if ($shipping->shipping_special_requirements == 0)
                                {{ 'Không' }}
                            @elseif($shipping->shipping_special_requirements == 1)
                                {{ 'Giao Nhanh Trong 2h' }}
                            @elseif($shipping->shipping_special_requirements == 2)
                                {{ 'Sơ Chế Hải Sản' }}
                            @elseif($shipping->shipping_special_requirements == 3)
                                {{ 'Giao Nhanh + Sơ Chế Hải Sản' }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Hóa Đơn</th>
                        <td>
                            @if ($shipping->shipping_receipt == 1)
                            {{ 'Kèm Hóa Đơn' }}
                            @else
                            {{ 'Không' }}
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div style="display: flex;justify-content: space-between">
                    <div class="card-title col-sm-9">Chi Tiết Đơn Hàng</div>
                    <div class="col-sm-3">
                    </div>
                </div>
                <table style="margin-top:20px " class="table table-bordered">
                    <thead>
                        <tr>
                            @php
                                $i = 1;
                            @endphp
                            <th>STT</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Số Lượng</th>
                            <th>Giá</th>
                            <th>Tổng Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalall = 0;
                        @endphp
                        @foreach ($orderdetails as $key => $orderdetails)
                            <tr>

                                <td>{{ $i++ }}</td>
                                <td>{{ $orderdetails->product_name }} </td>
                                <td>{{ $orderdetails->product_sales_quantity }}</td>
                                <td>{{ number_format($orderdetails->product_price, 0, ',', '.') . ' VND' }}</td>
                                <td>{{ number_format($orderdetails->product_sales_quantity * $orderdetails->product_price, 0, ',', '.') . ' VND' }}
                                </td>
                            </tr>

                            @php
                                $fee_ship = $order->product_fee;
                                $totalall = $totalall + $orderdetails->product_sales_quantity * $orderdetails->product_price;
                            @endphp
                        @endforeach

                    </tbody>
                </table>
                <div style="margin-top:20px ">
                    <div>

                        <span>Phí Ship : {{ number_format($fee_ship, 0, ',', '.') . ' VND' }}</span>
                    </div>
                    <div>
                        <div>
                            @if ($order->product_coupon == "Không có")
                                <span>Mã Giảm Giá : Không Có !</span>
                            @else
                                <span>Mã Giảm Giá : {{ $order->product_coupon }} </span>
                            @endif
                        </div>
                        @if ($order->product_price_coupon > 0)
                            <div>
                                @if ($order->product_price_coupon <= 100)
                                    <?php
                                    $coupon_sale = ($totalall / 100) * $order->product_price_coupon;
                                    ?>
                                    <span>Số Tiền Giảm : {{ number_format($coupon_sale, 0, ',', '.') . ' VND' }}</span>
                                @else
                                    <?php
                                    $coupon_sale = $order->product_price_coupon;
                                    ?>
                                    <span>Số Tiền Giảm : {{ number_format($coupon_sale, 0, ',', '.') . ' VND' }}</span>
                                @endif
                            </div>
                        @endif

                        @if ($order->product_price_coupon == 0)
                            <div>
                                <?php
                                $coupon_sale = 0;
                                ?>
                            </div>
                        @endif
                    </div>
                    @if ($order->product_price_coupon > 0)
                        <div>
                            <span>Tổng Tiền Chưa Giảm :
                            </span>{{ number_format($totalall + $fee_ship, 0, ',', '.') . ' VND' }}
                        </div>
                        <div>
                            <span>Tổng Tiền Đã Giảm :
                            </span>{{ number_format($totalall - $coupon_sale + $fee_ship, 0, ',', '.') . ' VND' }}
                        </div>
                    @else
                        <div>
                            <span>Tổng Tiền :
                            </span>{{ number_format($totalall - $coupon_sale + $fee_ship, 0, ',', '.') . ' VND' }}
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
    <div>
        <div class="template-demo">
            <a target="_blank" style="text-decoration: none"
                href="{{ URL::to('admin/order/print-order?checkout_code=' . $order->order_code) }}">
                <button type="button" class="btn btn-gradient-info btn-icon-text"> Xuất Hóa Đơn PDF <i
                        class="mdi mdi-printer btn-icon-append"></i>
                </button>
            </a>
            <a style="text-decoration: none" href="">
                <button type="button" class="btn btn-gradient-danger btn-icon-text">
                    <i class="mdi mdi-upload btn-icon-prepend"></i> Upload </button>
            </a>
            <a style="text-decoration: none" href="">
                <button type="button" class="btn btn-gradient-warning btn-icon-text">
                    <i class="mdi mdi-reload btn-icon-prepend"></i> Reset </button>
            </a>


        </div>
    </div>
@endsection
