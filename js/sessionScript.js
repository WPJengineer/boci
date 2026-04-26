window.getSessionUser = async function () {
  if (window.__sessionUser) return window.__sessionUser;

  try {
    const response = await fetch(
      // `http://localhost/boci/backend/endpoints/loggedIn_frontend.php`,
      `https://remotehost.es/student014/boci/backend/endpoints/loggedIn_frontend.php`,
      { credentials: "include" }
    );

    if (!response.ok) {
      window.__sessionUser = { loggedIn: false };
      return window.__sessionUser;
    }

    const data = await response.json();
    window.__sessionUser = data;

    const btnLogOut = document.querySelector(".btnLogOut");
    const btnShoppingCart = document.querySelector(".btnShoppingCart");

    if (btnLogOut && btnShoppingCart) {
      if (data.loggedIn) {
        // show logout button
        btnLogOut.style.display = "block";

        // move cart up
        btnShoppingCart.classList.add("with-logout");
      } else {
        // hide logout button
        btnLogOut.style.display = "none";

        // move cart down
        btnShoppingCart.classList.remove("with-logout");
      }
    }

    return window.__sessionUser;

  } catch (error) {
    console.error("Session error:", error);
    window.__sessionUser = { loggedIn: false };
    return window.__sessionUser;
  }
};