document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    if (username === "admin" && password === "admin") {
        alert(`Welcome, ${username}!`);
        window.location.href = "admin.html";
    } else {
        const errorMessage = document.querySelector(".error-message");
        errorMessage.textContent = "❌ Invalid username or password.";
        document.getElementById("username").value = "";
        document.getElementById("password").value = "";
    }
});

document.getElementById("username").addEventListener("input", clearError);
document.getElementById("password").addEventListener("input", clearError);

function clearError() {
    const errorMessage = document.querySelector(".error-message");
    errorMessage.textContent = "";
}
