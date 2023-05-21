@extends('admin.admin_layout')
@section('admin_content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-crosshairs-gps"></i>
            </span> Quản Lý Sự Kiện Flashsale
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
                <h4 style="margin-top: -15px" class="card-title">Thêm Sản Phẩm Vào Sự Kiện</h4>
                <form class="forms-sample" action="{{ 'save-product-flashsale' }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                   
                    <div class="form-group">
                        <label for="">Sản Phẩm</label>
                        <select class="form-control m-bot15" name="product_id">
                            @foreach ($products as $key => $product)
                            
                                <option value="{{ $product->product_id }}">{{ $product->product_name }}
                                </option>
                                
                            @endforeach
                        </select>
                    </div>
                  
                    <div class="form-group">
                        <label for="">Loại Giảm Giá</label>
                        <select class="form-control" name="flashsale_condition">
                            <option value="0">Giảm Giá Theo %</option>
                            <option value="1">Giảm Giá Theo Số Tiền</option>
                        </select>
                    </div>
  
                    <div class="form-group">
                        <label for="">Số Tiền Giảm</label>
                        <input type="number" class="form-control" name="flashsale_price_sale" id="" placeholder="Số Tiền Giảm Giá">
                    </div>
                   
                    <div class="form-group">
                        <label for="">Hiển Thị</label>
                        <select class="form-control" name="flashsale_status">
                            <option value="0">Ẩn</option>
                            <option value="1">Hiện</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Gửi</button>
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
