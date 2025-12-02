document.addEventListener("DOMContentLoaded", () => {

  const numberOfRandom = Math.floor(Math.random() * (80 - 1 + 1)) + 1;
  const allSlots = Array.from(document.querySelectorAll(".slot"));
  const shuffled = allSlots.sort(() => Math.random() - 0.5);

  shuffled.slice(0, numberOfRandom).forEach(slot => {
    slot.classList.remove("available");
    slot.classList.add("occupied");
  });

});

let selectedSpots = [];

function clearBooking() {
  selectedSpots = [];
  document.querySelectorAll(".slot.selected").forEach(slot => {
    slot.classList.remove("selected");
  });
  document.getElementById("spot").innerHTML = '<option value="" disabled selected>Select a spot</option>';
}

document.addEventListener("DOMContentLoaded", () => {
  const selectSpot = document.getElementById("spot");
  
  function updateDropdown() {
    selectSpot.innerHTML = '<option value="" disabled>Selected spot(s)</option>';

    if (selectedSpots.length === 0) {
      selectSpot.value = "";
      return;
    }

    selectedSpots.forEach(spotValue => {
      let option = new Option(`Spot ${spotValue}`, spotValue, true, true);
      selectSpot.add(option);
    });
  }

  document.querySelectorAll(".slot").forEach(slot => {
    slot.addEventListener("click", () => {
      const selectedSpotValue = slot.getAttribute("data-spot");

      if (slot.classList.contains("occupied")) {
        return alert(`❌ Spot ${selectedSpotValue} is already booked.`);
      }

      if (slot.classList.contains("selected")) {
        slot.classList.remove("selected");
        selectedSpots = selectedSpots.filter(spot => spot !== selectedSpotValue);
      } else {
        slot.classList.add("selected");
        selectedSpots.push(selectedSpotValue);
      }
      selectedSpots.sort((a, b) => a - b);

      updateDropdown();
    });
  });
});

document.getElementById("booking-form").addEventListener("submit", function (e) {
  e.preventDefault();

  const selectElement = document.getElementById("spot");
  const selectedOptions = Array.from(selectElement.options).filter(option => option.selected && option.value !== "");
  
  const time = document.getElementById("time").value;

  if (selectedOptions.length === 0) return alert("❌ Please select at least one parking spot.");
  if (!time) return alert("❌ Please select a booking time.");

  let bookedSpotsCount = 0;
  let occupiedAlerts = [];

  selectedOptions.forEach(option => {
    const spotValue = option.value;
    const selectedSlot = document.querySelector(`.slot[data-spot="${spotValue}"]`);

    if (!selectedSlot) {
      console.warn(`Slot ${spotValue} not found in DOM.`);
      return;
    }

    if (selectedSlot.classList.contains("occupied")) {
      occupiedAlerts.push(spotValue);
    } else {
      selectedSlot.classList.remove("available");
      selectedSlot.classList.remove("selected");
      selectedSlot.classList.add("occupied");
      bookedSpotsCount++;
    }
  });

  if (occupiedAlerts.length > 0) {
    alert(`The following spots were already booked: ${occupiedAlerts.join(', ')}.`);
  }

  if (bookedSpotsCount > 0) {
    alert(`✔ Successfully booked ${bookedSpotsCount} spot(s) for ${time}!`);
  }

  clearBooking();
  document.getElementById("booking-form").reset();
});

function adminLog(){
  window.location.href = "login.html";
}
