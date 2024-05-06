<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
  <link rel="stylesheet" href="/views/admin/types/style.css">
  <script defer src="/views/admin/types/script.js"></script>
  <script async type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script async nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <title>COOK Admin - Categories</title>
</head>

<body>
  <div class="grid grid--2-cols">
    <div class="side-bar">
      <header class="header">
        <nav class="header-brand">
          <a href="/" class="brand-box">
            <img src="/assets/img/brand.svg" class="brand-img" alt="Our brand logo">
          </a>
        </nav>
      </header>

      <nav class="side-nav margin-bottom-small">
        <a class="nav-link" href="/admin/home">
          <ion-icon name="home-outline" class="icon-link"></ion-icon>
          <p>Dashboard</p>
        </a>
        <a class="nav-link">
          <ion-icon name="receipt-outline" class="icon-link"></ion-icon>
          <p>Orders</p>
        </a>
        <a class="nav-link">
          <ion-icon name="fast-food-outline" class="icon-link"></ion-icon>
          <p>Products</p>
        </a>
        <a class="nav-link" href="/admin/menus">
          <ion-icon name="grid-outline" class="icon-link"></ion-icon>
          <p>Menus</p>
        </a>
        <a class="nav-link">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.0" stroke="currentColor" class="icon-link hero-icon-link">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
          </svg>
          <p>Categories</p>
        </a>
        <a class="nav-link nav-link--active">
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
              <p class="summary-title margin-bottom-very-small">Total categories</p>
              <p class="summary-figure summary-figure--category">150000</p>
            </div>
            <ion-icon name="apps-outline" class="summary-icon"></ion-icon>
          </div>
          <div class="summary-card">
            <div class="summary-info">
              <p class="summary-title margin-bottom-very-small">Bestseller categories</p>
              <p class="summary-figure summary-figure--bestseller-category">150000</p>
            </div>
            <ion-icon name="analytics-outline" class="summary-icon"></ion-icon>
          </div>
          <div class="summary-card">
            <div class="summary-info">
              <p class="summary-title margin-bottom-very-small">Highest rated category</p>
              <p class="summary-figure summary-figure--highest-rated-category">150000</p>
            </div>
            <ion-icon name="trending-up-outline" class="summary-icon"></ion-icon>
          </div>
        </div>
      </section>

      <section class="section-button margin-bottom-small">
        <div class="button-container">
          <button class="btn btn--new-type">
            <ion-icon name="add-outline" class="add-icon icon-link"></ion-icon>
            <p class="btn--new-type">New category</p>
          </button>
        </div>
      </section>

      <section class="section-search margin-bottom-small">
        <form class="search-form">
          <input type="search" placeholder="Enter a category" class="search-input">
          <button type="submit" class="search-btn btn">
            Search
          </button>
        </form>
      </section>

      <section class="section-dashboard">
        <table class="products-table margin-bottom-medium">
          <thead>
            <tr>
              <th class="table-header-id">Id</th>
              <th class="table-header-type-name">Type name</th>
              <th class="table-header-category-name">Menu name</th>
              <th class="table-header-menu-name">Category name</th>
              <th class="table-header-action">Action</th>
            </tr>
          </thead>
          <tbody class="tbody">
            <!-- Table data for categories -->
          </tbody>
        </table>

        <div class="pagination">
          <a href="#" class="pagination-link pagination-link--special" id="start-link">
            <ion-icon name="play-skip-back-outline"></ion-icon>
          </a>
          <a href="#" class="pagination-link pagination-link--special" id="previous-link">
            <ion-icon name="chevron-back-outline"></ion-icon>
          </a>
          <a href="#" class="pagination-link pagination-link--item pagination-link--active">1</a>
          <a href="#" class="pagination-link pagination-link--item">2</a>
          <a href="#" class="pagination-link pagination-link--item">3</a>
          <a href="#" class="pagination-link pagination-link--item">4</a>
          <a href="#" class="pagination-link pagination-link--item">5</a>
          <a href="#" class="pagination-link pagination-link--special" id="next-link">
            <ion-icon name="chevron-forward-outline"></ion-icon>
          </a>
          <a href="#" class="pagination-link pagination-link--special" id="end-link">
            <ion-icon name="play-skip-forward-outline"></ion-icon>
          </a>
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

  <div class="modal new-type-modal hidden">
    <button class="btn--close-modal btn--close-create-modal">&times;</button>
    <h2 class="heading-secondary margin-bottom-small">Create type</h2>
    <form class="modal-form create-form" novalidate>
      <label class="label" for="create-type-name-input">Type name</label>
      <div class="input-info">
        <input type="text" placeholder="Type name" class="input" name="type-name" id="create-type-name-input">
        <p class="error type-name-error" id="create-type-name-error"></p>
      </div>
      <label class="label" for="create-menu-name-input">Menu name</label>
      <div class="input-info">
        <input type="text" placeholder="Menu name" class="input" name="menu-name" id="create-menu-name-input">
        <p class="error menu-name-error" id="create-menu-name-error"></p>
      </div>
      <label class="label" for="create-category-name-input">Category name</label>
      <div class="input-info">
        <input type="text" placeholder="category name" class="input" name="category-name" id="create-category-name-input">
        <p class="error category-name-error" id="create-category-name-error"></p>
      </div>
      <label class="label" for="">Description</label>
      <div class="input-info">
        <textarea class="textarea create-description-textarea" placeholder="Describe about category" id="create-description-textarea" rows="10" cols="30"></textarea>
        <p class="error description-error" id="create-description-error"></p>
      </div>

      <button type="submit" class="btn btn--submit margin-top-small">Create new type</button>
    </form>
  </div>

  <div class="modal edit-type-modal hidden">
    <button class="btn--close-modal btn--close-edit-modal">&times;</button>
    <h2 class="heading-secondary margin-bottom-small">Edit type</h2>
    <form class="modal-form edit-form" novalidate>
      <label class="label" for="edit-id-input">Id</label>
      <div class="input-info">
        <input type="text" placeholder="Id" class="input" name="id" id="edit-id-input" readonly>
        <p class="error id-error" id="edit-id-error"></p>
      </div>
      <label class="label" for="edit-type-name-input">Type name</label>
      <div class="input-info">
        <input type="text" placeholder="type name" class="input" name="type-name" id="edit-type-name-input">
        <p class="error type-name-error" id="edit-type-name-error"></p>
      </div>
      <label class="label" for="edit-menu-name-input">Menu name</label>
      <div class="input-info">
        <input type="text" placeholder="Menu name" class="input" name="menu-name" id="edit-menu-name-input">
        <p class="error menu-name-error" id="edit-menu-name-error"></p>
      </div>
      <label class="label" for="edit-category-name-input">Category name</label>
      <div class="input-info">
        <input type="text" placeholder="Category name" class="input" name="category-name" id="edit-category-name-input">
        <p class="error category-name-error" id="edit-category-name-error"></p>
      </div>
      <label class="label" for="edit-description-textarea">Description</label>
      <div class="input-info">
        <textarea class="textarea edit-description-textarea" placeholder="Describe about type" id="edit-description-textarea" rows="10" cols="30"></textarea>
        <p class="error description-error" id="edit-description-error"></p>
      </div>

      <button type="submit" class="btn btn--submit margin-top-small">Update category</button>
    </form>
  </div>

  <div class="modal delete-type-modal hidden">
    <button class="btn--close-modal btn--close-delete-modal">&times;</button>
    <h2 class="heading-secondary margin-bottom-small">Delete type</h2>
    <form class="modal-form delete-form" novalidate>
      <label class="label" for="delete-id-input">Id</label>
      <div class="input-info">
        <input type="text" placeholder="Id" class="input" name="id" id="delete-id-input" readonly>
      </div>
      <label class="label" for="delete-type-name-input">Type name</label>
      <div class="input-info">
        <input type="text" placeholder="Type name" class="input" name="id" id="delete-type-name-input" readonly>
      </div>
      <label class="label" for="delete-menu-name-input">Menu name</label>
      <div class="input-info">
        <input type="text" placeholder="Menu name" class="input" name="menu-name" id="delete-menu-name-input" readonly>
      </div>
      <label class="label" for="delete-category-name-input">Category name</label>
      <div class="input-info">
        <input type="text" placeholder="Category name" class="input" name="id" id="delete-category-name-input" readonly>
      </div>
      <button type="submit" class="btn btn--submit margin-top-small">Delete category</button>
    </form>
  </div>

  <div class="modal success-notification-modal hidden">
    <img src="/assets/img/success.svg" alt="success icon" class="success-icon">
    <p class="notification"></p>
  </div>

  <div class="modal failure-notification-modal hidden">
    <img src="/assets/img/failure.svg" alt="failure icon" class="failure-icon">
    <p class="notification"></p>
  </div>

  <div class="overlay hidden"></div>
</body>

</html>