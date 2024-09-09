@extends('admin.master');
@section('main')
@section('name_page','Quản lý Tool')
@section('title', 'Danh sách Key')
@include('errors.error')
<div class="container-fluid">
    <!-- /.row-->
    <div class="row">
        <div class="col-lg-12">
            <form action="{{url('/admin/tool/add')}}" id="form-add-edit" method="post">
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="ccmonth">Chọn Game: </label>
                        <select class="form-control" id="sel1" name="game_id">
                            @foreach ($games as $game)
                            <option value="{{$game->id}}">
                                {{$game->name}} (By: {{$game->other ? $game->other : 'Ẩn danh'}})
                            </option>
                            @endforeach
                        </select>

                        <div class="form-group" style="margin-top: 16px;">
                              <input type="text" class="form-control" placeholder="Nhập số thiết bị cho Key" name="total_devices" id="total_devices" required>
                          </div>

                        <div class="form-group" style="margin-top: 16px;">
                            <input type="text" class="form-control" placeholder="Nhập vào tên khách hàng(@telegram)" name="customer" id="customer" required>
                        </div>

                        <div class="form-group" style="margin-top: 16px;">
                            <button class="btn btn-primary" id="btn-add" type="submit"> Lấy Key </button>
                        </div>
                    </div>
                </div>
                @csrf
            </form>
        </div>


        <div class="container-fluid">
            <!-- /.row-->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Key mới tạo gần nhất!
                        </div>
                        <div class="card-body">
                            <table class="table table-responsive-sm table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Game</th>
                                        <th>Key</th>
                                        <th>Thiết bị</th>
                                        <th>Trạng thái</th>
                                        <th>Khách hàng</th>
                                        <th>Thời gian tạo</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="content-table">
                                    @foreach ($keyTodayRecords as $row)
                                    <tr id="tr-id-{{$row->mt_id}}">
                                        <td>{{ $row->mt_id }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->license_key }}</td>
                                        <td>{{ 
                                            empty($row->mac_address) ? 0 : count(explode('|', $row->mac_address)) }} / {{$row->total_devices}}</td>
                                        @if($row->active == 1)
                                            <td><span class="badge badge-success" >Active</span></td>
                                        @else
                                            <td><span class="badge badge-danger">Suspend</span></td>
                                        @endif
                                        <td>{{ $row->customer }}</td>
                                        <td><b>{{ \Carbon\Carbon::parse($row->created_at)->format('H:i:s   [d/m/Y]') }}<b></td>
                                        <td>
                                           @if($row->active == 1)
                                                <span class="btn badge badge-danger" style="cursor: pointer;" onclick="updateStatus({{$row->mt_id}}, 'suspend')">Suspend</span>
                                            @else
                                                <span class="btn badge badge-success" style="cursor: pointer;" onclick="updateStatus({{$row->mt_id}}, 'active')">Active</span>
                                            @endif

                                            <a href="{{url('/admin/tool/delete/'.$row->mt_id)}}" class="btn badge badge-danger" style="cursor: pointer;">Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                                <script>
                                    function updateStatus(id, type) {
                                      $.ajaxSetup({
                                          headers: {
                                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                          }
                                      });

                                      $.ajax({
                                            type: 'POST',
                                            url: "/admin/tool/"+id,
                                            data: { type: type},
                                            success: function(responseData) {
                                               toastr.success('Update thành công!');
                                               setTimeout(() => {
                                                location.reload();
                                               }, 500);
                                            },
                                            error: function(error) {
                                              
                                            }
                                        });
                                      
                                    }
                                </script>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.col-->
            </div>
            <!-- /.row-->
        </div>
    </div>
    <div id="table-paginate" style="display: flex; align-items: center; justify-content: center;">
        {{$keyTodayRecords->links()}}
    </div>
</div>
<script>
@stop