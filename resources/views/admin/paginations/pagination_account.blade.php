
          <div class="card-body">
                <table class="table table-responsive-sm table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th>Mã Acc</th> 
                      <th>TypeAccount</th>
                      <th>Username</th>
                      <th>Password</th>
                      <th>Price</th>
                      <th>Content</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody class="content-table">
                      @foreach ($accountlist as $rows)
                      <tr>
                        <td>{{ $rows->acc_id }}</td>
                        <td>{{ $rows->type_account }}</td>
                        <td>{{ $rows->username }}</td>
                        <td>{{ $rows->password }}</td>
                        <td>{{ $rows->price }}</td>
                        <td>{{ $rows->content }}</td>
                      <td>
                          @if($rows->status==1) 
                              <span class="badge badge-success">Active</span>
                          @else
                              <span  class="badge badge-danger">UnActive</span>
                          @endif
                      </td>
                      <td>
                      <a href="/admin/accounts/update/{{$rows->acc_id}}"><button id="btn-update" id-data="{{$rows->acc_id}}" class="btn btn-primary"><i class="cui-pencil"></i></button></a>
                          <button id="btn-delete" id-data="{{$rows->acc_id}}" class="btn btn-danger"><i class="cui-delete"></i></button>
                      </td>
                      </tr>
                      @endforeach
                  </tbody>
                </table>
                <nav>
                    {{$accountlist->links()}}
                </nav>
              </div>
            </div>
          </div>
          <!-- /.col-->
        </div>
        <!-- /.row-->
      </div>
      <script>
        $('.pagination a').click(function(event){
            event.preventDefault();
            $('.wrap-loading').css({'visibility' : 'visible'});
            let page = $(this).attr('href').split('page=')[1];
            fetch_data(page);
            return;
        });
      
        function fetch_data(page){
          ajaxSetup();
          $.ajax({
                type: 'POST',
                url: "/admin/accounts/fetch_data?page="+page,
                success: function(responseData) {
                    $('#table-paginate').html(responseData);
                    $('.wrap-loading').css({'visibility' : 'hidden'});
                },
                error: function(error) {
                  console.log(error);
                  $('.wrap-loading').css({'visibility' : 'hidden'});
                }
            });
        }
    </script>
      