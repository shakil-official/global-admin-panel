<x-guest-layout>
    <!-- auth-page content -->
    <div class="auth-page-content overflow-hidden pt-lg-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card overflow-hidden m-0">
                        <div class="row justify-content-center g-0">
                            <div class="col-lg-6">
                                <div class="p-lg-5 p-4 auth-one-bg h-100">
                                    <div class="bg-overlay"></div>
                                    <div class="position-relative h-100 d-flex flex-column">
                                        <div class="mb-4">
                                            <a href="#" class="d-block">
                                                <img src="{{ asset('theme/assets/images/modal-bg.png') }}" alt=""
                                                     height="18">
                                            </a>
                                        </div>
                                        <div class="mt-auto">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="p-lg-5 p-4">
                                    <div>
                                        <h5 class="text-primary text-center">Admin Panel</h5>
                                        <p class="text-muted"></p>
                                    </div>

                                    <div class="mt-4">
                                        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                                            @csrf

                                            <!-- Email Address -->
                                            <div class="mb-3">
                                                <x-input-label for="email" :value="__('Email')"/>
                                                <x-text-input id="email"
                                                              class=""
                                                              type="email"
                                                              name="email"
                                                              :value="old('email')" required autofocus
                                                              autocomplete="email"/>
                                                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                                            </div>

                                            <!-- Password -->
                                            <div class="mt-4">
                                                <x-input-label for="password" :value="__('Password')"/>

                                                <x-text-input id="password" class="block mt-1 w-full"
                                                              type="password"
                                                              name="password"
                                                              required autocomplete="current-password"/>

                                                <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                                            </div>

                                            <!-- Remember Me -->
                                            <div class="form-check mt-4">
                                                <label for="remember_me" class="inline-flex items-center">
                                                    <input id="remember_me" type="checkbox" class="form-check-input"
                                                           name="remember">
                                                    <span class="form-check-label">{{ __('Remember me') }}</span>
                                                </label>
                                            </div>

                                            <div class="flex items-center justify-end mb-2">
                                                @if (Route::has('password.request'))
                                                    <a class="text-muted mt-2" href="{{ route('password.request') }}">
                                                        {{ __('Forgot your password?') }}
                                                    </a>
                                                @endif

                                                <x-primary-button class="btn btn-success w-100 mt-2">
                                                    {{ __('Log in') }}
                                                </x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->
</x-guest-layout>


