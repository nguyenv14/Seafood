@extends('admin.admin_layout')
@section('admin_content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-book-variant"></i>
            </span> Quản Lý Khách Hàng
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="mdi mdi-timetable"></i>
                    <span><?php
                    $today = date('d/m/Y');
                    echo $today;
                    ?></span>
                </li>
            </ul>
        </nav>
    </div>
    
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div style="display: flex;justify-content: space-between">
                    <div class="card-title col-sm-5">Bảng Danh Sách Khách Hàng</div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input id="search" type="text" class="form-control" name="search"
                                placeholder="Tìm Kiếm Tên Người Dùng Hoặc Email">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                data-bs-toggle="dropdown">Theo Loại Tài Khoản</button>
                            <div class="dropdown-menu">
                                <span class="dropdown-item" data-type="0">Tất Cả</span>
                                <span class="dropdown-item" data-type="1">Hệ Thống</span>
                                <span class="dropdown-item" data-type="2">Facebook</span>
                                <span class="dropdown-item" data-type="3">Google</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        @hasanyroles(['admin','manager'])
                        <div class="input-group">
                            <a style="text-decoration: none"
                                href="{{ URL::to('admin/customer/list-soft-deleted-customer') }}">
                                <button type="button" class="btn btn-outline-secondary">Thùng Rác ( )
                                </button>
                            </a>
                        </div>
                        @endhasanyroles
                    </div>
                </div>
                <table style="margin-top:20px " class="table table-bordered">
                    <thead>
                        <tr>
                            <th> #ID </th>
                            <th> Tên Khách Hàng </th>
                            <th> Số Điện Thoại </th>
                            <th> Email </th>
                            @hasanyroles(['admin','manager'])
                            <th> Mật Khẩu </th>
                            @endhasanyroles
                            <th> Tài Khoản </th>
                            @hasanyroles(['admin','manager'])
                            <th> Trạng Thái </th>
                            <th> IP </th>
                            <th> Vị Trí </th>
                            <th> Thiết Bị </th>
                            <th> Thao Tác </th>
                            @endhasanyroles
                        </tr>
                    </thead>
                    <tbody id="loading-table-customers">
                     
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Phân Trang Bằng Paginate + Boostraps , Apply view Boostrap trong Provider --}}
    <nav aria-label="Page navigation example">
        {!! $customers->links() !!}
    </nav>

    <script>
        load_customers();
        function load_customers(){
            $.ajax({
                url: '{{ url('/admin/customer/load-all-customer') }}',
                method: 'GET',
                data: {
                  
                },
                success: function(data) {
                    $('#loading-table-customers').html(data);
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        }

        $('#search').keyup(function() {
          
            var key_sreach = $(this).val();
          
            $.ajax({
                url: '{{ url('/admin/customer/all-customer-sreach') }}',
                method: 'GET',
                data: {
                    key_sreach: key_sreach,
                },
                success: function(data) {
                    $('#loading-table-customers').html(data);
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        });

        $('.dropdown-item').click(function() {
            var type = $(this).data('type');
            $.ajax({
                url: '{{ url('/admin/customer/sort-customer-by-type') }}',
                method: 'GET',
                data: {
                    type: type,
                },
                success: function(data) {
                    $('#loading-table-customers').html(data);
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        });

        $(document).on('click', '.update-status', function() {
                var customer_id = $(this).data('customer_id');
                var status = $(this).data('status');

                $.ajax({
                    url: '{{ url('/admin/customer/update-status-customer') }}',
                    method: 'GET',
                    data: {
                        customer_id: customer_id,
                        status: status,
                    },
                    success: function(data) {
                        load_customers();
                        if(status == 1){
                            message_toastr("success", "Tài Khoản Đã Được Kích Hoạt!");
                        }else if(status == 0){
                            message_toastr("success", "Tài Khoản Đã Bị Khóa!");
                        }
                    },
                    error: function() {
                        // alert("Bug Huhu :<<");
                    }
                })
        });

        $(document).on('click', '.btn-delete-customer', function() {
                var customer_id = $(this).data('customer_id');
                $.ajax({
                    url: '{{ url('/admin/customer/delete-customer') }}',
                    method: 'GET',
                    data: {
                        customer_id: customer_id,
                    },
                    success: function(data) {
                         load_customers();
                         message_toastr("success", "Tài Khoản Đã Được Đưa Vào Thùng Rác !");
                   },
                    error: function() {
                        // alert("Bug Huhu :<<");
                    }
                })
        });
    </script>

@endsection
