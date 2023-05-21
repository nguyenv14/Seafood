
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
                    <div class="card-title col-sm-9">Bảng Danh Sách Sản Phẩm</div>
                    <div class="col-sm-3">
                        
                    </div>
                </div>
                <table style="margin-top:20px " class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tên Sản Phẩm</th>
                            <th>Tên Thể Loại</th>
                            <th>Lượng Bán</th>
                            <th>Giá</th>
                            <th>Ảnh Đại Diện</th>
                            <th>Ngày Xóa</th>
                            <th>Thao Tác 
                        
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_product as $key => $products)
                            <tr>
                                </td>
                                <td>{{ $products->product_name }}</td>
                                <td>{{ $products->category->category_name }}</td>
                                <?php
                                $product_unit = '';
                                switch ($products->product_unit) {
                                    case '0':
                                        $product_unit = 'Con';
                                        break;
                                    case '1':
                                        $product_unit = 'Phần';
                                        break;
                                    case '2':
                                        $product_unit = 'khay';
                                        break;
                                    case '3':
                                        $product_unit = 'Túi';
                                        break;
                                    case '4':
                                        $product_unit = 'Kg';
                                        break;
                                    case '5':
                                        $product_unit = 'Gam';
                                        break;
                                    case '6':
                                        $product_unit = 'Combo';
                                        break;
                                    default:
                                        $product_unit = 'Bug Rùi :<';
                                        break;
                                }
                            
                                ?>
                                <td>{{ $products->product_unit_sold . ' ' . $product_unit }}</td>
                                <td>{{ number_format($products->product_price) }}</td>
                                <td><img style="object-fit: cover" width="40px" height="20px"
                                        src="{{ URL::to('public/fontend/assets/img/product/' . $products->product_image) }}"
                                        alt="">
                                </td>
                                <td>{{ $products->deleted_at->format('d-m-Y') }}</td>
                                <td>
                                    <a  onclick="return confirm('Bạn muốn khôi phục danh mục này không?')"
                                        href="{{ URL::to('admin/product/un-trash?product_id=' . $products->product_id) }}">
                                        <i style="font-size: 20px" class="mdi mdi-backup-restore"></i>
                                    </a>
                                    @hasanyroles(['admin','manager'])
                                    <a onclick="return confirm('Bạn muốn xóa danh mục này vĩnh viển không?')"
                                        href="{{ URL::to('admin/product/trash-delete?product_id=' . $products->product_id) }}"
                                        style="margin-left: 14px">
                                        <i style="font-size: 22px" class="mdi mdi-delete-forever text-danger "></i>
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
@endsection
