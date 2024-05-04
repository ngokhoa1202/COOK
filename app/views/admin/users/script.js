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
const WRONG_PASSWORD_PATTERN_MSG = "Wrong password pattern";
const TOO_SHORT_PASSWORD_MSG = "At least 8 characters for password";
const WRONG_CONFIRM_PASSWORD_MSG = "Wrong connfirmed password";
const REQUIRED_ROLE_MSG = "Role is required";
const REQUIRED_STATUS_MSG = "Status is required";
const ROLE_VALUES = ["admin", "member"];
const STATUS_VALUES = ["active", "inactive"];
const WRONG_ROLE_MSG = "Wrong role value";
const WRONG_STATUS_MSG = "Wrong status value";
const NETWORK_ERROR_MSG = "Your network connection is not stable";

const createEmailInput = document.querySelector("#create-email-input");


/**
 * @param {HTMLInputElement} emailInput 
 * @returns {Promise}
 */
function validateEmail(emailInput) {
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


const createPasswordInput = document.querySelector("#create-password-input");
/**
 * @param {HTMLInputElement} passwordInput
 * @param {boolean} [empty=false]
 * @returns {Promise}
 */
function validatePassword(passwordInput, empty = false) {
  return new Promise((resolve, reject) => {
    if (empty && !passwordInput.value) {
      resolve("");
    } else if (!passwordInput.value) {
      reject(REQUIRED_PASSWORD_MSG);
    } else if (passwordInput.value.length < MIN_PASSWORD_LENGTH) {
      reject(TOO_SHORT_PASSWORD_MSG);
    } else if (!passwordInput.value.match(PASSWORD_PATTERN)) {
      reject(WRONG_PASSWORD_PATTERN_MSG);
    } else {
      resolve(passwordInput.value);
    }
  });
}

const createConfirmPasswordInput = document.querySelector("#create-confirm-password-input");
/**
 * @param {HTMLInputElement} confirmPasswordInput
 * @param {HTMLInputElement} passwordInput
 * @param {boolean} [empty=false]
 * @returns {Promise}
 */
function validateConfirmPassword(confirmPasswordInput, passwordInput, empty = false) {
  return new Promise((resolve, reject) => {
    if (empty && !confirmPasswordInput.value && !passwordInput.value) {
      resolve("");
    } else if (!confirmPasswordInput.value && passwordInput.value) {
      reject(REQUIRED_PASSWORD_MSG);
    } else if (confirmPasswordInput.value !== passwordInput.value) {
      reject(WRONG_CONFIRM_PASSWORD_MSG);
    } else {
      resolve(true);
    }
  });
}

const createRoleSelect = document.querySelector("#create-role-select");
/**
 * @param {HTMLSelectElement} roleSelect 
 * @returns {Promise}
 */
function validateRole(roleSelect) {
  return new Promise((resolve, reject) => {
    if (! roleSelect.value) {
      reject(REQUIRED_ROLE_MSG);
    } else if (! ROLE_VALUES.includes(roleSelect.value)) {
      reject(WRONG_ROLE_MSG);
    } else {
      resolve(roleSelect.value);
    }
  });
}

/**
 * 
 * @param {HTMLSelectElement} statusSelect 
 */
function validateStatus(statusSelect) {
  return new Promise((resolve, reject) => {
    if (! statusSelect.value) {
      reject(REQUIRED_STATUS_MSG);
    } else if (! STATUS_VALUES.includes(statusSelect.value)) {
      reject(WRONG_STATUS_MSG);
    } else {
      resolve(statusSelect.value);
    }
  });
}

const newUserModalForm = document.querySelector(".modal-form");
const createEmailError = document.querySelector("#create-email-error");
const createPasswordError = document.querySelector("#create-password-error");
const createConfirmPasswordError = document.querySelector("#create-confirm-password-error");
const createRoleError = document.querySelector("#create-role-error");

let validatedEmail = "";
let validatedPassword = "";
let matchedPassword = false;
let validatedRole = "";
/**
 * @param {string} url 
 * @param {FormData} formData
 */
function submitUserData(formData, url) {
  fetch(url, {
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
      getUserForPage(userPageIndex);
      Notification.requestPermission().then((notificationPermission) => {
        if (notificationPermission === "granted") {
          const sucessNotification = new Notification(data, {
            body: "The user has been successfully created or updated",
            tag: "success",
            icon: "/assets/img/success.svg"
          });
          
          sucessNotification.addEventListener("error", function (e) {
            alert(NETWORK_ERROR_MSG);
          });
          sucessNotification.addEventListener("click", function (e) {
            this.close();
            window.parent.focus();
          });
        }
      });
    })
    .catch((error) => {
      console.log(error.message);
    });
}

newUserModalForm.addEventListener("submit", function (e) {
  e.preventDefault();
  const emailValidatorPromise = validateEmail(createEmailInput)
    .then((email) => {
      validatedEmail = email;
      createEmailError.textContent = "";
    })
    .catch((msg) => {
      createEmailError.textContent = msg;
    })
    .finally(() => {});

  const passwordValidatorPromise = validatePassword(createPasswordInput)
    .then((password) => {
      validatedPassword = password;
      createPasswordError.textContent = "";
    })
    .catch((msg) => {
      createPasswordError.textContent = msg;
    })
    .finally(() => {});

  const confirmPasswordValidatorPromise = validateConfirmPassword(createConfirmPasswordInput, createPasswordInput)
    .then((isMatched) => {
      matchedPassword = isMatched;
      createConfirmPasswordError.textContent = "";
    })
    .catch((msg) => {
      createConfirmPasswordError.textContent = msg;
    })
    .finally(() => {});
  
  const roleValidatorPromise = validateRole(createRoleSelect).
    then((role) => {
      validatedRole = role;
    })
    .catch((msg) => {
      createRoleError.textContent = msg;
    })
    .finally(() => {});
  
  function handleNewUserData() {
    Promise.allSettled([
      emailValidatorPromise,
      passwordValidatorPromise,
      confirmPasswordValidatorPromise,
      roleValidatorPromise
    ]).then(() => {
      if (validatedEmail && validatedPassword && matchedPassword && validatedRole) {
        const formData = new FormData();
        formData.append("signup", true);
        formData.append("email", validatedEmail);
        formData.append("password", validatedPassword);
        formData.append("confirm_password", validatedPassword);
        formData.append("role", validatedRole);
        if (! newUserModal.classList.contains("hidden")) {
          newUserModal.classList.add("hidden");
          overlay.classList.add("hidden");
        }
        submitUserData(formData, CREATE_USER_URL);
      }
    });
  }
  handleNewUserData();
});

/*********************************************************
 *HANDLE INPUT GAINING FOUCS AND LOSING FOCUS EVENT******* 
 * *******************************************************/

createEmailInput.addEventListener("focus", function (e) {
  validateEmail(this)
    .then((email) => {
      validatedEmail = email;
      createEmailError.textContent = "";
    })
    .catch((msg) => {
      createEmailError.textContent = msg;
    })
    .finally(() => {});
});

createEmailInput.addEventListener("input", function(e) {
  validateEmail(this)
    .then((email) => {
      validatedEmail = email;
      createEmailError.textContent = "";
    })
    .catch((msg) => {
      createEmailError.textContent = msg;
    })
    .finally(() => {});
});

createEmailInput.addEventListener("focusout", function(e) {
  validateEmail(this)
    .then((email) => {
      validatedEmail = email;
      createEmailError.textContent = "";
    })
    .catch((msg) => {
      createEmailError.textContent = msg;
    })
    .finally(() => {});
});

createPasswordInput.addEventListener("focus", function(e) {
  validatePassword(this)
    .then((password) => {
      validatedPassword = password;
      createPasswordError.textContent = "";
    })
    .catch((msg) => {
      createPasswordError.textContent = msg;
    })
    .finally(() => {});
});

createPasswordInput.addEventListener("input", function(e) {
  validatePassword(this)
    .then((password) => {
      validatedPassword = password;
      createPasswordError.textContent = "";
    })
    .catch((msg) => {
      createPasswordError.textContent = msg;
    })
    .finally(() => {});
});

createPasswordInput.addEventListener("focusout", function(e) {
  validatePassword(this)
    .then((password) => {
      validatedPassword = password;
      createPasswordError.textContent = "";
    })
    .catch((msg) => {
      createPasswordError.textContent = msg;
    })
    .finally(() => {});
});

createConfirmPasswordInput.addEventListener("focus", function(e) {
  validateConfirmPassword(this, createPasswordInput)
    .then((isMatched) => {
      matchedPassword = isMatched;
      createConfirmPasswordError.textContent = "";
    })
    .catch((msg) => {
      createConfirmPasswordError.textContent = msg;
    })
    .finally(() => {});
});

createConfirmPasswordInput.addEventListener("input", function(e) {
  validateConfirmPassword(this, createPasswordInput)
    .then((isMatched) => {
      matchedPassword = isMatched;
      createConfirmPasswordError.textContent = "";
    })
    .catch((msg) => {
      createConfirmPasswordError.textContent = msg;
    })
    .finally(() => {});
});

createConfirmPasswordInput.addEventListener("focusout", function (e) {
  validateConfirmPassword(this, createPasswordInput)
    .then((isMatched) => {
      matchedPassword = isMatched;
      createConfirmPasswordError.textContent = "";
    })
    .catch((msg) => {
      createConfirmPasswordError.textContent = msg;
    })
    .finally(() => {});
});

createRoleSelect.addEventListener("change", function (e) {
  validateRole(this)
    .then((role) => {
      validatedRole = role;
      createRoleError.textContent = "";
    })
    .catch((msg) => {
      createRoleError.textContent = msg;
    })
    .finally(() => {});
});

createRoleSelect.addEventListener("focusout", function (e) {
  validateRole(this)
    .then((role) => {
      validatedRole = role;
      createRoleError.textContent = "";
    })
    .catch((msg) => {
      createRoleError.textContent = msg;
    })
    .finally(() => {});
})

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
    console.log(error.message);
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
      console.log(error.message);
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
      console.log(error.message);
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
      console.log(error.message);
    })
}
const USER_LIST_INTERVAL = 10000;
getUserForPage(userPageIndex);
setInterval(() => getUserForPage(userPageIndex), USER_LIST_INTERVAL);
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
  
  for (let i = paginationLength; i < MAX_PAGINATION_LENGTH; ++i) {
    allPaginationItems[i].classList.add("hidden");
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
      console.log(error.message);
    })
}


