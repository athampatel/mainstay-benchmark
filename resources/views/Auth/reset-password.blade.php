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
									<img src="/assets/images/logo.svg" width="180" alt="" />
								</div>
								
								@if(Session::has('status'))
									<div class="alert alert-success font-12">{{ Session::get('status')}}</div>
									<script>
										setTimeout(function() {
											window.location.href = "/"
										}, 2000); // 2 second
									 </script>
								@endif
								@if($errors->any())
									@foreach ($errors->all() as $error)	
										<div class="alert alert-danger font-12">{{$error}}</div>
									@endforeach
								@endif								
								<div class="form-body">
									@if($valid || $errors->any())
									<div class="text-center py-4">
										<h3 class="login-from-title font-open-sans font-bold font-32">Reset account password</h3>									
									</div>
									<form class="row g-3" method="POST" action="{{route('password.update')}}">
										@csrf
                                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                        <input type="hidden" name="email" value="{{ $request->email }}">
                                        <div class="col-12">
                                            <label for="inputChoosePassword" class="form-label">New password</label>
                                            <div class="input-group align-items-center" id="show_new_hide_password">
                                                <input type="password" name="password" class="form-control border-end-0" id="inputNewPassword" value="" placeholder="New password" required> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide' id="show-new-password-icon"></i></a>
                                            </div>
                                        </div>											
                                        <div class="col-12">
                                            <label for="inputChoosePassword" class="form-label">Confirm new password</label>
                                            <div class="input-group align-items-center" id="show_confirm_hide_password">
                                                <input type="password" name="password_confirmation" class="form-control border-end-0" id="inputConfirmPassword" value="" placeholder="Confirm new password" required> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide' id="show-confirm-password-icon"></i></a>
                                            </div>
                                        </div>											

										<div class="col-12">
											<div class="d-grid">
												<button type="submit" class="btn mb-4 button-bg-primary-green padding-y-15 font-open-sans font-semi-bold font-20">Reset</button>
											</div>
										</div>
									</form>
									@elseif(Session::has('status'))
										<div class="text-center py-4">
											<h3 class="login-from-title font-open-sans font-bold font-32 text-success">{{ Session::get('status')}}</h3>									
										</div>									
									@elseif(!Session::has('status'))
									<div class="text-center py-4">
										<h3 class="login-from-title font-open-sans font-bold font-32 text-danger">Password reset link is expired or invalid.</h3>									
									</div>
									<a href="/forgot-password" class="btn mb-4 button-bg-primary-green padding-y-15 font-open-sans font-semi-bold font-20" style="width:100%;">Forgot Password</a>
										<div class="alert alert-danger font-12">Password reset link is expired or invalid.</div>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection