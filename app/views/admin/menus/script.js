"use strict";

/**OPEN AND CLOSE MODAL WINDOW WHEN CREATING A NEW USER */
const createMenuModal = document.querySelector(".new-menu-modal");
const overlay = document.querySelector(".overlay");

const openCreateMenuModalButton = document.querySelector(".btn--new-menu");
const closeCreateMenuModalButton = document.querySelector(".btn--close-create-modal") ;


function openCreateMenuModal() {
  createMenuModal.classList.remove("hidden");
  overlay.classList.remove("hidden");
}

function closeCreateMenuModal() {
  createMenuModal.classList.add("hidden");
  overlay.classList.add("hidden");
}

openCreateMenuModalButton.addEventListener("click", (e) => openCreateMenuModal());
closeCreateMenuModalButton.addEventListener("click", (e) => closeCreateMenuModal());
overlay.addEventListener("click", (e) => closeCreateMenuModalButton());
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && ! createMenuModal.classList.contains("hidden")) {
    closeCreateMenuModal();
  }
});

/*********************************************************
 * VALIDATE AND SEND USER DATA**************************** 
 * *******************************************************/

const CREATE_MENU_URL = "/admin/menus/new";
const MENU_NAME_PATTERN = /^[a-zA-Z][a-zA-Z0-9_\s]+$/;
const WRONG_MENU_NAME_PATTERN_MSG = "Wrong menu name pattern";
const REQUIRED_MENU_NAME_MSG = "Menu name is required";
const MAX_LENGTH_DESCRIPTION = 1000;
const TOO_LONG_DESCRIPTION_MSG = "Description is too long";
const NETWORK_ERROR_MSG = "Your network connection is not stable";

const createMenuNameInput = document.querySelector("#create-menu-name-input");
const createDescriptionTextArea = document.querySelector("#create-description-textarea");

/**
 * @param {HTMLInputElement} menuNameInput 
 * @returns {Promise<string>} 
 */
function validateMenuName(menuNameInput) {
  return new Promise((resolve, reject) => {
    if (!menuNameInput.value) {
      reject(REQUIRED_MENU_NAME_MSG);
    } else if (!menuNameInput.value.match(MENU_NAME_PATTERN)) {
      reject(WRONG_MENU_NAME_PATTERN_MSG);
    } else {
      resolve(menuNameInput.value);
    }
  });
}

/**
 * 
 * @param {HTMLTextAreaElement} descriptionTextarea 
 */
function validateDescription(descriptionTextarea) {
  return new Promise((resolve, reject) => {
    if (descriptionTextarea.value.length > MAX_LENGTH_DESCRIPTION) {
      reject(TOO_LONG_DESCRIPTION_MSG);
    } else {
      resolve(descriptionTextarea.value);
    }
  });
}

const createMenuModalForm = document.querySelector(".create-form");

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
        throw new Error(NETWORK_ERROR_MSG);
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

createMenuModalForm.addEventListener("submit", function (e) {
  e.preventDefault();
  
});

/*********************************************************
 *HANDLE INPUT GAINING FOUCS AND LOSING FOCUS EVENT******* 
 * *******************************************************/
let validatedMenuName = "";
let validatedDescription = "";

const createMenuNameError = document.querySelector("#create-menu-name-error");
const createDescriptionError = document.querySelector("#create-description-error");

createMenuNameInput.addEventListener("focus", function (e) {
  validateMenuName(this)
    .then((menuName) => {
      validatedMenuName = menuName;
      createMenuNameError.textContent = "";
    })
    .catch((msg) => {
      createMenuNameError.textContent = msg;
    })
});
createMenuNameInput.addEventListener("input", function (e) {
  validateMenuName(this)
    .then((menuName) => {
      validatedMenuName = menuName;
      createMenuNameError.textContent = "";
    })
    .catch((msg) => {
      createMenuNameError.textContent = msg;
    });
});
createMenuNameInput.addEventListener("focusout", function (e) {
  validateMenuName(this)
    .then((menuName) => {
      validatedMenuName = menuName;
      createMenuNameError.textContent = "";
    })
    .catch((msg) => {
      createMenuNameError.textContent = msg;
    });
});

createDescriptionTextArea.addEventListener("focus", function (e) {
  validateDescription(this)
    .then((description) => {
      validatedDescription = description;
      createDescriptionError.textContent = "";
    })
    .catch((msg) => {
      createDescriptionError.textContent = msg;
    })
});
createDescriptionTextArea.addEventListener("input", function (e) {
  validateDescription(this)
    .then((description) => {
      validatedDescription = description;
      createDescriptionError.textContent = "";
    })
    .catch((msg) => {
      createDescriptionError.textContent = msg;
    });
});
createDescriptionTextArea.addEventListener("focusout", function (e) {
  validateDescription(this)
    .then((description) => {
      validatedDescription = description;
      createDescriptionError.textContent = "";
    })
    .catch((msg) => {
      createDescriptionError.textContent = msg;
    });
});

/*********************************************************
 *FETCH SUMMARY FIGURE FROM SERVER************************ 
 * *******************************************************/
const SUMMARY_FIGURE_INTERVAL = 5000;
const summaryFigureOfMenus = document.querySelector(".summary-figure--menu");
const summaryFigureOfBestSellerMenu = document.querySelector(".summary-figure--bestseller-menu");
const summaryFigureOfHighestRatedMenu = document.querySelector(".summary-figure--highest-rated-menu");
const GET_MENU_FIGURE_URL = "/admin/menus/total";
const GET_MEMBER_FIGURE_URL = "/admin/users/members/total";
const GET_ACTIVE_FIGURE_URL = "/admin/users/active/total";

async function getSummaryFigureOfMenu() {
  await fetch(GET_MENU_FIGURE_URL, {
    method: "GET"
  }).then((response) => {
    if (! response.ok) {
      throw new Error(NETWORK_ERROR_MSG);
    }
    return response.json();
  }).then((data) => {
    summaryFigureOfMenus.textContent = data;
  }).catch((error) => {
    console.log(error.message);
  })
}
getSummaryFigureOfMenu();
setInterval(getSummaryFigureOfMenu, SUMMARY_FIGURE_INTERVAL);

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
      summaryFigureOfBestSellerMenu.textContent = data;
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




