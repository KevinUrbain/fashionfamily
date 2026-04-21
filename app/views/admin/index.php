<div class="layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <img src="<?= BASE_URL ?>/img/logo_footer.png" alt="Logo" />
            <h1>Admin</h1>
        </div>
        <div class="sidebar_nav">
            <nav>
                <ul>
                    <li>
                        <img src="<?= BASE_URL ?>/img/img-dash/icone_dash.png" alt="" />
                        <a href="#" data-page="dashboard">Dashboard</a>
                    </li>
                    <li>
                        <img src="<?= BASE_URL ?>/img/img-dash/icone_produits.png" alt="" />
                        <a href="#" data-page="products">Products</a>
                    </li>
                    <li>
                        <img src="<?= BASE_URL ?>/img/img-dash/icone_orders.png" alt="" />
                        <a href="#" data-page="orders">Orders</a>
                    </li>
                    <li>
                        <img src="<?= BASE_URL ?>/img/img-dash/icone_customers.png" alt="" />
                        <a href="#" data-page="customers">Customers</a>
                    </li>
                    <li>
                        <img src="<?= BASE_URL ?>/img/img-dash/icone_reviews.png" alt="" />
                        <a href="#" data-page="reviews">Reviews</a>
                    </li>
                    <li>
                        <img src="<?= BASE_URL ?>/img/img-dash/icone_settings.png" alt="" />
                        <a href="#" data-page="settings">Settings</a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="main">
        <header class="header">
            <h1>Admin ></h1>
            <div class="header-actions-admin">
                <a href="<?= BASE_URL ?>/home" class="btn-back-site">Retour au site</a>
                <a href="<?= BASE_URL ?>/logout">
                    <img src="<?= BASE_URL ?>/img/img-dash/logout.png" alt="Déconnexion" />
                </a>
            </div>
        </header>
        <div class="content" id="content"></div>
    </div>
</div>
