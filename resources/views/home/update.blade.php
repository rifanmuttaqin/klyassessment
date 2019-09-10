@extends('master', ['profile_content'=>true])

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
	
<form method="post" action="{{ route('do-update-data') }}" enctype="multipart/form-data">

    @csrf
    <div class="form-group">
      <label>Name</label>
      <input type="text" value="{{ $data['name'] }}" class="form-control" value="" name="name">
      @if ($errors->has('name'))
          <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('name') }}</p></div>
      @endif
    </div>

    <div class="form-group">
      <label>Email</label>
      <input type="text" value="{{ $data['email'] }}" class="form-control" value="" name="email">
      @if ($errors->has('email'))
          <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('email') }}</p></div>
      @endif
    </div>

    <div class="form-group">
      <label>Date of birth</label>
      <input autocomplete="off" type="text" name="date_of_birth" value="{{ $data['date_of_birth'] }}" class="form-control"/>
      @if ($errors->has('date_of_birth'))
          <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('date_of_birth') }}</p></div>
      @endif
    </div>

    <div class="form-group">
      <label>Phone Number</label>
      <input type="text" value="{{ $data['phone_number'] }}" class="form-control" value="{{ $data['phone_number'] }}" name="phone_number">
      @if ($errors->has('phone_number'))
          <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('phone_number') }}</p></div>
      @endif
    </div>

	<div class="form-group">
		<label for="sel1">Gender</label>
		<select class="form-control" name="gender" id="gender">
			<option value="10" {{ ($data['gender']) != 10 ? '' : 'selected=selected' }} >Male</option>
			<option value="20" {{ ($data['gender']) != 20 ? '' : 'selected=selected' }} >Female</option>
		</select>
		@if ($errors->has('gender'))
          <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('gender') }}</p></div>
      	@endif
	</div>

	<input type="hidden" value="{{ $data['iduser'] }}" class="form-control" value="" name="iduser">

    <div class="form-group" style="padding-top: 20px">
      <button type="submit" class="btn btn-info"> UPDATE </button>
    </div>

@endsection

@section('profile_picture')

<div style="text-align: center">
    <img src="<?= $data['profile_pic'] != null ? URL::to('/').'/storage/data_picture/'. $data['profile_pic'] : URL::to('/').'/layout/assets/img/default-avatar.png' ?>" style="width:200px;height:200px;" class="img-thumbnail center-cropped" id="profile_pic"> 
</div>


<div style="text-align: center; padding-top: 10px">

@if ($data['profile_pic'] != null)
  
  <div id="btnDelete">
    <button type="button" class="btn btn-info" id="delete_image">
        <span class="glyphicon glyphicon-trash"></span>
    </button>
  </div>

  <div id="btnUpload" style="display: none">
    <input type="file" name="file" id="file" class="inputfile" accept="image/x-png,image/gif,image/jpeg"/>
    <label for="file"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Pilih Gambar</label>
    <p> Gambar Max. 2 MB </p>
  </div>

@else

  <input type="file" name="file" id="file" class="inputfile" accept="image/x-png,image/gif,image/jpeg"/>
  <label for="file"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Pilih Gambar</label>

  <p> Gambar Max. 2 MB </p>
  
@endif
  
@if ($errors->has('file'))
    <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('file') }}</p></div>
@endif

</div>

</form>

@endsection

@push('scripts')

<script type="text/javascript">

	$( document ).ready(function() {

	  $("#file").change(function() {
	    
	    var size = this.files[0].size;
	  
	    if(size >= 2000587)
	    {
	      swal('Ukuran file maksimal 2 MB', { button:false, icon: "error", timer: 1000});
	      return false;
	    }

	    readURL(this);

	  });

	});

  function removePicture()
  {
    $('#profile_pic').attr('src', '');
    $('#btnUpload').show();
    $('#btnDelete').hide();
  }

	function readURL(input) {
		if (input.files && input.files[0]) {
			
      var reader = new FileReader();

			reader.onload = function(e) {
			  $('#profile_pic').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}

	$(function() {

		$('input[name="date_of_birth"]').daterangepicker({
				singleDatePicker: true,
				showDropdowns: true,
				minYear: 1901,
				maxYear: parseInt(moment().format('YYYY'),10)
		},

		function(start, end, label) {
			var years = moment().diff(start, 'years');
		});

	});

  $( document ).ready(function() {
      
      $('#delete_image').click(function() { 
        
        removePicture();
      
      })

  });
 


</script>

@endpush