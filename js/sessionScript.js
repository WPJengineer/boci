window.getSessionUser = async function () {
  if (window.__sessionUser) return window.__sessionUser;

  try {
    const response = await fetch(
      `http://localhost/boci/backend/endpoints/loggedIn_frontend.php`,
      // "https://remotehost.es/student014/shop/backend/endpoints/loggedIn_frontend.php",
      { credentials: "include" }
    );

    if (!response.ok) {
      window.__sessionUser = { loggedIn: false };
      return window.__sessionUser;
    }

    window.__sessionUser = await response.json();
    return window.__sessionUser;
  } catch {
    window.__sessionUser = { loggedIn: false };
    return window.__sessionUser;
  }
};