document.addEventListener('DOMContentLoaded', () => {

  const password = document.querySelector(".password-field input");
  const showPassword = document.querySelector(".btn-show-password");

  // console.log(password, showPassword);

  if (password && showPassword) {
    showPassword.addEventListener("pointerdown", () => {
      password.type = "text";
    });

    showPassword.addEventListener("pointerup", () => {
      password.type = "password";
    });
  }

// missing to check for if valid entry into form

const formLogin = document.querySelector(".login");
const formNewRegister = document.querySelector(".new-register");

if (formLogin) {
  const inputsLogin = formLogin.querySelectorAll("input");
  formLogin.addEventListener("submit", (e) => {
    inputsLogin.forEach((input) => {
      if (input.checkValidity()) {
        input.classList.remove("invalid");
        input.classList.add("valid");
      } else {
        input.classList.remove("valid");
        input.classList.add("invalid");
      }
    });

    // prevent submit if invalid
    if (!formLogin.checkValidity()) {
      e.preventDefault();
    }
  });
}

if (formNewRegister) {
  const inputsRegister = formNewRegister.querySelectorAll("input");
  formNewRegister.addEventListener("submit", (e) => {
    let formIsValid = true;
    inputsRegister.forEach((input) => {
      if (input.name === "newsletter") return;
      if (input.checkValidity()) {
        input.classList.remove("invalid");
        input.classList.add("valid");
      } else {
        input.classList.remove("valid");
        input.classList.add("invalid");
        formIsValid = false;
      }
    });

    // prevent submit if invalid
    if (!formIsValid) {
      e.preventDefault();
    }
  });
}
});