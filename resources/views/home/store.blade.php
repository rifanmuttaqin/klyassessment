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
	
<form method="post" action="{{ route('store-data') }}" enctype="multipart/form-data">

    @csrf
    <div class="form-group">
      <label>Name</label>
      <input type="text" value="" class="form-control" value="" name="name" id="name">
      @if ($errors->has('name'))
          <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('name') }}</p></div>
      @endif
    </div>

    <div class="form-group">
      <label>Email</label>
      <input type="text" value="" class="form-control" value="" name="email" id="email">
      @if ($errors->has('email'))
          <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('email') }}</p></div>
      @endif
    </div>

    <div class="form-group">
      <label>Date of birth</label>
      <input autocomplete="off" type="text" name="date_of_birth" class="form-control" id="date_of_birth"/>
      @if ($errors->has('date_of_birth'))
          <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('date_of_birth') }}</p></div>
      @endif
    </div>

    <div class="form-group">
      <label>Phone Number</label>
      <input type="text" value="" class="form-control" value="" name="phone_number" id="phone_number">
      @if ($errors->has('phone_number'))
          <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('phone_number') }}</p></div>
      @endif
    </div>

	<div class="form-group">
		<label for="sel1">Gender</label>
		<select class="form-control" name="gender" id="gender">
			<option value="" ></option>
			<option value="10" >Male</option>
			<option value="20" >Female</option>
		</select>
		@if ($errors->has('gender'))
          <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('gender') }}</p></div>
      	@endif
	</div>

    <div class="form-group" style="padding-top: 20px">
      <button type="submit" class="btn btn-info"> CREATE </button>
      <button type="button" class="btn btn-danger pull-right" id="reset_data"> RESET </button>
    </div>

@endsection

@section('profile_picture')

<div style="text-align: center">
    <img src="<?= URL::to('/').'/layout/assets/img/default-avatar.png' ?>" style="width:200px;height:200px;" class="img-thumbnail center-cropped" id="profile_pic"> 
</div>
<div style="text-align: center; padding-top: 10px">
  
  <input type="file" name="file" id="file" class="inputfile" accept="image/x-png,image/gif,image/jpeg"/>
  <label for="file"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Pilih Gambar</label>

  <p> Gambar Max. 2 MB </p>
  
@if ($errors->has('file'))
    <div class="error"><p style="color: red"><span>&#42;</span> {{ $errors->first('file') }}</p></div>
@endif

</div>

</form>

@endsection

@push('scripts')

<script type="text/javascript">

  function clearData()
  {
    $("#name").val('');
    $("#email").val('');
    $("#date_of_birth").val('');
    $("#phone_number").val('');
    $("#gender").val('');
  }

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

    $( "#reset_data" ).click(function() {
        clearData();
    });

	});

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

</script>

@endpush