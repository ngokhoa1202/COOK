"use strict";

/**OPEN AND CLOSE MODAL WINDOW WHEN CREATING A NEW USER */
const newUserModal = document.querySelector(".new-user-modal");
const overlay = document.querySelector(".overlay");

const openNewUserModalBtn = document.querySelector(".btn--new-user");
const closeNewUserModalBtn = document.querySelector(".btn--close-create-modal");

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

const createEmailInput = document.querySelector("#create-email-input");
/**
 *
 * @returns {Promise}
 */
function validateEmail() {
  return new Promise((resolve, reject) => {
    if (!createEmailInput.value) {
      reject(REQUIRED_EMAIL_MSG);
    } else if (!createEmailInput.value.match(EMAIL_PATTERN)) {
      reject(WRONG_EMAIL_PATTERN);
    } else {
      resolve(createEmailInput.value);
    }
  });
}


const createPasswordInput = document.querySelector("#create-password-input");
/**
 *
 * @returns {Promise}
 */
function validatePassword() {
  return new Promise((resolve, reject) => {
    if (!createPasswordInput.value) {
      reject(REQUIRED_PASSWORD_MSG);
    } else if (createPasswordInput.value.length < MIN_PASSWORD_LENGTH) {
      reject(TOO_SHORT_PASSWORD);
    } else if (!createPasswordInput.value.match(PASSWORD_PATTERN)) {
      reject(WRONG_PASSWORD_PATTERN);
    } else {
      resolve(createPasswordInput.value);
    }
  });
}

const createConfirmPasswordInput = document.querySelector("#create-confirm-password-input");
/**
 *
 * @returns {Promise}
 */
function validateConfirmPassword() {
  return new Promise((resolve, reject) => {
    if (!createConfirmPasswordInput.value) {
      reject(REQUIRED_PASSWORD_MSG);
    } else if (createConfirmPasswordInput.value !== createPasswordInput.value) {
      reject(WRONG_CONFIRM_PASSWORD);
    } else {
      resolve(true);
    }
  });
}

const createRoleSelect = document.querySelector("#create-role-select");
/**
 *
 * @returns {Promise}
 */
