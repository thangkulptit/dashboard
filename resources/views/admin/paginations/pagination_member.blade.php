<div class="card-body" id="content-table-members">
        <table class="table table-responsive-sm table-hover table-outline mb-0">
           <thead class="thead-light">
               <tr>
               <th class="text-center">
                   <i class="icon-people"></i>
               </th>
               <th>User</th>
               <th>Tai Khoan</th>
               <th class="text-center">UID</th>
               <th>Email</th>
               <th class="text-center">Money</th>
               <th>Action</th>
               </tr>
           </thead>
           <tbody class='content-table'>
               @foreach ($members as $item)
           <tr id="row-member-{{$item->id}}">
               <td class="text-center">
                   <div class="avatar">
                   <img class="img-avatar" src="img/avatars/1.jpg" alt="{{$item->email}}">
                   <span class="avatar-status badge-success"></span>
                   </div>
               </td>
               <td>
               <div>{{ $item->name }}</div>
                   <div class="small text-muted">
                   Đăng ký ngày: {{$item->created_at}}</div>
               </td>
               <td>
                    <div>{{ $item->username }}</div>
                </td>
               <td class="text-center">
               <strong>{{ $item->uid }}</strong>
               </td>
               <td>
                   <div class="clearfix">
                   <div class="float-left">
                       <strong>{{$item->email}}</strong>
                   </div>
                   </div>
               </td>
               <td class="text-center">
                    <strong> {{ number_format($item->money) }} <sup>VNĐ</sup></strong>
               </td>
               <td>
                    <button class="btn btn-info" id="btnUpdateMoney" member-id="{{$item->id}}" ><i class="fa fa-edit"></i></button>
                    <button class="btn btn-danger" id="btnPlusMoney" member-id="{{$item->id}}" ><i class="fa fa-plus fa-lg mt-1"></i></button>
               </td>
            </tr>
           @endforeach    
           </tbody>
           </table>
            @if(empty($flag)) 
                <nav>{{$members->links()}}</nav>
            @endif
       </div>
<script>
$(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        $('.wrap-loading').css({'visibility' : 'visible'});
        let page = $(this).attr('href').split('page=')[1];
        fetch_data(page);
    })

    function fetch_data(page){
    ajaxSetup();
    $.ajax({
        type: 'POST',
        url: "/admin/members/fetch_data?page="+page,
        success: function(responseData) {
            $('#table-paginate-member').html(responseData);
            $('.wrap-loading').css({'visibility' : 'hidden'})
        },
        error: function(error) {
        $('.wrap-loading').css({'visibility' : 'hidden'})
        console.log(error);
        }
    });
    
    }
</script>