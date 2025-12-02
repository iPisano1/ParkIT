function logout(){
    alert("You have been logged out.");
    window.location.href = "main.html";
}

document.addEventListener("DOMContentLoaded", () => {
  const numberOfRandom = 60;
  const allSlots = Array.from(document.querySelectorAll(".slot"));
  const shuffled = allSlots.sort(() => Math.random() - 0.5);

  shuffled.slice(0, numberOfRandom).forEach(slot => {
    slot.classList.add("occupied");
  });
});

document.addEventListener("DOMContentLoaded", () => {
    const selectSpot = document.getElementById("spot");

    document.querySelectorAll(".slot").forEach(slot => {
        slot.addEventListener("click", () => {

            slot.classList.toggle("occupied");
            const label = slot.dataset.spot;
            selectSpot.innerHTML = '<option value="" disabled selected>Select a spot</option>';
            let option = new Option(label, label);
            selectSpot.add(option);

            selectSpot.value = label;
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {

    function updateStats() {
        const total = document.querySelectorAll(".slot").length;
        const occupied = document.querySelectorAll(".slot.occupied").length;
        const available = total - occupied;

        document.getElementById("totalspots").innerText = total;
        document.getElementById("availableCount").innerText = available;
        document.getElementById("occupiedCount").innerText = occupied;
    }

    updateStats();

    document.querySelectorAll(".slot").forEach(slot => {
        slot.addEventListener("click", () => {
            updateStats();
        });
    });

});

function setAllAvailable() {
    if (confirm("Are you sure you want to set all spots to available?") == false){
        return;
    }
    document.querySelectorAll(".slot").forEach(slot => {
        slot.classList.remove("occupied");
    });
    const total = document.querySelectorAll(".slot").length;
    document.getElementById("availableCount").innerText = total;
    document.getElementById("occupiedCount").innerText = 0;
}

function refreshStats() {
    const total = document.querySelectorAll(".slot").length;
    const occupied = document.querySelectorAll(".slot.occupied").length;
    const available = total - occupied;
    document.getElementById("totalspots").innerText = total;
    document.getElementById("availableCount").innerText = available;
    document.getElementById("occupiedCount").innerText = occupied;
}

function setAllOccupied() {
    if (confirm("Are you sure you want to set all spots to occupied?") == false){
        return;
    }
    document.querySelectorAll(".slot").forEach(slot => {
        slot.classList.add("occupied");
    });
    document.getElementById("availableCount").innerText = 0;
    const total = document.querySelectorAll(".slot").length;
    document.getElementById("occupiedCount").innerText = total;
}