function validateRole() {
  return new Promise((resolve, reject) => {
    if (! createRoleSelect.value) {
      reject(REQUIRED_ROLE_MSG);
    } else if (! ROLE_VALUES.includes(createRoleSelect.value)) {
      reject(WRONG_ROLE);
    } else {
      resolve(createRoleSelect.value);
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

createEmailInput.addEventListener("focus", function (e) {
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

createEmailInput.addEventListener("input", function(e) {
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

createEmailInput.addEventListener("focusout", function(e) {
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

createPasswordInput.addEventListener("focus", function(e) {
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

createPasswordInput.addEventListener("input", function(e) {
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

createPasswordInput.addEventListener("focusout", function(e) {
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

createConfirmPasswordInput.addEventListener("focus", function(e) {
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

createConfirmPasswordInput.addEventListener("input", function(e) {
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

createConfirmPasswordInput.addEventListener("focusout", function (e) {
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

const tableBody = document.querySelector(".tbody");
let users = null;
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
    }).then((data) => {
      users = data;
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
        const actionForm = document.createElement("div");
        actionForm.classList.add("action-form");
        const editButton = document.createElement("button");
        editButton.type = "submit";
        editButton.className = "btn--edit table-btn";
        const editIcon = document.createElement("ion-icon");
        editIcon.name = "pencil-outline";
        editIcon.className = "table-icon edit-icon";
        editButton.appendChild(editIcon);
        actionForm.appendChild(editButton);
        const deleteButton = document.createElement("button");
        deleteButton.type = "submit";
        deleteButton.className = "btn--delete table-btn";
        const deleteIcon = document.createElement("ion-icon");
        deleteIcon.name = "trash-outline";
        deleteIcon.className = "table-icon trash-icon";
        deleteIcon.id = "delete-icon";
        deleteButton.appendChild(deleteIcon);
        actionForm.appendChild(deleteButton);
        tableDataForAction.appendChild(actionForm);
        tableRow.appendChild(tableDataForAction);
        tableBody.appendChild(tableRow);
      })
    }).catch((error) => {
      alert(error.message);
    })
}
const USER_LIST_INTERVAL = 10000;
getUserForPage(userPageIndex);

/*********************************************************
 *EDIT/DELETE USER INFO************************ 
 * *******************************************************/

/*********************************************************
 *PAGINATION************************ 
 * *******************************************************/
const pagination = document.querySelector(".pagination");
const USER_LIST_LENGTH = 20;


const MAX_PAGINATION_LENGTH = 5;
/**
 * 
 * @param {Number} offset 
 * @returns {void}
 */
function renderPageIndexForPagination(offset = 1) {
  if (numberOfUserPages === 0) {
    return;
  }
  if (offset < 1) {
    offset = 1;
  }
  const allPaginationItems = document.querySelectorAll(".pagination-link--item");
  if (allPaginationItems.length > numberOfUserPages && numberOfUserPages > 0) {
    for (let i = numberOfUserPages + 1; i <= allPaginationItems.length; ++i) {
      allPaginationItems[i].classList.add("hidden");
    }
    return;
  }
  
  let paginationLength = Math.min(MAX_PAGINATION_LENGTH, numberOfUserPages - offset + 1);
  for (let i = 0; i < paginationLength; ++i) {
    allPaginationItems[i].classList.remove("hidden");
    allPaginationItems[i].textContent = offset + i;
  }
  for (let i = numberOfUserPages + 1; i < offset + MAX_PAGINATION_LENGTH; ++i) {
    allPaginationItems[i - offset].classList.add("hidden");
  }
}

const NUMBER_OF_USER_PAGES_URL = "/admin/users/pages/total";
let numberOfUserPages = 0;

async function getNumberOfUserPages() {
  await fetch(
    NUMBER_OF_USER_PAGES_URL +
      "?" +
      new URLSearchParams({
        length: USER_LIST_LENGTH,
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

window.addEventListener("DOMContentLoaded", function (e) {
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
    })
  });

  const allPaginationSpecialLinks = document.querySelectorAll(".pagination-link--special");
  allPaginationSpecialLinks.forEach((item) => {
    let pageStartIndex = Number.parseInt(
      document.querySelector(".pagination-link--item").textContent
    );
    if (item.id === "next-link") {
      pageStartIndex += MAX_PAGINATION_LENGTH;
    } else if (item.id === "previous-link") {
      pageStartIndex -= MAX_PAGINATION_LENGTH;
    } else if (item.id === "start-link") {
      pageStartIndex = 1;
    } else {
      pageStartIndex = (numberOfUserPages - 1) * MAX_PAGINATION_LENGTH;
    }
    item.addEventListener("click", function (ev) {
      ev.preventDefault();
      renderPageIndexForPagination(pageStartIndex);
    });
  });
});

/**
 * 
 * @param {Array<MutationRecord>} mutationRecords 
 * @param {MutationObserver} observer 
 */
function handleUserTableBodyMutation(mutationRecords, observer) {
  const editUserModal = document.querySelector(".edit-user-modal");
  const allEditButtons = document.querySelectorAll(".btn--edit");
  const editEmailInput = document.querySelector("#edit-email-input");
  const editAvatarInput = document.querySelector("#edit-avatar-input");
  const editRoleSelect = document.querySelector("#edit-role-select");


  const editStatusSelect = document.querySelector("#edit-status-select");
  allEditButtons.forEach((btn, index) => {
    btn.addEventListener("click", function (ev) {
      ev.preventDefault();
      editUserModal.classList.remove("hidden");
      overlay.classList.remove("hidden");
      console.log(index, users[index]);
      editEmailInput.value = users[index].email;
      // editAvatarInput.v = users[index].avatar;
      editRoleSelect.value = users[index].role;
      editStatusSelect.value = users[index].status;
    })
  });

  const closeEditUserModalButton = document.querySelector(".btn--close-edit-modal");
  closeEditUserModalButton.addEventListener("click", function (ev) {
    editUserModal.classList.add("hidden");
    overlay.classList.add("hidden");
  });
  
  document.addEventListener("keydown", (ev) => {
    if (ev.key === "Escape" && ! editUserModal.classList.contains("hidden")) {
      editUserModal.classList.remove("hidden");
      overlay.classList.remove("hidden");
    }
  });
}

const tableBodyObserver = new MutationObserver(handleUserTableBodyMutation);
tableBodyObserver.observe(tableBody, {
  attributes: false,
  childList: true,
  subtree: false
});
// tableBodyObserver.disconnect();


