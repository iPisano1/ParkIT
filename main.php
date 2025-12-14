<?php
    include("php/config.php");

    $slotsQuery = $conn->query("SELECT * FROM parking_slots ORDER BY LEFT(slot_code, 1), CAST(SUBSTRING(slot_code, 2) AS UNSIGNED)");
    $slots = $slotsQuery->fetch_all(MYSQLI_ASSOC);
    $success = "";
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $selected = $_POST["spot"] ?? [];
        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $time = $_POST["time"] ?? "";

        if (empty($selected)) {
            $error = "❌ Please select at least one spot.";
        } elseif (empty($time)) {
            $error = "❌ Please select a booking time.";
        } else {

            $already = [];
            $newly = 0;

            $checkStmt = $conn->prepare("SELECT status FROM parking_slots WHERE slot_code = ?");
            $updateStmt = $conn->prepare("UPDATE parking_slots SET status='occupied' WHERE slot_code = ?");
            $insertStmt = $conn->prepare("INSERT INTO bookings (slot_code, reserved_by, email, booking_time) VALUES (?, ?, ?, ?)");

            foreach ($selected as $spot) {

                $checkStmt->bind_param("s", $spot);
                $checkStmt->execute();
                $res = $checkStmt->get_result()->fetch_assoc();

                if ($res && $res["status"] === "occupied") {
                    $already[] = $spot;
                } else {

                    $updateStmt->bind_param("s", $spot);
                    $updateStmt->execute();

                    $insertStmt->bind_param("ssss", $spot, $name, $email, $time);
                    $insertStmt->execute();

                    $newly++;
                }
            }

            if ($newly > 0) {
                $success = "✔ Successfully booked $newly spot(s) for $time!";
            }

            if (!empty($already)) {
                $error .= "❌ Already booked: " . implode(", ", $already);
            }

            $checkStmt->close();
            $updateStmt->close();
            $insertStmt->close();

            $slotsQuery = $conn->query("SELECT * FROM parking_slots ORDER BY slot_code ASC");
            $slots = $slotsQuery->fetch_all(MYSQLI_ASSOC);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ParkIT | Home</title>
    <link rel="icon" type="image/png" href="css/ParkIT.png">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <header>
        <nav>
            <img src="css/ParkIT.png" alt="">
            <button class="adminBtn" onclick="adminLog()" id="adminBtn">Admin</button>
        </nav>
    </header>

    <main>

        <section class="parking-view">
            <h2>Parking Availability</h2>

            <div class="parking-wrapper">
                <div class="center-block">
                    <?php
                    $sections = ['A', 'B', 'C', 'D', 'E'];

                    foreach ($sections as $section) {
                        echo "<div class=\"block\" data-section=\"{$section}\">";

                        foreach ($slots as $slot) {
                            if (stripos($slot['slot_code'], $section) === 0) {
                                $statusClass = $slot['status'] === "occupied" ? "occupied" : "available";
                                $code = htmlspecialchars($slot['slot_code']);

                                echo '<div class="slot ' . $statusClass . '" data-spot="' . $code . '">';
                                echo '<span style="position:absolute;bottom:4px;right:4px;font-size:10px;color:white;">' . $code . '</span>';
                                echo '</div>';
                            }
                        }
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </section>

        <section id="booking">
            <h2>Book a Parking Spot</h2>

            <?php if ($error): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
            <?php if ($success): ?><p style="color:green;"><?= htmlspecialchars($success) ?></p><?php endif; ?>

            <form method="POST" id="bookingForm">

                <label>Selected Parking Spot(s):</label>
                <select name="spot[]" id="spotSelect" multiple size="6" required></select>

                <label>Booking Time:</label>
                <input type="datetime-local" name="time" required id="datetime">

                <label>Your Name:</label>
                <input type="text" name="name" required id="name">

                <label>Email:</label>
                <input type="email" name="email" required id="email">

                <button type="submit" id="bookBtn">Book Now</button>
                <button type="button" id="clearSelection" style="background-color: #e74c3c;">Clear</button>
            </form>
        </section>

    </main>

    <footer>
        <p>© 2025 Park-IT. All rights reserved.</p>
    </footer>

    <script>
        const slots = document.querySelectorAll(".slot");
        const select = document.getElementById("spotSelect");
        const clearBtn = document.getElementById("clearSelection");

        function refreshSelect() {
            const selectedCodes = Array.from(document.querySelectorAll(".slot.selected"))
                .map(s => s.dataset.spot);

            select.innerHTML = "";

            selectedCodes.forEach(code => {
                const opt = document.createElement("option");
                opt.value = code;
                opt.textContent = code;
                opt.selected = true;
                select.appendChild(opt);
            });
        }


        slots.forEach(slot => {
            slot.addEventListener("click", () => {
                if (slot.classList.contains("occupied")) return;

                slot.classList.toggle("selected");
                refreshSelect();
            });
        });


        clearBtn.addEventListener("click", () => {
            document.querySelectorAll(".slot.selected")
                .forEach(s => s.classList.remove("selected"));
            refreshSelect();
        });

        function adminLog() {
            window.location.href = "login.php";
        }
    </script>

</body>

</html>