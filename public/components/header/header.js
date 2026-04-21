export function createHeader(isLoggedIn = false, baseUrl = "", cartCount = 0) {
  const header = document.createElement("header");

  const base = baseUrl ? baseUrl + "/" : "./";
  const homeLink = baseUrl ? baseUrl + "/home" : "home";
  const about = baseUrl + "/home/about";
  const contact = baseUrl + "/home/contact";

  header.innerHTML = `
    <div class="container">
      <div class="branding">
        <a href="${homeLink}" class="logo">
          <img src="${base}img/Logomark.png" alt="Logo Fashion Family" />
          <span>Fashion Family</span>
        </a>

        <nav aria-label="Navigation principale">
          <ul>
            <li><a href="${homeLink}">Accueil</a></li>

            <li class="dropdown">
              <a href="#" aria-expanded="false"> Catégorie </a>

              <ul class="submenu">
                <li><a href="${baseUrl}/products/category?cat=vetements">Vêtements</a></li>
                <li><a href="${baseUrl}/products/category?cat=accessoires">Accessoires</a></li>
                <li><a href="${baseUrl}/products/category?cat=chaussures">Chaussures</a></li>
                <li><a href="${baseUrl}/products/category?cat=sacs">Sacs</a></li>
                <li><a href="${baseUrl}/products/category?cat=bijoux">Bijoux</a></li>
                <li><a href="${baseUrl}/products/category?cat=sous-vetements">Sous-vêtements</a></li>
                <li><a href="${baseUrl}/products/category?cat=sport">Sport</a></li>
                <li><a href="${baseUrl}/products/category?cat=maison">Maison</a></li>
                <li><a href="${baseUrl}/products/category?cat=electronique">Électronique</a></li>
                <li><a href="${baseUrl}/products/category?cat=jeux-video">Jeux vidéo</a></li>
              </ul>
            </li>

            <li><a href="${about}">À propos</a></li>
            <li><a href="${contact}">Contact</a></li>

            ${
              isLoggedIn
                ? `
            <li>
              <a href="${baseUrl}/sell" class="nav-sell-btn">Vendre</a>
            </li>`
                : ""
            }

            <li>
              <form role="search" action="${baseUrl}/search">
                <label for="search-input" class="visually-hidden">Rechercher</label>
                <button type="submit" class="search-icon-btn" aria-label="Lancer la recherche">
                  <img src="${base}img/Search.png" alt="" aria-hidden="true" />
                </button>
                <input id="search-input" type="search" name="q" placeholder="Recherche..."
                       autocomplete="off" />
              </form>
            </li>
          </ul>
        </nav>
      </div>

      <div class="header-actions">
        ${isLoggedIn ? `
        <a href="${baseUrl}/dashboard" aria-label="Mon compte">
          <img src="${base}img/Vector.png" alt="Icône du compte utilisateur" />
        </a>
        ` : `
        <a href="${baseUrl}/login" aria-label="Se connecter">
          <img src="${base}img/Vector.png" alt="Icône de connexion" />
        </a>
        <a href="${baseUrl}/register" aria-label="S'inscrire">
          <img src="${base}img/user-plus-solid.png" alt="Icône d'inscription" />
        </a>
        `}
        <a href="${baseUrl}/cart" aria-label="Panier" style="position: relative; display: inline-flex;">
          <img src="${base}img/Icon.png" alt="Icône du panier" />
          ${cartCount > 0 ? `<span style="position: absolute; top: -4px; right: -4px; background: #e00; color: #fff; border-radius: 50%; font-size: 0.55rem; font-weight: bold; width: 13px; height: 13px; display: flex; align-items: center; justify-content: center; line-height: 1;">${cartCount}</span>` : ""}
        </a>
        ${isLoggedIn ? `
        <a href="${baseUrl}/logout" aria-label="Déconnexion">
          <img src="${base}img/logout.png" alt="Icône de déconnexion" />
        </a>
        ` : ""}
      </div>
      <img src="${base}img/hamburger.png" alt="Menu" class="hamburger" aria-label="Menu de navigation mobile" />
    </div>
  `;
  return header;
}

export function initHeader(headerRoot) {
  const hamburger = headerRoot.querySelector(".hamburger");
  const navMenu = headerRoot.querySelector(".branding nav ul");
  const dropDown = headerRoot.querySelector(".dropdown");
  const subMenu = headerRoot.querySelector(".submenu");
  const dropDownLink = headerRoot.querySelector(".dropdown > a");

  if (!hamburger || !navMenu || !dropDown || !subMenu || !dropDownLink) {
    return;
  }

  hamburger.addEventListener("click", () => {
    navMenu.classList.toggle("active");
  });

  dropDown.addEventListener("click", () => {
    subMenu.classList.toggle("active");
    const isExpanded = subMenu.classList.contains("active");
    dropDownLink.setAttribute("aria-expanded", isExpanded);
  });

  document.addEventListener("click", (event) => {
    if (!navMenu.contains(event.target) && !hamburger.contains(event.target)) {
      navMenu.classList.remove("active");
    }
  });

  document.addEventListener("click", (event) => {
    if (!dropDown.contains(event.target)) {
      subMenu.classList.remove("active");
      dropDownLink.setAttribute("aria-expanded", false);
    }
  });

  const subMenuLinks = headerRoot.querySelectorAll(".submenu a");
  subMenuLinks.forEach((link) => {
    link.addEventListener("click", () => {
      navMenu.classList.remove("active");
    });
  });
}
