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
    
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div style="display: flex;justify-content: space-between">
                    <div class="card-title col-sm-9">Bảng Danh Sách Slider</div>
                </div>
                <table style="margin-top:20px " class="table table-bordered">
                    <thead>
                        <tr>
                            <th> #ID </th>
                            <th>Tên Slider</th>
                            <th>Ảnh Slider</th>
                            <th> Mô Tả </th>
                            <th> Hiễn Thị </th>
                            <th> Thao Tác </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_slider as $key => $slider)
                            <tr>
                                <td>{{ $slider->slider_id }}</label>
                                </td>
                                <td>{{ $slider->slider_name }}</td>
                               
                                <td><img style="object-fit: cover" width="40px" height="20px"
                                        src="{{ URL::to('public/fontend/assets/img/slider/'.$slider->slider_image) }}"
                                        alt=""></td>
                                <td>{{ $slider->slider_desc }}</td>
                                <td>
                                    @if ($slider->slider_status == 1)
                                        <a href="{{ URL::to('admin/slider/unactive-slider?slider_id=' . $slider->slider_id) }}">
                                            <i style="color: rgb(52, 211, 52); font-size: 30px"
                                            class="mdi mdi-toggle-switch"></i>
                                        </a>
                                    @else
                                        <a href="{{ URL::to('admin/slider/active-slider?slider_id=' . $slider->slider_id) }}">
                                            <i style="color: rgb(196, 203, 196);font-size: 30px"
                                            class="mdi mdi-toggle-switch-off"></i>
                                        </a>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ URL::to('admin/slider/edit-slider?slider_id=' . $slider->slider_id) }}">
                                        <i style="font-size: 20px" class="mdi mdi-lead-pencil"></i>
                                    </a>
                                    @hasanyroles(['admin','manager'])
                                    <a onclick="return confirm('Bạn muốn xóa slider này không?')"
                                        href="{{ URL::to('admin/slider/delete-slider?slider_id=' . $slider->slider_id) }}"
                                        style="margin-left: 14px">
                                        <i style="font-size: 22px" class="mdi mdi-delete-sweep text-danger "></i>
                                    </a>
                                    @endhasanyroles
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

        {{-- Phân Trang Bằng Paginate + Boostraps , Apply view Boostrap trong Provider--}}
        <nav aria-label="Page navigation example">
            {!!  $all_slider->links()  !!}
       </nav>

@endsection
