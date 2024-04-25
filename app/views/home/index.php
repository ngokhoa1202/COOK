<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
  <link rel="stylesheet" href="/views/home/style.css">
  <script defer src="/views/home/script.js"></script>
  <title>Welcome to COOK</title>
</head>

<body>
  <header class="header">
    <nav class="header-brand">
      <a href="#" class="btn header-btn">Follow us</a>
      <a href="#" class="brand-box">
        <img src="/assets/img/brand.svg" class="brand-img" alt="Our brand logo">
      </a>
      <div class="icon-links">
        <a href="./account/login/login.php" class="icon-link account-link">
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
    <section class="section-hero margin-bottom-medium">
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
              <img src="/assets/img/home/hero/customer/customer-1.jpg" alt="A customer portrait" class="customer-icon">
              <img src="/assets/img/home/hero/customer/customer-2.jpg" alt="A customer portrait" class="customer-icon">
              <img src="/assets/img/home/hero/customer/customer-3.jpg" alt="A customer portrait" class="customer-icon">
              <img src="/assets/img/home/hero/customer/customer-4.jpg" alt="A customer portrait" class="customer-icon">
              <img src="/assets/img/home/hero/customer/customer-5.jpg" alt="A customer portrait" class="customer-icon">
              <img src="/assets/img/home/hero/customer/customer-6.jpg" alt="A customer portrait" class="customer-icon">
            </div>
            <div class="delivered-message">
              <span class="delivered-number">100000+</span> ordered meals 2023
            </div>
          </div>
        </div>
        <div class="hero-img-box">
          <img src="/assets/img/home/hero/hero-meal.png" class="hero-img" alt="A tasty spring roll with savoury sauce">
        </div>
      </div>
    </section>

    <section class="feature-section margin-bottom-medium">
      <p class="heading-secondary feature-heading margin-bottom-small">&quot;Excellent meals for a balanced diet&quot; &horbar; BBC Review</p>
      <p class="heading-secondary feature-heading margin-bottom-small">&quot;Such a beautiful catering service&quot; &horbar; Gordon Ramsay</p>
      <div class="feature-container">
        <a class="feature-link" href="#">
          <div class="feature-img-container margin-bottom-very-small">
            <img src="/assets/img/home/feature-box/new-dish.jpg" alt="A tasty plate with bacon and tomato sauce" class="feature-img">
          </div>
          <p class="heading-tertiary">What's new</p>
        </a>
        <a class="feature-link" href="#">
          <div class="feature-img-container margin-bottom-very-small">
            <img src="/assets/img/home/feature-box/offer.jpg" alt="A tasty plate with bacon and tomato sauce" class="feature-img">
          </div>
          <p class="heading-tertiary">Our offers</p>
        </a>
        <a class="feature-link" href="#">
          <div class="feature-img-container margin-bottom-very-small">
            <img src="/assets/img/home/feature-box/bestseller.jpg" alt="A tasty plate with bacon and tomato sauce" class="feature-img">
          </div>
          <p class="heading-tertiary">Bestsellers</p>
        </a>
      </div>
    </section>

    <section class="section-partner margin-bottom-medium">
      <p class="heading-tertiary margin-bottom-small">We are published by</p>
      <div class="partner-container">
        <img src="/assets/img/home/partner/business-insider.png" class="partner-logo" alt="Business Insider brand logo">
        <img src="/assets/img/home/partner/forbes.png" class="partner-logo" alt="Forbes brand logo">
        <img src="/assets/img/home/partner/techcrunch.png" class="partner-logo" alt="Techcrunch brand logo">
        <img src="/assets/img/home/partner/the-new-york-times.png" class="partner-logo" alt="The New York Times brand logo">
        <img src="/assets/img/home/partner/usa-today.png" class="partner-logo" alt="USA Today brand logo">
      </div>
    </section>

    <section class="section-party margin-bottom-medium">
      <a class="party-link" href="#">
        <div class="party-box">
          <img src="/assets/img/home/party/party-1.jpg" class="party-img" alt="A party image">
          <img src="/assets/img/home/party/party-2.jpg" class="party-img" alt="A party image">
          <img src="/assets/img/home/party/party-3.jpg" class="party-img" alt="Let's party slogan">
        </div>
      </a>
    </section>

    <section class="section-feature-icon margin-bottom-medium">
      <div class="featured-container">
        <div class="featured-item">
          <ion-icon name="restaurant-outline" class="featured-img margin-bottom-very-small"></ion-icon>

          <p class="heading-tertiary margin-bottom-small">Savoury</p>
          <p class="featured-description">
            Our happiness is to bring you the best recipe. Leave your comment, and you can enjoy your mother-like dishes.
          </p>
        </div>
        <div class="featured-item">
          <ion-icon name="fitness-outline" class="featured-img margin-bottom-very-small"></ion-icon>
          <p class="heading-tertiary margin-bottom-small">Balanced</p>
          <p class="featured-description">
            We do care about the nutrient and mineral content. If you are following a balanced diet, you are just in the right place.
          </p>
        </div>
        <div class="featured-item">
          <ion-icon name="leaf-outline" class="featured-img margin-bottom-very-small"></ion-icon>
          <p class="heading-tertiary margin-bottom-small">Fresh &amp; Organic</p>
          <p class="featured-description">
            We say no with GMO produces. All of our raw materials are certified by DEA.
          </p>
        </div>
        <div class="featured-item">
          <ion-icon name="hourglass-outline" class="featured-img margin-bottom-very-small"></ion-icon>
          <p class="heading-tertiary margin-bottom-small">Save time</p>
          <p class="featured-description">
            You have no time to prepare for your lunch? No problem, we will tackle it for you.
          </p>
        </div>
      </div>
    </section>

    <section class="section-carousel margin-bottom-medium">
      <div class="container">
        <a class="carousel-link margin-bottom-small" href="#">
          <p class="heading-secondary">Bestsellers</p>
        </a>
        <div class="carousel-container">
          <button class="carousel-btn carousel-back-btn">
            <ion-icon name="arrow-back-outline" class="arrow-back-icon"></ion-icon>
          </button>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-1.jpg" alt="Chicken Alexander dish" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Chicken Alexander</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-2.jpg" alt="Smoke Haddock &amp; Bacon Grutin" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Smoke Haddock &amp; Bacon Gratin</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-3.jpg" alt="Salmon &amp; Asparagus Gratin" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name">Salmon &amp; Asparagus Gratin</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-4.jpg" alt="Roasted Vegetable Lasagne" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Roasted Vegetable Lasagne</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-5.jpg" alt="Beef Stroganoff" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Beef Stroganoff</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-6.jpg" alt="Beef Stroganoff" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Beef Stroganoff</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-7.jpg" alt="Beef Stroganoff" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Beef Stroganoff</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-8.jpg" alt="Beef Stroganoff" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Beef Stroganoff</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-9.jpg" alt="Beef Stroganoff" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Beef Stroganoff</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-10.jpg" alt="Beef Stroganoff" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Beef Stroganoff</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-11.jpg" alt="Beef Stroganoff" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Beef Stroganoff</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-12.jpg" alt="Beef Stroganoff" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Beef Stroganoff</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-13.jpg" alt="Beef Stroganoff" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Beef Stroganoff</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-14.jpg" alt="Beef Stroganoff" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Beef Stroganoff</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-16.jpg" alt="Beef Stroganoff" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Beef Stroganoff</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-17.jpg" alt="Beef Stroganoff" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Beef Stroganoff</p>
          </div>
          <div class="carousel-item">
            <img src="/assets/img/home/carousel/meal-18.jpg" alt="Beef Stroganoff" class="carousel-img margin-bottom-very-small">
            <p class="carousel-dish-name margin-bottom-very-small">Beef Stroganoff</p>
          </div>
          <button class="carousel-btn carousel-forward-btn">
            <ion-icon name="arrow-forward-outline" class="arrow-forward-icon"></ion-icon>
          </button>
        </div>
      </div>
    </section>

    <section class="section-hero-organic margin-bottom-medium">
      <div class="organic-container">
        <div class="organic-img-box">
          <img src="/assets/img/home/hero-organic/organic-farm.jpg" alt="Organic farms and cultivating methods" class="organic-img">
        </div>
        <div class="organic-text-box">
          <p class="heading-primary organic-title margin-bottom-medium">Fresh ingredients, Originated right the way</p>
          <p class="organic-description margin-bottom-small">
            A nutritional meal begins with fresh ingredients. We're proud to collaborate with the best American farmers. Together, we've raised the animal welfare and adopting more sustainable ways to product high quality foods.
          </p>
          <a class="organic-link" href="#">Our stories</a>
        </div>
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

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>