window.addEventListener("DOMContentLoaded", function (e) {

  getNumberOfUserPages().then(() => {
    renderPageIndexForPagination();
  });
});

window.addEventListener("load", (e) => {
  const allPaginationItems = document.querySelectorAll(".pagination-link--item");

  function removeAllActivePaginationItems() {
    allPaginationItems.forEach((item) => {
      item.classList.remove("pagination-link--active");
    });
  }
  allPaginationItems.forEach((item) => {
    item.addEventListener("click", function (ev) {
      ev.preventDefault();
      removeAllActivePaginationItems();
      this.classList.add("pagination-link--active");
      userPageIndex = Number.parseInt(item.textContent);
      getUserForPage(userPageIndex);
    })
  });

  const allPaginationSpecialLinks = document.querySelectorAll(".pagination-link--special");
  allPaginationSpecialLinks.forEach((item) => {
    let offset = Number.parseInt(
      document.querySelector(".pagination-link--item").textContent
    );
    if (item.id === "next-link") {
      offset += MAX_PAGINATION_LENGTH;
    } else if (item.id === "previous-link") {
      offset -= MAX_PAGINATION_LENGTH;
    } else if (item.id === "start-link") {
      offset = 1;
    } else {
      offset = Math.floor(numberOfUserPages / MAX_PAGINATION_LENGTH) * MAX_PAGINATION_LENGTH + 1;
    }
    item.addEventListener("click", function (ev) {
      ev.preventDefault();
      renderPageIndexForPagination(offset);
    });
  });
});

