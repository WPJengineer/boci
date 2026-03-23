const password = document.querySelector(".password-field input");
const showPassword = document.querySelector(".password-field img");

// showPassword.addEventListener('mousedown', () => {
//   const isPassword = password.type === "password";
//   password.type = isPassword ? "text" : "password";
// });

showPassword.addEventListener("mousedown", () => {
  password.type = "text";
});

showPassword.addEventListener("mouseup", () => {
  password.type = "password";
});

// missing to check for if valid entry into form

const formLogin = document.querySelector(".login");
const inputs = formLogin.querySelectorAll("input");

formLogin.addEventListener("submit", (e) => {
  inputs.forEach((input) => {
    if (input.checkValidity()) {
      input.classList.remove("invalid");
      input.classList.add("valid");
    } else {
      input.classList.remove("valid");
      input.classList.add("invalid");
    }
  });

  // Optional: prevent submit if invalid
  if (!formLogin.checkValidity()) {
    e.preventDefault();
  }
});