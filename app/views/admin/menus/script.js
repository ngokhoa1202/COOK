"use strict";

/** NAVIGATION LINK CLICK EVENT */


/**OPEN AND CLOSE MODAL WINDOW WHEN CREATING A NEW MENU */
const createMenuModal = document.querySelector(".new-menu-modal");
const overlay = document.querySelector(".overlay");

const openCreateMenuModalButton = document.querySelector(".btn--new-menu");
const closeCreateMenuModalButton = document.querySelector(".btn--close-create-modal");

const successNotificationModal = document.querySelector(".success-notification-modal");
const successNotificationMessage = document.querySelector(".success-notification-modal .notification");
const failureNotificationModal = document.querySelector(".failure-notification-modal");
const failureNotificationMessage = document.querySelector(".failure-notification-modal .notification");

function openCreateMenuModal() {
  createMenuModal.classList.remove("hidden");
  overlay.classList.remove("hidden");
}

function closeCreateMenuModal() {
  createMenuModal.classList.add("hidden");
  overlay.classList.add("hidden");
}

function openSuccessNotificationModal() {
  successNotificationModal.classList.remove("hidden");
  overlay.classList.remove("hidden");
}

function closeSuccessNotificationModal() {
  successNotificationModal.classList.add("hidden");
  overlay.classList.add("hidden");
}

function openFailureNotificationModal() {
  failureNotificationModal.classList.remove("hidden");
  overlay.classList.remove("hidden");
}

function closeFailureNotificationModal() {
  failureNotificationModal.classList.add("hidden");
  overlay.classList.add("hidden");
}

openCreateCategoryModalButton.addEventListener("click", (e) => openCreateMenuModal());
closeCreateCategoryModalButton.addEventListener("click", (e) => closeCreateMenuModal());
overlay.addEventListener("click", (e) => {
  closeCreateMenuModal();
  closeSuccessNotificationModal();
});
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && ! createMenuModal.classList.contains("hidden")) {
    closeCreateMenuModal();
  }

  if (e.key === "Escape" && ! successNotificationModal.classList.contains("hidden")) {
    closeSuccessNotificationModal();
  }

  if (e.key === "Escape" && ! failureNotificationModal.classList.contains("hidden")) {
    closeFailureNotificationModal();
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
const NOTIFICATION_TIMEOUT = 3000;

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
      return response.json();
    })
    .then((data) => {
      successNotificationMessage.textContent = data;
      openSuccessNotificationModal();
      setTimeout(closeSuccessNotificationModal, NOTIFICATION_TIMEOUT);
      getMenuForPage(menuPageIndex);
    })
    .catch((error) => {
      failureNotificationMessage.textContent = error;
      openFailureNotificationModal();
      setTimeout(closeFailureNotificationModal, NOTIFICATION_TIMEOUT);
    });
}

createMenuModalForm.addEventListener("submit", function (e) {
  e.preventDefault();
  const menuNameValidatorPromise = validateMenuName(createMenuNameInput)
    .then((menuName) => {
      validatedMenuName = menuName;
      createMenuNameError.textContent = "";
    })
    .catch((msg) => {
      createMenuNameError.textContent = msg;
    });
  const descriptionValidatorPromise = validateDescription(createDescriptionTextArea)
    .then((description) => {
      validatedDescription = description;
      createDescriptionError.textContent = "";
    })
    .catch((msg) => {
      createDescriptionError.textContent = msg;
    });
  
  function handleMenuData() {
    Promise.all([menuNameValidatorPromise, descriptionValidatorPromise])
      .then(() => {
        if (validatedMenuName) {
          const formData = new FormData();
          formData.append("menu_name", validatedMenuName);
          formData.append("description", validatedDescription);
          submitUserData(formData, CREATE_MENU_URL);
        }
      })
      .finally(() => {});
  }
  handleMenuData();
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
    });
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

/*********************************************************
 *FETCH SUMMARY FIGURE FROM SERVER************************ 
 * *******************************************************/
const GET_MENU_URL = "/admin/menus/list";
const MENU_LIST_LENGTH = 10;
let menuPageIndex = 1;

function removeOldUserTableData() {
  let tableRow = null;
  while (tableRow = document.querySelector(".tbody tr")) {
    tableRow.remove();
  }
}

const tableBody = document.querySelector(".tbody");
let menus = [];

/**
 * 
 * @param {int} pageIndex
 * @returns {Promise<void>}
 */
