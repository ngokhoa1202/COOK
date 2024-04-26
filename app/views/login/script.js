const SERVER_URL = "/login";
const MIN_PASSWORD_LENGTH = 8;
const EMAIL_PATTERN = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;


/* password has at least 8 characters which contain at least one numeric digit and a special character */
const PASSWORD_PATTERN = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/;

const REQUIRED_EMAIL_MSG = "Email is required";
const WRONG_EMAIL_PATTERN = "Wrong email pattern";
const REQUIRED_PASSWORD_MSG = "Password is required";
const WRONG_PASSWORD_PATTERN = "Wrong password pattern";
const TOO_SHORT_PASSWORD = "At least 8 characters for password";
const WRONG_CONFIRM_PASSWORD = "Wrong connfirmed password";
/**
 *
 * @param {HTMLElement} radioContainer
 */
function toggleLoginAndSignupRadios(radioContainer) {
  let accordionClassName = radioContainer.id + "-accordion";
  let selectedAccordion = document.querySelector("." + accordionClassName);
  if (selectedAccordion.classList.contains("hidden")) {
    let activeAccordion = document.querySelector(".active");
    activeAccordion.classList.remove("active");
    activeAccordion.classList.add("hidden");
    selectedAccordion.classList.remove("hidden");
    selectedAccordion.classList.add("active");
  }
}
const radioContainerList = document.querySelectorAll(".radio-container");
radioContainerList.forEach((radioContainer) =>
  radioContainer.addEventListener("click", function (e) {
    toggleLoginAndSignupRadios(this);
  })
);

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

const loginPasswordInput = document.querySelector("#login-password-input");
/**
 * 
 * @returns {Promise}
 */
function validateLoginPassword() {
  return new Promise((resolve, reject) => {
    if (!loginPasswordInput.value) {
      reject(REQUIRED_PASSWORD_MSG);
    } else if (loginPasswordInput.value.length < MIN_PASSWORD_LENGTH) {
      reject(TOO_SHORT_PASSWORD);
    } else if (!loginPasswordInput.value.match(PASSWORD_PATTERN)) {
      reject(WRONG_PASSWORD_PATTERN);
    } else {
      resolve(loginPasswordInput.value);
    }
  });
}

const signupPasswordInput = document.querySelector("#signup-password-input");
/**
 * 
 * @returns {Promise}
 */
function validateSignupPassword() {
  return new Promise((resolve, reject) => {
    if (!signupPasswordInput.value) {
      reject(REQUIRED_PASSWORD_MSG);
    } else if (signupPasswordInput.value.length < MIN_PASSWORD_LENGTH) {
      reject(TOO_SHORT_PASSWORD);
    } else if (!signupPasswordInput.value.match(PASSWORD_PATTERN)) {
      reject(WRONG_PASSWORD_PATTERN);
    } else {
      resolve(signupPasswordInput.value);
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
    } else if (confirmPasswordInput.value !== signupPasswordInput.value) {
      reject(WRONG_CONFIRM_PASSWORD);
    } else {
      resolve(true);
    }
  });
}

const loginForm = document.querySelector(".login-form");
loginForm.addEventListener("submit", function(e) {
  e.preventDefault();
  /**
   * 
   * @returns {boolean}
   */
  function isLoggingIn() {
    return document.querySelector(".login-accordion").classList.contains("active");
  }

  /**
   * 
   * @param {FormData} formData 
   */
  function submitData(formData) {
    fetch(
      SERVER_URL, 
      {
        method: "POST",
        body: formData
      }
    ).then((response) => {
      if (!response.ok) {
        throw new Error("Your network connection is not stable")
      }
      return response.json();
    }).then((data) => {
      console.log("Receive: ", data);
    }).catch((error) => {
      alert(error);
    })
  }

  const emailError = document.querySelector(".email-error");
  const loginPasswordError = document.querySelector(".login-password-error");
  const signupPasswordError = document.querySelector(".signup-password-error");
  const confirmPasswordError = document.querySelector(".confirm-password-error");

  const emailValidatorPromise = validateEmail()
    .then((email) => {
      validatedEmail = email;
    })
    .catch((msg) => {
      emailError.textContent = msg;
    })
    .finally(() => {});

  const loginPasswordValidatorPromise = validateLoginPassword()
    .then((password) => {
      validatedPassword = password;
    })
    .catch((msg) => {
      loginPasswordError.textContent = msg;
    })
    .finally(() => {});

  const confirmPasswordValidatorPromise = validateConfirmPassword()
    .then((isMatched) => {
      matchedPassword = true;
    })
    .catch((msg) => {
      confirmPasswordError.textContent = msg;
    })
    .finally(() => {});
  
  const signupPasswordValidatorPromise = validateSignupPassword()
    .then((password) => {
      validatedPassword = password;
    })
    .catch((msg) => {
      signupPasswordError.textContent = msg;
    })
    .finally(() => {});

  function handleLoginData() {
    Promise.allSettled([emailValidatorPromise, loginPasswordValidatorPromise]).then(() => {
      if (validatedEmail && validatedPassword) {
        const formData = new FormData();  
        formData.append("email", validatedEmail);
        formData.append("password", validatedPassword);
        submitData(formData);
      }
    });
  }

  function handleSignupData() {
    Promise.allSettled([emailValidatorPromise, signupPasswordValidatorPromise, confirmPasswordValidatorPromise]).then(() => {
      if (validatedEmail && validatedPassword && matchedPassword) {
        const formData = new FormData();
        formData.append("signup", true);
        formData.append("email", validatedEmail);
        formData.append("password", validatedPassword);
        formData.append("confirm_password", validatedPassword);
        submitData(formData);
      }
    });
  }

 
  let validatedEmail = "";
  let validatedPassword = "";
  let matchedPassword = false;
  if (isLoggingIn()) {
    handleLoginData();
  } else { // signup
    handleSignupData();
  }
});




