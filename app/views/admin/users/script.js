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

/*********************************************************
 * VALIDATE AND SEND USER DATA**************************** 
 * *******************************************************/

const CREATE_USER_URL = "/admin/users/new";
const MIN_PASSWORD_LENGTH = 8;
const EMAIL_PATTERN =
  /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

/* password has at least 8 characters which contain at least one numeric digit and a special character */
const PASSWORD_PATTERN = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/;

const REQUIRED_EMAIL_MSG = "Email is required";
const WRONG_EMAIL_PATTERN = "Wrong email pattern";
const REQUIRED_PASSWORD_MSG = "Password is required";
const REQUIRED_CONFIRM_PASSWORD_MSG = "Confirm password is required";
const WRONG_PASSWORD_PATTERN = "Wrong password pattern";
const TOO_SHORT_PASSWORD = "At least 8 characters for password";
const WRONG_CONFIRM_PASSWORD = "Wrong connfirmed password";
const REQUIRED_ROLE_MSG = "Role is required";
const ROLE_VALUES = ["admin", "member"];
const WRONG_ROLE = "Wrong role value";
const NETWORK_ERROR_MSG = "Your network connection is not stable";

const emailInput = document.querySelector("#email-input");
/**
 *
 * @returns {Promise}
 */
function validateEmail() {
  return new Promise((resolve, reject) => {
    if (!emailInput.value) {
      reject(REQUIRED_EMAIL_MSG);
    } else if (!emailInput.value.match(EMAIL_PATTERN)) {
      reject(WRONG_EMAIL_PATTERN);
    } else {
      resolve(emailInput.value);
    }
  });
}


const passwordInput = document.querySelector("#password-input");
/**
 *
 * @returns {Promise}
 */
function validatePassword() {
  return new Promise((resolve, reject) => {
    if (!passwordInput.value) {
      reject(REQUIRED_PASSWORD_MSG);
    } else if (passwordInput.value.length < MIN_PASSWORD_LENGTH) {
      reject(TOO_SHORT_PASSWORD);
    } else if (!passwordInput.value.match(PASSWORD_PATTERN)) {
      reject(WRONG_PASSWORD_PATTERN);
    } else {
      resolve(passwordInput.value);
    }
  });
}

const confirmPasswordInput = document.querySelector("#confirm-password-input");
/**
 *
 * @returns {Promise}
 */
function validateConfirmPassword() {
  return new Promise((resolve, reject) => {
    if (!confirmPasswordInput.value) {
      reject(REQUIRED_PASSWORD_MSG);
    } else if (confirmPasswordInput.value !== passwordInput.value) {
      reject(WRONG_CONFIRM_PASSWORD);
    } else {
      resolve(true);
    }
  });
}

const roleSelect = document.querySelector("#role-select");
/**
 *
 * @returns {Promise}
 */
function validateRole() {
  return new Promise((resolve, reject) => {
    if (! roleSelect.value) {
      reject(REQUIRED_ROLE_MSG);
    } else if (! ROLE_VALUES.includes(roleSelect.value)) {
      reject(WRONG_ROLE);
    } else {
      resolve(roleSelect.value);
    }
  });
}

const newUserModalForm = document.querySelector(".modal-form");
const emailError = document.querySelector(".email-error");
const passwordError = document.querySelector(".password-error");
const confirmPasswordError = document.querySelector(".confirm-password-error");
const roleError = document.querySelector(".role-error");

let validatedEmail = "";
let validatedPassword = "";
let matchedPassword = false;
let validatedRole = "";
/**
 *
 * @param {FormData} formData
 */
function submitData(formData) {
  fetch(CREATE_USER_URL, {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Your network connection is not stable");
      }
      return response.json();
    })
    .then((data) => {
      console.log("Receive: ", data);
    })
    .catch((error) => {
      alert(error);
    });
}

newUserModalForm.addEventListener("submit", function (e) {
  e.preventDefault();
  const emailValidatorPromise = validateEmail()
    .then((email) => {
      validatedEmail = email;
      emailError.textContent = "";
    })
    .catch((msg) => {
      emailError.textContent = msg;
    })
    .finally(() => {});

  const passwordValidatorPromise = validatePassword()
    .then((password) => {
      validatedPassword = password;
      passwordError.textContent = "";
    })
    .catch((msg) => {
      passwordError.textContent = msg;
    })
    .finally(() => {});

  const confirmPasswordValidatorPromise = validateConfirmPassword()
    .then((isMatched) => {
      matchedPassword = isMatched;
      confirmPasswordError.textContent = "";
    })
    .catch((msg) => {
      confirmPasswordError.textContent = msg;
    })
    .finally(() => {});
  
  const roleValidatorPromise = validateRole().
    then((role) => {
      validatedRole = role;
    })
    .catch((msg) => {
      roleError.textContent = msg;
    })
    .finally(() => {});
  
  function handleNewUserData() {
    Promise.allSettled([
      emailValidatorPromise,
      passwordValidatorPromise,
      confirmPasswordValidatorPromise,
    ]).then(() => {
      if (validatedEmail && validatedPassword && matchedPassword && validatedRole) {
        const formData = new FormData();
        formData.append("signup", true);
        formData.append("email", validatedEmail);
        formData.append("password", validatedPassword);
        formData.append("confirm_password", validatedPassword);
        formData.append("role", validatedRole)
        submitData(formData);
      }
    });
  }
  handleNewUserData();
});

