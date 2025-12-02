document.addEventListener("DOMContentLoaded", () => {

  const numberOfRandom = 60; 
  const allSlots = Array.from(document.querySelectorAll(".slot"));
  const shuffled = allSlots.sort(() => Math.random() - 0.5);

  shuffled.slice(0, numberOfRandom).forEach(slot => {
    slot.classList.remove("available");
    slot.classList.add("occupied");
  });

});

document.addEventListener("DOMContentLoaded", () => {
  const selectSpot = document.getElementById("spot");

  document.querySelectorAll(".slot").forEach(slot => {
    slot.addEventListener("click", () => {

      const selectedSpot = slot.getAttribute("data-spot");
      selectSpot.innerHTML = '<option value="" disabled selected>Select a spot</option>';

      if(slot.classList.contains("occupied")){
        return alert(`❌ Spot ${selectedSpot} is already booked.`);
        selectSpot.value = "";
      }

      document.querySelectorAll(".slot").forEach(s => s.classList.remove("selected"));

      slot.classList.add("selected");

      let option = new Option(selectedSpot, selectedSpot);
      selectSpot.add(option);

      selectSpot.value = selectedSpot;
    });
  });
});

document.getElementById("booking-form").addEventListener("submit", function (e) {
  e.preventDefault();

  const spot = document.getElementById("spot").value;
  const time = document.getElementById("time").value;

  if (!spot) return alert("❌ Please select a parking spot.");
  if (!time) return alert("❌ Please select a booking time.");

  const selectedSlot = document.querySelector(`.slot[data-spot="${spot}"]`);

  if (!selectedSlot) return alert("⚠ Slot not found.");

  if (selectedSlot.classList.contains("occupied")) {
    return alert(`❌ Spot ${spot} is already booked.`);
  }

  selectedSlot.classList.remove("available");
  selectedSlot.classList.remove("selected");
  selectedSlot.classList.add("occupied");
  
  alert(`✔ Spot ${spot} booked successfully for ${time}!`);

  document.getElementById("booking-form").reset();
});

function adminLog(){
  window.location.href = "login.html";
}
