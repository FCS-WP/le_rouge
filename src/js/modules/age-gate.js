/**
 * Age Gate Module
 * Handles showing/hiding age verification and cookie management.
 */

const COOKIE_NAME = "az_age_verified";
const COOKIE_EXPIRY_DAYS = 30;

export function initAgeGate() {
  const ageGate = document.getElementById("age-gate");
  if (!ageGate) return;

  // Check if already verified
  if (getCookie(COOKIE_NAME)) {
    return;
  }

  // Show age gate
  ageGate.classList.add("show");
  document.body.style.overflow = "hidden";

  const btnYes = ageGate.querySelector(".btn-yes");
  const btnNo = ageGate.querySelector(".btn-no");
  const rememberCheckbox = ageGate.querySelector("#ag-remember-check");

  btnYes.addEventListener("click", () => {
    const isRemembered = rememberCheckbox ? rememberCheckbox.checked : false;

    // Set cookie (session only if not remembered, else expiry days)
    setCookie(COOKIE_NAME, "true", isRemembered ? COOKIE_EXPIRY_DAYS : null);

    // Hide age gate
    ageGate.classList.remove("show");
    document.body.style.overflow = "";

    setTimeout(() => {
      ageGate.remove();
    }, 500);
  });

  btnNo.addEventListener("click", () => {
    // Redirect to a safe page (e.g. Google)
    window.location.href = "https://www.google.com";
  });
}

function setCookie(name, value, days) {
  let expires = "";
  if (days) {
    const date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie =
    name + "=" + (value || "") + expires + "; path=/; SameSite=Lax";
}

function getCookie(name) {
  const nameEQ = name + "=";
  const ca = document.cookie.split(";");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) === " ") c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}
