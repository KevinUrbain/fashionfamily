const content = document.getElementById("content");
const baseUrl = document.getElementById("header")?.dataset.baseUrl ?? "";

async function initCharts() {
  const response = await fetch(`${baseUrl}/admin/stats`);
  if (!response.ok) return;

  const { articles, users } = await response.json();

  const formatMonth = (raw) => {
    if (!raw) return "";
    const [year, month] = raw.split("-");
    return new Date(year, month - 1).toLocaleDateString("fr-FR", { month: "short", year: "2-digit" });
  };

  const miniBarOptions = (months) => ({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: true,
        position: "top",
        labels: {
          font: { size: 11, family: "Manrope, sans-serif" },
          color: "#474b57",
          boxWidth: 12,
          padding: 6,
        },
      },
      tooltip: {
        enabled: true,
        callbacks: {
          title: (items) => `Mois : ${formatMonth(months[items[0].dataIndex])}`,
          label: (item) => ` ${item.formattedValue} ajouté(s)`,
        },
        backgroundColor: "#0e1422",
        titleColor: "#fff",
        bodyColor: "#ccc",
        padding: 10,
        cornerRadius: 6,
        displayColors: false,
      },
    },
    scales: {
      x: {
        display: true,
        ticks: {
          font: { size: 10, family: "Manrope, sans-serif" },
          color: "#5c5f6a",
          maxRotation: 0,
          callback: (_, i) => formatMonth(months[i]),
        },
        grid: { display: false },
      },
      y: { display: false, beginAtZero: true },
    },
    animation: { duration: 600 },
  });

  new Chart(document.getElementById("chart-articles"), {
    type: "bar",
    data: {
      labels: articles.map((r) => r.month),
      datasets: [
        {
          label: "Articles ajoutés",
          data: articles.map((r) => Number(r.count)),
          backgroundColor: "#0e1422",
          borderRadius: 3,
          borderSkipped: false,
        },
      ],
    },
    options: miniBarOptions(articles.map((r) => r.month)),
  });

  new Chart(document.getElementById("chart-users"), {
    type: "bar",
    data: {
      labels: users.map((r) => r.month),
      datasets: [
        {
          label: "Utilisateurs inscrits",
          data: users.map((r) => Number(r.count)),
          backgroundColor: "#c0392b",
          borderRadius: 3,
          borderSkipped: false,
        },
      ],
    },
    options: miniBarOptions(users.map((r) => r.month)),
  });

  // Barre de progression commandes
  const ordersEl = document.getElementById("orders");
  const currentOrders = ordersEl ? parseInt(ordersEl.textContent, 10) : 0;
  const goal = 1000;
  const pct = Math.min((currentOrders / goal) * 100, 100);
  const bar = document.getElementById("orders-bar");
  const leftEl = document.getElementById("orders-left");
  if (bar) bar.style.width = pct + "%";
  if (leftEl) leftEl.textContent = Math.max(goal - currentOrders, 0);

  // Donut chart — répartition articles vs utilisateurs
  const topCanvas = document.getElementById("chart-top-articles");
  if (topCanvas) {
    const totalArticles = articles.reduce((s, r) => s + Number(r.count), 0);
    const totalUsers = users.reduce((s, r) => s + Number(r.count), 0);
    new Chart(topCanvas, {
      type: "doughnut",
      data: {
        labels: ["Articles (6 mois)", "Utilisateurs (6 mois)"],
        datasets: [
          {
            data: [totalArticles, totalUsers],
            backgroundColor: ["#0e1422", "#c0392b"],
            borderWidth: 0,
          },
        ],
      },
      options: {
        responsive: false,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: true, position: "bottom", labels: { font: { size: 10 } } },
        },
        cutout: "65%",
      },
    });
  }
}

async function loadPage(page) {
  if (!content) return;
  try {
    content.innerHTML = "<p>Chargement...</p>";

    const response = await fetch(`${baseUrl}/admin/${page}`);
    if (!response.ok) throw new Error(response.status);

    content.innerHTML = await response.text();
    history.pushState({ page }, "", `${baseUrl}/admin#${page}`);

    if (page === "dashboard") initCharts();

    // Re-bind les liens data-page chargés dynamiquement dans le nouveau contenu
    bindPageLinks(content);
    bindSearchForm(page);
  } catch (error) {
    content.innerHTML = "<p>Erreur de chargement.</p>";
  }
}

function bindSearchForm(page) {
  const input = content?.querySelector("#search-input");
  const btn   = content?.querySelector("#search-btn");
  const reset = content?.querySelector("#search-reset-btn");

  if (!input || !btn) return;

  const endpoint = `${baseUrl}/admin/${page}`;

  async function doSearch() {
    const q = input.value.trim();
    const url = q ? `${endpoint}?q=${encodeURIComponent(q)}` : endpoint;
    content.innerHTML = "<p>Chargement...</p>";
    try {
      const response = await fetch(url);
      if (!response.ok) throw new Error(response.status);
      content.innerHTML = await response.text();
      bindPageLinks(content);
      bindSearchForm(page);
      content.querySelector("#search-input")?.focus();
    } catch {
      content.innerHTML = "<p>Erreur de chargement.</p>";
    }
  }

  btn.addEventListener("click", doSearch);

  input.addEventListener("keydown", (e) => {
    if (e.key === "Enter") doSearch();
  });

  reset?.addEventListener("click", async () => {
    content.innerHTML = "<p>Chargement...</p>";
    try {
      const response = await fetch(endpoint);
      if (!response.ok) throw new Error(response.status);
      content.innerHTML = await response.text();
      bindPageLinks(content);
      bindSearchForm(page);
    } catch {
      content.innerHTML = "<p>Erreur de chargement.</p>";
    }
  });
}

function bindPageLinks(root) {
  root.querySelectorAll("[data-page]").forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      loadPage(link.dataset.page);
    });
  });
}

// Liens de la sidebar (présents dès le chargement initial)
document.querySelectorAll(".sidebar a[data-page]").forEach((link) => {
  link.addEventListener("click", (e) => {
    e.preventDefault();
    if (content) {
      loadPage(link.dataset.page);
    } else {
      window.location.href = baseUrl + "/admin";
    }
  });
});

if (content) {
  const validPages = ["dashboard", "products", "customers", "orders", "reviews", "settings"];
  const hash = window.location.hash.replace("#", "");
  loadPage(validPages.includes(hash) ? hash : "dashboard");
}

window.addEventListener("popstate", (e) => {
  if (e.state?.page) loadPage(e.state.page);
});
