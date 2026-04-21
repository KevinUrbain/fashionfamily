export function createFooter(baseUrl = "") {
  const footer = document.createElement("footer");
  footer.classList.add("footer");

  const base = baseUrl ? baseUrl + "/" : "./";

  footer.innerHTML = `
  <div class="footer_container">

    <div class="footer_left">
      <div class="footer_logo">
        <img src="${base}img/logo_footer.png" alt="Logo Fashion Family">
        <h3>Fashion Family</h3>
      </div>

      <p>Fashion Family : Achetez et vendez des articles partout dans le monde</p>

      <div class="footer_icon">
        <a href="https://github.com/G-Guenael/FashionFamily" aria-label="GitHub"><img src="${base}img/Github.png" alt=""></a>
        <a href="#" aria-label="Instagram"><img src="${base}img/Insta.png" alt=""></a>
        <a href="#" aria-label="YouTube"><img src="${base}img/Youtube.png" alt=""></a>
      </div>
    </div>

    <div class="footer_middle">

      <nav aria-label="Support">
        <h3>Support</h3>
        <ul>
          <li><a href="${baseUrl}/home/faq">FAQ</a></li>
          <li><a href="${baseUrl}/home/terms">Conditions d'utilisation</a></li>
          <li><a href="${baseUrl}/home/privacy">Politique de confidentialité</a></li>
        </ul>
      </nav>

      <nav aria-label="Company">
        <h3>Compagny</h3>
        <ul>
          <li><a href="${baseUrl}/home/about">À propos</a></li>
          <li><a href="${baseUrl}/home/contact">Contact</a></li>
          <li><a href="${baseUrl}/home/careers">Carrières</a></li>
        </ul>
      </nav>

      <nav aria-label="Shop">
        <h3>Shop</h3>
        <ul>
          <li><a href="${baseUrl}/dashboard">Mon compte</a></li>
          <li><a href="${baseUrl}/cart">Panier</a></li>
          <li><a href="${baseUrl}/sell">Vendre</a></li>
        </ul>
      </nav>
  
    </div>
   <div class="footer_right">
      <h3>Accepted Payments</h3>
      <div class="footer_container_payment">
        <img src="${base}img/MasterCard.png" alt="Mastercard">
        <img src="${base}img/AMEX.png" alt="American Express">
        <img src="${base}img/Visa.png" alt="Visa">
      </div>
    </div>
    
    </div>
      <div class="copyright">
    <p>&copy 2026 All rights reserved</p>
  </div>
    `;

  return footer;
}
