<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
  <link rel="stylesheet" href="/views/admin/login/style.css">
  <script defer src="/views/login/script.js"></script>
  <script defer type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script defer nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <title>Admin login</title>
</head>

<body>
  <header class="header">
    <nav class="header-brand">
      <a href="#" class="brand-box">
        <img src="/assets/img/brand.svg" class="brand-img" alt="Our brand logo">
      </a>
    </nav>
  </header>
  <main class="main">
    <section class="section-log-in margin-bottom-very-large">
      <div class="login-container">
        <p class="heading-secondary log-in-title margin-bottom-small">Log in</p>
        <form class="login-form" novalidate>
          <label class="heading-tertiary block margin-bottom-tiny" for="email-input">Email address</label>
          <input type="email" class="text-input margin-bottom-tiny" id="email-input" placeholder="example@abc.com" required name="email" maxlength="60">
          <p class="error email-error margin-bottom-rather-small"></p>

          <div class="active login-accordion margin-bottom">
            <label class="heading-tertiary block margin-bottom-tiny" for="login-password-input">Password</label>
            <input type="password" class="text-input margin-bottom-tiny" id="login-password-input" placeholder="********" required name="password" maxlength="60" minlength="8">
            <p class="error login-password-error margin-bottom-rather-small"></p>
            <button type="submit" class="btn">Log in</button>
          </div>
        </form>
      </div>
    </section>
  </main>


  <footer class="footer">
    <div class="grid grid--4-cols footer-container">
      <div class="brand-col">
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
      <div class="contact-col">
        <p class="heading-tertiary footer-heading margin-bottom-small">Contact us</p>
        <p class="home-address margin-bottom-rather-small">The COOK Kitchen Sittingbourne Kent ME10 3HH, 623 Harrison St., 2nd Floor, San Francisco, CA 94107</p>
        <a href="tel:01732-759000" class="tel-link margin-bottom-small footer-link">01732 759000</a>
        <a href="mailto:customerservice@cookfood.com.us" class="email-link margin-bottom-small footer-link">customerservice@cookfood.com.us</a>
      </div>
      <div class="account-col">
        <p class="heading-tertiary footer-heading margin-bottom-small">Account</p>
        <ul class="account-links">
          <li>
            <a class="account-link footer-link" href="#">Sign up</a>
          </li>
          <li>
            <a class="account-link footer-link" href="#">Log in</a>
          </li>
        </ul>
      </div>
      <div class="about-us-col">
        <p class="heading-tertiary footer-heading margin-bottom-small">About us</p>
        <ul class="about-us-links">
          <li>
            <a class="about-us-link footer-link" href="#">About COOK</a>
          </li>
          <li>
            <a class="about-us-link footer-link" href="#">Our partners</a>
          </li>
          <li>
            <a class="about-us-link footer-link" href="#">FAQs</a>
          </li>
          <li>
            <a class="about-us-link footer-link" href="#">Our staffs</a>
          </li>
        </ul>
      </div>
    </div>
  </footer>
</body>

</html>