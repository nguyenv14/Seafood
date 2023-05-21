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
                    <div class="card-title col-sm-9">Bảng Danh Sách Sản Phẩm</div>
                    <div class="col-sm-3">
                        <form action="{{ URL::to('admin/product/all-product-sreachbyname') }}" method="get">
                            <div class="input-group">
                                <input type="text" class="form-control" name="searchbyname" placeholder="Search">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-gradient-primary me-2">Tìm kiếm</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                <table style="margin-top:20px " class="table table-bordered">
                    <thead>
                        <tr>
                            <th> #ID </th>
                            <th>Tên Sản Phẩm</th>
                            <th>Loại Giảm Giá</th>
                            <th>Mức giảm</th>
                            <th>Giá Tiền Gốc</th>
                            <th>Giá Tiền Giảm</th>
                            <th>Hiễn Thị</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($flashsales as $key => $flashsale)
                            <tr>
                                <td>{{ $flashsale->product_id }}</label>
                                </td>
                                <td>{{ $flashsale->product->product_name }}</td>
                                <td>
                                    <?php
                                    $type_sale = 'Giảm giá theo ';
                                    if($flashsale->flashsale_condition == 0){
                                        $type = '%';
                                        $type_sale .= $type;
                                    }else{
                                        $type = 'đ';
                                        $type_sale .= $type;
                                    }
                                    echo $type_sale;
                                    ?></td>
                                <td>{{ $flashsale->flashsale_price_sale.$type }}</td>
                                {{-- <td>{{ $products->product_content }}</td> --}}
                                <td>
                                    {{ number_format($flashsale->product->product_price, 0,',','.').'đ' }}
                                </td>
                           
                                <td>
                                    {{ number_format($flashsale->flashsale_product_price, 0,',','.').'đ' }}
                                </td>
                                <td>
                                    @if ($flashsale->flashsale_status == 1)
                                        <a href="{{ URL::to('admin/flashsale/unactive-product-flashsale?flashsale_id=' . $flashsale->flashsale_id) }}">
                                            <i style="color: rgb(52, 211, 52); font-size: 30px"
                                                class="mdi mdi-toggle-switch"></i>
                                        </a>
                                    @else
                                        <a href="{{ URL::to('admin/flashsale/active-product-flashsale?flashsale_id=' . $flashsale->flashsale_id) }}">
                                            <i style="color: rgb(196, 203, 196);font-size: 30px"
                                                class="mdi mdi-toggle-switch-off"></i>
                                        </a>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ URL::to('admin/flashsale/edit-product-flashsale?flashsale_id=' . $flashsale->flashsale_id) }}">
                                        <i style="font-size: 20px" class="mdi mdi-lead-pencil"></i>
                                    </a>
                                    <a onclick="return confirm('Bạn muốn xóa danh mục này không?')"
                                        href="{{ URL::to('admin/flashsale/delete-product-flashsale?flashsale_id=' . $flashsale->flashsale_id) }}"
                                        style="margin-left: 14px">
                                        <i style="font-size: 22px" class="mdi mdi-delete-sweep text-danger "></i>
                                    </a>
                                    <?php
                                    $sort = 1;
                                    ?>
                                    @if ($sort == 1)
                                        <a style="margin-left: 14px;color: darkorange"
                                            href="{{ URL::to('admin/flashsale/all-product-sort_az') }}">
                                            <i class="fa-solid fa-arrow-down-a-z"></i>
                                        </a>
                                    @elseif($sort == 0)
                                        <a style="margin-left: 14px;color: darkorange"
                                            href="{{ URL::to('admin/flashsale/all-product-sort_za') }}">
                                            <i class="fa-solid fa-arrow-down-z-a"></i>
                                        </a>
                                    @endif

                                </td> 
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection
