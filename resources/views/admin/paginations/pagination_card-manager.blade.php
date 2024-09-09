
          <div class="card-body">
                <table class="table table-responsive-sm table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Seri</th>
                      <th>Loại</th>
                      <th>Mã</th>
                      <th>Mệnh giá</th>
                      <th>Trạng thái</th>
                      <th>Mã status</th>
                      <th>Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody class="content-table">
                      @foreach ($card as $rows)
                      <tr id="tr-id-{{$rows->id}}">
                        <td>{{ $rows->id }}</td>
                        <td>{{ $rows->name }}</td>
                        <td>{{ $rows->seri_card }}</td>
                        <td>{{ $rows->type_card }}</td>
                        <td>{{ $rows->code_card }}</td>
                        <td>{{ $rows->amount_card }}</td>
                        <td>
                            @if($rows->status == 0) 
                                <span style="background: #f1c40f;" class="badge badge-warning">Chưa gửi</span>
                            @elseif($rows->status == 1)
                                <span style="background: #27ae60; color: white" class="badge badge-success">Thành công</span>
                            @elseif($rows->status == 2 || $rows->status == 3)
                                <span style="background: #c0392b; color: white" class="badge badge-error">Thất bại</span>
                            @elseif($rows->status == 99)
                                <span style="background: #f1c40f;" class="badge badge-warning">Pending Chưa Callback</span>
                            @endif                        </td>
                        <td>{{ $rows->status }}</td>
                        <td>{{ $rows->created_at }}</td>
                      <td> 
                        <button type="button" id="duyet_the_user" row-amount="{{$rows->amount_card}}" row-id="{{$rows->id}}" user-uid="{{$rows->uid}}"  class="btn btn-success">+</button> 
                        <button type="button" row-id={{$rows->id}} id="btn-failed" class="btn btn-danger">x</button>
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
<script>
  $(document).ready(function () {
    $(document).on('click', '#duyet_the_user', function(event){
        // console.log('xxxx');
        // event.preventDefault();
        $('.wrap-loading').css({'visibility' : 'visible'});
        let uid = $(this).attr('user-uid');
        let id = $(this).attr('row-id');
        let amount = $(this).attr('row-amount');
        duyetThe(uid, id, amount, 'success');
    });
  $(document).on('click', '#btn-failed', function(event){
        $('.wrap-loading').css({'visibility' : 'visible'});
        let id = $(this).attr('row-id');
        duyetThe(undefined, id, undefined, 'failed');
    });
  });
  function duyetThe(uid, id, amount, action){
      ajaxSetup();
      if (action === 'success') {
        $.ajax({
            type: 'POST',
            url: "/admin/card/duyet_the",
            data: {
              'id' : id,
              'uid' : uid,
              'amount' : amount
            },
            dataType: 'json',
            success: function(res) {
                if (res.rcode === 200) {
                  toastr.success(res.msg);
                  $('#tr-id-' + id).hide('slow');
                  $('.wrap-loading').css({'visibility' : 'hidden'});
                } else {
                  toastr.error(res.msg);
                  $('.wrap-loading').css({'visibility' : 'hidden'});
                }
            },
            error: function(error) {
              $('.wrap-loading').css({'visibility' : 'hidden'});
              location.reload();
            }
        });
      } else if (action === 'failed'){
        $.ajax({
            type: 'POST',
            url: "/admin/card/duyet_the_thatbai",
            data: {
              'id' : id,
            },
            dataType: 'json',
            success: function(res) {
                if (res.rcode === 200) {
                  $('#tr-id-' + id).hide('slow');
                  toastr.success(res.msg);
                  $('.wrap-loading').css({'visibility' : 'hidden'});
                } else {
                  toastr.error(res.msg);
                  $('.wrap-loading').css({'visibility' : 'hidden'});
                }
            },
            error: function(error) {
              $('.wrap-loading').css({'visibility' : 'hidden'});
              location.reload();
            }
      });
    }
}
</script>
      