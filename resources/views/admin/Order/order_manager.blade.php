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
                    <div class="card-title col-sm-9">Bảng Danh Sách Đơn Hàng</div>
                    <div class="col-sm-3">  
                        <div class="input-group">
                            <input  type="text" class="form-control" name="searchbyname" placeholder="Tìm Mã Đơn Hàng">
                        </div>
                    
                    </div>
                </div>
                <table style="margin-top:20px " class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th> #ID Đơn Hàng </th>
                            <th>Trạng Thái</th>
                            <th>Thanh Toán</th>
                            <th>Trạng Thanh Toán</th>
                            <th>Ngày Tạo Đơn</th>
                            <th> Thao Tác </th>
                        </tr>
                    </thead>
                    <tbody id="loading-order-manager">
                       
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <script>
        loading_order_manager();
        /* Loading Bảng Order */
        function loading_order_manager(){
            $.ajax({
                url: '{{ url('/admin/order/loading-order-manager') }}',
                method: 'get',
                data: {
                },
                success: function(data) {
                    $('#loading-order-manager').html(data);
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        }



            $(document).on('click', '.btn-order-status', function() {
                var order_code = $(this).data('order_code');
                var order_status =   $(this).data('order_status');
                var _token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '{{ url('admin/order/order-status') }}',
                    method: 'POST',
                    data: {
                        _token: _token,
                        order_code: order_code,
                        order_status: order_status,
                    },
                    success: function(data) {
                        loading_order_manager();
                      if(data == "refuse"){
                        message_toastr("success", "Đơn Hàng Đã Bị Từ Chối !");
                      } else if(data == "browser"){
                        message_toastr("success", "Đơn Hàng Đã Được Duyệt !");
                      } else if(data == "success"){
                        message_toastr("success", "Đơn Hàng Đã Giao Thành Công !");
                      } else if(data == "return"){
                        message_toastr("success", "Đơn Hàng Đã Bị Bom Huhu :< Icon Mếu");
                      }
                    },
                    error: function(data) {
                        alert("Nhân Ơi Fix Bug Huhu :<");
                    },
                });

            });







        
    </script>
@endsection
