<x-guest-layout>
    <div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
        <!-- Main container for the form and illustration -->
        <div class="container bg-white rounded shadow-lg d-flex flex-lg-row flex-column p-0 overflow-hidden" style="max-width: 900px;">
            
            <!-- Illustration Section -->
            <div class="d-none d-lg-flex align-items-center justify-content-center" style="width: 50%; background-color: #f8f9fa;">
                <img src="{{('assets/images/wbi-logo.png')}}" alt="Illustration" class="img-fluid" style="max-width: 80%;" />
            </div>
            
            <!-- Form Section -->
            <div class="p-5" style="width: 100%; max-width: 500px;">
                
                <!-- Form Title -->
                <h2 class="text-center font-weight-bold mb-4">{{ __('Register') }}</h2>
                
                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="username">
                        @error('email')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
                        @error('password')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        @error('password_confirmation')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Register Button -->
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
                    </div>

                    <!-- Social Login -->
                    <div class="text-center mt-4">
                        <p class="small text-muted">Or sign up with</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="text-primary"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-info"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-danger"><i class="fab fa-google"></i></a>
                        </div>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center mt-3">
                        <p class="small text-muted">{{ __('Already registered?') }}
                            <a href="{{ route('login') }}" class="text-decoration-none">{{ __('Log In') }}</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
