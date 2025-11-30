document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const username = document.getElementById("username").value;
    alert(`Welcome, ${username}!`);
    window.location.href = "main.html";
});