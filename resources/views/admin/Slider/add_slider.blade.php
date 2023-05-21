@extends('admin.admin_layout')
@section('admin_content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-crosshairs-gps"></i>
            </span> Quản Lý Slider
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
                <h4 style="margin-top: -15px" class="card-title">Thêm Sản Phẩm</h4>
                <form class="forms-sample" action="{{ 'save-slider' }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="">Tên Slider</label>
                        <input type="text" name="slider_name" class="form-control" id=""
                            placeholder="Tên Slider">
                    </div>
            
                    <div class="form-group">
                        <label>Tải Ảnh Lên</label>
                        <input id="slider_image" type="file" name="slider_image" class="file-upload-default">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                            <span class="input-group-append">
                                <label for="slider_image" class="file-upload-browse btn btn-gradient-primary"
                                    type="button">Tải Lên</label>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Hiễn thị</label>
                        <select class="form-control" name="slider_status">
                            <option value="0">Ẩn</option>
                            <option value="1">Hiện</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleTextarea1">Mô Tả Slider</label>
                        <textarea rows="8" class="form-control" name="slider_desc" id="editor"></textarea>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Xác Nhận</button>
                    <button class="btn btn-light">Quay Lại</button>
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