/*********************************************************
 *HANDLE INPUT GAINING FOUCS AND LOSING FOCUS EVENT******* 
 * *******************************************************/

emailInput.addEventListener("focus", function (e) {
  validateEmail()
    .then((email) => {
      validatedEmail = email;
      emailError.textContent = "";
    })
    .catch((msg) => {
      emailError.textContent = msg;
    })
    .finally(() => {});
});

emailInput.addEventListener("input", function(e) {
  validateEmail()
    .then((email) => {
      validatedEmail = email;
      emailError.textContent = "";
    })
    .catch((msg) => {
      emailError.textContent = msg;
    })
    .finally(() => {});
});

emailInput.addEventListener("focusout", function(e) {
  validateEmail()
    .then((email) => {
      validatedEmail = email;
      emailError.textContent = "";
    })
    .catch((msg) => {
      emailError.textContent = msg;
    })
    .finally(() => {});
});

passwordInput.addEventListener("focus", function(e) {
  validatePassword()
    .then((password) => {
      validatedPassword = password;
      passwordError.textContent = "";
    })
    .catch((msg) => {
      passwordError.textContent = msg;
    })
    .finally(() => {});
});

passwordInput.addEventListener("input", function(e) {
  validatePassword()
    .then((password) => {
      validatedPassword = password;
      passwordError.textContent = "";
    })
    .catch((msg) => {
      passwordError.textContent = msg;
    })
    .finally(() => {});
});

passwordInput.addEventListener("focusout", function(e) {
  validatePassword()
    .then((password) => {
      validatedPassword = password;
      passwordError.textContent = "";
    })
    .catch((msg) => {
      passwordError.textContent = msg;
    })
    .finally(() => {});
});

confirmPasswordInput.addEventListener("focus", function(e) {
  validateConfirmPassword()
    .then((isMatched) => {
      matchedPassword = isMatched;
      confirmPasswordError.textContent = "";
    })
    .catch((msg) => {
      confirmPasswordError.textContent = msg;
    })
    .finally(() => {});
});

confirmPasswordInput.addEventListener("input", function(e) {
  validateConfirmPassword()
    .then((isMatched) => {
      matchedPassword = isMatched;
      confirmPasswordError.textContent = "";
    })
    .catch((msg) => {
      confirmPasswordError.textContent = msg;
    })
    .finally(() => {});
});

confirmPasswordInput.addEventListener("focusout", function (e) {
  validateConfirmPassword()
    .then((isMatched) => {
      matchedPassword = isMatched;
      confirmPasswordError.textContent = "";
    })
    .catch((msg) => {
      confirmPasswordError.textContent = msg;
    })
    .finally(() => {});
});

/*********************************************************
 *FETCH SUMMARY FIGURE FROM SERVER************************ 
 * *******************************************************/
const SUMMARY_FIGURE_INTERVAL = 5000;
const summarFigureOfUsers = document.querySelector(".summary-figure--user");
const summarFigureOfMembers = document.querySelector(".summary-figure--member");
const summarFigureOfActiveUsers = document.querySelector(".summary-figure--online");
const summaryFigureOfOrdersPerUser = document.querySelector(".summary-figure--online");
const GET_USER_FIGURE_URL = "/admin/users/total";
const GET_MEMBER_FIGURE_URL = "/admin/users/members/total";
const GET_ACTIVE_FIGURE_URL = "/admin/users/active/total";

async function getUserSummaryFigure() {
  await fetch(GET_USER_FIGURE_URL, {
    method: "GET"
  }).then((response) => {
    if (! response.ok) {
      throw new Error(NETWORK_ERROR_MSG);
    }
    return response.json();
  }).then((data) => {
    summarFigureOfUsers.textContent = data;
  }).catch((error) => {
    alert(error.message);
  })
}
getUserSummaryFigure();
setInterval(getUserSummaryFigure, SUMMARY_FIGURE_INTERVAL);

async function getMemberSummaryFigure() {
  await fetch(GET_MEMBER_FIGURE_URL, {
    method: "GET",
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(NETWORK_ERROR_MSG);
      }
      return response.json();
    })
    .then((data) => {
      summarFigureOfMembers.textContent = data;
    })
    .catch((error) => {
      alert(error.message);
    });
}
getMemberSummaryFigure();
setInterval(getMemberSummaryFigure, SUMMARY_FIGURE_INTERVAL);

async function getActiveUserSummaryFigure() {
  await fetch(GET_ACTIVE_FIGURE_URL, {
    method: "GET",
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(NETWORK_ERROR_MSG);
      }
      return response.json();
    })
    .then((data) => {
      summarFigureOfActiveUsers.textContent = data;
    })
    .catch((error) => {
      alert(error.message);
    });
}
getActiveUserSummaryFigure();
setInterval(getActiveUserSummaryFigure, SUMMARY_FIGURE_INTERVAL);


