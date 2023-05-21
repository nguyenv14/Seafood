@extends('admin.admin_layout')
@section('admin_content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-book-variant"></i>
            </span> Quản Lý Thể Loại
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
                    <div class="card-title col-sm-9">Bảng Danh Sách Thể Loại</div>
                    <div class="col-sm-3">
                        {{-- <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-gradient-primary me-2">Tìm kiếm</button>
                            </span>
                        </div> --}}
                    </div>
                </div>
                <table style="margin-top:20px " class="table table-bordered">
                    <thead>
                        <tr>
                            <th> #ID </th>
                            <th> Tên Danh Mục </th>
                            <th> Mô Tả </th>
                            @hasanyroles(['admin','manager'])
                            <th> Hiễn Thị </th>
                            @endhasanyroles
                            <th> Ngày Thêm </th>
                            @hasanyroles(['admin','manager'])
                            <th> Thao Tác </th>
                            @endhasanyroles
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_category as $key => $cate_pro)
                            <tr>
                                <td>{{ $cate_pro->category_id }}</td>
                                <td>{{ $cate_pro->category_name }}</td>
                                <td>{{ $cate_pro->category_desc }}</td>
                                @hasanyroles(['admin','manager'])
                                <td>
                                    @if ($cate_pro->category_status == 1)
                                        <a
                                            href="{{ URL::to('admin/category/unactive-category?category_id=' . $cate_pro->category_id) }}">
                                            <i style="color: rgb(52, 211, 52); font-size: 30px"
                                                class="mdi mdi-toggle-switch"></i>
                                        </a>
                                    @else
                                        <a
                                            href="{{ URL::to('admin/category/active-category?category_id=' . $cate_pro->category_id) }}"><i
                                                style="color: rgb(196, 203, 196);font-size: 30px"
                                                class="mdi mdi-toggle-switch-off"></i></a>
                                    @endif
                                </td>
                                @endhasanyroles
                                <td>{{ $cate_pro->created_at }}</td>
                                
                                @hasanyroles(['admin','manager'])
                                <td>
                                    <a
                                        href="{{ URL::to('admin/category/edit-category?category_id=' . $cate_pro->category_id) }}">
                                        <i style="font-size: 20px" class="mdi mdi-lead-pencil"></i>
                                    </a>
                                    <a onclick="return confirm('Bạn muốn xóa danh mục này không?')"
                                        href="{{ URL::to('admin/category/delete-category?category_id=' . $cate_pro->category_id) }}"
                                        style="margin-left: 14px">
                                        <i style="font-size: 22px" class="mdi mdi-delete-sweep text-danger "></i>
                                    </a>
                                </td>
                                @endhasanyroles
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Phân Trang Bằng Paginate + Boostraps , Apply view Boostrap trong Provider --}}
    <nav aria-label="Page navigation example">
        {!! $all_category->links() !!}
    </nav>

    {{-- <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div style="display: flex;justify-content: space-between">
                    <div class="card-title col-sm-9">Bảng Danh Sách Thể Loại Lấy Dữ Liệu Bằng API</div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-gradient-primary me-2">Tìm kiếm</button>
                            </span>
                        </div>
                    </div>
                </div>
                <table style="margin-top:20px " class="table table-bordered">
                    <thead>
                        <tr>
                            <th> #ID </th>
                            <th> Tên Danh Mục </th>
                            <th> Mô Tả </th>
                            <th> Hiễn Thị </th>
                            <th> Ngày Thêm </th>
                            <th> Thao Tác </th>
                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}

    {{-- <script>
        $.get('http://localhost/DoAnCNWeb/api/admin/category/all-category',function(res){
            if(res.status_code == 200){ /* Kiểm Tra */
                let all_category = res.data;
            }
        });
    </script> --}}

@endsection
