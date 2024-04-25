
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
    <link rel="icon" type="image/x-icon" href="{{ asset('../assets/img/favicon/favicon.ico')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('../assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('../assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('../assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('../assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('../assets/vendor/css/pages/page-auth.css') }}" />
    <!-- Helpers -->
    <script src="{{ asset('../assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('../assets/js/config.js') }}"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </head>

  <body>

    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="text-center">
                <div class="modal-header">
                    
                        @if(session('status') === 'proses')
                        <h5 class="modal-title" id="exampleModalLabel">Menunggu Konfirmasi Akun</h5>
                        @elseif(session('status') === 'failed')
                        <h5 class="modal-title" id="exampleModalLabel">Pendaftaran Akun Ditolak</h5>
                        @elseif(session('status') === 'verif')
                        <h5 class="modal-title" id="exampleModalLabel">Sukses</h5>
                        @endif
                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                </div>
                <div class="modal-body text-center">
                    <span class="icon-container">
                        @if(session('status') === 'proses')
                        <i class='bx bxs-time' style="font-size: 10rem;"></i>
                        @elseif(session('status') === 'failed')
                        <i class='bx bx-x' style="font-size: 10rem; color:red;"></i>
                        @elseif(session('status') === 'verif')
                        <i class='bx bx-check' style="font-size: 20rem; color:green;"  id="check-icon"></i>
                        @endif
                    </span>
                    @if(session('status') === 'proses')
                    <p class="mt-2" style="font-size: 1.3rem; color:black;">Menunggu Verifikasi Akun, Hubungi Admin untuk Segera Melakukan Verifikasi.</p>
                    @elseif(session('status') === 'failed')
                    <p class="mt-2" style="font-size: 1.3rem; color:black;">Pendaftaran Akun Anda Ditolak, Pastikan Data yang Diinput Sudah Benar, Silahkan Daftar Lagi.</p>
                    @elseif(session('status') === 'verif')
                    <p class="mt-2" style="font-size: 1.3rem; color:black;">Selamat datang! Anda berhasil masuk.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Content -->
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <img src="{{ asset('assets/img/logo-rsia.png') }}" alt="Logo Rumah Sakit Ibu dan Anak Kendangsari Merr" style="width: 100%; height:100%;">
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">Welcome👋</h4>
              <span class="mb-4">Please sign-in to your account</span>
              <br>

              <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                  <label for="email" class="form-label">Email Address</label>
                  <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    value="{{ old('email') }}" required autocomplete="email"
                    autofocus
                  />
                  @error('email')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror

                </div>
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
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Log In</button>
              </form>
                  <br>
                  <a href="{{ route('user.create') }}" class="btn btn-secondary d-grid w-100">Sign Up</a>
                </div>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>
    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('../assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{ asset('../assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{ asset('../assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{ asset('../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{ asset('../assets/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('../assets/js/main.js')}}"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script>
        $(document).ready(function() {
            var status = '{{ session("status") }}';

            if (status === 'proses' || status === 'failed') {
                $('#myModal').modal('show');
            }
        });
    </script>
  </body>
</html>
