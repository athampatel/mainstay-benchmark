@extends("layouts.login")

@section("wrapper")	
	<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
		<div class="container-fluid">
			<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
				<div class="col mx-auto login-widget">
					<div class="card">
						<div class="card-body no-shadow">
							
							<div class="p-4 rounded">
								<div class="mb-4 mt-3 text-center">
									<img src="assets/images/logo.svg" width="180" alt="" />
								</div>
								<div class="text-center">
									<h3 class="">Sign in</h3>									
								</div>								
								<div class="form-body">
									<form class="row g-3">
										<div class="col-12">
											<label for="inputEmailAddress" class="form-label">Email Address</label>
											<input type="email" class="form-control" id="inputEmailAddress" placeholder="Email Address">
										</div>
										<div class="col-12">
											<label for="inputChoosePassword" class="form-label">Enter Password</label>
											<div class="input-group" id="show_hide_password">
												<input type="password" class="form-control border-end-0" id="inputChoosePassword" value="password" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-check form-switch">
												<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
												<label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
											</div>
										</div>											
										</div>
										<div class="col-12">
											<div class="d-grid">
												<button type="submit" class="btn mb-4 btn-primary">Sign in</button>
												<a href="#" class="btn btn-secondary">Join us customer?</a>
											</div>
										</div>
										<div class="col-12 py-3 text-center">	<a href="{{ url('authentication-forgot-password') }}">Forgot Password ?</a>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--end row-->
		</div>
	</div>
@endsection
	
	