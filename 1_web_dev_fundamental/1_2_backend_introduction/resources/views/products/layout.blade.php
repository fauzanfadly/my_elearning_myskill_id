<!DOCTYPE html>
<html>
<head>
    <title>Laravel 8 CRUD Application - ItSolutionStuff.com</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
	@if ($message = Session::get('success'))
	<div id="alert-success" class="alert alert-success alert-dismissible fade show py-1 sticky-top" role="alert">
		<p class="mb-0">{{ $message }}</p>
		<button type="button" class="close py-0" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	@endif

	@if ($message = Session::get('error'))
	<div id="alert-error" class="alert alert-danger alert-dismissible fade show py-1 sticky-top" role="alert">
		<p class="mb-0">{{ $message }}</p>
		<button type="button" class="close py-0" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	@endif

	@if ($message = Session::get('warning'))
	<div id="alert-warning" class="alert alert-warning alert-dismissible fade show py-1 sticky-top" role="alert">
		<p class="mb-0">{{ $message }}</p>
		<button type="button" class="close py-0" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	@endif

    <div class="container">
        @yield('content')
    </div>

	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
	<script>
		$(document).ready(function() {
			setTimeout(function() {
				$('#alert-success').alert('close');
			}, 3000);
		});
	</script>
</body>
</html>

