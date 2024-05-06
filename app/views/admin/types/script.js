"use strict";

/** NAVIGATION LINK CLICK EVENT */


/**OPEN AND CLOSE MODAL WINDOW WHEN CREATING A NEW MENU */
const createTypeModal = document.querySelector(".new-type-modal");
const overlay = document.querySelector(".overlay");

const openCreateTypeModalButton = document.querySelector(".btn--new-type");
const closeCreateTypeModalButton = document.querySelector(".btn--close-create-modal");

const successNotificationModal = document.querySelector(".success-notification-modal");
const successNotificationMessage = document.querySelector(".success-notification-modal .notification");
const failureNotificationModal = document.querySelector(".failure-notification-modal");
const failureNotificationMessage = document.querySelector(".failure-notification-modal .notification");

function openCreateTypeModal() {
  createTypeModal.classList.remove("hidden");
  overlay.classList.remove("hidden");
}

function closeCreateTypeModal() {
  createTypeModal.classList.add("hidden");
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

openCreateTypeModalButton.addEventListener("click", (e) => openCreateTypeModal());
closeCreateTypeModalButton.addEventListener("click", (e) => closeCreateTypeModal());
overlay.addEventListener("click", (e) => {
  closeCreateTypeModal();
  closeSuccessNotificationModal();
});
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && ! createTypeModal.classList.contains("hidden")) {
    closeCreateTypeModal();
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

const CREATE_TYPE_URL = "/admin/types/new";
const TYPE_NAME_PATTERN = /^[a-zA-Z][a-zA-Z0-9_\s]+$/;
const CATEGORY_NAME_PATTERN = /^[a-zA-Z][a-zA-Z0-9_\s]+$/;
const REQUIRED_TYPE_NAME_MSG = "Type name is required";
const WRONG_TYPE_NAME_PATTERN_MSG = "Wrong type name pattern";
const WRONG_CATEGORY_NAME_PATTERN_MSG = "Wrong category name pattern";
const REQUIRED_CATEGORY_NAME_MSG = "Category name is required";
const REQUIRED_MENU_NAME_MSG = "Menu name is required";
const MENU_NAME_PATTERN = /^[a-zA-Z][a-zA-Z0-9_\s]+$/;
const WRONG_MENU_NAME_PATTERN_MSG = "Wrong menu name pattern";
const MAX_LENGTH_DESCRIPTION = 1000;
const TOO_LONG_DESCRIPTION_MSG = "Description is too long";
const NETWORK_ERROR_MSG = "Your network connection is not stable";

const createCategoryNameInput = document.querySelector("#create-category-name-input");
const createMenuNameInput = document.querySelector("#create-menu-name-input");
const createDescriptionTextArea = document.querySelector("#create-description-textarea");
const NOTIFICATION_TIMEOUT = 3000;

/**
 *
 * @param {HTMLInputElement} typeNameInput
 * @return {Promise<string>}
 */
function validateTypeName(typeNameInput) {
  return new Promise((resolve, reject) => {
    if (! typeNameInput.value) {
      reject(REQUIRED_TYPE_NAME_MSG);
    } else if (! TYPE_NAME_PATTERN.test(typeNameInput.value)) {
      reject(WRONG_TYPE_NAME_PATTERN_MSG);
    } else {
      resolve(typeNameInput.value);
    }
  });
}
/**
 * 
 * @param {HTMLInputElement} categoryNameInput
 * @returns {Promise<string>}
 */
function validateCategoryName(categoryNameInput) {
  return new Promise((resolve, reject) => {
    if (!categoryNameInput.value) {
      reject(REQUIRED_CATEGORY_NAME_MSG);
    } else if (! CATEGORY_NAME_PATTERN.test(categoryNameInput.value)) {
      reject(WRONG_CATEGORY_NAME_PATTERN_MSG);
    } else {
      resolve(categoryNameInput.value);
    }
  });
}

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

/**
 * 
 * @param {string} data 
 */
function displaySuccessNotification(data) {
  successNotificationMessage.textContent = data;
  openSuccessNotificationModal();
  setTimeout(closeSuccessNotificationModal, NOTIFICATION_TIMEOUT);
}

/**
 * 
 * @param {string} error 
 */
function displayFailureNotification(error) {
  failureNotificationMessage.textContent = error;
  openFailureNotificationModal();
  setTimeout(closeFailureNotificationModal, NOTIFICATION_TIMEOUT);
}

const createTypeModalForm = document.querySelector(".create-form");

/**
 * @param {string} url 
 * @param {FormData} formData
 */
function submitTypeData(formData, url) {
  fetch(url, {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.includes("successfully")) {
        displaySuccessNotification(data);
        getTypeForPage(categoryPageIndex);
      } else {
        displayFailureNotification(data);
      }
    });
}

