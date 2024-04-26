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

const SERVER_URL = "/admin/users/new";
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
  fetch(SERVER_URL, {
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
  
  function handleSignupData() {
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
        formData.append("role", validatedRole)
        submitData(formData);
      }
    });
  }
  handleSignupData();
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
      matchedPassword = true;
      confirmPasswordError.textContent = "";
    })
    .catch((msg) => {
      confirmPasswordError.textContent = msg;
    })
    .finally(() => {});
});




