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
    <!-- Chosen (For Select Multiple UI) -->
    <link rel="stylesheet" href="{{ asset('admin_lte/chosen/chosen.css'); }}">
    <link rel="stylesheet" href="{{ asset('admin_lte/chosen/chosen.min.css'); }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
  </head>

  <body class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-5 form-section">

          @if ($errors->any())
              <div class="alert alert-danger">
                  <ul class="mb-0">
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
          
          <!-- Login Form -->
          <div class="auth-wrapper login-wrapper">
            <h2 class="login-title">Sign in</h2>
            <form action="/login" method="post" class="w-100" style="max-width: 23rem;">
              @csrf
              <div class="mb-3">
                <label for="username" class="visually-hidden">Username / Email</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Username / Email">
              </div>
              <div class="mb-3">
                <label for="password" class="visually-hidden">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
              </div>
              <div class="form-floating mb-4">
                <div class="icheck-primary">
                  <input type="checkbox" name="remember_me" id="remember">
                  <label for="remember">
                    &nbsp; &nbsp; Remember Me
                  </label>
                </div>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-5">
                <input name="login" id="login" class="btn login-btn" type="submit" value="Login">
                <a href="#!" class="forgot-password-link">Forgot Password?</a>
              </div>
            </form>           
            <p class="login-wrapper-footer-text">Need an account? <a href="#!" class="text-reset text-decoration-underline">Request Account here</a></p>
          </div>

          <!-- Forgot Password Form -->
          <div class="auth-wrapper forgot-password-wrapper" style="display: none;">
            <h2 class="login-title">Forgot Password</h2>
            <form action="/forgot-password" method="post" class="w-100" style="max-width: 23rem;">
              @csrf
              <div class="mb-3">
                <label for="email" class="visually-hidden">Email</label>
                <input type="email" name="email" id="email_forgot" class="form-control" placeholder="Email">
              </div>
              <div class="d-flex justify-content-between align-items-center mb-5">
                <input name="submit" id="submit" class="btn btn-secondary login-btn" type="submit" value="Send Link">
                <a href="#!" class="back-to-login forgot-password-link">Back to Login</a>
              </div>
            </form>           
            <p class="login-wrapper-footer-text">Need an account? <a href="#!" class="text-reset text-decoration-underline">Request Account here</a></p>
          </div>

          <!-- Register Form -->
          <div class="auth-wrapper register-wrapper" style="display: none;">
            <h2 class="login-title">Register</h2>
            <!-- WARNING MESSAGES (hidden by default) -->
            <div id="email-warning" class="alert alert-danger p-2 mb-2" style="display:none;"></div>
            <div id="contact-num-warning" class="alert alert-danger p-2 mb-2" style="display:none;"></div>
            <div id="password-warning" class="alert alert-danger p-2 mb-2" style="display:none;"></div>
            <form action="/register" method="post" class="w-100" style="max-width: 23rem;">
              @csrf
              <div class="mb-3">
                <label for="fname" class="visually-hidden">First Name</label>
                <input type="text" name="fname" id="fname" class="form-control" placeholder="First Name" required>
              </div>
              <div class="mb-3">
                <label for="mname" class="visually-hidden">Middle Name</label>
                <input type="text" name="mname" id="mname" class="form-control" placeholder="Middle Name" required>
              </div>
              <div class="mb-3">
                <label for="lname" class="visually-hidden">Last Name</label>
                <input type="text" name="lname" id="lname" class="form-control" placeholder="Last Name" required>
              </div>
              <div class="mb-3">
                <label for="usertype" class="visually-hidden">Usertype</label>
                <select name="usertype" id="usertype" class="form-control chosen-select" data-placeholder="SELECT USERTYPE" required>
                  <option value="" disabled selected>SELECT USERTYPE</option>
                  <option value="2">ENCODER</option>
                  <option value="3">COLLECTOR</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="branch" class="visually-hidden">Branch</label>
                <select name="branch" id="branch" class="form-control chosen-select" data-placeholder="SELECT BRANCH" required>
                  <option value="" disabled selected>SELECT BRANCH</option>
                  @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->branch }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="email" class="visually-hidden">Email</label>
                <input type="text" name="email" id="email_reg" class="form-control" placeholder="Email" required>
              </div>
              <div class="mb-3">
                <label for="contact" class="visually-hidden">Contact #</label>
                <input type="number" name="contact_num" id="contact_num_register" class="form-control" placeholder="Contact #" required>
              </div>
              <div class="mb-3">
                <label for="birthdate_register" class="form-label">Birthdate</label>
                <input type="date" name="birthdate" id="birthdate_register" class="form-control" max="2010-12-31" required>
              </div>
              <div class="mb-3">
                <label for="password" class="visually-hidden">Password</label>
                <input type="password" name="password" id="password_reg" class="form-control" placeholder="Password" required>
              </div>
              <div class="mb-3">
                <label for="confirm_password" class="visually-hidden">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password_reg" class="form-control" placeholder="Confirm Password" required>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-5">
                <input name="register" id="register" class="btn login-btn" type="submit" value="Register">
                <a href="#!" class="forgot-password-link">Forgot Password?</a>
              </div>
            </form>           
            <p class="login-wrapper-footer-text">Already have an account? <a href="#!" class="text-reset text-decoration-underline back-to-login">Login here</a></p>
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