async function getMenuForPage(pageIndex) {
  await fetch(
    GET_MENU_URL + "?" + new URLSearchParams({
      page: pageIndex,
      length: MENU_LIST_LENGTH
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
      menus = data;
      removeOldUserTableData();  
      menus.forEach((menu) => {
        const tableRow = document.createElement("tr");

        const tableDataForId = document.createElement("td");
        tableDataForId.textContent = menu.menu_id;
        tableRow.appendChild(tableDataForId);

        const tableDataForMenuName = document.createElement("td");
        tableDataForMenuName.textContent = menu.menu_name;
        tableRow.appendChild(tableDataForMenuName);
      
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
const MENU_LIST_INTERVAL = 10000;
getMenuForPage(menuPageIndex);
setInterval(() => getMenuForPage(menuPageIndex), MENU_LIST_INTERVAL);
/*********************************************************
 *PAGINATION************************ 
 * *******************************************************/
const pagination = document.querySelector(".pagination");

const MAX_PAGINATION_LENGTH = 5;
/**
 * 
 * @param {Number} offset 
 * @returns {void}
 */
function renderPageIndexForPagination(offset = 1) {
  if (numberOfMenuPages === 0) {
    return;
  }
  if (offset < 1) {
    offset = 1;
  }
  const allPaginationItems = document.querySelectorAll(".pagination-link--item");
  if (MAX_PAGINATION_LENGTH > numberOfMenuPages && numberOfMenuPages > 0) {
    for (let i = numberOfMenuPages; i < MAX_PAGINATION_LENGTH; ++i) {
      allPaginationItems[i].classList.add("hidden");
    }
    return;
  }
  
  let paginationLength = Math.min(MAX_PAGINATION_LENGTH, numberOfMenuPages - offset + 1);
  for (let i = 0; i < paginationLength; ++i) {
    allPaginationItems[i].classList.remove("hidden");
    allPaginationItems[i].textContent = offset + i;
  }
  
  for (let i = paginationLength; i < MAX_PAGINATION_LENGTH; ++i) {
    allPaginationItems[i].classList.add("hidden");
  }
}

const NUMBER_OF_MENU_PAGES_URL = "/admin/menus/pages/total";
let numberOfMenuPages = 0;

async function getNumberOfMenuPages() {
  await fetch(  
    NUMBER_OF_MENU_PAGES_URL +
      "?" +
      new URLSearchParams({
        length: MENU_LIST_LENGTH,
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
      numberOfMenuPages = data;
    })
    .catch((error) => {
      console.log(error.message);
    })
}


window.addEventListener("DOMContentLoaded", function (e) {

  getNumberOfMenuPages().then(() => {
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
      menuPageIndex = Number.parseInt(item.textContent);
      getMenuForPage(menuPageIndex);
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
      offset = Math.floor(numberOfMenuPages / MAX_PAGINATION_LENGTH) * MAX_PAGINATION_LENGTH + 1;
    }
    item.addEventListener("click", function (ev) {
      ev.preventDefault();
      renderPageIndexForPagination(offset);
    });
  });
});

/*********************************************************
 *EDIT/DELETE MENU INFO************************ 
 * *******************************************************/
/**
 * 
 * @param {Array<MutationRecord>} mutationRecords 
 * @param {MutationObserver} observer 
 */
const EDIT_MENU_URL = "/admin/menus/update/id";
const DELETE_MENU_URL = "/admin/menus/delete/id";
function handleProductTableBodyMutation(mutationRecords, observer) {
  /*******EDIT USER************************************************** */
  const editMenuModal = document.querySelector(".edit-menu-modal");
  const editIdInput = document.querySelector("#edit-id-input");
  const editMenuNameInput = document.querySelector("#edit-menu-name-input");
  const editDescriptionTextarea = document.querySelector("#edit-description-textarea");
  const allEditButtons = document.querySelectorAll(".btn--edit");
  allEditButtons.forEach((btn, index) => {
    btn.addEventListener("click", function (ev) {
      ev.preventDefault();
      editMenuModal.classList.remove("hidden");
      overlay.classList.remove("hidden");
      editIdInput.value = menus[index].menu_id;
      editMenuNameInput.value = menus[index].menu_name;
      editDescriptionTextarea.innerHTML = menus[index].description;
    })
  });
  
  let validatedMenuName = "";
  let validatedDescription = "";
  const editMenuNameError =  document.querySelector("#edit-menu-name-error");
  editMenuNameInput.addEventListener("focus", function (e) {
    validateMenuName(this)
      .then((menuName) => {
        validatedMenuName = menuName;
        editMenuNameError.textContent = "";
      })
      .catch((msg) => {
        editMenuNameError.textContent = msg;
      });
  });
  editMenuNameInput.addEventListener("input", function (e) {
    validateMenuName(this)
      .then((menuName) => {
        validatedMenuName = menuName;
        editMenuNameError.textContent = "";
      })
      .catch((msg) => {
        editMenuNameError.textContent = msg;
      });
  });
  editMenuNameInput.addEventListener("focusout", function (e) {
    validateMenuName(this)
      .then((menuName) => {
        validatedMenuName = menuName;
        editMenuNameError.textContent = "";
      })
      .catch((msg) => {
        editMenuNameError.textContent = msg;
      });
  });
  
  const editDescriptionError = document.querySelector("#edit-description-error");
  editDescriptionTextarea.addEventListener("focus", function (e) {
    validateDescription(this)
      .then((description) => {
        validatedDescription = description;
        editDescriptionError.textContent = "";
      })
      .catch((msg) => {
        editDescriptionError.textContent = msg;
      });
  });

  const editMenuForm = document.querySelector(".edit-form");
  editMenuForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const menuNameValidatorPromise = validateMenuName(editMenuNameInput)
      .then((menuName) => {
        validatedMenuName = menuName;
        editMenuNameError.textContent = "";
      })
      .catch((msg) => {
        editMenuNameError.textContent = msg;
      });
    const descriptionValidatorPromise = validateDescription(editDescriptionTextarea)
      .then((description) => {
        validatedDescription = description;
        editDescriptionError.textContent = "";
      })
      .catch((msg) => {
        editDescriptionError.textContent = msg;
      });
    
    function handleMenuData() {
      let validatedId = editIdInput.value;
      Promise.all([menuNameValidatorPromise, descriptionValidatorPromise])
        .then(() => {
          if (validatedId && validatedMenuName) {
            const formData = new FormData();
            formData.append("menu_id", validatedId);
            formData.append("menu_name", validatedMenuName);
            formData.append("description", validatedDescription);
            submitUserData(formData, EDIT_MENU_URL);
          }
        });
    }
    handleMenuData();
  });

  function closeEditMenuModal() {
    editMenuModal.classList.add("hidden");
    overlay.classList.add("hidden");
  }
  
  const closeMenuModalButton = document.querySelector(".btn--close-edit-modal");
  closeMenuModalButton.addEventListener("click", function (ev) {
    closeEditMenuModal();
  });

  overlay.addEventListener("click", (ev) => {
    closeEditMenuModal();
  });
  
  document.addEventListener("keydown", (ev) => {
    if (ev.key === "Escape" && ! editMenuModal.classList.contains("hidden")) {
      closeEditMenuModal();
    }
  });

  /******DELETE USER************************************************** */
  const deleteMenuModal = document.querySelector(".delete-menu-modal");
  const closeDeleteUserModalButton = document.querySelector(".btn--close-delete-modal");
  const deleteMenuIdInput = document.querySelector("#delete-id-input");
  const deleteMenuNameInput = document.querySelector("#delete-menu-name-input"); 
  function closeDeleteMenuModal() {
    deleteMenuModal.classList.add("hidden");
    overlay.classList.add("hidden");
  }

  function openDeleteMenuModal() {
    deleteMenuModal.classList.remove("hidden");
    overlay.classList.remove("hidden");
  }

  closeDeleteUserModalButton.addEventListener("click", (e) => closeDeleteMenuModal());
  document.addEventListener("keydown", (ev) => {
    if (ev.key === "Escape" && ! deleteMenuModal.classList.contains("hidden")) {
      closeDeleteMenuModal();
    }
  });

  const allDeleteButtons = document.querySelectorAll(".btn--delete");
  allDeleteButtons.forEach((btn, index) => {
    btn.addEventListener("click", function (ev) {
      ev.preventDefault();
      openDeleteMenuModal();
      deleteMenuIdInput.value = menus[index].menu_id;
      deleteMenuNameInput.value = menus[index].menu_name;
    });
  });
  let validatedMenuId = "";

  const deleteMenuForm = document.querySelector(".delete-form");
  deleteMenuForm.addEventListener("submit", function (e) {
    e.preventDefault();
    console.log("submitted");
    function handleMenuData() {
      validatedMenuId = deleteMenuIdInput.value;
      const formData = new FormData();
      formData.append("menu_id", validatedMenuId);
      submitUserData(formData, DELETE_MENU_URL);
    }
    handleMenuData();
  });
} 

const tableBodyObserver = new MutationObserver(handleProductTableBodyMutation);
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




