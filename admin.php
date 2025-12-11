<?php
session_start();
include("php/config.php");

if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php");
  exit();
}
$slotsQuery = $conn->query("SELECT * FROM parking_slots ORDER BY LEFT(slot_code, 1), CAST(SUBSTRING(slot_code, 2) AS UNSIGNED)");
$slots = $slotsQuery->fetch_all(MYSQLI_ASSOC);

$total = count($slots);
$occupied = 0;
foreach ($slots as $s) {
  if ($s['status'] === 'occupied') $occupied++;
}
$available = $total - $occupied;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Park-IT | Admin</title>
  <link rel="icon" type="image/png" href="css/ParkIT.png">
  <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
  <link rel="stylesheet" href="css/admin.css">
</head>

<body>
  <header>
    <nav>
      <img src="css/ParkIT.png" alt="">
      <h1>Admin | Dashboard</h1>
      <form method="post" action="php/logout.php">
        <button type="submit" id="logoutBtn">Log Out</button>
      </form>
    </nav>
  </header>

  <main>
    <section class="parkingarea">
      <h2>Parking Availability</h2>

      <div class="parking-wrapper">
        <div class="center-block">
          <?php
          $sections = ['A', 'B', 'C', 'D', 'E'];
          foreach ($sections as $section) {
            echo '<div class="block" data-section="' . $section . '">';
            echo '<h3 class="block-title">Block ' . $section . '</h3>';

            foreach ($slots as $slot) {
              if (stripos($slot['slot_code'], $section) === 0) {
                $code = htmlspecialchars($slot['slot_code']);
                $status = $slot['status'] === 'occupied' ? 'occupied' : 'available';

                echo '<div class="slot ' . $status . '" data-spot="' . $code . '" data-id="' . intval($slot['id']) . '">';
                echo '<span class="slot-label">' . $code . '</span>';
                echo '</div>';
              }
            }
            echo '</div>';
          }
          ?>
        </div>
      </div>
    </section>

    <section class="stats-section">
      <div class="stats-card">
        <h2>Parking Status</h2>
        <div class="stats-grid">
          <div class="stat-item totalCounter">
            <h3>Total Spots</h3>
            <p id="totalspots"><?= $total ?></p>
          </div>
          <div class="stat-item availableCounter">
            <h3>Available</h3>
            <p id="availableCount"><?= $available ?></p>
          </div>
          <div class="stat-item occupiedCounter">
            <h3>Occupied</h3>
            <p id="occupiedCount"><?= $occupied ?></p>
          </div>
        </div>
      </div>
    </section>

    <section class="maniBtns">
      <div class="btn-group">
        <div class="btn-row">
          <button class="btn-item" id="setAllAvailable">Set All Available</button>
          <button class="btn-item" id="refreshStats">Refresh Stats</button>
          <button class="btn-item" id="setAllOccupied">Set All Occupied</button>
        </div>
      </div>
    </section>

    <section class="bookings-section">
      <h2>Current Bookings</h2>

      <form method="GET" style="margin-bottom:15px; display:flex; gap:10px;" class="searchPanel">
        <input
          type="text"
          name="search"
          placeholder="Search by ID / Slot / Name / Email"
          value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
          style="flex:1; padding:8px;">
        <button type="submit" style="padding:8px 15px;">Search</button>
      </form>

      <table class="bookings-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Slot</th>
            <th>Reserved By</th>
            <th>Email</th>
            <th>Booking Time</th>
            <th>Date Created</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $search = isset($_GET['search']) ? trim($_GET['search']) : '';

          if ($search !== "") {
            $searchSafe = $conn->real_escape_string($search);
            $bookQry = $conn->query("
              SELECT * FROM bookings
              WHERE id LIKE '%$searchSafe%'
                 OR slot_code LIKE '%$searchSafe%'
                 OR reserved_by LIKE '%$searchSafe%'
                 OR email LIKE '%$searchSafe%'
              ORDER BY date_created DESC
            ");
          } else {
            $bookQry = $conn->query("SELECT * FROM bookings ORDER BY date_created DESC");
          }

          if ($bookQry->num_rows > 0):
            while ($b = $bookQry->fetch_assoc()):
          ?>
              <tr>
                <td><?= $b['id'] ?></td>
                <td><?= $b['slot_code'] ?></td>
                <td><?= $b['reserved_by'] ?></td>
                <td><?= $b['email'] ?></td>
                <td><?= $b['booking_time'] ?></td>
                <td><?= $b['date_created'] ?></td>

                <td>
                  <form method="POST" action="php/delete_booking.php">
                    <input type="hidden" name="id" value="<?= $b['id'] ?>">
                    <input type="hidden" name="slot_code" value="<?= $b['slot_code'] ?>">
                    <button type="submit" style="background:#dc3545;color:white;padding:5px 10px;border:none;border-radius:5px;cursor:pointer;">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endwhile;
          else: ?>
            <tr>
              <td colspan="7" style="text-align:center;">No bookings found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>


  </main>

  <footer>
    <p>© 2025 Park-IT. All rights reserved.</p>
  </footer>

  <script src="js/admin.js"></script>
</body>

</html>