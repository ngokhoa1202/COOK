<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="../../../../assets/img/favicon.ico">
  <link rel="stylesheet" href="style.css">
  <title>Log in</title>
</head>

<body>
  <header class="header">
    <nav class="header-brand">
      <a href="#" class="btn header-btn">Follow us</a>
      <a href="#" class="brand-box">
        <img src="../../../../assets/img/brand.svg" class="brand-img" alt="Our brand logo">
      </a>
      <div class="icon-links">
        <a href="#" class="icon-link account-link">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon account-icon" viewBox="0 0 512 512">
            <path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" class="path" />
            <path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32" class="path" />
          </svg>
        </a>
        <a href="#" class="icon-link cart-link">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon cart-icon" viewBox="0 0 512 512">
            <circle cx="176" cy="416" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" class="circle" />
            <circle cx="400" cy="416" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" class="circle" />
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M48 80h64l48 272h256" class="path" />
            <path style="transition: fill 1s ease;" d="M160 288h249.44a8 8 0 007.85-6.43l28.8-144a8 8 0 00-7.85-9.57H128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" class="path" />
          </svg>
          <p class="cost">$500</p>
        </a>
      </div>
    </nav>

    <div class="header-nav">
      <nav class="main-nav">
        <a href="#" class="nav-link">Main meals</a>
        <a href="#" class="nav-link">Puddings</a>
        <a href="#" class="nav-link">Entertaining</a>
        <a href="#" class="nav-link">Meal Boxes</a>
        <a href="#" class="nav-link">About us</a>
      </nav>
      <form class="search-form">
        <input type="search" class="search-input" placeholder="Your favorite dishes or ingredients">
        <button type="submit" class="submit-btn">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon search-icon">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
        </button>
      </form>
    </div>
  </header>
  <main class="main">
    <section class="section-log-in">
      <div class="login-container">
        <p class="heading-secondary log-in-title margin-bottom-small">Log in or Sign up</p>
        <form class="login-form" novalidate>
          <label class="heading-tertiary block margin-bottom-tiny" for="email-input">Email address</label>
          <input type="text" class="text-input margin-bottom-rather-small" id="email-input" placeholder="example@abc.com" required name="email" maxlength="60">

          <p class="heading-tertiary margin-bottom-smaller">Have you already signed up?</p>
          <label class="radio-container" for="signup-radio" id="signup">
            No, (you can sign up next)
            <input type="radio" name="sign-up" id="signup-radio" class="radio-input" value="no" checked>
            <span class="radio-checkmark"></span>
          </label>
          <label class="radio-container margin-bottom-rather-small" for="login-radio" id="login">
            Yes
            <input type="radio" name="sign-up" id="login-radio" class="radio-input" value="yes">
            <span class="radio-checkmark"></span>
          </label>
          <div class="active login-accordion">
            <label class="heading-tertiary block margin-bottom-tiny" for="login-password-input">Password</label>
            <input type="password" class="text-input margin-bottom-rather-small" id="login-password-input" placeholder="********" required name="password" maxlength="60" minlength="8">
            <button type="submit" class="btn">Log in</button>
          </div>
          <div class="hidden signup-accordion">
            <label class="heading-tertiary block margin-bottom-tiny" for="signup-password-input">Password</label>
            <input type="password" class="text-input margin-bottom-rather-small" id="signup-password-input" placeholder="********" required name="password" maxlength="60" minlength="8">
            <label class="heading-tertiary block margin-bottom-tiny" for="confirm-password-input">Confirm password</label>
            <input type="password" class="text-input margin-bottom-rather-small" id="confirm-password-input" placeholder="********" required name="password" maxlength="60" minlength="8">
            <button type="submit" class="btn">Sign up</button>
          </div>
        </form>
      </div>
    </section>
  </main>


  <footer class="footer">
    <div class="grid grid--4-cols footer-container">
      <div class="brand-col">
        <img src="../../../../assets/img/brand.svg" class="footer-brand-img" alt="Our brand logo">
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

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="script.js"></script>
</body>

</html>