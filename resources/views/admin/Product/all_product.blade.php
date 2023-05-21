@extends('admin.admin_layout')
@section('admin_content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-crosshairs-gps"></i>
            </span> Quản Lý Sản Phẩm
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
                    <div class="card-title col-sm-5">Bảng Danh Sách Sản Phẩm</div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input id="search" type="text" class="form-control" name="search"
                                placeholder="Tìm Kiếm Sản Phẩm">
                        </div>
                    </div>
                    <div class="col-sm-2">

                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                data-bs-toggle="dropdown">Theo Danh Mục</button>
                            <div class="dropdown-menu">
                                <span class="dropdown-item" data-category_id="-1">Tất Cả</span>
                                @foreach ($categories as $category)
                                    <span class="dropdown-item"
                                        data-category_id="{{ $category->category_id }}">{{ $category->category_name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <a style="text-decoration: none"
                                href="{{ URL::to('admin/product/list-soft-deleted-product') }}">
                                <button type="button" class="btn btn-outline-secondary">Thùng Rác ( {{ $countDelete }} )
                                </button>
                            </a>
                        </div>
                    </div>
                </div>

                <table style="margin-top:20px " class="table table-bordered">
                    <thead>
                        <tr>
                            <th>

                                <label for="product_name">Tên Sản Phẩm <i style="font-size: 18px"
                                        class="mdi mdi-sort-alphabetical"></i></label>
                                <input type="checkbox" hidden class="btn-sort" id="product_name" data-type='product'>
                            </th>
                            <th>
                                <label for="product_category">Tên Danh Mục <i style="font-size: 18px"
                                        class="mdi mdi-sort-alphabetical"></i></label>
                                <input type="checkbox" hidden class="btn-sort" id="product_category" data-type='category'>
                            </th>
                            <th>
                                <label for="product_quantity">Lượng Bán<i style="font-size: 18px"
                                        class="mdi mdi-sort-numeric"></i></label>
                                <input type="checkbox" hidden class="btn-sort" id="product_quantity" data-type='quantity'>
                            </th>
                            <th>
                                <label for="product_price">Giá<i style="font-size: 18px"
                                        class="mdi mdi-sort-numeric"></i></label>
                                <input type="checkbox" hidden class="btn-sort" id="product_price" data-type='price'>
                            </th>

                            <th>Ảnh Đại Diện</th>
                            <th>Hiễn Thị</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody id="loading-table-product">

                    </tbody>
                </table>

            </div>
        </div>
    </div>

    {{-- Phân Trang Bằng Paginate + Boostraps , Apply Boostrap trong Provider --}}
    {{-- <nav aria-label="Page navigation example"> --}}
    {!! $all_product->links('admin.pagination') !!}
    {{-- </nav> --}}


    {{-- Phân Trang Bằng Ajax --}}
    <script>
        $('.pagination a').unbind('click').on('click', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getPosts(page);
        });

        function getPosts(page) {
            $.ajax({
                url: '{{ url('admin/product/all-product-ajax?page=') }}' + page,
                method: 'get',
                data: {

                },
                success: function(data) {
                    $('#loading-table-product').html(data);
                },
                error: function() {
                    alert("Bug Huhu :<<");
                }
            })
        }
    </script>
    <script>
        $('#search').keyup(function() {
            var key_sreach = $(this).val();
            $.ajax({
                url: '{{ url('/admin/product/all-product-sreach') }}',
                method: 'GET',
                data: {
                    key_sreach: key_sreach,
                },
                success: function(data) {
                    $('#loading-table-product').html(data);
                },
                error: function() {
                    // alert("Bug Huhu :<<");
                }
            })
        });
    </script>
    <script>
        $('.dropdown-item').click(function() {
            var category_id = $(this).data('category_id');
            $.ajax({
                url: '{{ url('/admin/product/sort-product-by-category') }}',
                method: 'GET',
                data: {
                    category_id: category_id,
                },
                success: function(data) {
                    $('#loading-table-product').html(data);
                },
                error: function() {
                    // alert("Bug Huhu :<<");
                }
            })
        });
    </script>
    <script>
        $('.btn-sort').click(function() {
            var check = $(this).prop("checked");
            var type = $(this).data('type');
            $.ajax({
                url: '{{ url('/admin/product/sort-all') }}',
                method: 'GET',
                data: {
                    check: check,
                    type: type
                },
                success: function(data) {
                    $('#loading-table-product').html(data);
                },
                error: function() {
                    // alert("Bug Huhu :<<");
                }
            })
        })
    </script>

    <script>
        $(document).ready(function() {
            load_all_product();
            $(document).on('click', '.update-status', function() {
                var product_id = $(this).data('product_id');
                var status = $(this).data('status');

                $.ajax({
                    url: '{{ url('/admin/product/update-status-product') }}',
                    method: 'GET',
                    data: {
                        product_id: product_id,
                        status: status,
                    },
                    success: function(data) {
                        load_all_product();
                        if(status == 1){
                            message_toastr("success", "Sản Phẩm Đã Được Kích Hoạt!");
                        }else if(status == 0){
                            message_toastr("success", "Sản Phẩm Đã Bị Vô Hiệu!");
                        }
                    },
                    error: function() {
                        // alert("Bug Huhu :<<");
                    }
                })
            })

            $(document).on('click', '.btn-delete-product', function() {
                var product_id = $(this).data('product_id');

                $.ajax({
                    url: '{{ url('/admin/product/delete-product') }}',
                    method: 'GET',
                    data: {
                        product_id: product_id,
                    },
                    success: function(data) {
                        load_all_product();
                         message_toastr("success", "Sản Phẩm Đã Được Đưa Vào Thùng Rác !");
                   },
                    error: function() {
                        // alert("Bug Huhu :<<");
                    }
                })
            })

            function load_all_product() {
                $.ajax({
                    url: '{{ url('/admin/product/all-product-ajax') }}',
                    method: 'GET',
                    data: {

                    },
                    success: function(data) {
                        // alert(data);
                        $('#loading-table-product').html(data);
                    },
                    error: function(data) {
                        alert("Nhân Ơi Fix Bug Huhu :<");
                    },
                });
            }
        });
    </script>
@endsection