createTypeModalForm.addEventListener("submit", function (e) {
  e.preventDefault();
  const categoryNameValidatorPromise = validateCategoryName(createCategoryNameInput)
    .then((categoryName) => {
      validatedCategoryName = categoryName;
      createCategoryNameError.textContent = "";
    })
    .catch((msg) => {
      createCategoryNameError.textContent = msg;
    });
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
  
  function handleCategoryData() {
    Promise.all([categoryNameValidatorPromise, menuNameValidatorPromise, descriptionValidatorPromise])
      .then(() => {
        if (validatedCategoryName && validatedMenuName) {
          const formData = new FormData();
          formData.append("category_name", validatedCategoryName);
          formData.append("menu_name", validatedMenuName);
          formData.append("description", validatedDescription);
          submitTypeData(formData, CREATE_TYPE_URL);
        }
      })
      .finally(() => {});
  }
  handleCategoryData();
});

/*********************************************************
 *HANDLE INPUT GAINING FOUCS AND LOSING FOCUS EVENT******* 
 * *******************************************************/
let validatedCategoryName = "";
let validatedMenuName = "";
let validatedDescription = "";

const createCategoryNameError = document.querySelector("#create-category-name-error");
const createMenuNameError = document.querySelector("#create-menu-name-error");
const createDescriptionError = document.querySelector("#create-description-error");

createCategoryNameInput.addEventListener("focus", function (e) {
  validateCategoryName(this)
    .then((categoryName) => {
      validatedCategoryName = categoryName;
      createCategoryNameError.textContent = "";
    })
    .catch((msg) => {
      createCategoryNameError.textContent = msg;
    });
});
createCategoryNameInput.addEventListener("input", function (e) {
  validateCategoryName(this)
    .then((categoryName) => {
      validatedCategoryName = categoryName;
      createCategoryNameError.textContent = "";
    })
    .catch((msg) => {
      createCategoryNameError.textContent = msg;
    });
});
createCategoryNameInput.addEventListener("focusout", function (e) {
  validateCategoryName(this)
    .then((categoryName) => {
      validatedCategoryName = categoryName;
      createCategoryNameError.textContent = "";
    })
    .catch((msg) => {
      createCategoryNameError.textContent = msg;
    });
});

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
const TYPE_LIST_INTERVAL = 10000;
const summaryFigureOfCategory = document.querySelector(".summary-figure--category");
const summaryFigureOfBestSellerCategory = document.querySelector(".summary-figure--bestseller-category");
const summaryFigureOfHighestRatedCategory = document.querySelector(".summary-figure--highest-rated-category");
const GET_CATEGORY_FIGURE_URL = "/admin/types/total";


async function getSummaryFigureOfCategory() {
  await fetch(GET_CATEGORY_FIGURE_URL, {
    method: "GET"
  }).then((response) => {
    if (! response.ok) {
      throw new Error(NETWORK_ERROR_MSG);
    }
    return response.json();
  }).then((data) => {
    summaryFigureOfCategory.textContent = data;
  }).catch((error) => {
    console.log(error.message);
  })
}

