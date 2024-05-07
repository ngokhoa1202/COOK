"use strict";

/** NAVIGATION LINK CLICK EVENT */


/**OPEN AND CLOSE MODAL WINDOW WHEN CREATING A NEW MENU */
const createProductModal = document.querySelector(".new-product-modal");
const overlay = document.querySelector(".overlay");

const openCreateProductModalButton = document.querySelector(".btn--new-product");
const closeCreateProductModalButton = document.querySelector(".btn--close-create-modal");

const successNotificationModal = document.querySelector(".success-notification-modal");
const successNotificationMessage = document.querySelector(".success-notification-modal .notification");
const failureNotificationModal = document.querySelector(".failure-notification-modal");
const failureNotificationMessage = document.querySelector(".failure-notification-modal .notification");

function openCreateProductModal() {
  createProductModal.classList.remove("hidden");
  overlay.classList.remove("hidden");
}

function closeCreateProductModal() {
  createProductModal.classList.add("hidden");
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

openCreateProductModalButton.addEventListener("click", (e) => openCreateProductModal());
closeCreateProductModalButton.addEventListener("click", (e) => closeCreateProductModal());
overlay.addEventListener("click", (e) => {
  closeCreateProductModal();
  closeSuccessNotificationModal();
});
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && ! createProductModal.classList.contains("hidden")) {
    closeCreateProductModal();
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

const CREATE_PRODUCT_URL = "/admin/products/new";
const PRODUCT_NAME_PATTERN = /^[a-zA-Z][a-zA-Z0-9_\s]+$/;
const TYPE_NAME_PATTERN = /^[a-zA-Z][a-zA-Z0-9_\s]+$/;
const CATEGORY_NAME_PATTERN = /^[a-zA-Z][a-zA-Z0-9_\s]+$/;
const WRONG_CATEGORY_NAME_PATTERN_MSG = "Wrong category name pattern";
const REQUIRED_PRODUCT_NAME_MSG = "Product name is required";
const REQUIRED_CATEGORY_NAME_MSG = "Category name is required";
const REQUIRED_TYPE_NAME_MSG = "Type name is required";
const WRONG_TYPE_NAME_PATTERN_MSG = "Wrong type name pattern";
const WRONG_PRODUCT_NAME_PATTERN = "Wrong product name pattern";
const REQUIRED_MENU_NAME_MSG = "Menu name is required";
const MENU_NAME_PATTERN = /^[a-zA-Z][a-zA-Z0-9_\s]+$/;
const WRONG_MENU_NAME_PATTERN_MSG = "Wrong menu name pattern";
const MAX_LENGTH_DESCRIPTION = 1000;
const TOO_LONG_DESCRIPTION_MSG = "Description is too long";
const NETWORK_ERROR_MSG = "Your network connection is not stable";

const createProductNameInput = document.querySelector("#create-product-name-input");
const createTypeNameInput = document.querySelector("#create-type-name-input");
const createCategoryNameInput = document.querySelector("#create-category-name-input");
const createMenuNameInput = document.querySelector("#create-menu-name-input");
const createDescriptionTextArea = document.querySelector("#create-description-textarea");
const NOTIFICATION_TIMEOUT = 3000;

/**
 * 
 * @param {HTMLInputElement} productNameInput 
 * @returns 
 */
function validateProductName(productNameInput) {
  return new Promise((resolve, reject) => {
    if (! productNameInput.value) {
      reject(REQUIRED_PRODUCT_NAME_MSG);
    } else if (! PRODUCT_NAME_PATTERN.test(productNameInput.value)) {
      reject(WRONG_PRODUCT_NAME_PATTERN);
    } else {
      resolve(productNameInput.value);
    }
  });
}
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
function validateCategoryNameInput(categoryNameInput) {
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

const createProductModalForm = document.querySelector(".create-form");

/**
 * @param {string} url 
 * @param {FormData} formData
 */
function submitProductData(formData, url) {
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
        getProductForPage(productPageIndex);
      } else {
        displayFailureNotification(data);
      }

    });
}

