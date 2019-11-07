@extends('layout.layout')
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
            <form action="{{route('users.update',$user->id)}}" method="post">
                        @csrf
                        <input name="_method" type="hidden" value="PUT">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Thay đổi thông tin cá nhân </h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="role_id" value="1">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tên </label>
                                <input type="text" class="form-control" name="name" value="{{$user->name}}" aria-describedby="nameHelp" placeholder="Enter Name">
                                </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email </label>
                                <input type="email" class="form-control" name="email" value="{{$user->email}}" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                                </div>
                                <div class="form-group">
                                    <label for="">Quốc Gia</label>
                                        <select name="countries" id="country" class="form-control" >
                                            @foreach ($listCountry as $key => $country)
                                        <option value="{{$key}}" @if($user->countries === $key) selected="selected" @endif>{{$country}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Tỉnh Thành</label>
                                    <select name="provinces" id="states"   class="form-control"><option value="{{$user->provinces}}">{{$user->provinces}}</option></select>
                                </div>
                                <div class="form-group">
                                    <label for="">Địa chỉ</label>
                                    <input type="text" class="form-control" name="address" value="{{$user->address}}"   id="address" placeholder="Nhập địa chỉ">
                                </div>
                                <div class="form-group">
                                    <label for="">Phone</label>
                                    <input type="number" class="form-control" name="phone" value="{{$user->phone}}"   id="phone" placeholder="nhập phone">
                                    </div>
                        </div>
                        <div class="modal-footer">
                            <a href="{{route('users.index')}}" type="button" class="btn btn-danger" data-dismiss="modal">Close</a>
                            <button type="submit" class="btn btn-default" >Save</button>
                        </div>
                        </div>
                </form>
            </div>
        </div>
    </section>
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