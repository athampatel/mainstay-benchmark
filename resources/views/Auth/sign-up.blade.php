@extends("layouts.login")

@section("wrapper")	
	<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
		<div class="container-fluid">
			<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
				<div class="col mx-auto login-widget">
					<div class="card">
						<div class="card-body no-shadow">
							@if(Session::has('success'))
								<h5 class="alert alert-success text-center font-12">{{ Session::get('success')}}</h5>
							@endif

							@if($errors->any())
								<h5 class="alert alert-danger font-bold text-center font-12">{{$errors->first()}}</h5>
							@endif
							<div class="p-4 rounded">
								<div class="mb-4 mt-3 text-center">
									<img src="assets/images/logo.svg" width="180" alt="" />
								</div>
								<div class="text-center py-4">
									<h3 class="login-from-title font-32 font-open-sans font-bold pb-3">Sign Up</h3>									
								</div>								
								<div class="form-body">
									<form class="row g-3" method="POST" action="/sign-up">
										@csrf
										<div class="col-12"> 
											<label for="inputFullname" class="form-label">Full Name</label>
											<input type="text" class="form-control" id="inputFullname" name="full_name" value="" placeholder="Full Name *" required>
										</div>
										<div class="col-12">
											<label for="inputEmailAddress" class="form-label">Email Address</label>
											<input type="email" class="form-control" id="inputEmailAddress" name="email" value="" placeholder="Email Address *" required>
										</div>
										<div class="col-12">
											<label for="inputPhoneNo" class="form-label">Phone Number</label>
											<input type="text" class="form-control" id="inputPhoneNo" name="phone_no" value="" placeholder="Contact No" >
										</div>
										<div class="col-12">
											<label for="CompanyName" class="form-label">Company Name</label>
											<input type="text" class="form-control" id="CompanyName" name="company_name" value="" placeholder="Company Name *" required>
										</div>
                                        <div class="col-12 pt-1">
                                            <div class="sign-up-checbox d-flex align-items-center position-relative">
                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate" required>
                                                <label class="font-16 font-regular font-open-sans d-block" for="flexCheckIndeterminate">
                                                    I agree to <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#TermsModel"><span class="text-primary-green font-bold">Terms and Conditions</span></a>
                                                </label>
                                            </div>
                                        </div>
										<div class="col-12">
											<div class="d-grid">
												<button type="submit" class="btn mb-4 button-bg-primary-green padding-y-15 font-open-sans font-semi-bold font-20">Sign Up</button>
											</div>
										</div>
										<div class="col-12 pb-3 font-open-sans font-18">Already have an Account? <a href="{{ url('sign-in') }}" class="font-bold sign-in-hover">Sign In</a>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	@include('Auth.terms')
@endsection
	
	