createProductModalForm.addEventListener("submit", function (e) {
  e.preventDefault();
  const productNameValidatorPromise = validateProductName(createProductNameInput)
    .then((productName) => {
      let validated
    })
  const categoryNameValidatorPromise = validateCategoryNameInput(createCategoryNameInput)
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
  
  function handleProductData() {
    Promise.all([categoryNameValidatorPromise, menuNameValidatorPromise, descriptionValidatorPromise])
      .then(() => {
        if (validatedProductName && validatedCategoryName && validatedMenuName && validatedTypeName) {
          const formData = new FormData();
          formData.append("product_name", validatedProductName);
          formData.append("type_name", validatedTypeName);
          formData.append("category_name", validatedCategoryName);
          formData.append("menu_name", validatedMenuName);
          formData.append("description", validatedDescription);
          submitProductData(formData, CREATE_PRODUCT_URL);
        }
      })
      .finally(() => {});
  }
  handleProductData();
});

/*********************************************************
 *HANDLE INPUT GAINING FOUCS AND LOSING FOCUS EVENT******* 
 * *******************************************************/
let validatedProductName = "";
let validatedTypeName = "";
let validatedCategoryName = "";
let validatedMenuName = "";
let validatedDescription = "";

const createProductNameError = document.querySelector("#create-product-name-error");
const createTypeNameError = document.querySelector("#create-type-name-error");
const createCategoryNameError = document.querySelector("#create-category-name-error");
const createMenuNameError = document.querySelector("#create-menu-name-error");
const createDescriptionError = document.querySelector("#create-description-error");

createProductNameInput.addEventListener("focus", function (e) {
  validateProductName(this)
    .then((productName) => {
      validatedProductName = productName;
      createProductNameError.textContent = "";
    })
    .catch((msg) => {
      createProductNameError.textContent = msg;
    });
});
createProductNameInput.addEventListener("input", function (e) {
  validateProductName(this)
    .then((productName) => {
      validatedProductName = productName;
      createProductNameError.textContent = "";
    })
    .catch((msg) => {
      createProductNameError.textContent = msg;
    });
});
createProductNameInput.addEventListener("focusout", function (e) {
  validateProductName(this)
    .then((productName) => {
      validatedProductName = productName;
      createProductNameError.textContent = "";
    })
    .catch((msg) => {
      createProductNameError.textContent = msg;
    });
});

createTypeNameInput.addEventListener("focus", function (e) {
  validateTypeName(this)
    .then((typeName) => {
      validatedTypeName = typeName;
      createTypeNameError.textContent = "";
    })
    .catch((msg) => {
      createTypeNameError.textContent = msg;
    })
});
createTypeNameInput.addEventListener("input", function (e) {
  validateTypeName(this)
    .then((typeName) => {
      validatedTypeName = typeName;
      createTypeNameError.textContent = "";
    })
    .catch((msg) => {
      createTypeNameError.textContent = msg;
    });
});
createTypeNameInput.addEventListener("focusout", function (e) {
  validateTypeName(this)
    .then((typeName) => {
      validatedTypeName = typeName;
      createTypeNameError.textContent = "";
    })
    .catch((msg) => {
      createTypeNameError.textContent = msg;
    });
});

