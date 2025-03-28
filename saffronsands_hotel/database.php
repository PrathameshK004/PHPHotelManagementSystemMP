<?php
$conn = new mysqli("localhost", "root", "", "hotel_management");

$table = isset($_POST['table']) ? $_POST['table'] : 'users';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Database</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
        }
        .container {
            width: 100%;
            max-width: 800px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #ff9800;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            display: block;
        }
        button {
            background: #ff9800;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }
        button:hover {
            background: #e68900;
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            position: relative;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            white-space: nowrap;
        }
        th {
            background-color: #ff9800;
            color: white;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        .table-container::-webkit-scrollbar {
            width: 8px;
        }
        .table-container::-webkit-scrollbar-thumb {
            background: #ff9800;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Hotel Database</h2>
    <form method="POST">
        <label for="table">Select Table:</label>
        <select name="table" id="table">
            <option value="users" <?= ($table == 'users') ? 'selected' : '' ?>>Users</option>
            <option value="rooms" <?= ($table == 'rooms') ? 'selected' : '' ?>>Rooms</option>
        </select>
        <button type="submit">View Data</button>
    </form>

    <div class="table-container">
        <?php
        if ($table == 'users') {
            $result = $conn->query("SELECT name, aadhar_number, mobile_number, room_number, room_type, days, total_amount, check_in FROM users");
            
            if ($result->num_rows > 0) {
                echo "<h3>Bookings</h3><table><tr>
                        <th>Name</th>
                        <th>Aadhar Number</th>
                        <th>Mobile Number</th>
                        <th>Room</th>
                        <th>Room Type</th>
                        <th>Days</th>
                        <th>Amount</th>
                        <th>Check-in</th>
                      </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['name']}</td>
                            <td>{$row['aadhar_number']}</td>
                            <td>{$row['mobile_number']}</td>
                            <td>{$row['room_number']}</td>
                            <td>{$row['room_type']}</td>
                            <td>{$row['days']}</td>
                            <td>₹{$row['total_amount']}</td>
                            <td>{$row['check_in']}</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No results found</p>";
            }
        }
         elseif ($table == 'rooms') {
            $result = $conn->query("SELECT * FROM rooms");
            echo "<h3>Room Status</h3><table><tr>
                    <th>Room Number</th>
                    <th>Type</th>
                    <th>Occupied By</th>
                    <th>Status</th>
                  </tr>";
            while ($row = $result->fetch_assoc()) {
                $occupied_by = $row['occupied_by'] ?: 'None';
                $status = $row['is_occupied'] ? 'Occupied' : 'Available';
                echo "<tr>
                        <td>{$row['room_number']}</td>
                        <td>{$row['room_type']}</td>
                        <td>{$occupied_by}</td>
                        <td>{$status}</td>
                      </tr>";
            }
            echo "</table>";
        }
        ?>
    </div>
</div>

</body>
</html>
