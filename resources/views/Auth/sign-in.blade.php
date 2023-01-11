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
									<h3 class="login-from-title font-open-sans font-bold font-32">Sign in</h3>									
								</div>
								@if($errors->any())
									@foreach ($errors->all() as $error)	
										<div class="alert alert-danger">{{$error}}</div>
									@endforeach
								@endif								
								<div class="form-body">
									<form class="row g-3" method="POST" action="{{route('login')}}">
										@csrf
										<div class="col-12">
											<label for="inputEmailAddress" class="form-label">Email Address</label>
											<input type="email" class="form-control" id="inputEmailAddress" name="email" placeholder="Email Address" required>
										</div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Enter Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    {{-- <input type="password" name="password" class="form-control border-end-0" id="inputChoosePassword" value="" placeholder="Enter Password" required> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a> --}}
                                                    <input type="password" name="password" class="form-control border-end-0" id="inputChoosePassword" value="" placeholder="Enter Password" required> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide' id="show-password-icon"></i></a>
                                                </div>
                                            </div>											
										{{-- </div> --}}

										<div class="col-12">
											<div class="d-grid">
												<button type="submit" class="btn mb-4 button-bg-primary-green color-white padding-y-15 font-open-sans font-semi-bold font-20">Sign in</button>
												<a href="{{route('register')}}" class="btn button-bg-primary-black color-white padding-y-15 font-open-sans font-semi-bold font-20">Join us customer?</a>
											</div>
										</div>
										<div class="col-12 py-3 text-center"><a href="{{ url('forgot-password') }}" class="font-open-sans font-regular font-20 primary-gray">Forgot Your Password?</a>
										{{-- <div class="col-12 py-3 text-center"><a href="{{ url('delete') }}" class="font-open-sans font-regular font-20 primary-gray">Delete</a> --}}
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
	
	