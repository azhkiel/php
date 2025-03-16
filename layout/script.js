// menu popup
function openPopup() {
    document.getElementById("menuPopup").classList.remove("hidden");
}
function closePopup() {
    document.getElementById("menuPopup").classList.add("hidden");
}

// password toggle
function togglePassword() {
    var passwordField = document.getElementById("password");
    var toggleIcon = document.getElementById("toggleIcon");
    
    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.innerHTML = `
            <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5c5 0 9 5 9 6s-4 6-9 6-9-5-9-6 4-6 9-6Zm0 3a3 3 0 1 1 0 6 3 3 0 0 1 0-6Z"/>
            </svg>
        `;
    } else {
        passwordField.type = "password";
        toggleIcon.innerHTML = `
            <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
            </svg>
        `;
    }
}