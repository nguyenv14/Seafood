@extends('admin.admin_layout')
@section('admin_content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-crosshairs-gps"></i>
            </span> Quản Lý Footer
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
                    <div class="card-title col-sm-9">Bảng Danh Sách Cấu Hình 
                    </div>
                    <div class="col-sm-3">
                    </div>
                </div>
                
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th> #ID </th>
                            <td>{{ $company_config->company_id }}</td>
                        </tr>
                        <tr>
                            <th>Tên Công Ty/Shop</th>
                            <td contenteditable class="company_edit" data-company_type="1">{{ $company_config->company_name }}</td>
                        </tr>
                        <tr>
                            <th>Tổng Đài Chăm Sóc</th>
                            <td contenteditable class="company_edit" data-company_type="2">{{ $company_config->company_hostline }}</td>
                        </tr>
                        <tr>
                            <th>Email Đơn Vị</th>
                            <td contenteditable class="company_edit" data-company_type="3">{{ $company_config->company_mail }}</td>
                        </tr>
                        <tr>
                            <th>Địa Chỉ</th>
                            <td contenteditable class="company_edit" data-company_type="4">{{ $company_config->company_address }}</td>
                        </tr>
                        <tr>
                            <th>Slogan Công Ty</th>
                            <td contentEditable class="company_edit" data-company_type="5"><div class="company_edit" style="width: 660px;overflow: hidden">{{ $company_config->company_slogan }}</div> </td>
                        </tr>
                        {{-- <tr>
                            <th>Ảnh Đại Diện</th>
                            <td><img style="object-fit: cover" width="40px" height="20px"
                                    src="{{ URL::to('public/fontend/assets/img/product/' . $product->product->product_image) }}"
                                    alt=""></td>
                        </tr> --}}
                        <tr>
                            <th>Copyright</th>
                            <td contentEditable class="company_edit" data-company_type="6"><div class="company_edit" style="width: 660px;overflow: hidden">{{ $company_config->company_copyright }}</div> </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    
    {{-- Toàn Bộ Script Liên Quan Đến Gallery --}}
    <script>
        $(document).ready(function() {
            /* Loading Gallrery On Table */
            // load_gallery_product();


            $('.table-bordered tbody').on('blur', '.company_edit', function(){
                var content_edit = $(this).text();
                var edit_type = $(this).data('company_type');
                // alert(content_edit);
                // var _token = $("input[name='_token']").val();

                $.ajax({
                    url: '{{ url('admin/config-footer/edit-content-footer') }}',
                    method: 'post',
                    data: {
                        // _token: _token,
                        content_edit: content_edit,
                        edit_type:edit_type
                    },
                    headers:{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        // $('#loading_gallery_product').html(data);
                        message_toastr("success", "Cập nhập thông tin trang web thành công")
                    },
                    error: function(data) {
                        alert("Nhân Ơi Fix Bug Huhu :<");
                    },
                });

            })

            // function load_gallery_product() {
            //     var product_id = $("input[name='pro_id']").val();
            //     var _token = $("input[name='_token']").val();
            //     $.ajax({
            //         url: '{{ url('admin/product/loading-gallery') }}',
            //         method: 'post',
            //         data: {
            //             _token: _token,
            //             product_id: product_id
            //         },
            //         success: function(data) {
            //             $('#loading_gallery_product').html(data);
            //         },
            //         error: function(data) {
            //             alert("Nhân Ơi Fix Bug Huhu :<");
            //         },
            //     });

            // }
            /* Cập Nhật Tên Ảnh Gallery */
            // $('.tab-gallery #loading_gallery_product').on('blur', '.edit_cofig_content', function() {
            //     var config_id = $(this).data('config_id');
            //     var _token = $("input[name='_token']").val();
            //     var config_content = $(this).text();

            //     $.ajax({
            //         url: '{{ url('admin/product/update-nameimg-gallery') }}',
            //         method: 'post',
            //         data: {
            //             _token: _token,
            //             config_id: config_id,
            //             config_content: config_content,
            //         },
            //         success: function(data) {
            //             message_toastr("success", "Tên Ảnh Đã Được Cập Nhật !");
            //             load_gallery_product();
            //         },
            //         error: function(data) {
            //             alert("Nhân Ơi Fix Bug Huhu :<");
            //         },
            //     });

            // });

            /* Cập Nhật Nội Dung Ảnh Gallery */
            // $('.tab-gallery #loading_gallery_product').on('blur', '.edit_gallery_product_content', function() {
            //     var gallery_id = $(this).data('gallery_id');
            //     var _token = $("input[name='_token']").val();
            //     var gallery_content = $(this).text();

            //     $.ajax({
            //         url: '{{ url('admin/product/update-content-gallery') }}',
            //         method: 'post',
            //         data: {
            //             _token: _token,
            //             gallery_id: gallery_id,
            //             gallery_content: gallery_content,
            //         },
            //         success: function(data) {
            //             message_toastr("success", "Nội Dung Ảnh Đã Được Cập Nhật !");
            //             load_gallery_product();
            //         },
            //         error: function(data) {
            //             alert("Nhân Ơi Fix Bug Huhu :<");
            //         },
            //     });

            // });


            /* Xóa Gallery */
            // $('.tab-gallery #loading_gallery_product').on('click', '.delete_gallery_product', function() {
            //     var gallery_id = $(this).data('gallery_id');
            //     var _token = $("input[name='_token']").val();
            //     $.ajax({
            //         url: '{{ url('admin/product/delete-gallery') }}',
            //         method: 'post',
            //         data: {
            //             _token: _token,
            //             gallery_id: gallery_id,
            //         },
            //         success: function(data) {
            //             message_toastr("success", "Ảnh Đã Được Xóa !");
            //             load_gallery_product();
            //             load_gallery_product();
            //             setTimeout(removemesage, 4000);
            //         },
            //         error: function(data) {
            //             alert("Nhân Ơi Fix Bug Huhu :<");
            //         },
            //     });

            // });

            // $('.tab-gallery #loading_gallery_product').on('change', '.up_load_file', function() {
            //     var gallery_id = $(this).data('gallery_id');
            //     // var file = $("input[name='file_image']").val();
            //     var image = document.getElementById('up_load_file'+gallery_id).files[0];
            //     //var _token = $("input[name='_token']").val();
                
            //     var form_data = new FormData();
            //     form_data.append("file",document.getElementById('up_load_file'+gallery_id).files[0]);
            //     form_data.append("gallery_id",gallery_id);

              
            //     $.ajax({
            //         url: '{{ url('admin/product/update-image-gallery') }}',
            //         method: 'post',
            //         headers:{
            //             'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: form_data,
            //         contentType:false,
            //         cache:false,
            //         processData:false,
            //         success: function(data) {
            //             message_toastr("success", "Cập Nhật Ảnh Thành Công !");
            //             load_gallery_product();
            //         },
            //         error: function(data) {
            //             alert("Nhân Ơi Fix Bug Huhu :<");
            //         },
            //     });
            // });

            // $('#formFile').change(function() {
            //     var error = '';
            //     var files = $('#formFile')[0].files;

            //     if (files.length > 20) {
            //         error += 'Bạn Không Được Chọn Quá 20 Ảnh';

            //     } else if (files.length == '') {
            //         error += 'Vui lòng chọn ảnh';

            //     } else if (files.size > 10000000) {
            //         error += 'Ảnh Không Được Lớn Hơn 10Mb';
            //     }

            //     if (error == '') {

            //     } else {
            //         $('#formFile').val('');
            //         message_toastr("error", ''+error+'');
            //         return false;
            //     }

            // });

        });
    </script>
@endsection
