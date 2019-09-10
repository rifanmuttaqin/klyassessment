@extends('master')
 
@section('title', '')

@section('alert')

@if(Session::has('alert_success'))
  @component('components.alert')
        @slot('class')
            success
        @endslot
        @slot('title')
            Terimakasih
        @endslot
        @slot('message')
            {{ session('alert_success') }}
        @endslot
  @endcomponent
@elseif(Session::has('alert_error'))
  @component('components.alert')
        @slot('class')
            error
        @endslot
        @slot('title')
            Cek Kembali
        @endslot
        @slot('message')
            {{ session('alert_error') }}
        @endslot
  @endcomponent 
@endif

@endsection
 
@section('content')

<div style="padding-bottom: 20px">
  <a  href="{{ route('create-data') }}" type="button" class="btn btn-info"> TAMBAH </a>
</div>

<div style="width: 100%; padding-left: -10px;">
<div class="table-responsive">
<table id="datas_table" class="table table-bordered data-table display nowrap" style="width:100%">
    <thead>
        <tr>
            <th>File Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
</div>
</div>

@endsection

@section('modal')

<div class="modal fade" id="detailModal" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <p class="modal-title">User Detail</p>
      </div>
      <div class="modal-body">

        <div class="form-group">
          <label>Username</label>
          <input type="text" class="form-control" value="" id="username">
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="text" class="form-control" value="" id="email">
        </div>

        <div class="form-group">
          <label>Nama</label>
          <input type="text" class="form-control" value="" id="nama_lengkap">
        </div>

        <div class="form-group">
          <label for="sel1">Tipe Akun</label>
          <select class="form-control" id="tipe_akun">
            <option value="{{ User::ACCOUNT_TYPE_USER }}" >User</option>
          </select>
        </div>

        <label>Alamat</label>
        <textarea class="form-control" placeholder="" rows="3" id="alamat"></textarea>

      </div>
      <div class="modal-footer">
        <button type="button" id="update_data" class="btn btn-default pull-left">Update</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')

<script type="text/javascript">

var table;
var iduser;

function clearAll(){
  $('#username').val('');
  $('#tipe_akun').val('');
  $('#email').val('');
  $('#nama_lengkap').val('');
  $('#alamat').val('');
}

function btnUbah(id)
{
  var url              = '{{ route("update-data", ":iduser") }}';
  url                  = url.replace(':iduser', id);
  window.location.href = url;
}

function btnDel(id)
{
  iduser = id;
  
  swal({
      title: "Menghapus Data",
      text: 'Data yang telah dihapus tidak dapat dikembalikan kembali', 
      icon: "warning",
      buttons: true,
      dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      $.ajax({
        type:'POST',
        url: base_url + '/home/delete',
        data:{
          iduser:iduser, 
          "_token": "{{ csrf_token() }}",},
        success:function(data) {
          
          if(data.status != false)
          {
            swal(data.message, { button:false, icon: "success", timer: 1000});
          }
          else
          {
            swal(data.message, { button:false, icon: "error", timer: 1000});
          }

          table.ajax.reload();
        },
        error: function(error) {
          swal('Terjadi kegagalan sistem', { button:false, icon: "error", timer: 1000});
        }
      });      
    }
  });
}

$(function () {
  table = $('#datas_table').DataTable({
      processing: true,
      serverSide: true,
      rowReorder: {
          selector: 'td:nth-child(2)'
      },
      responsive: true,
      ajax: "{{ route('home-data') }}",
      columns: [
          {data: 'name', name: 'name'},
          {data: 'action', name: 'action', orderable: false, searchable: false},
      ]
  });
});
</script>

@endpush