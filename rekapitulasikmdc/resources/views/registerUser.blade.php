<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Login Pages</title>
    <style>
      /* Animasi putaran satu kali pada ikon check saat load pertama */
      @keyframes spin-once {
          0% { transform: rotate(180deg); }
          100% { transform: rotate(360deg); }
      }

      /* Terapkan animasi pada ikon check */
      #check-icon {
          animation: spin-once 0.5s linear; /* Ubah sesuai dengan kecepatan atau durasi putaran yang Anda inginkan */
          animation-fill-mode: forwards; /* Tetapkan ikon pada posisi terakhir setelah animasi selesai */
      }


    </style>

    <meta name="description" content="" />
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('../public/assets/img/favicon/favicon.ico')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('../public/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('../public/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('../public/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('../public/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('../public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('../public/assets/vendor/css/pages/page-auth.css') }}" />
    <!-- Helpers -->
    <script src="{{ asset('../public/assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('../public/assets/js/config.js') }}"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="font-size: 20px; font-weight: bold;">{{ __('Registrasi') }}</div>
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="card-body">
                        <form method="POST" action="{{route('user.store')}}">
                            @csrf
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" id="nama" required value="{{ old('nama') }}">
    
                                <label>Singkatan</label>
                                <input type="text" name="namaSingkatan" class="form-control" id="namaSingkatan" required value="{{ old('namaSingkatan') }}">
                        
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" id="email" required value="{{ old('email') }}">
                        
                                <label>Nomor Telepon</label>
                                <input type="number" name="nomorTelepon" class="form-control" id="nomorTelepon" required value="{{ old('nomorTelepon') }}">
                        
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" id="username" required value="{{ old('username') }}">
                        
                                <label>Daftar Sebagai</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role" id="admin" value="admin" checked {{ old('role') === 'admin' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="admin">Admin</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role" id="dokter" value="dokter" {{ old('role') === 'dokter' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dokter">Dokter</label>
                                </div>
                                
    
                                {{-- <label>Password</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror --}}

                                <div class="mb-3 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                      <label class="form-label" for="password">Password</label>
                                      {{-- <a href="{{ url('forgot') }}">
                                        <small>Forgot Password?</small>
                                      </a> --}}
                                    </div>
                                    <div class="input-group input-group-merge">
                                      <input
                                        type="password"
                                        id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password"
                                        required autocomplete="current-password"
                                      />
                                      <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                      @error('password')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                      @enderror
                                    </div>
                                  </div>
    
                                <label>Confirm Password</label>
                                <input type="password" name="confirmPassword" class="form-control" id="confirmPassword" required>
                                @error('confirmPassword')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <a href="/login"class="btn btn-primary" style="margin-top: 20px;">Back</a>
                            <button type="submit" class="btn btn-success" style="margin-top: 20px;">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