getSummaryFigureOfCategory();
setInterval(getSummaryFigureOfCategory, SUMMARY_FIGURE_INTERVAL);

/*********************************************************
 *FETCH SUMMARY FIGURE FROM SERVER************************ 
 * *******************************************************/
const GET_TYPE_URL = "/admin/types/list";
const TYPE_LIST_LENGTH = 10;
let categoryPageIndex = 1;

function removeOldTypeTableData() {
  let tableRow = null;
  while (tableRow = document.querySelector(".tbody tr")) {
    tableRow.remove();
  }
}

const tableBody = document.querySelector(".tbody");
let types = [];

/**
 * 
 * @param {int} pageIndex
 * @returns {Promise<void>}
 */
async function getTypeForPage(pageIndex) {
  await fetch(
    GET_TYPE_URL + "?" + new URLSearchParams({
      page: pageIndex,
      length: TYPE_LIST_LENGTH
    }), 
    {
      method: "GET"
    })
    .then((response) => {
      return response.json();
    }).then((data) => {
      types = data;
      removeOldTypeTableData();  
      types.forEach((type) => {
        const tableRow = document.createElement("tr");

        const tableDataForId = document.createElement("td");
        tableDataForId.textContent = type.type_id;
        tableRow.appendChild(tableDataForId);

        const tableDataForTypeName = document.createElement("td");
        tableDataForTypeName.textContent = type.type_name;
        tableRow.append(tableDataForTypeName);

        const tableDataForCategoryName = document.createElement("td");
        tableDataForCategoryName.textContent = type.category_name;
        tableRow.appendChild(tableDataForCategoryName);

        const tableDataForMenuName = document.createElement("td");
        tableDataForMenuName.textContent = type.menu_name;
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
      displayFailureNotification(error);
    });
}
getTypeForPage(categoryPageIndex);
setInterval(() => getTypeForPage(categoryPageIndex), TYPE_LIST_INTERVAL);

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
  if (numberOfCategoryPages === 0) {
    return;
  }
  if (offset < 1) {
    offset = 1;
  }
  const allPaginationItems = document.querySelectorAll(".pagination-link--item");
  if (MAX_PAGINATION_LENGTH > numberOfCategoryPages && numberOfCategoryPages > 0) {
    for (let i = numberOfCategoryPages; i < MAX_PAGINATION_LENGTH; ++i) {
      allPaginationItems[i].classList.add("hidden");
    }
    return;
  }
  
  let paginationLength = Math.min(MAX_PAGINATION_LENGTH, numberOfCategoryPages - offset + 1);
  for (let i = 0; i < paginationLength; ++i) {
    allPaginationItems[i].classList.remove("hidden");
    allPaginationItems[i].textContent = offset + i;
  }
  
  for (let i = paginationLength; i < MAX_PAGINATION_LENGTH; ++i) {
    allPaginationItems[i].classList.add("hidden");
  }
}

const NUMBER_OF_TYPE_PAGES_URL = "/admin/types/pages/total";
let numberOfCategoryPages = 0;

async function getNumberOTypePages() {
  await fetch(  
    NUMBER_OF_TYPE_PAGES_URL +
      "?" +
      new URLSearchParams({
        length: TYPE_LIST_LENGTH,
      }),
    {
      method: "GET",
    }
  )
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      numberOfCategoryPages = data;
    })
    .catch((error) => {
      displayFailureNotification(error);
    });
}
getNumberOTypePages();
setInterval(() => getNumberOTypePages(), TYPE_LIST_INTERVAL);

