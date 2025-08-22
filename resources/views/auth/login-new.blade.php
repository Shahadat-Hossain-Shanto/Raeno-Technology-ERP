<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	{{-- <title>{{ config('app.name', 'Login') }}</title> --}}
	<title>Bikroyik LogIn</title>

	<!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

	<!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

	<style>
		body, html {
		  height: 100%;
		  margin: 0;
		  font-family: Arial, Helvetica, sans-serif;
		}

		* {
		  box-sizing: border-box;
		}

		.bg-image {
		  /* The image used */
		  background-image: url("images/login-background1.jpg");

		  /* Add the blur effect */
		  filter: blur(8px);
		  -webkit-filter: blur(8px);

		  /* Full height */
		  height: 100%;

		  /* Center and scale the image nicely */
		  background-position: center;
		  background-repeat: no-repeat;
		  background-size: cover;
		}

		/* Position text in the middle of the page/image */
		.bg-text {
		  background-color: white; /* Fallback color */
  		  /*background-color: rgba(0,0,0, 0); /* Black w/opacity/see-through */*/
		  color: white;
		  font-weight: bold;
		  border-radius: 10px;
		  /*border: 3px solid #f1f1f1;*/
		  box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
		  position: absolute;
		  top: 50%;
		  left: 50%;
		  transform: translate(-50%, -50%);
		  z-index: 2;
		  width: 400px;
		  height: 550px;
		  padding: 20px;
		  /*text-align: center;*/
		}

		@media only screen and (max-width:500px) {
		  /* For mobile phones: */
		  .bg-image, .bg-text, .logo, .deco {
		    width: 100%;
		  }
		}

		img.logo {
		  /*height: 90px;*/
		  position: absolute;
		  margin: auto;
		  display: block;
		  top: 50px;
		  left: 0;
		  right: 0;
		  width: 130px;
		  height: 130px;
		}

		img.deco {
			position: absolute;
			right: 0px;
			bottom: 0px;
			width: 160px;
		    height: 160px;
		}

		a.forgotpassword {
		    color: red;
		    text-decoration: none;
		}

		a.forgotpassword:hover {
		    text-decoration: underline;
		}
	</style>

	<!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

	<!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>
<body>
	<div class="bg-image"></div>
	<!-- <img class="logo" src="images/logo1.png"> -->

	<form method="POST" action="{{ route('login') }}">
	@csrf
		<div style="overflow: auto;">
			<div class="bg-text">
				<img class="deco" src="images/e-shop.jpeg">
				<h4 class="text-center pt-5"><strong>Sign in</strong></h4>
				<div class="row">
					<div class="col-1"></div>
					<div class="col-10">

                        <div class="mb-3 pt-5">
                            <label for="username" style="font-weight: normal;" class="form-label">Enter Your Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="admin123" value="{{ old('username') }}" autofocus>
                            @error('username')
                                <h6 class="text-danger pt-1" style="font-size: 14px;">{{ $message }}</h6>
                            @enderror
                        </div>
						<div class="mb-3 pt-1">
							<label for="" style="font-weight: normal;" class="form-label">Password</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="password*" autocomplete="current-password">
							@error('password')
							    <h6 class="text-danger pt-1" style="font-size: 14px;">{{ $message }}</h6>
							  @enderror
							<div class="form-check pt-2">
							  <input class="form-check-input" type="checkbox" value="" id="showPassword" onclick="myFunction()">
							  <label style="font-weight: normal;" class="form-check-label" for="showPassword">
							    Show Password
							  </label>

							</div>
							@if($errors->any())
								@if($errors->first() == "The provided credentials do not match our records.")
									<h6 class="text-danger pt-2" style="font-size: 14px;">{{$errors->first()}}</h6>
								@endif
							@endif
						</div>
					</div>
				</div>
				<div class="row pt-3 ">
					<div class="col-1">

					</div>
					<div class="col-10">
						<button type="submit" class="btn btn-primary px-5 py-1" style="font-size: 16px; width: 160px;">
                            {{ __('Sign in') }}
                        </button>
						@if (Route::has('password.request'))
							<p class="text-sm pt-2" style="font-size: 14px;"><a href="{{ route('password.request') }}" class="forgotpassword text-primary" >{{ __('Forgot your password?') }}</a></p>
							<p class="text-sm pt-2" style="font-size: 14px;"><a href="{{ url('/subscriber-create') }}" class=" text-primary" >{{ __('Dont have an account?') }}</a></p>

						@endif
					</div>
				</div>
			</div>
		</div>
	</form>




<!-- Bootstrap 5 -->

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
</script>
<script type="text/javascript">
	function myFunction() {
	  var x = document.getElementById("password");
	  if (x.type === "password") {
	    x.type = "text";
	  } else {
	    x.type = "password";
	  }
	}
</script>
</body>
</html>
