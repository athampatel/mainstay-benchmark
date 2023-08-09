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
									<h3 class="font-bold font-open-sans font-32">Forgot your password</h3>									
								</div>
								@if(Session::has('status'))
									<div class="alert alert-success font-12">{{ Session::get('status')}}</div>
								@endif
								@if($errors->any())
									@foreach ($errors->all() as $error)	
										<div class="alert alert-danger font-12">{{$error}}</div>
									@endforeach
								@endif									
								<div class="form-body mt-5">
									<form class="row g-3" method="POST" action="/forgot-password">
										@csrf
										<div class="col-12">
											<label for="inputEmailAddress" class="form-label">Email Address</label>
											<div class="input-group">
												<input type="email" class="form-control" id="inputEmailAddress" placeholder="Email Address" name="email" required>
												<a href="javascript:void(0);" class="absolute-icon"><i class='bx bx-envelope'></i></a>
											</div>
										</div>
										<div class="col-12 pt-2">
											<div class="d-grid">
												<button type="submit" class="btn mb-4 button-bg-primary-green padding-y-15 font-open-sans font-semi-bold font-20">Submit</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
	