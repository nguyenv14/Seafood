
@extends('admin.admin_layout')
@section('admin_content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-crosshairs-gps"></i>
            </span> Thùng Rác
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
                    <div class="card-title col-sm-9">Bảng Danh Sách Khách Hàng</div>
                    <div class="col-sm-3">
                        
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

    <script>
        load_customers();
       function load_customers(){
            $.ajax({
                url: '{{ url('/admin/customer/load-list-soft-deleted') }}',
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
    </script>
@endsection