window.addEventListener("DOMContentLoaded", function (e) {
  getNumberOTypePages().then(() => {
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
      categoryPageIndex = Number.parseInt(item.textContent);
      console.log(categoryPageIndex);
      getTypeForPage(categoryPageIndex);
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
      offset = Math.floor(numberOfCategoryPages / MAX_PAGINATION_LENGTH) * MAX_PAGINATION_LENGTH + 1;
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
const EDIT_TYPE_URL = "/admin/types/update/id";
const DELETE_TYPE_URL = "/admin/types/delete/id";
function handleMenuTableBodyMutation(mutationRecords, observer) {
  /*******EDIT USER************************************************** */
  const editTypeModal = document.querySelector(".edit-type-modal");
  const editTypeNameInput = document.querySelector("#edit-type-name-input");
  const editCategoryNameInput = document.querySelector("#edit-category-name-input");
  const editIdInput = document.querySelector("#edit-id-input");
  const editMenuNameInput = document.querySelector("#edit-menu-name-input");
  const editDescriptionTextarea = document.querySelector("#edit-description-textarea");
  const allEditButtons = document.querySelectorAll(".btn--edit");

  function openEditTypeModal() {
    editTypeModal.classList.remove("hidden");
    overlay.classList.remove("hidden");
  }

  allEditButtons.forEach((btn, index) => {
    btn.addEventListener("click", function (ev) {
      ev.preventDefault();
      openEditTypeModal();
      editIdInput.value = types[index].type_id;
      editTypeNameInput.value = types[index].type_name;
      editCategoryNameInput.value = types[index].category_name;
      editMenuNameInput.value = types[index].menu_name;
      editDescriptionTextarea.innerHTML = types[index].description;
    })
  });
  let validatedTypeName = "";
  let validatedCategoryName = "";
  let validatedMenuName = "";
  let validatedDescription = "";
  const editTypeNameError = document.querySelector("#edit-type-name-error");
  editTypeNameInput.addEventListener("focus", function (e) {
    validateTypeName(this)
      .then((typeName) => {
        validatedTypeName = typeName;
        editTypeNameError.textContent = "";
      })
      .catch((msg) => {
        editTypeNameError.textContent = msg;
      });
  });
  editTypeNameInput.addEventListener("input", function (e) {
    validateTypeName(this)
      .then((typeName) => {
        validatedTypeName = typeName;
        editTypeNameError.textContent = "";
      })
      .catch((msg) => {
        editTypeNameError.textContent = msg;
      });
  });
  editTypeNameInput.addEventListener("focusout", function (e) {
    validateTypeName(this)
      .then((typeName) => {
        validatedTypeName = typeName;
        editTypeNameError.textContent = "";
      })
      .catch((msg) => {
        editTypeNameError.textContent = msg;
      });
  });
  const editCategoryNameError = document.querySelector("#edit-category-name-error");
  editCategoryNameInput.addEventListener("focus", function (e) {
    validateCategoryName(this)
      .then((categoryName) => {
        validatedCategoryName = categoryName;
        editCategoryNameError.textContent = "";
      })
      .catch((msg) => {
        editCategoryNameError.textContent = msg;
      });
  });
  editCategoryNameInput.addEventListener("input", function (e) {
    validateCategoryName(this)
      .then((categoryName) => {
        validatedCategoryName = categoryName;
        editCategoryNameError.textContent = "";
      })
      .catch((msg) => {
        editCategoryNameError.textContent = msg;
      });
  });
  editCategoryNameInput.addEventListener("focusout", function (e) {
    validateCategoryName(this)
      .then((categoryName) => {
        validatedCategoryName = categoryName;
        editCategoryNameError.textContent = "";
      })
      .catch((msg) => {
        editCategoryNameError.textContent = msg;
      });
  });

  const editMenuNameError = document.querySelector("#edit-menu-name-error");
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
   editDescriptionTextarea.addEventListener("focusout", function (e) {
     validateDescription(this)
       .then((description) => {
         validatedDescription = description;
         editDescriptionError.textContent = "";
       })
       .catch((msg) => {
         editDescriptionError.textContent = msg;
       });
   });

  const editTypeForm = document.querySelector(".edit-form");
  editTypeForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const typeNameValidatorPromise = validateTypeName(editTypeNameInput)
      .then((typeName) => {
        validatedTypeName = typeName;
        editTypeNameError.textContent = "";
      })
      .catch((msg) => {
        editTypeNameError.textContent = msg;
      });
    const categoryNameValidatorPromise = validateCategoryName(editCategoryNameInput)
      .then((categoryName) => {
        validatedCategoryName = categoryName;
        editCategoryNameError.textContent = "";
      })
      .catch((msg) => {
        editCategoryNameError.textContent = msg;
      });
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
    
    function handleCategoryData() {
      let validatedId = editIdInput.value;
      Promise.all([
        typeNameValidatorPromise,
        categoryNameValidatorPromise,
        menuNameValidatorPromise,
        descriptionValidatorPromise,
      ]).then(() => {
        if (validatedId && validatedTypeName && validatedMenuName && validatedCategoryName) {
          const formData = new FormData();
          formData.append("type_id", validatedId);
          formData.append("type_name", validatedTypeName);
          formData.append("category_name", validatedCategoryName);
          formData.append("menu_name", validatedMenuName);
          formData.append("description", validatedDescription);
          submitTypeData(formData, EDIT_TYPE_URL);
        }
      });
    }
    handleCategoryData();
  });

  function closeEditTypeModal() {
    editTypeModal.classList.add("hidden");
    overlay.classList.add("hidden");
  }
  
  const closeEditTypeModalButton = document.querySelector(".btn--close-edit-modal");
  closeEditTypeModalButton.addEventListener("click", function (ev) {
    closeEditTypeModal();
  });

  overlay.addEventListener("click", (ev) => {
    closeEditTypeModal();
  });
  
  document.addEventListener("keydown", (ev) => {
    if (ev.key === "Escape" && ! editTypeModal.classList.contains("hidden")) {
      closeEditTypeModal();
    }
  });

  /******DELETE USER************************************************** */
  const deleteTypeModal = document.querySelector(".delete-type-modal");
  const closeDeleteTypeModalButton = document.querySelector(".btn--close-delete-modal");
  const deleteIdInput = document.querySelector("#delete-id-input");
  const deleteTypeNameInput = document.querySelector("#delete-type-name-input");
  const deleteCategoryNameInput = document.querySelector("#delete-category-name-input");
  const deleteMenuNameInput = document.querySelector("#delete-menu-name-input"); 
  function closeDeleteCategoryModal() {
    deleteTypeModal.classList.add("hidden");
    overlay.classList.add("hidden");
  }

  function openDeleteTypeModal() {
    deleteTypeModal.classList.remove("hidden");
    overlay.classList.remove("hidden");
  }

  closeDeleteTypeModalButton.addEventListener("click", (e) => closeDeleteCategoryModal());
  document.addEventListener("keydown", (ev) => {
    if (ev.key === "Escape" && ! deleteTypeModal.classList.contains("hidden")) {
      closeDeleteCategoryModal();
    }
  });

  const allDeleteButtons = document.querySelectorAll(".btn--delete");
  allDeleteButtons.forEach((btn, index) => {
    btn.addEventListener("click", function (ev) {
      ev.preventDefault();
      openDeleteTypeModal();
      deleteIdInput.value = types[index].type_id;
      deleteTypeNameInput.value = types[index].type_name;
      deleteCategoryNameInput.value = types[index].category_name;
      deleteMenuNameInput.value = types[index].menu_name;
    });
  });
  let validatedTypeId = "";

  const deleteMenuForm = document.querySelector(".delete-form");
  deleteMenuForm.addEventListener("submit", function (e) {
    e.preventDefault();
    function handleCategoryData() {
      validatedTypeId = deleteIdInput.value;
      const formData = new FormData();
      formData.append("type_id", validatedTypeId);
      submitTypeData(formData, DELETE_TYPE_URL);
    }
    handleCategoryData();
  });
} 

const tableBodyObserver = new MutationObserver(handleMenuTableBodyMutation);
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




