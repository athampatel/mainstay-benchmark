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
								<div class="text-center py-4">
									<h3 class="login-from-title font-open-sans font-bold font-32">Sign In</h3>									
								</div>
								@if($errors->any())
									@foreach ($errors->all() as $error)	
										<div class="alert alert-danger font-12">{{$error}}</div>
									@endforeach
								@endif								
								<div class="form-body">
									<form class="row g-3" method="POST" action="{{route('login')}}">
										{{-- @csrf --}}
										<div class="col-12">
											<label for="inputEmailAddress" class="form-label">Email Address</label>
											<div class="input-group">
											<input type="email" class="form-control" id="inputEmailAddress" name="email" placeholder="Email Address" required>
											<a href="javascript:void(0);" class="absolute-icon"><i class='bx bx-envelope'></i></a>
											</div>
										</div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Enter Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" name="password" class="form-control border-end-0" id="inputChoosePassword" value="" placeholder="Enter Password" required> <a href="javascript:void(0);" class="input-group-text bg-transparent"><i class='bx bx-hide' id="show-password-icon"></i></a>
                                                </div>
                                            </div>											
										<div class="col-12">
											<div class="d-grid">
												<button type="submit" class="btn mb-4 button-bg-primary-green  padding-y-15 font-open-sans font-semi-bold font-20">Sign In</button>
												{{-- <a href="/sign-up" class="btn button-bg-primary-black color-white padding-y-15 font-open-sans font-semi-bold font-20">Join as a customer?</a> --}}
												<a href="/sign-up" class="btn button-bg-primary-black color-white padding-y-15 font-open-sans font-semi-bold font-20">Sign Up</a>
											</div>
										</div>
										<div class="col-12 py-3 text-center"><a href="{{ url('forgot-password') }}" class="font-open-sans font-regular font-20 primary-gray">Forgot Password?</a>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
@endsection
	
	