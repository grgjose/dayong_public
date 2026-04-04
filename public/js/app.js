  document.addEventListener("DOMContentLoaded", function () {

    const loginWrapper = document.querySelector(".login-wrapper");
    const forgotWrapper = document.querySelector(".forgot-password-wrapper");
    const registerWrapper = document.querySelector(".register-wrapper");

    function showOnly(wrapper) {
      loginWrapper.style.display = "none";
      forgotWrapper.style.display = "none";
      registerWrapper.style.display = "none";
      wrapper.style.display = "block";
    }

    // Forgot password links
    document.querySelectorAll(".forgot-password-link").forEach(link => {
      link.addEventListener("click", function (e) {
        e.preventDefault();
        showOnly(forgotWrapper);
      });
    });

    // Register links
    document.querySelectorAll(".login-wrapper-footer-text a").forEach(link => {
      link.addEventListener("click", function (e) {
        e.preventDefault();
        showOnly(registerWrapper);
      });
    });

  });