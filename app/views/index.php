<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="../../assets/img/favicon.ico">
  <link rel="stylesheet" href="style.css">
  <title>Welcome to COOK</title>
</head>

<body>
  <header class="header">
    <nav class="header-brand">
      <a href="#" class="btn header-btn">Follow us</a>
      <a href="#" class="brand-box">
        <img src="../../assets/img/brand.svg" class="brand-img">
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
    <section class="section-hero">
      <div class="hero-container">
        <div class="hero-text-box">
          <p class="heading-secondary hero-heading margin-bottom-medium">
            Nourishing food, with
            <br>
            fresh ingredients from
            <br>
            organic farms
          </p>
          <p class="hero-description margin-bottom-small">We understand the pressure of modern life and your need to have a balanced nutrition. Tailored to your personal tastes and nutritional needs.</p>
          <div class="btns margin-bottom-small">
            <a class="btn btn--full" href="#">Order now</a>
            <a class="btn btn--outline" href="#">Learn more</a>
          </div>
          <div class="delivered-meal">
            <div class="delivered-face">
              <img src="../../assets/img/hero/customer/customer-1.jpg" alt="A customer portrait" class="customer-icon">
              <img src="../../assets/img/hero/customer/customer-2.jpg" alt="A customer portrait" class="customer-icon">
              <img src="../../assets/img/hero/customer/customer-3.jpg" alt="A customer portrait" class="customer-icon">
              <img src="../../assets/img/hero/customer/customer-4.jpg" alt="A customer portrait" class="customer-icon">
              <img src="../../assets/img/hero/customer/customer-5.jpg" alt="A customer portrait" class="customer-icon">
              <img src="../../assets/img/hero/customer/customer-6.jpg" alt="A customer portrait" class="customer-icon">
            </div>
            <div class="delivered-message">
              <span class="delivered-number">100000+</span> ordered meals 2023
            </div>
          </div>
        </div>
        <div class="hero-img-box">
          <img src="../../assets/img/hero/hero-meal.png" class="hero-img" alt="A tasty spring roll with savoury sauce">
        </div>
      </div>
    </section>
  </main>
  <footer class="footer"></footer>
</body>

</html>