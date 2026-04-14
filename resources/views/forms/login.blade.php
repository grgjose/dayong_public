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

    <style>
      /* =====================================================
         Fix left border being clipped by overflow or
         Bootstrap's negative row margin
      ===================================================== */
      .form-section {
        overflow: visible !important;
      }

      /* Ensure the Bootstrap row itself doesn't clip */
      .form-section ~ div,
      .row {
        overflow: visible !important;
      }

      /* =====================================================
         REGISTER FORM — Chosen dropdown styled to match
         the underline-only input aesthetic
      ===================================================== */

      /* Override Chosen to look like the underline inputs */
      .register-wrapper .chosen-container-single .chosen-single {
        background: transparent !important;
        border: none !important;
        border-bottom: 1px solid #e7e7e7 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        padding: 15px 10px !important;
        font-size: 14px !important;
        font-weight: bold !important;
        color: #b0adad !important;
        height: auto !important;
        line-height: 1.2 !important;
        margin-bottom: 7px !important;
      }

      /* When a value is selected, darken the text */
      .register-wrapper .chosen-container-single.chosen-with-drop .chosen-single,
      .register-wrapper .chosen-container-single .chosen-single span {
        color: #333 !important;
      }

      /* Remove the default Chosen arrow background, use a simple caret */
      .register-wrapper .chosen-container-single .chosen-single div {
        border: none !important;
        background: transparent !important;
        width: 24px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
      }

      .register-wrapper .chosen-container-single .chosen-single div b {
        background: none !important;
        border-left: 4px solid transparent !important;
        border-right: 4px solid transparent !important;
        border-top: 5px solid #b0adad !important;
        border-bottom: none !important;
        display: block !important;
        width: 0 !important;
        height: 0 !important;
        margin: auto !important;
        position: relative !important;
        top: 50% !important;
      }

      /* Dropdown panel */
      .register-wrapper .chosen-drop {
        border: 1px solid #e7e7e7 !important;
        border-top: none !important;
        border-radius: 0 !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
      }

      .register-wrapper .chosen-results li {
        font-size: 13px !important;
        font-weight: 400 !important;
        padding: 10px 12px !important;
      }

      .register-wrapper .chosen-results li.highlighted {
        background: #000 !important;
        color: #fff !important;
      }

      /* Search box inside Chosen */
      .register-wrapper .chosen-search input {
        border: none !important;
        border-bottom: 1px solid #e7e7e7 !important;
        border-radius: 0 !important;
        font-size: 13px !important;
        padding: 8px 10px !important;
      }

      /* Make the Chosen container full width */
      .register-wrapper .chosen-container {
        width: 100% !important;
        display: block !important;
      }

      /* Hide the actual <select> but let Chosen take its place */
      .register-wrapper select.form-control.chosen-select {
        display: none !important;
      }

      /* =====================================================
         Birthdate field — floating label style
         so it feels consistent with placeholder inputs
      ===================================================== */
      .register-wrapper .birthdate-group {
        position: relative;
        margin-bottom: 7px;
      }

      .register-wrapper .birthdate-group label {
        position: absolute;
        top: -8px;
        left: 10px;
        font-size: 10px;
        font-weight: bold;
        color: #b0adad;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        pointer-events: none;
        transition: all 0.2s ease;
      }

      .register-wrapper .birthdate-group input[type="date"] {
        border: 0;
        border-bottom: 1px solid #e7e7e7;
        border-radius: 0;
        font-size: 14px;
        font-weight: bold;
        padding: 15px 10px 8px 10px;
        margin-bottom: 0;
        width: 100%;
        background: transparent;
        color: #333;
        /* Hide the browser's default placeholder color for unfilled date */
        color-scheme: light;
      }

      .register-wrapper .birthdate-group input[type="date"]:focus {
        outline: none;
        border-bottom-color: #000;
      }

      /* Style the date input when empty to show muted color */
      .register-wrapper .birthdate-group input[type="date"]:not(:valid) {
        color: #b0adad;
      }

      /* =====================================================
         Name row — two inputs side by side
      ===================================================== */
      .register-wrapper .input-row {
        display: flex;
        gap: 12px;
        align-items: flex-start;
      }

      .register-wrapper .input-row .form-control {
        flex: 1;
        min-width: 0;
      }

      /* flex-fill wrappers used around Chosen selects and
         paired inputs so they each take equal space */
      .register-wrapper .input-row .flex-fill {
        flex: 1 1 0;
        min-width: 0;
      }

      /* =====================================================
         Section divider label
      ===================================================== */
      .register-wrapper .section-label {
        font-size: 10px;
        font-weight: bold;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #b0adad;
        margin: 18px 0 4px 10px;
      }

      /* =====================================================
         Left panel — remove the heavy horizontal padding
         when showing the register form so fields fill
         the available space instead of leaving white gaps
      ===================================================== */
      .form-section:has(.register-wrapper[style*="block"]) {
        padding-left: 48px !important;
        padding-right: 48px !important;
      }

      /* Fallback for browsers without :has() support —
         we add a JS class .showing-register to .form-section */
      .form-section.showing-register {
        padding-left: 48px !important;
        padding-right: 48px !important;
      }

      /* =====================================================
         Scrollable form area so right panel stays visible
      ===================================================== */
      .register-wrapper {
        width: 100%;
        max-width: 100%;
        max-height: calc(100vh - 80px);
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 4px;
        scrollbar-width: thin;
        scrollbar-color: #e0e0e0 transparent;
      }

      .register-wrapper::-webkit-scrollbar {
        width: 4px;
      }

      .register-wrapper::-webkit-scrollbar-thumb {
        background: #e0e0e0;
        border-radius: 2px;
      }

      /* Password match indicators */
      .register-wrapper .pass-match {
        font-size: 11px;
        font-weight: bold;
        margin-top: -4px;
        margin-bottom: 6px;
        padding-left: 10px;
      }

      .register-wrapper .pass-match.ok  { color: #28a745; }
      .register-wrapper .pass-match.bad { color: #dc3545; }

      /* Warning alert strip */
      .register-wrapper .field-warning {
        font-size: 11px;
        padding: 6px 10px;
        border-radius: 0;
        margin-bottom: 4px;
        border-left: 3px solid #dc3545;
        background: #fff5f5;
        color: #dc3545;
      }
    </style>
  </head>

  <body class="container-fluid">
    <div class="row">

      <!-- ================================================
           LEFT: FORM PANEL
      ================================================ -->
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

        <!-- ── LOGIN FORM ── -->
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

        <!-- ── FORGOT PASSWORD FORM ── -->
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

        <!-- ── REGISTER FORM ── -->
        <div class="auth-wrapper register-wrapper" style="display: none;">
          <h2 class="login-title">Register</h2>

          <!-- Warning alerts -->
          <div id="password-warning" class="field-warning" style="display:none;"></div>

          <form action="/register" method="post" class="w-100" autocomplete="off">
            @csrf

            {{-- ── Full Name ── --}}
            <p class="section-label">Full Name</p>

            <div class="input-row">
              <input type="text" name="fname" id="fname"
                     class="form-control" placeholder="First Name"
                     autocomplete="off" list="autocompleteOff" required>
              <input type="text" name="mname" id="mname"
                     class="form-control" placeholder="Middle Name"
                     autocomplete="off" list="autocompleteOff" required>
            </div>

            <input type="text" name="lname" id="lname"
                   class="form-control" placeholder="Last Name"
                   autocomplete="off" list="autocompleteOff" required>

            {{-- ── Account Details ── --}}
            <p class="section-label">Account Details</p>

            <div class="input-row">
              <div class="flex-fill min-w-0">
                <select name="usertype" id="usertype"
                        class="form-control chosen-select"
                        data-placeholder="Select Usertype" required>
                  <option value="" disabled selected></option>
                  <option value="2">ENCODER</option>
                  <option value="3">COLLECTOR</option>
                </select>
              </div>
              <div class="flex-fill min-w-0">
                <select name="branch" id="branch"
                        class="form-control chosen-select"
                        data-placeholder="Select Branch" required>
                  <option value="" disabled selected></option>
                  @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->branch }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            {{-- ── Contact Info ── --}}
            <p class="section-label">Contact Info</p>

            <div class="input-row">
              <div class="flex-fill min-w-0">
                <input type="text" name="email" id="email_reg"
                       class="form-control" placeholder="Email"
                       autocomplete="off" list="autocompleteOff" required>
                <div id="email-warning" class="field-warning" style="display:none;"></div>
              </div>
              <div class="flex-fill min-w-0">
                <input type="number" name="contact_num" id="contact_num_register"
                       class="form-control" placeholder="Contact #"
                       autocomplete="off" list="autocompleteOff" required>
                <div id="contact-num-warning" class="field-warning" style="display:none;"></div>
              </div>
            </div>

            {{-- ── Personal Info ── --}}
            <p class="section-label">Personal Info</p>

            <div class="birthdate-group">
              <label for="birthdate_register">Birthdate</label>
              <input type="date" name="birthdate" id="birthdate_register"
                     max="2010-12-31" required>
            </div>

            {{-- ── Security ── --}}
            <p class="section-label">Security</p>

            <input type="password" name="password" id="password_reg"
                   class="form-control" placeholder="Password"
                   autocomplete="off" required>

            <input type="password" name="confirm_password" id="confirm_password_reg"
                   class="form-control" placeholder="Confirm Password"
                   autocomplete="off" required>

            <div class="pass-match" id="pass-match-msg" style="display:none;"></div>

            <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
              <input name="register" id="register"
                     class="btn login-btn" type="submit" value="Register" disabled>
              <a href="#!" class="forgot-password-link">Forgot Password?</a>
            </div>
          </form>

          <p class="login-wrapper-footer-text">
            Already have an account?
            <a href="#!" class="text-reset text-decoration-underline back-to-login">Login here</a>
          </p>
        </div>

      </div>
      {{-- end left panel --}}

      <!-- ================================================
           RIGHT: HERO PANEL
      ================================================ -->
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
  </body>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
          crossorigin="anonymous"></script>

  <!-- jQuery -->
  <script src="{{ asset('admin_lte/plugins/jquery/jquery.min.js'); }}"></script>

  <!-- Toast -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

  <!-- Chosen JS -->
  <script src="{{ asset('admin_lte/chosen/chosen.jquery.js'); }}"></script>
  <script src="{{ asset('admin_lte/chosen/chosen.jquery.min.js'); }}"></script>

  <script>
  // ── Init Chosen when register form becomes visible ──────────────────────────
  function initRegisterChosen() {
    $('.register-wrapper .chosen-select').chosen({
      disable_search_threshold: 5,
      width: '100%',
      placeholder_text_single: ' '
    });
  }

  // ── Panel toggling ───────────────────────────────────────────────────────────
  document.addEventListener("DOMContentLoaded", function () {

    const loginWrapper    = document.querySelector(".login-wrapper");
    const forgotWrapper   = document.querySelector(".forgot-password-wrapper");
    const registerWrapper = document.querySelector(".register-wrapper");

    function showOnly(wrapper) {
      loginWrapper.style.display    = "none";
      forgotWrapper.style.display   = "none";
      registerWrapper.style.display = "none";
      wrapper.style.display = "block";

      // Toggle padding class on the left panel
      const formSection = document.querySelector(".form-section");
      if (wrapper === registerWrapper) {
        formSection.classList.add("showing-register");
        setTimeout(initRegisterChosen, 50);
      } else {
        formSection.classList.remove("showing-register");
      }
    }

    // Forgot password
    document.querySelectorAll(".forgot-password-link").forEach(link => {
      link.addEventListener("click", e => { e.preventDefault(); showOnly(forgotWrapper); });
    });

    // Register
    document.querySelectorAll(".login-wrapper-footer-text a").forEach(link => {
      link.addEventListener("click", e => { e.preventDefault(); showOnly(registerWrapper); });
    });

    // Back to login
    document.querySelectorAll(".back-to-login").forEach(link => {
      link.addEventListener("click", e => { e.preventDefault(); showOnly(loginWrapper); });
    });

    // ── Email duplicate check ──────────────────────────────────────────────────
    let checkTimeout = null;

    $('#email_reg').on('blur', function () {
      const email = $(this).val().trim();
      if (!email) { $('#email-warning').hide(); return; }

      clearTimeout(checkTimeout);
      checkTimeout = setTimeout(() => {
        $.ajax({
          url: "{{ route('users.checkEmail') }}",
          type: "POST",
          data: { _token: "{{ csrf_token() }}", email },
          success: function (res) {
            if (res.exists) {
              $('#email-warning').show().text(res.message);
              $('#email_reg').addClass('is-invalid');
              $('#register').prop('disabled', true);
            } else {
              $('#email-warning').hide();
              $('#email_reg').removeClass('is-invalid');
              $('#register').prop('disabled', false);
            }
          }
        });
      }, 300);
    });

    // ── Contact number duplicate check ────────────────────────────────────────
    $('#contact_num_register').on('blur', function () {
      const contactNum = $(this).val().trim();
      if (!contactNum) { $('#contact-num-warning').hide(); return; }

      clearTimeout(checkTimeout);
      checkTimeout = setTimeout(() => {
        $.ajax({
          url: "{{ route('users.checkContactNum') }}",
          type: "POST",
          data: { _token: "{{ csrf_token() }}", contact_num: contactNum },
          success: function (res) {
            if (res.exists) {
              $('#contact-num-warning').show().text(res.message);
              $('#contact_num_register').addClass('is-invalid');
              $('#register').prop('disabled', true);
            } else {
              $('#contact-num-warning').hide();
              $('#contact_num_register').removeClass('is-invalid');
              $('#register').prop('disabled', false);
            }
          }
        });
      }, 300);
    });

    // ── Password match check ──────────────────────────────────────────────────
    const $msg = $('#pass-match-msg');

    function checkPasswords() {
      const pw  = $('#password_reg').val();
      const cpw = $('#confirm_password_reg').val();

      if (!pw && !cpw) { $msg.hide(); return; }

      if (pw && cpw) {
        if (pw === cpw) {
          $msg.show()
              .text('Passwords match')
              .removeClass('bad').addClass('ok');
          $('#register').prop('disabled', false);
          $('#password-warning').hide();
        } else {
          $msg.show()
              .text('Passwords do not match')
              .removeClass('ok').addClass('bad');
          $('#register').prop('disabled', true);
        }
      } else {
        $msg.hide();
      }
    }

    $('#password_reg, #confirm_password_reg').on('input blur', checkPasswords);

  }); // end DOMContentLoaded
  </script>

  <!-- Custom JS (toast, etc.) -->
  @include('plus.scripts')

</html>