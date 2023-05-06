@extends('_layout')


@section('content')
    <section class="vh-100 bg-image"
        style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                @if (Session::has('message'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ Session::get('message') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                                <h2 class="text-uppercase text-center mb-5">Create an account</h2>

                                <form method="post" action="{{ route('register') }}">
                                    @csrf
                                    <div class="form-outline mb-4">
                                        <input type="text" id="name" name="name"
                                            class="form-control form-control-lg" />
                                        <label class="form-label" for="name">Your Name</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="email" id="email" name="email"
                                            class="form-control form-control-lg" />
                                        <label class="form-label" for="email">Your Email</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="password" id="password" name="password"
                                            class="form-control form-control-lg" />
                                        <label class="form-label" for="password">Password</label>
                                    </div>

                                    {{-- <div class="form-outline mb-4">
                                        <input type="password" id="repeatPassword" name="password_confirmation" class="form-control form-control-lg" />
                                        <label class="form-label" for="repeatPassword">Repeat your password</label>
                                    </div> --}}



                                    <div class="d-flex justify-content-center">
                                        <input type="submit"
                                            class="btn btn-success btn-block btn-lg gradient-custom-4 text-body"
                                            value="Register" />
                                    </div>

                                    <p class="text-center text-muted mt-5 mb-0">Have already an account? <a href="#!"
                                            class="fw-bold text-body"><u>Login here</u></a></p>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