/*********************************************************
 *EDIT/DELETE USER INFO************************ 
 * *******************************************************/
/**
 * 
 * @param {Array<MutationRecord>} mutationRecords 
 * @param {MutationObserver} observer 
 */
function handleUserTableBodyMutation(mutationRecords, observer) {
  /*******EDIT USER************************************************** */
  const editIdInput = document.querySelector("#edit-id-input");
  const editEmailInput = document.querySelector("#edit-email-input");
  const editPasswordInput = document.querySelector("#edit-password-input");
  const editConfirmPasswordInput = document.querySelector("#edit-confirm-password-input");
  const editAvatarInput = document.querySelector("#edit-avatar-input");
  const editRoleSelect = document.querySelector("#edit-role-select");
  const editStatusSelect = document.querySelector("#edit-status-select");

  const editEmailError = document.querySelector("#edit-email-error");
  const editPasswordError = document.querySelector("#edit-password-error");
  const editConfirmPasswordError = document.querySelector("#edit-confirm-password-error");
  const editRoleError = document.querySelector("#edit-role-error");
  const editStatusError = document.querySelector("#edit-status-error");
  const editUserModal = document.querySelector(".edit-user-modal");
  const allEditButtons = document.querySelectorAll(".btn--edit");
  
  const EDT_USER_URL = "/admin/users/id";
  allEditButtons.forEach((btn, index) => {
    btn.addEventListener("click", function (ev) {
      ev.preventDefault();
      editUserModal.classList.remove("hidden");
      overlay.classList.remove("hidden");
      editIdInput.value = users[index].user_id;
      editEmailInput.value = users[index].email;
      // editAvatarInput.v = users[index].avatar;
      editRoleSelect.value = users[index].role;
      editStatusSelect.value = users[index].status;
    })
  });

  let validatedEmail = "";
  let validatedPassword = "";
  let matchedPassword = false;
  let validatedAvatar = "";
  let validatedRole = "";
  let validatedStatus = "";

  editEmailInput.addEventListener("focus", function (ev) {
    validateEmail(this)
      .then((email) => {
        validatedEmail = email;
        editEmailError.textContent = "";
      })
      .catch((msg) => {
        editEmailError.textContent = msg;
      })
      .finally(() => {});
  });
  editEmailInput.addEventListener("input", function (ev) {
    validateEmail(this)
      .then((email) => {
        validatedEmail = email;
        editEmailError.textContent = "";
      })
      .catch((msg) => {
        editEmailError.textContent = msg;
      })
      .finally(() => {});
  });
  editEmailInput.addEventListener("focusout", function (ev) {
    validateEmail(this)
      .then((email) => {
        validatedEmail = email;
        editEmailError.textContent = "";
      })
      .catch((msg) => {
        editEmailError.textContent = msg;
      })
      .finally(() => {});
  });

  editPasswordInput.addEventListener("focus", function (ev) {
    validatePassword(this, true)
      .then((password) => {
        validatedPassword = password;
        editPasswordError.textContent = "";
      })
      .catch((msg) => {
        editPasswordError.textContent = msg;
      })
      .finally(() => {});
  });

  editPasswordInput.addEventListener("input", function (ev) {
    validatePassword(this, true)
      .then((password) => {
        validatedPassword = password;
        editPasswordError.textContent = "";
      })
      .catch((msg) => {
        editPasswordError.textContent = msg;
      })
      .finally(() => {});
  });

  editPasswordInput.addEventListener("focusout", function (ev) {
    validatePassword(this, true)
      .then((password) => {
        validatedPassword = password;
        editPasswordError.textContent = "";
      })
      .catch((msg) => {
        editPasswordError.textContent = msg;
      })
      .finally(() => {});
  });

  editConfirmPasswordInput.addEventListener("focus", function (ev) {
    validateConfirmPassword(this, editPasswordInput, true)
      .then((isMatched) => {
        matchedPassword = isMatched;
        editConfirmPasswordError.textContent = "";
      })
      .catch((msg) => {
        editConfirmPasswordError.textContent = msg;
      })
      .finally(() => {});
  });

  editConfirmPasswordInput.addEventListener("input", function (ev) {
    validateConfirmPassword(this, editPasswordInput, true)
      .then((isMatched) => {
        matchedPassword = isMatched;
        editConfirmPasswordError.textContent = "";
      })
      .catch((msg) => {
        editConfirmPasswordError.textContent = msg;
      })
      .finally(() => {});
  });

  editConfirmPasswordInput.addEventListener("focusout", function (ev) {
    validateConfirmPassword(this, editPasswordInput, true)
      .then((isMatched) => {
        matchedPassword = isMatched;
        editConfirmPasswordError.textContent = "";
      })
      .catch((msg) => {
        editConfirmPasswordError.textContent = msg;
      })
      .finally(() => {});
  });

  editRoleSelect.addEventListener("focus", function (ev) {
    validateRole(this)
      .then((role) => {
        validatedRole = role;
        editRoleError.textContent = "";
      })
      .catch((msg) => {
        editEmailError.textContent = msg;
      })
      .finally(() => {});
  });

  editRoleSelect.addEventListener("input", function (ev) {
    validateRole(this)
      .then((role) => {
        validatedRole = role;
        editRoleError.textContent = "";
      })
      .catch((msg) => {
        editEmailError.textContent = msg;
      })
      .finally(() => {});
  });

  editRoleSelect.addEventListener("focusout", function (ev) {
    validateRole(this)
      .then((role) => {
        validatedRole = role;
        editRoleError.textContent = "";
      })
      .catch((msg) => {
        editEmailError.textContent = msg;
      })
      .finally(() => {});
  });

  const editUserForm = document.querySelector(".edit-form");
  editUserForm.addEventListener("submit", function (ev) {
    ev.preventDefault();
    const emailValidatorPromise = validateEmail(editEmailInput)
      .then((email) => {
        validatedEmail = email;
        editEmailError.textContent = "";
      })
      .catch((msg) => {
        editEmailError.textContent = msg;
      })
      .finally(() => {});
    const passwordValidatorPromise = validatePassword(editPasswordInput, true)
      .then((password) => {
        validatedPassword = password;
        editPasswordError.textContent = "";
      })
      .catch((msg) => {
        editPasswordError.textContent = msg;
      })
      .finally(() => {});
    const confirmPasswordValidatorPromise = validateConfirmPassword(editConfirmPasswordInput, editPasswordInput, true)
      .then((isMatched) => {
        matchedPassword = isMatched;
        editConfirmPasswordError.textContent = "";
      })
      .catch((msg) => {
        editConfirmPasswordError.textContent = msg;
      })
      .finally(() => {});
    const roleValidatorPromise = validateRole(editRoleSelect)
      .then((role) => {
        validatedRole = role;
        editRoleError.textContent = "";
      })
      .catch((msg) => {
        editRoleError.textContent = msg;
      })
      .finally(() => {});
    const statusValidatorPromise = validateStatus(editStatusSelect)
      .then((_status) => {
        validatedStatus = _status;
        editStatusError.textContent = "";
      })
      .catch((msg) => {
        editStatusError.textContent = msg;
      })
      .finally(() => {});
    
    function handleUserData() {
      Promise.all([emailValidatorPromise, passwordValidatorPromise, confirmPasswordValidatorPromise, 
      roleValidatorPromise, statusValidatorPromise])
        .then(() => {
          let validatedId = editIdInput.value;
          
          const formData = new FormData();
          formData.append("username", validatedEmail);
          formData.append("user_id", validatedId);
          formData.append("email", validatedEmail);
          formData.append("password", validatedPassword);
          formData.append("confirm_password", validatedPassword);
          formData.append("role", validatedRole);
          formData.append("status", validatedStatus);
          formData.append("avatar", validatedAvatar);
          if (! editUserModal.classList.contains("hidden")) {
            editUserModal.classList.add("hidden");
            overlay.classList.add("hidden");
          }
          submitUserData(formData, EDT_USER_URL);
        })
        .catch(() => {})
        .finally(() => {});
    }
    handleUserData();
  });
  
  const closeEditUserModalButton = document.querySelector(".btn--close-edit-modal");
  closeEditUserModalButton.addEventListener("click", function (ev) {
    editUserModal.classList.add("hidden");
    overlay.classList.add("hidden");
  });
  
  document.addEventListener("keydown", (ev) => {
    if (ev.key === "Escape" && ! editUserModal.classList.contains("hidden")) {
      editUserModal.classList.add("hidden");
      overlay.classList.add("hidden");
    }
  });

  /******DELETE USER************************************************** */
  const deleteUserModal = document.querySelector(".delete-user-modal");
  const closeDeleteUserModalButton = document.querySelector(".btn--close-delete-modal");
  closeDeleteUserModalButton.addEventListener("click", function (ev) {
    deleteUserModal.classList.add("hidden");
    overlay.classList.add("hidden");
  });
  document.addEventListener("keydown", (ev) => {
    if (ev.key === "Escape" && ! deleteUserModal.classList.contains("hidden")) {
      deleteUserModal.classList.add("hidden");
      overlay.classList.add("hidden");
    }
  });

  const allDeleteButtons = document.querySelectorAll(".btn--delete");
  allDeleteButtons.forEach((btn, index) => {
    btn.addEventListener("click", function (ev) {
      ev.preventDefault();
      deleteUserModal.classList.remove("hidden");
      overlay.classList.remove("hidden");
    });
  });
}

const tableBodyObserver = new MutationObserver(handleUserTableBodyMutation);
tableBodyObserver.observe(tableBody, {
  attributes: false,
  childList: true,
  subtree: false  
});

/*********************************************************
 *CONFIRM LEAVING FOR USER******************************** 
 * *******************************************************/
window.addEventListener("beforeunload", (e) => {
  e.preventDefault();
  e.returnValue = "Are you sure you want to leave this page?";
});




