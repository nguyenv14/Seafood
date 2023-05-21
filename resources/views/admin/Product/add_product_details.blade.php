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
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 style="margin-top: -15px" class="card-title">Thêm Chi Tiết Sản Phẩm {{ $product->product_name }}</h4>
                <form class="forms-sample" action="{{ 'save-product-details' }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $product->product_id }}" name="product_id">
                    
                    <div class="form-group">
                        <label for="exampleTextarea1">Nội Dung Sản Phẫm</label>
                        <textarea rows="8" class="form-control" name="product_details_content" id="editor"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="">Tổng Số Lượng</label>
                        <input type="number" class="form-control" name="product_details_quantity" id="" placeholder="Tổng Số Lượng">
                    </div>

                    <div class="form-group">
                        <label for="">Cách Thức Giao Hàng</label>
                        <input type="text" class="form-control" name="product_details_deliveryway" id="" placeholder="Cách Thức Giao Hàng">
                    </div>
                    <div class="form-group">
                        <label for="">Nơi Xuất Xứ</label>
                        <input type="text" class="form-control" name="product_details_origin" id="" placeholder="Nơi Xuất Xứ">
                    </div>
                    <div class="form-group">
                        <label for="exampleTextarea1">Món Ngon</label>
                        <textarea rows="8" class="form-control" name="product_details_delicious_foods" id=""></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('#editor1'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
