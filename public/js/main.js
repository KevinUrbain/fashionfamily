// v1.2.0
import { createHeader, initHeader } from "../components/header/header.js?v=4";

import { createFooter } from "../components/footer/footer.js?v=2";

window.addEventListener("DOMContentLoaded", () => {
  // HEADER
  const headerPlaceholder = document.getElementById("header");
  const isLoggedIn = headerPlaceholder?.dataset.loggedIn === "1";
  const baseUrl = headerPlaceholder?.dataset.baseUrl ?? "";
  const cartCount = parseInt(headerPlaceholder?.dataset.cartCount ?? "0", 10);
  const header = createHeader(isLoggedIn, baseUrl, cartCount);

  if (headerPlaceholder) {
    headerPlaceholder.replaceWith(header);
  } else {
    document.body.prepend(header);
  }

  initHeader(header);

  // FOOTER
  const footerPlaceholder = document.getElementById("footer");
  if (footerPlaceholder) {
    footerPlaceholder.replaceWith(createFooter(baseUrl));
  }
});
