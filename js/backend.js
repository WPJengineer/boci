const password = document.querySelector(".password-field input");
const showPassword = document.querySelector(".password-field img");

// showPassword.addEventListener('mousedown', () => {
//   const isPassword = password.type === "password";
//   password.type = isPassword ? "text" : "password";
// });

showPassword.addEventListener("mousedown", () => {
  password.type = "text";
});

// Hide when mouse released
showPassword.addEventListener("mouseup", () => {
  password.type = "password";
});