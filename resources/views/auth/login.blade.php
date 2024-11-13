<x-guest-layout>
    <div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
        <!-- Main container for the form and illustration -->
        <div class="container bg-white rounded-4 shadow-lg d-flex flex-lg-row flex-column p-0 overflow-hidden"
            style="max-width: 1000px;">

            <!-- Sidebar with Illustration -->
            <div class="d-none d-lg-flex align-items-center justify-content-center bg-light p-5"
                style="width: 50%; background-color: #ffff;">
                <img src="{{('assets/images/wbi-logo.png')}}" alt="Illustration" class="img-fluid" style="max-height: 300px;">
            </div>

            <!-- Login Form Section -->
            <div class="p-5" style="width: 100%; max-width: 450px;">
                <!-- Heading -->
                <h2 class="text-center text-primary fw-bold mb-4" style="font-size: 1.8rem;">{{ __('Log In') }}</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Username Field -->
                    <div class="mb-4">
                        <label for="email" class="form-label">{{ __('Your Name') }}</label>
                        <div class="input-group">
                            <input type="email" name="email" id="email"
                                class="form-control rounded-pill shadow-sm" value="{{ old('email') }}" required
                                autofocus autocomplete="username" placeholder="Enter your email" />
                            <span class="input-group-text rounded-pill bg-white border-0">
                                <i class="fas fa-user text-muted"></i>
                            </span>
                        </div>
                        @error('email')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                class="form-control rounded-pill shadow-sm" required autocomplete="current-password"
                                placeholder="Enter your password" />
                            <span class="input-group-text rounded-pill bg-white border-0">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="form-check mb-4">
                        <input type="checkbox" name="remember" id="remember_me" class="form-check-input" />
                        <label for="remember_me" class="form-check-label text-muted">
                            {{ __('Remember me') }}
                        </label>

                        <div class="float-end">
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none text-secondary" href="{{ route('password.request') }}">
                                    {{ __('Forgot pwd?') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Log In Button -->
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary rounded-pill py-2 shadow-sm">
                            {{ __('Log In') }}
                        </button>
                    </div>

                    <!-- Or Login With -->
                    <div class="text-center text-muted my-3">{{ __('Or login with') }}</div>
                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <a href="#" class="btn btn-outline-primary btn-sm rounded-pill px-3"><i
                                class="fab fa-facebook"></i> Facebook</a>
                        <a href="#" class="btn btn-outline-info btn-sm rounded-pill px-3"><i
                                class="fab fa-twitter"></i> Twitter</a>
                        <a href="/socialite/google" class="btn btn-outline-danger btn-sm rounded-pill px-3"><i
                                class="fab fa-google"></i> Google</a>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center mt-4">
                        <small class="text-muted">
                            {{ __("Don't have an account?") }}
                            <a href="{{ route('register') }}" class="text-decoration-none text-primary">
                                {{ __('Create an account') }}
                            </a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
