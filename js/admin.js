// js/admin.js
document.addEventListener('DOMContentLoaded', () => {
  const api = 'admin_update.php';
  const slots = document.querySelectorAll('.slot');
  const totalEl = document.getElementById('totalspots');
  const availEl = document.getElementById('availableCount');
  const occEl = document.getElementById('occupiedCount');

  // helper to fetch JSON POST
  async function postForm(data) {
    const resp = await fetch(api, {
      method: 'POST',
      headers: { 'Accept': 'application/json' },
      body: data
    });
    return resp.json();
  }

  async function updateStatsUI() {
    const form = new FormData();
    form.append('action', 'stats');
    const res = await postForm(form);
    if (res.success) {
      totalEl.innerText = res.total;
      availEl.innerText = res.available;
      occEl.innerText = res.occupied;
    }
  }

  // toggle single slot when clicked
  slots.forEach(slot => {
    slot.addEventListener('click', async () => {
      const spot = slot.dataset.spot;
      if (!spot) return;

      // optimistic UI: toggle classes immediately
      const wasOccupied = slot.classList.contains('occupied');
      // toggle classes visually
      if (wasOccupied) {
        slot.classList.remove('occupied');
        slot.classList.add('available');
      } else {
        slot.classList.remove('available');
        slot.classList.add('occupied');
      }

      // send request to server to toggle
      const form = new FormData();
      form.append('action', 'toggle');
      form.append('slot', spot);
      // no explicit status => server will toggle
      const res = await postForm(form);

      if (!res.success) {
        // revert UI on failure
        if (wasOccupied) {
          slot.classList.add('occupied');
          slot.classList.remove('available');
        } else {
          slot.classList.add('available');
          slot.classList.remove('occupied');
        }
        alert('Error: ' + (res.message || 'Server error'));
      } else {
        // update stats from response
        totalEl.innerText = res.total;
        availEl.innerText = res.available;
        occEl.innerText = res.occupied;
      }
    });
  });

  // buttons
  document.getElementById('setAllAvailable').addEventListener('click', async () => {
    if (!confirm('Are you sure you want to set all spots to available?')) return;
    const f = new FormData();
    f.append('action', 'set_all');
    f.append('status', 'available');
    const res = await postForm(f);
    if (res.success) {
      // reflect UI
      document.querySelectorAll('.slot').forEach(s => {
        s.classList.remove('occupied');
        s.classList.add('available');
      });
      totalEl.innerText = res.total;
      availEl.innerText = res.available;
      occEl.innerText = res.occupied;
    } else {
      alert('Error: ' + (res.message || 'Server error'));
    }
  });

  document.getElementById('setAllOccupied').addEventListener('click', async () => {
    if (!confirm('Are you sure you want to set all spots to occupied?')) return;
    const f = new FormData();
    f.append('action', 'set_all');
    f.append('status', 'occupied');
    const res = await postForm(f);
    if (res.success) {
      document.querySelectorAll('.slot').forEach(s => {
        s.classList.remove('available');
        s.classList.add('occupied');
      });
      totalEl.innerText = res.total;
      availEl.innerText = res.available;
      occEl.innerText = res.occupied;
    } else {
      alert('Error: ' + (res.message || 'Server error'));
    }
  });

  document.getElementById('refreshStats').addEventListener('click', updateStatsUI);

  // logout button
  document.getElementById('logoutBtn').addEventListener('click', () => {
    if (confirm('Logout?')) {
        window.location.href = 'logout.php';
    }
  });


  // initial stats refresh (in case DB changed)
  updateStatsUI();
});
