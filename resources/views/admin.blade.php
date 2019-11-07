
@extends('layout.layout')
@section('content')


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
    <!-- Main content -->
    @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
    @endif

    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Đăng ký</button>

    <section class="content">
        <div class="row">
    
          <table class="table" >
            <thead>
              <tr>
                <th>Stt</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Số Phone</th>
                <th>Tỉnh thành</th>
                <th>Địa Chỉ</th>
                <th>Thời hạn</th>
                <th colspan="3">action</th>    
              </tr>
            </thead>
            <tbody  {{$count = 0}}>
            
              @foreach ($user as $item)
              <tr>
                <th>{{$count++}}</th>
                <th>{{$item->name}}</th>
                <th>{{$item->email}}</th>
                <th>{{$item->phone}}</th>
                <th>{{$item->provinces}}</th>
                <th>{{$item->address}}</th>
                <th>{{$item->time}}</th>
                 <th><a href="{{route('users.edit',$item->id)}}" class="btn btn-info">Edit</a></th>
                <th>
                  <form action="{{route('users.destroy',$item->id)}}" method="POST">
                    @method('DELETE')
                    @csrf
                   <button type="submit" class="btn btn-danger">Delete</button>
                  </form>
                </th>
                </tr>
              @endforeach
          
             </tbody>
          </table>
          {{$user->links()}}
        </div>
    </section>
  </div>

      <!-- Modal -->
      <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog">
          <form action="{{route('users.store')}}" method="post">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Đăng ký</h4>
              </div>
              <div class="modal-body">
                <input type="hidden" name="role_id" value="1">
                  <div class="form-group">
                      <label for="exampleInputEmail1">Tên </label>
                      <input type="text" class="form-control" name="name"  aria-describedby="nameHelp" placeholder="Enter Name">
                    </div>
                  <div class="form-group">
                      <label for="exampleInputEmail1">Email </label>
                      <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Password</label>
                      <input type="password"  class="form-control" name="password" id="exampleInputPassword1" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="">Quốc Gia</label>
                            <select name="country" id="country" class="form-control" >
                                @foreach ($listCountry as $key => $country)
                            <option value="{{$key}}">{{$country}}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="form-group">
                        <select name="provinces" id="states"  class="form-control"></select>
                    </div>
                    <div class="form-group">
                        <label for="">Tỉnh Thành</label>
                        <input type="text" class="form-control" name="address"   id="address" placeholder="Nhập địa chỉ">
                    </div>
                      <div class="form-group">
                          <label for="">Phone</label>
                          <input type="number" class="form-control" name="phone"   id="phone" placeholder="nhập phone">
                        </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-default" >Save</button>
              </div>
            </div>
          </form>
          </div>
        </div>

<script>
  $('#country').change(function(){
      $("#states option").remove();
      var country = $(this).val();
     $.ajax({
        url : '{{ route( 'users.country' ) }}',
        data: {
          "_token": "{{ csrf_token() }}",
            "country": country
          },
        type: 'post',
        dataType: 'json',
        success: function( result )
        {
              $.each(result, function() {
                $("#phone").val(result.phone);
                $.each(result.states, function(v,k) {
                   $('#states').append($('<option>', {value:k, text:k},'</option>'));
                });
            });
        },
        error: function()
        {
            //handle errors
            alert('lỗi không lấy được các nước');
        }
      });
  });
</script>
@endsection