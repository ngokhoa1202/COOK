"use strict";

/**OPEN AND CLOSE MODAL WINDOW WHEN CREATING A NEW USER */
const newUserModal = document.querySelector(".new-user-modal");
const overlay = document.querySelector(".overlay");

const openNewUserModalBtn = document.querySelector(".btn--new-user");
const closeNewUserModalBtn = document.querySelector(".btn--close-modal");

function openNewUserModal() {
  newUserModal.classList.remove("hidden");
  overlay.classList.remove("hidden");
}

function closeNewUserModal() {
  newUserModal.classList.add("hidden");
  overlay.classList.add("hidden");
}

openNewUserModalBtn.addEventListener("click", (e) => openNewUserModal());
closeNewUserModalBtn.addEventListener("click", (e) => closeNewUserModal());
overlay.addEventListener("click", (e) => closeNewUserModal());
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && ! newUserModal.classList.contains("hidden")) {
    closeNewUserModal();
  }
});