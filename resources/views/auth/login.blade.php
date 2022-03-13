<x-guest-layout>
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="../../index2.html" class="h1"><b>Admin</b>LTE</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input name="username" type="text" class="form-control @error('username') tw-border-red-700 @enderror" placeholder="Username" value="{{ old('username') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input name="password" type="password" class="form-control @error('password') tw-border-red-700 @enderror" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary text-primary hover:tw-text-blue-50 btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <x-auth.auth-session-status class="tw-mb-4" :status="session('status')" />
                        </div>
                    </div>
                </form>
                <!-- /.social-auth-links -->
                @if (Route::has('password.request'))
                    <p class="mb-1">
                        <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                    </p>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</x-guest-layout>
