@extends('admin.master');
@section('main')
@section('name_page','Game')
@section('title', 'Danh sách Game')
<div class="container-fluid">
    <!-- /.row-->
    <div class="row">
        <div class="col-lg-12">
            <form action="{{url('/admin/game/add')}}" id="form-add-edit" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Nhập tên game" name="name" id="name"
                                required>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Nhập người làm Tool(Ko bắt buộc)" name="other" id="other">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                          <button class="btn btn-primary" id="btn-add" type="submit"><i
                          class="cui-user-follow"></i></button>
                        </div>
                    </div>
                </div>
                @csrf
            </form>
            <div class="container-fluid">
                    <!-- /.row-->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fa fa-align-justify"></i> Danh Sách Game
                                </div>
                                <div class="card-body">
                                    <table class="table table-responsive-sm table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Game</th>
                                                <th>Tác Giả</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="content-table">
                                            @foreach ($games as $game)
                                            <tr id="tr-id-{{$game->id}}">
                                                <td>{{ $game->id }}</td>
                                                <td>{{ $game->name }}</td>
                                                <td>{{ $game->other }}</td>
                                                <td>
                                                    {{-- <button class="btn btn-primary">Sửa</button> --}}
                                                    <button class="btn btn-danger" onclick="deleteGame({{$game->id}})">Xoá</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                    </div>
                    <!-- /.row-->
                </div>
            </div>
            <div id="table-paginate">
                {{$games->links()}}
            </div>
                
    </div>
</div>
<script>
    function deleteGame(id) {
        var result = window.confirm('Bạn có chắc chắn muốn xoá id ' + id)
        if (result) {
            location.href = '/admin/game/delete/'+id
        }
    }
</script>
@stop