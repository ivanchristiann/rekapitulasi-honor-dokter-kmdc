
<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
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

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('../public/assets/img/favicon/favicon.ico') }}" />

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
    <script src="{{ asset('../assets/js/config.js') }}"></script>
  </head>
<body>
      <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <div class="card">
          <div class="card-body">

            <h4 class="mb-2">Change Password</h4>

            <form id="formAuthentication" class="mb-3" method="GET" action="{{ route('newPassword') }}">
              @csrf
              <div class="mb-3">
              @if (session('error'))
                  <div class="alert alert-danger" role="alert">
                      {{ session('error') }}
                  </div>
              @endif
              </div>
              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="password">Old Password</label>
                </div>
                <div class="input-group input-group-merge">
                  <input
                    type="password"
                    id="oldPassword"
                    class="form-control @error('oldPassword') is-invalid @enderror"
                    name="oldPassword"
                    placeholder="Old Password"
                    aria-describedby="password"
                    required autocomplete="current-password"
                    value="{{ old('oldPassword') }}"
                  />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>

              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="password">New Password</label>
                </div>
                <div class="input-group input-group-merge">
                  <input
                    type="password"
                    id="newPassword"
                    class="form-control @error('newPassword') is-invalid @enderror"
                    name="newPassword"
                    placeholder="New Password"
                    aria-describedby="password"
                    required autocomplete="current-password"
                    value="{{ old('newPassword') }}"
                  />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  @error('newPassword')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
              </div>

              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label" for="password">Konfirmasi New Password</label>
                </div>
                <div class="input-group input-group-merge">
                  <input
                    type="password"
                    id="KonfNewPassword"
                    class="form-control @error('KonfNewPassword') is-invalid @enderror"
                    name="KonfNewPassword"
                    placeholder="Confirm New Password"
                    aria-describedby="password"
                    required autocomplete="current-password"
                    value="{{ old('KonfNewPassword') }}"
                  />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  @error('KonfNewPassword')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
              </div>
              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit" onclick="goBack()">Change</button>
                <br>
                <button class="btn btn-danger d-grid w-100" onclick="goBack()">Cancel</button>
              </div>
            </form>
          </div>
        </div>
    </div>
  </div>
  <!-- / Content -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="{{ asset('../public/assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('../public/assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('../public/assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('../public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

  <script src="{{ asset('../public/assets/vendor/js/menu.js') }}"></script>
  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="{{ asset('../public/assets/js/main.js') }}"></script>

  <!-- Page JS -->

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>

<script>
  function goBack() {
    window.history.back();
  }
</script>
