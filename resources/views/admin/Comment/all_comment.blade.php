@extends('admin.admin_layout')
@section('admin_content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-crosshairs-gps"></i>
            </span> Quản Lý Bình Luận
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
                    <div class="card-title col-sm-9">Bảng Danh Danh Bình Luận</div>
                    <div class="col-sm-3">

                    </div>
                </div>

                <table style="margin-top:20px " class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Thao Tác</th>
                            <th>Người Bình Luận</th>
                            <th>Tiêu Đề</th>
                            <th>Nội Dung</th>
                            <th>Ngày Gửi</th>
                            <th>Sản Phẩm</th>
                            {{-- <th>Trả Lời</th> --}}
                            <th>Quản Lý</th>
                        </tr>
                    </thead>
                    <tbody id="loading_all_comment">

                    </tbody>
                </table>

            </div>
        </div>
    </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Trả Lời Bình Luận</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="" class="form-label">Tiêu Đề</label>
                            <input type="" class="form-control title-reply" id="">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Nội Dung</label>
                            <textarea class="form-control content-reply" rows="5"></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button"  class="btn btn-primary submit-reply">Gửi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        {!! $all_comment->links('admin.pagination') !!}


        <script>
            loading_table_comment();

            function loading_table_comment() {
                $.ajax({
                    url: '{{ url('admin/comment/loading-table-comment') }}',
                    method: 'get',
                    data: {

                    },
                    success: function(data) {
                        $('#loading_all_comment').html(data);
                    },
                    error: function() {
                        alert("Bug Huhu :<<");
                    }
                })
            }
        </script>

        <script>
            $(document).on("click", '.agree-comment', function() {
                var comment_id = $(this).data('comment_id');
                var comment_status = $(this).data('status');

                $.ajax({
                    url: '{{ url('admin/comment/set-status') }}',
                    method: 'get',
                    data: {
                        comment_id: comment_id,
                        comment_status: comment_status,
                    },
                    success: function(data) {
                        loading_table_comment();
                        message_toastr("success", "Bình Luận Đã Được Duyệt !", "Thành Công!");
                    },
                    error: function() {
                        alert("Bug Huhu :<<");
                    }
                })
            });

            $(document).on("click", '.refuse-comment', function() {
                var comment_id = $(this).data('comment_id');
                var comment_status = $(this).data('status');

                $.ajax({
                    url: '{{ url('admin/comment/set-status') }}',
                    method: 'get',
                    data: {
                        comment_id: comment_id,
                        comment_status: comment_status,
                    },
                    success: function(data) {
                        loading_table_comment();
                        message_toastr("success", "Bình Luận Đã Được Bị Từ Chối !", "Thành Công!");
                    },
                    error: function() {
                        alert("Bug Huhu :<<");
                    }
                })

            });

            // Xóa Bình Luận
            $(document).on('click', '.mdi-delete-sweep', function() {
                var delete_id = $(this).data('delete_comment');
                // alert(delete_id);
                var _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{ url('admin/comment/delete-comment') }}',
                    method: 'post',
                    data: {
                        delete_id: delete_id,
                        _token: _token,
                    },
                    success: function(data) {
                        loading_table_comment();
                        message_toastr("success", "Bình Luận Đã Được Xóa !", "Thành Công!");
                    },
                    error: function() {
                        alert("Bug Huhu :<<");
                    }
                })
            })

            /*Điều Chỉnh Bình Luận Duyệt Hoặc Từ Chối */
            $(document).on('click', '.un-permit', function() {
                var comment_id = $(this).data('comment_id');
                var comment_status = $(this).data('status');
                $.ajax({
                    url: '{{ url('admin/comment/set-status') }}',
                    method: 'get',
                    data: {
                        comment_id: comment_id,
                        comment_status: comment_status
                    },
                    success: function(data) {
                        loading_table_comment();
                        message_toastr("success", "Bình Luận Đã Được Điều Chỉnh !", "Thành Công!");
                    },
                    error: function() {
                        alert("Bug Huhu :<<");
                    }
                })
            })
            /* Sự Kiện Khi Ấn Vào Repply Disable */
            $(document).on('click', '.repply-disable', function() {
                message_toastr("warning", "Bình Luận Được Duyệt Mới Có Thể Trả Lời !", "Cảnh Báo!");
            })

            /* Trả Lời Bình Luận */
            
        </script>

        <script>
            $('.pagination a').unbind('click').on('click', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                getPosts(page);
            });

            function getPosts(page) {
                $.ajax({
                    url: '{{ url('admin/comment/loading-table-comment?page=') }}' + page,
                    method: 'get',
                    data: {

                    },
                    success: function(data) {
                        $('#loading_all_comment').html(data);
                    },
                    error: function() {
                        alert("Bug Huhu :<<");
                    }
                })
            }
        </script>
    @endsection