createCategoryNameInput.addEventListener("focus", function (e) {
  validateCategoryNameInput(this)
    .then((categoryName) => {
      validatedCategoryName = categoryName;
      createCategoryNameError.textContent = "";
    })
    .catch((msg) => {
      createCategoryNameError.textContent = msg;
    });
});
createCategoryNameInput.addEventListener("input", function (e) {
  validateCategoryNameInput(this)
    .then((categoryName) => {
      validatedCategoryName = categoryName;
      createCategoryNameError.textContent = "";
    })
    .catch((msg) => {
      createCategoryNameError.textContent = msg;
    });
});
createCategoryNameInput.addEventListener("focusout", function (e) {
  validateCategoryNameInput(this)
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
const PRODUCT_LIST_INTERVAL = 10000;
const summaryFigureOfProduct = document.querySelector(".summary-figure--product");
const summaryFigureOfBestSellerProduct = document.querySelector(".summary-figure--bestseller-product");
const summaryFigureOfHighestRatedProduct = document.querySelector(".summary-figure--highest-rated-product");
const GET_PRODUCT_FIGURE_URL = "/admin/products/total";


async function getSummaryFigureOfProduct() {
  await fetch(GET_PRODUCT_FIGURE_URL, {
    method: "GET"
  }).then((response) => {
    return response.json();
  }).then((data) => {
    summaryFigureOfProduct.textContent = data;
  }).catch((error) => {
    console.log(error);
  })
}

getSummaryFigureOfProduct();
setInterval(getSummaryFigureOfProduct, SUMMARY_FIGURE_INTERVAL);

/*********************************************************
 *FETCH SUMMARY FIGURE FROM SERVER************************ 
 * *******************************************************/
const GET_PRODUCT_URL = "/admin/products/list";
const PRODUCT_LIST_LENGTH = 10;
let productPageIndex = 1;

function removeOldProductTableData() {
  let tableRow = null;
  while (tableRow = document.querySelector(".tbody tr")) {
    tableRow.remove();
  }
}

const tableBody = document.querySelector(".tbody");
let products = [];

/**
 * 
 * @param {int} pageIndex
 * @returns {Promise<void>}
 */
async function getProductForPage(pageIndex) {
  await fetch(
    GET_PRODUCT_URL + "?" + new URLSearchParams({
      page: pageIndex,
      length: PRODUCT_LIST_LENGTH
    }), 
    {
      method: "GET"
    })
    .then((response) => {
      return response.json();
    }).then((data) => {
      products = data;
      removeOldProductTableData();  
      products.forEach((product) => {
        const tableRow = document.createElement("tr");

        const tableDataForId = document.createElement("td");
        tableDataForId.textContent = product.product_id;
        tableRow.appendChild(tableDataForId);

        const tableDataForProductName = document.createElement("td");
        tableDataForProductName.textContent = product.product_name;
        tableRow.appendChild(tableDataForProductName);

        const tableDataForMenuName = document.createElement("td");
        tableDataForMenuName.textContent = product.menu_name;
        tableRow.appendChild(tableDataForMenuName);

        const tableDataForCategoryName = document.createElement("td");
        tableDataForCategoryName.textContent = product.category_name;
        tableRow.appendChild(tableDataForCategoryName);

        const tableDataForTypeName = document.createElement("td");
        tableDataForTypeName.textContent = product.type_name;
        tableRow.appendChild(tableDataForTypeName);

        
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
      // displayFailureNotification(error);
    });
}
getProductForPage(productPageIndex);
setInterval(() => getProductForPage(productPageIndex), PRODUCT_LIST_INTERVAL);

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
  if (numberOfProductPages === 0) {
    return;
  }
  if (offset < 1) {
    offset = 1;
  }
  const allPaginationItems = document.querySelectorAll(".pagination-link--item");
  if (MAX_PAGINATION_LENGTH > numberOfProductPages && numberOfProductPages > 0) {
    for (let i = numberOfProductPages; i < MAX_PAGINATION_LENGTH; ++i) {
      allPaginationItems[i].classList.add("hidden");
    }
    return;
  }
  
  let paginationLength = Math.min(MAX_PAGINATION_LENGTH, numberOfProductPages - offset + 1);
  for (let i = 0; i < paginationLength; ++i) {
    allPaginationItems[i].classList.remove("hidden");
    allPaginationItems[i].textContent = offset + i;
  }
  
  for (let i = paginationLength; i < MAX_PAGINATION_LENGTH; ++i) {
    allPaginationItems[i].classList.add("hidden");
  }
}

const NUMBER_OF_PRODUCT_PAGES_URL = "/admin/products/pages/total";
let numberOfProductPages = 0;

async function getNumberOfProductPages() {
  await fetch(  
    NUMBER_OF_PRODUCT_PAGES_URL +
      "?" +
      new URLSearchParams({
        length: PRODUCT_LIST_LENGTH,
      }),
    {
      method: "GET",
    }
  )
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      numberOfProductPages = data;
    })
    .catch((error) => {
      displayFailureNotification(error);
    });
}
getNumberOfProductPages();
setInterval(() => getNumberOfProductPages(), PRODUCT_LIST_INTERVAL);

window.addEventListener("DOMContentLoaded", function (e) {
  getNumberOfProductPages().then(() => {
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
      productPageIndex = Number.parseInt(item.textContent);
      getProductForPage(productPageIndex);
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
      offset = Math.floor(numberOfProductPages / MAX_PAGINATION_LENGTH) * MAX_PAGINATION_LENGTH + 1;
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
const EDIT_PRODUCT_URL = "/admin/products/update/id";
const DELETE_PRODUCT_URL = "/admin/products/delete/id";
function handleCategoryTableBodyMutation(mutationRecords, observer) {
  /*******EDIT USER************************************************** */
  const editProductModal = document.querySelector(".edit-product-modal");
  const editCategoryNameInput = document.querySelector("#edit-category-name-input");
  const editIdInput = document.querySelector("#edit-id-input");
  const editProductNameInput = document.querySelector(
    "#edit-product-name-input"
  );
  const editTypeNameInput = document.querySelector("#edit-type-name-input");
  const editMenuNameInput = document.querySelector("#edit-menu-name-input");
  const editDescriptionTextarea = document.querySelector("#edit-description-textarea");
  const allEditButtons = document.querySelectorAll(".btn--edit");

  function openEditProductModal() {
    editProductModal.classList.remove("hidden");
    overlay.classList.remove("hidden");
  }

  allEditButtons.forEach((btn, index) => {
    btn.addEventListener("click", function (ev) {
      ev.preventDefault();
      openEditProductModal();
      editIdInput.value = products[index].product_id;
      editProductNameInput.value = products[index].product_name;
      editTypeNameInput.value = products[index].type_name;
      editCategoryNameInput.value = products[index].category_name;
      editMenuNameInput.value = products[index].menu_name;
      editDescriptionTextarea.innerHTML = products[index].description;

    })
  });
  let validatedProductName = "";
  let validatedTypeName = "";
  let validatedCategoryName = "";
  let validatedMenuName = "";
  let validatedDescription = "";
  const editProductNameError = document.querySelector("#edit-product-name-error");
  editProductNameInput.addEventListener("focus", function (e) {
    validateProductName(editProductNameInput)
      .then((productName) => {
        validatedProductName = productName;
        editProductNameError.textContent = "";
      })
      .catch((msg) => {
        editProductNameError.textContent = msg;
      });
  });
  editProductNameInput.addEventListener("input", function (e) {
    validateProductName(this)
      .then((productName) => {
        validatedProductName = productName;
        editProductNameError.textContent = "";
      })
      .catch((msg) => {
        editProductNameError.textContent = msg;
      });
  });
  editProductNameInput.addEventListener("focusout", function (e) {
    validateProductName(editProductNameInput)
      .then((productName) => {
        validatedProductName = productName;
        editProductNameError.textContent = "";
      })
      .catch((msg) => {
        editProductNameError.textContent = msg;
      });
  });

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
    validateCategoryNameInput(this)
      .then((categoryName) => {
        validatedCategoryName = categoryName;
        editCategoryNameError.textContent = "";
      })
      .catch((msg) => {
        editCategoryNameError.textContent = msg;
      });
  });
  editCategoryNameInput.addEventListener("input", function (e) {
    validateCategoryNameInput(this)
      .then((categoryName) => {
        validatedCategoryName = categoryName;
        editCategoryNameError.textContent = "";
      })
      .catch((msg) => {
        editCategoryNameError.textContent = msg;
      });
  });
  editCategoryNameInput.addEventListener("focusout", function (e) {
    validateCategoryNameInput(this)
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

  const editProductForm = document.querySelector(".edit-form");
  editProductForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const productNameValidatorPromise = validateProductName(editProductNameInput)
      .then((productName) => {
        validatedProductName = productName;
        editProductNameError.textContent = "";
      })
      .catch((msg) => {
        editProductNameError.textContent = msg;
      });
    
    const typeNameValidatorPromise = validateTypeName(editTypeNameInput)
      .then((typeName) => {
        validatedTypeName = typeName;
        editTypeNameError.textContent = "";
      })
      .catch((msg) => {
        editTypeNameError.textContent = msg;
      });
  
    const categoryNameValidatorPromise = validateCategoryNameInput(editCategoryNameInput)
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
    
    function handleProductData() {
      let validatedId = editIdInput.value;
      Promise.all([
        productNameValidatorPromise,
        typeNameValidatorPromise,
        categoryNameValidatorPromise,
        menuNameValidatorPromise,
        descriptionValidatorPromise
      ]).then(() => {
        if (validatedId && validatedMenuName && validatedCategoryName && validatedTypeName) {
          const formData = new FormData()
          formData.append("menu_id", validatedId);
          formData.append("menu_name", validatedMenuName);
          formData.append("category_name", validatedCategoryName);
          formData.append("type_name", validatedTypeName);
          formData.append("description", validatedDescription);
          submitProductData(formData, EDIT_PRODUCT_URL);
        }
      });
    }
    handleProductData();
  });

  function closeEditProductModal() {
    editProductModal.classList.add("hidden");
    overlay.classList.add("hidden");
  }
  
  const closeProductModalButton = document.querySelector(".btn--close-edit-modal");
  closeProductModalButton.addEventListener("click", function (ev) {
    closeEditProductModal();
  });

  overlay.addEventListener("click", (ev) => {
    closeEditProductModal();
  });
  
  document.addEventListener("keydown", (ev) => {
    if (ev.key === "Escape" && ! editProductModal.classList.contains("hidden")) {
      closeEditProductModal();
    }
  });

  /******DELETE USER************************************************** */
  const deleteProductModal = document.querySelector(".delete-product-modal");
  const closeDeleteProductModalButton = document.querySelector(".btn--close-delete-modal");
  const deleteIdInput = document.querySelector("#delete-id-input");
  const deleteProductNameInput = document.querySelector("#delete-product-name-input");
  const deleteTypeNameInput = document.querySelector("#delete-type-name-input");
  const deleteCategoryNameInput = document.querySelector("#delete-category-name-input");
  const deleteMenuNameInput = document.querySelector("#delete-menu-name-input"); 
  function closeDeleteProductModal() {
    deleteProductModal.classList.add("hidden");
    overlay.classList.add("hidden");
  }

  function openDeleteProductModal() {
    deleteProductModal.classList.remove("hidden");
    overlay.classList.remove("hidden");
  }

  closeDeleteProductModalButton.addEventListener("click", (e) => closeDeleteProductModal());
  document.addEventListener("keydown", (ev) => {
    if (ev.key === "Escape" && ! deleteProductModal.classList.contains("hidden")) {
      closeDeleteProductModal();
    }
  });

  const allDeleteButtons = document.querySelectorAll(".btn--delete");
  allDeleteButtons.forEach((btn, index) => {
    btn.addEventListener("click", function (ev) {
      ev.preventDefault();
      openDeleteProductModal();
      deleteIdInput.value = products[index].product_id;
      deleteProductNameInput.value = products[index].product_name;
      deleteMenuNameInput.value = products[index].menu_name;
      deleteCategoryNameInput.value = products[index].category_name;
      deleteTypeNameInput.value = products[index].type_name;
    });
  });

  const deleteMenuForm = document.querySelector(".delete-form");
  deleteMenuForm.addEventListener("submit", function (e) {
    e.preventDefault();
    function handleProductData() {
      let validatedId = deleteIdInput.value;
      const formData = new FormData();
      formData.append("product_id", validatedId);
      submitProductData(formData, DELETE_PRODUCT_URL);
    }
    handleProductData();
  });
} 

const tableBodyObserver = new MutationObserver(handleCategoryTableBodyMutation);
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
