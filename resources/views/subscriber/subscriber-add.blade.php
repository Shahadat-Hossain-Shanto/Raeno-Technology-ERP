<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>Subscriber Form</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />

    <style>
        body, html {
		  height: 100%;
		  margin: 0;
		  font-family: Arial, Helvetica, sans-serif;
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
		  width: auto;
		  height: auto;
		  padding: 10px;
		  /*text-align: center;*/
		}

		a.forgotpassword {
		    color: red;
		    text-decoration: none;
		}

		a.forgotpassword:hover {
		    text-decoration: underline;
		}

	</style>
</head>
<body>
    <div class="bg-image"></div>

	<div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <!-- Header -->
				</div>
			</div>
		</div>

		<div class="content pt-1 pb-1">
			<div class="container-fluid ">
				<div class="row justify-content-center">
          			<div class="col-lg-5 bg-text" style="overflow: auto;">
	          			<div class="card-primary card-outline">
				            <div class="">
				                <h5 class="text-center pt-5"><strong>Subscribe Now!</strong></h5>
				            </div>

			              	<div class="card-body">
		          				<div class="container">

									<form id="AddSubscriberForm" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="orgname" class="form-label">Organization Name<span class="text-danger"><strong>*</strong></span></label>
                                                    <input type="text" class="form-control" name="orgname" id="orgname" placeholder="Enter organization name">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                  <label for="orgaddress" class="form-label">Organization Address<span class="text-danger"><strong>*</strong></span></label>
                                                  <input type="text" class="form-control" name="orgaddress" id="orgaddress" placeholder="Enter organization address">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pt-3">
                                            <div class="col-6">
                                                <div class="form-group">
                                                  <label for="ownername" class="form-label">Owner Name<span class="text-danger"><strong>*</strong></span></label>
                                                  <input type="text" class="form-control" name="ownername" id="ownername" placeholder="Enter owner name">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                  <label for="binnumber" class="form-label">BIN</label>
                                                  <input type="text" class="form-control" name="binnumber" id="binnumber" placeholder="Enter BIN number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pt-3">
                                            <div class="col-6">
                                                <div class="form-group">
                                                  <label for="contactnumber" class="form-label">Contact Number<span class="text-danger"><strong>*</strong></span></label>
                                                  <input type="text" class="form-control" name="contactnumber" id="contactnumber" placeholder="Enter contact number">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                  <label for="email" class="form-label">Email-Address</label>
                                                  <input type="email" class="form-control" name="email" id="email" placeholder="Enter email address">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pt-3">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="password" class="form-label">Password<span class="text-danger"><strong>*</strong></span></label>
                                                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter password here">
                                                    <!-- An element to toggle between password visibility -->
                                                    <div class="form-check form-switch pt-1">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" onclick="showPassword()">
                                                        <label class="form-check-label" for="flexSwitchCheckDefault">Show Password</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                  <label for="postype" class="form-label">POS Type</label>
                                                  <select class="form-control" name="postype" id="postype">
                                                      <option value="option_select" disabled selected>Select POS type</option>
                                                      <option value="Single Branch">Single Branch</option>
                                                      <option value="Multiple Branch">Multiple Branch</option>
                                                  </select>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pt-3">
                                            <div class="col-6">
                                                <div class="form-group">
                                                  <label for="registrationType" class="form-label">Subscription Type<span class="text-danger"><strong>*</strong></span></label>
                                                  <select class="form-control" name="registrationType" id="registrationType">
                                                      <option value="option_select" disabled selected>Select subscription</option>
                                                      <option value="7 Days Trial">7 Days Trial</option>
                                                      <option value="Purchase">Purchase</option>
                                                  </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                  <label for="logo" class="form-label">Logo</label>
                                                  <input type="file" class="form-control" name="logo" id="logo">
                                                </div>
                                            </div>
                                        </div>
									  	<div class="form-group pt-3">
										  	<button type="submit" class="btn btn-outline-primary">Subscribe</button>
											<button type="reset" value="Reset" class="btn btn-primary">Reset</button>
									  	</div>
                                          <div class="form-group pt-2">
                                            <p class="text-sm pt-2" style="font-size: 14px;"><a href="{{ route('login') }}" class="forgotpassword text-primary" >{{ __('Already Have An Account?') }}</a></p>
                                        </div>
									</form>

								</div> <!-- container -->
							</div> <!-- card-body -->
			          	</div> <!-- card card-primary card-outline -->
          			</div> <!-- col-lg-5 -->
          		</div> <!-- row -->
			</div> <!-- container-fluid -->
		</div> <!-- content -->

	</div> <!-- content-wrapper -->

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('dist/js/notify.min.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/subscriber.js"></script>

</body>
</html>
