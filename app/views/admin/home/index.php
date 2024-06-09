<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/views/admin/home/style.css">
  <script defer src="/views/admin/home/script.js"></script>
  <script async type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script async nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <title>COOK - Admin Home</title>
</head>

<body>
  <div class="grid grid--2-cols">
    <div class="side-bar">
      <header class="header">
        <nav class="header-brand">
          <a href="#" class="brand-box">
            <img src="/assets/img/brand.svg" class="brand-img" alt="Our brand logo">
          </a>
        </nav>
      </header>

      <nav class="side-nav margin-bottom-small">
        <a class="nav-link nav-link--active" href="#">
          <ion-icon name="home-outline" class="icon-link"></ion-icon>
          <p>Dashboard</p>
        </a>
        <a class="nav-link">
          <ion-icon name="receipt-outline" class="icon-link"></ion-icon>
          <p>Orders</p>
        </a>
        <a class="nav-link" href="/admin/products">
          <ion-icon name="fast-food-outline" class="icon-link"></ion-icon>
          <p>Products</p>
        </a>
        <a class="nav-link" href="/admin/menus">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.0" stroke="currentColor" class="icon-link hero-icon-link">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
          </svg>
          <p>Menus</p>
        </a>
        <a class="nav-link" href="/admin/categories">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.0" stroke="currentColor" class="icon-link hero-icon-link">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
          </svg>
          <p>Categories</p>
        </a>
        <a class="nav-link" href="/admin/types">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.0" stroke="currentColor" class="icon-link hero-icon-link">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
          </svg>
          <p href="#">Types</p>
        </a>
        <a class="nav-link">
          <ion-icon name="calendar-outline" class="icon-link"></ion-icon>
          <p>Calendar</p>
        </a>
        <a class="nav-link" href="/admin/users">
          <ion-icon name="people-outline" class="icon-link"></ion-icon>
          <p>Users</p>
        </a>
      </nav>
    </div>

    <main class="main">
      <h2 class="heading-tertiary margin-bottom-medium">Hi, John</h2>
      <section class="summary margin-bottom-medium">
        <div class="summary-container">
          <div class="summary-card">
            <div class="summary-info">
              <p class="summary-title margin-bottom-very-small">Total products</p>
              <p class="summary-figure">150000</p>
            </div>
            <ion-icon name="fast-food-outline" class="summary-icon"></ion-icon>
          </div>
          <div class="summary-card">
            <div class="summary-info">
              <p class="summary-title margin-bottom-very-small">Total orders</p>
              <p class="summary-figure">150000</p>
            </div>
            <ion-icon name="receipt-outline" class="summary-icon"></ion-icon>
          </div>
          <div class="summary-card">
            <div class="summary-info">
              <p class="summary-title margin-bottom-very-small">Total users</p>
              <p class="summary-figure">150000</p>
            </div>
            <ion-icon name="people-outline" class="summary-icon"></ion-icon>
          </div>
          <div class="summary-card">
            <div class="summary-info">
              <p class="summary-title margin-bottom-very-small">Total earnings</p>
              <p class="summary-figure">150000</p>
            </div>
            <ion-icon name="cash-outline" class="summary-icon"></ion-icon>
          </div>
        </div>
      </section>
    </main>
  </div>

  <footer class="footer">
    <div class="footer-container">
      <img src="/assets/img/brand.svg" class="footer-brand-img" alt="Our brand logo">
      <ul class="media-links">
        <li>
          <a class="media-link footer-link">
            <ion-icon name="logo-twitter" class="media-icon"></ion-icon>
          </a>
        </li>
        <li>
          <a class="media-link footer-link">
            <ion-icon name="logo-instagram" class="media-icon"></ion-icon>
          </a>
        </li>
        <li>
          <a class="media-link footer-link" href="#">
            <ion-icon name="logo-tiktok" class="media-icon"></ion-icon>
          </a>
        </li>
        <li>
          <a class="media-link footer-link" href="#">
            <ion-icon name="logo-facebook" class="media-icon"></ion-icon>
          </a>
        </li>
        <li>
          <a class="media-link footer-link" href="#">
            <ion-icon name="logo-reddit" class="media-icon"></ion-icon>
          </a>
        </li>
      </ul>
      <p class="copyright">Copyright &copy; 2025 by COOK Inc. All rights reserved.</p>
    </div>
  </footer>
</body>

</html>