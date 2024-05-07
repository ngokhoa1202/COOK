<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
  <link rel="stylesheet" href="/views/admin/users/style.css">
  <script defer src="/views/admin/users/script.js"></script>
  <script async type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script async nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <title>COOK Admin - Users</title>
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
        <a class="nav-link">
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
          <p>Types</p>
        </a>
        <a class="nav-link">
          <ion-icon name="calendar-outline" class="icon-link"></ion-icon>
          <p>Calendar</p>
        </a>
        <a class="nav-link nav-link--active" href="#">
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
              <p class="summary-title margin-bottom-very-small">Total users</p>
              <p class="summary-figure summary-figure--user">150000</p>
            </div>
            <ion-icon name="people-outline" class="summary-icon"></ion-icon>
          </div>
          <div class="summary-card">
            <div class="summary-info">
              <p class="summary-title margin-bottom-very-small">Total members</p>
              <p class="summary-figure summary-figure--member">150000</p>
            </div>
            <ion-icon name="accessibility-outline" class="summary-icon"></ion-icon>
          </div>
          <div class="summary-card">
            <div class="summary-info">
              <p class="summary-title margin-bottom-very-small">Total online</p>
              <p class="summary-figure summary-figure--online">150000</p>
            </div>
            <ion-icon name="globe-outline" class="summary-icon"></ion-icon>
          </div>
          <div class="summary-card">
            <div class="summary-info">
              <p class="summary-title margin-bottom-very-small">Orders per user</p>
              <p class="summary-figure" id="summary-figure--orders-per-user">150000</p>
            </div>
            <ion-icon name="receipt-outline" class="summary-icon"></ion-icon>
          </div>
        </div>
      </section>

      <section class="section-button margin-bottom-small">
        <div class="button-container">
          <button class="btn btn--new-user">
            <ion-icon name="person-add-outline" class="add-icon icon-link"></ion-icon>
            <p>New user</p>
          </button>
        </div>
      </section>

      <section class="section-search margin-bottom-small">
        <form class="search-form">
          <input type="search" placeholder="Enter an email" class="search-input">
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
              <th class="table-header-email">Email</th>
              <th class="table-header-avatar">Avatar</th>
              <th class="table-header-role">Role</th>
              <th class="table-header-status">Status</th>
              <th class="table-header-action">Action</th>
            </tr>
          </thead>
          <tbody class="tbody">

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

  <div class="modal new-user-modal hidden">
    <button class="btn--close-modal btn--close-create-modal">&times;</button>
    <h2 class="heading-secondary margin-bottom-small">Create user</h2>
    <form class="modal-form create-form" novalidate>
      <label class="label" for="email-input">Email</label>
      <div class="input-info">
        <input type="email" placeholder="Email" class="input" name="email" id="create-email-input">
        <p class="error email-error" id="create-email-error"></p>
      </div>
      <label class="label" for="password-input">Password</label>
      <div class="input-info">
        <input type="password" placeholder="********" class="input" name="password" id="create-password-input">
        <p class="error password-error" id="create-password-error"></p>
      </div>
      <label class="label" for="confirm-password-input">Confirm password</label>
      <div class="input-info">
        <input type="password" placeholder="********" class="input" name="confirm-password" id="create-confirm-password-input">
        <p class="error confirm-password-error" id="create-confirm-password-error"></p>
      </div>
      <label class="label" for="avatar-input">Avatar</label>
      <div class="input-info">
        <input type="file" name="avatar" class="input avatar-input" id="create-avatar-input" accept="image/*">
        <div class="avatar" id="create-avatar"></div>
        <p class="error avatar-error" id="create-avatar-error"></p>
      </div>

      <label class="label" class="role-select">Role</label>
      <div class="input-info">
        <select class="select select--role" id="create-role-select">
          <option class="option" value="">Choose a role&hellip;</option>
          <option class="option" value="member">member</option>
          <option class="option" value="admin">admin</option>
        </select>
        <p class="error role-error" id="create-role-error"></p>
      </div>
      <button type="submit" class="btn btn--submit margin-top-small">Create new user</button>
    </form>
  </div>

  <div class="modal edit-user-modal hidden">
    <button class="btn--close-modal btn--close-edit-modal">&times;</button>
    <h2 class="heading-secondary margin-bottom-small">Edit user</h2>
    <form class="modal-form edit-form" novalidate>
      <label class="label" for="id-input">Id</label>
      <div class="input-info">
        <input type="text" placeholder="Id" class="input" name="id" id="edit-id-input" readonly>
        <p class="error id-error" id="edit-id-error"></p>
      </div>
      <!-- <label class="label" for="username-input">Username</label>
      <div class="input-info">
        <input type="text" placeholder="Id" class="input" name="username" id="edit-username-input">
        <p class="error username-error" id="edit-username-error"></p>
      </div> -->
      <label class="label" for="email-input">Email</label>
      <div class="input-info">
        <input type="email" placeholder="Email" class="input" name="email" id="edit-email-input">
        <p class="error email-error" id="edit-email-error"></p>
      </div>
      <label class="label" for="password-input">Password</label>
      <div class="input-info">
        <input type="password" placeholder="********" class="input" name="password" id="edit-password-input">
        <p class="error password-error" id="edit-password-error"></p>
      </div>
      <label class="label" for="confirm-password-input">Confirm password</label>
      <div class="input-info">
        <input type="password" placeholder="********" class="input" name="confirm-password" id="edit-confirm-password-input">
        <p class="error confirm-password-error" id="edit-confirm-password-error"></p>
      </div>
      <label class="label" for="avatar-input">Avatar</label>
      <div class="input-info">
        <input type="file" name="avatar" class="input avatar-input" name="avatar" id="edit-avatar-input">
      </div>

      <label class="label" class="role-select">Role</label>
      <div class="input-info">
        <select class="select select--role" id="edit-role-select">
          <option class="option" value="">Choose a role&hellip;</option>
          <option class="option" value="member">member</option>
          <option class="option" value="admin">admin</option>
        </select>
        <p class="error role-error" id="edit-role-error"></p>
      </div>

      <label class="label" class="role-select">Status</label>
      <div class="input-info">
        <select class="select select--status" id="edit-status-select">
          <option class="option" value="">Choose a status&hellip;</option>
          <option class="option" value="active">active</option>
          <option class="option" value="inactive">inactive</option>
        </select>
        <p class="error status-error" id="edit-status-error"></p>
      </div>
      <button type="submit" class="btn btn--submit margin-top-small">Update user</button>
    </form>
  </div>

  <div class="modal delete-user-modal hidden">
    <button class="btn--close-modal btn--close-delete-modal">&times;</button>
    <h2 class="heading-secondary margin-bottom-small">Delete user</h2>
    <form class="modal-form delete-form" novalidate>
      <label class="label" for="delete-id-input">Id</label>
      <div class="input-info">
        <input type="text" placeholder="Id" class="input" name="id" id="delete-id-input" readonly>
      </div>
      <label class="label" for="delete-username-input">Username</label>
      <div class="input-info">
        <input type="text" placeholder="Username" class="input" name="menu-name" id="delete-username-input" readonly>
      </div>
      <label class="label" for="delete-email-input">Email</label>
      <div class="input-info">
        <input type="email" placeholder="Email" class="input" name="email" id="delete-email-input" readonly>
      </div>

      <button type="submit" class="btn btn--submit margin-top-small">Delete user</button>
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