/*********************************************************
 *FETCH SUMMARY FIGURE FROM SERVER************************ 
 * *******************************************************/
const GET_USER_URL = "/admin/users/list";
const USER_LENGTH = 20;
let userPageIndex = 1;

function removeOldUserTableData() {
  let tableRow = null;
  while (tableRow = document.querySelector(".tbody tr")) {
    tableRow.remove();
  }
}

async function getUserForPage(pageIndex) {
  await fetch(
    GET_USER_URL + "?" + new URLSearchParams({
      page: pageIndex,
      length: USER_LENGTH
    }), 
    {
      method: "GET"
    })
    .then((response) => {
      if (! response.ok) {
        throw new Error(NETWORK_ERROR_MSG);
      }
      return response.json();
    }).then((users) => {
      removeOldUserTableData();   
      users.forEach((user) => {
        const tableRow = document.createElement("tr");

        const tableDataForId = document.createElement("td");
        tableDataForId.textContent = user.user_id;
        tableRow.appendChild(tableDataForId);

        const tableDataForEmail = document.createElement("td");
        tableDataForEmail.textContent = user.email;
        tableRow.appendChild(tableDataForEmail);

        const tableDataForAvatar = document.createElement("td");
        tableDataForAvatar.textContent = user.avatar;
        tableRow.appendChild(tableDataForAvatar);

        const tableDataForRole = document.createElement("td");
        tableDataForRole.textContent = user.role;
        tableRow.appendChild(tableDataForRole);

        const tableDataForStatus = document.createElement("td");
        tableDataForStatus.textContent = user.status;
        tableRow.appendChild(tableDataForStatus);

        const tableDataForAction = document.createElement("td");
        const actionForm = document.createElement("form");
        actionForm.classList.add("action-form");
        const editButton = document.createElement("button");
        editButton.type = "submit";
        editButton.className = "btn--edit table-btn";
        const editIcon = document.createElement("ion-icon");
        editIcon.name = "pencil-outline";
        editIcon.className = "table-icon";
        editButton.appendChild(editIcon);
        actionForm.appendChild(editButton);
        const deleteButton = document.createElement("button");
        deleteButton.type = "submit";
        deleteButton.className = "btn--delete table-btn";
        const deleteIcon = document.createElement("ion-icon");
        deleteIcon.name = "trash-outline";
        deleteIcon.className = "table-icon";
        deleteButton.appendChild(deleteIcon);
        actionForm.appendChild(deleteButton);
        tableDataForAction.appendChild(actionForm);
        tableRow.appendChild(tableDataForAction);

        const tableBody = document.querySelector(".tbody");
        tableBody.appendChild(tableRow);
      })
    }).catch((error) => {
      alert(error.message);
    })
}
const USER_LIST_INTERVAL = 10000;
getUserForPage(userPageIndex);


/*********************************************************
 *PAGINATION************************ 
 * *******************************************************/
const pagination = document.querySelector(".pagination");
const PAGINATION_LENGTH = 20;

function checkPaginationOverflowed() {
  const allPaginationItems = document.querySelectorAll(".pagination-link--item");
  return allPaginationItems.length >= numberOfUserPages;
}

function renderPageIndexForPagination(startIndex = 1) {
  if (checkPaginationOverflowed()) {
    return;
  }
  const documentFragment = new DocumentFragment();
  for (let i = 0; i < numberOfUserPages; ++i) {
    let paginationItem = document.createElement("a");
    paginationItem.className = "pagination-link pagination-link--item";
    paginationItem.href = "#";
    paginationItem.textContent = startIndex + i;
    documentFragment.appendChild(paginationItem);
  }
  const nextLink = document.querySelector("#next-link");
  pagination.insertBefore(documentFragment, nextLink);
}

function getStartIndexOfCurrentPagination() {
  return Number.parseInt(document.querySelector(".pagination-link--item").textContent);
}

const NUMBER_OF_USER_PAGES_URL = "/admin/users/pages/total";
let numberOfUserPages = 0;

async function getNumberOfUserPages() {
  await fetch(
    NUMBER_OF_USER_PAGES_URL +
      "?" +
      new URLSearchParams({
        length: PAGINATION_LENGTH,
      }),
    {
      method: "GET",
    }
  )
    .then((response) => {
      if (!response.ok) {
        throw new Error(NETWORK_ERROR_MSG);
      }
      return response.json();
    })
    .then((data) => {
      numberOfUserPages = data;
    })
    .catch((error) => {
      alert(error.message);
    })
}

window.addEventListener("DOMContentLoaded", function (e){
  getNumberOfUserPages().then(() => {
    renderPageIndexForPagination();
  });
});

window.addEventListener("load", (e) => {
  const allPaginationItems = document.querySelectorAll(".pagination-link--item");
  allPaginationItems.forEach((item) => {
    item.addEventListener("click", function (ev) {
      ev.preventDefault();
      let pageIndex = Number.parseInt(item.textContent);
      getUserForPage(pageIndex);
    });
  });
});





