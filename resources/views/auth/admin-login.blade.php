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
                                            <div class="mb-3">
                                                <i class="ri-double-quotes-l display-4 text-success"></i>
                                            </div>

                                            <div id="qoutescarouselIndicators" class="carousel slide"
                                                 data-bs-ride="carousel">
                                                <div class="carousel-indicators">
                                                    <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                            data-bs-slide-to="0" class="active" aria-current="true"
                                                            aria-label="Slide 1"></button>
                                                    <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                            data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                    <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                            data-bs-slide-to="2" aria-label="Slide 3"></button>
                                                </div>
                                                <div class="carousel-inner text-center text-white-50 pb-5">
                                                    <div class="carousel-item active">
                                                        <p class="fs-15 fst-italic"> </p>
                                                    </div>
                                                    <div class="carousel-item">
                                                        <p class="fs-15 fst-italic"> </p>
                                                    </div>
                                                    <div class="carousel-item">
                                                        <p class="fs-15 fst-italic"> </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end carousel -->

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="p-lg-5 p-4">
                                    <div>
                                        <h5 class="text-primary">Super Admin</h5>
                                        <p class="text-muted"></p>
                                    </div>

                                    <div class="mt-4">
                                        <form method="POST" action="{{ route('admin.login') }}" class="needs-validation"
                                              novalidate>
                                            @csrf
                                            <div class="mb-3">
                                                <label for="EmailInput" class="form-label">Email <span
                                                        class="text-danger">*</span></label>
                                                <input name="email"
                                                       type="email"
                                                       value="{{old('email')}}"
                                                       id="EmailInput"
                                                       class="form-control"
                                                       placeholder="Enter your email" required>
                                                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                                                <div class="invalid-feedback">Please enter email</div>
                                            </div>

                                            <div>
                                                <span id="EmailValidationMessage"></span>
                                            </div>


                                            <div class="mb-3">
                                                <label class="form-label" for="password-input">Password</label>
                                                <div class="position-relative auth-pass-inputgroup">
                                                    <input type="password"
                                                           name="password"
                                                           class="form-control pe-5 password-input"
                                                           onpaste="return false"
                                                           placeholder="Enter password"
                                                           id="password-input"
                                                           value="{{old('email')}}"
                                                           aria-describedby="passwordInput"
{{--                                                           pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"--}}
                                                           required>
                                                    <button
                                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none shadow-none text-muted password-addon"
                                                        type="button" id="password-addon"><i
                                                            class="ri-eye-fill align-middle"></i></button>
                                                    <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                                                    <div class="invalid-feedback">
                                                        Please enter password
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <button type="submit" class="btn btn-success w-100">Sign In</button>
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


