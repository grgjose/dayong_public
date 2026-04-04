<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Dayong Providers Inc | Login Page</title>

    <!-- Site Icon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/logo.ico') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Toast -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Karla:wght@200;300;400;500&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
  </head>

  <body class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-5 form-section">

          <!-- Reset Password Form -->
          <div class="auth-wrapper login-wrapper">
            <h2 class="login-title">Reset Password</h2>
            <form action="/change-password" method="post" class="w-100" style="max-width: 23rem;">
              @csrf
              <input type="hidden" name="user_id" value="{{ $user->id }}">
              <div class="mb-3">
                <label for="password" class="visually-hidden">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="New Password">
              </div>
              <div class="mb-3">
                <label for="confirm_password" class="visually-hidden">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm New Password">
              </div>

              <div class="d-flex justify-content-between align-items-center mb-5">
                <input name="resetBtn" id="resetBtn" class="btn login-btn" type="submit" value="Reset">
              </div>
            </form>           
          </div>

        </div>
        <div class="col-12 col-md-7 intro-section d-none d-md-flex">
          <div class="brand-wrapper">
            <h1>
              <a href="/?=1">
                <img src="{{ asset('img/logo.png') }}" 
                    alt="Logo" 
                    class="img-fluid"
                    style="max-width: 8rem;">
              </a>
            </h1>
          </div>
          <div class="intro-content-wrapper">
            <h1 class="intro-title">Welcome to <br> Dayong App!</h1>
            <p class="intro-text">to establish a bond among members, extend help in times of calamities, disaster and in case of death</p>
            <a href="#!" class="btn btn-read-more">Read more</a>
          </div>
          <div class="intro-section-footer">
            <nav class="footer-nav">
              <a href="https://www.facebook.com/dsrdprovidersinc">Facebook</a>
              <a href="#!">Twitter</a>
              <a href="#!">Gmail</a>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </body>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <!-- jQuery -->
  <script src="{{ asset('admin_lte/plugins/jquery/jquery.min.js'); }}"></script>

  <!-- Toast -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

  <!-- Custom JS -->
  @include('plus.scripts')

</html>