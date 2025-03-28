<?php
$conn = new mysqli("localhost", "root", "", "hotel_management");

$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_number = $_POST['room_number'];

    // Check if room is occupied
    $room_check = $conn->query("SELECT occupied_by FROM rooms WHERE room_number=$room_number AND is_occupied=1");

    if ($room_check->num_rows > 0) {
        // Delete user associated with this room
        $conn->query("DELETE FROM users WHERE room_number=$room_number");

        // Deallocate room
        $conn->query("UPDATE rooms SET occupied_by=NULL, is_occupied=0 WHERE room_number=$room_number");

        $message = "<div class='message' style='color: green;'>Room $room_number successfully checked out</div>";
    } else {
        $message = "<div class='message' style='color: red;'>Room is not occupied</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Checkout</title>
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
            overflow: hidden; /* No scrollbars */
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
            display: flex;
            flex-direction: column;
            min-height: 300px; /* Ensures proper spacing */
        }
        h2 {
            text-align: center;
            color: #ff9800;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input, button {
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
        .message {
            text-align: center;
            font-weight: bold;
            margin-top: auto; /* Pushes message to bottom */
            padding-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Hotel Check-Out</h2>
    <form method="POST">
        <label for="room_number">Room Number:</label>
        <input type="number" name="room_number" id="room_number" min="1" max="50" required>

        <button type="submit">Checkout</button>
    </form>

    <!-- Show message at the bottom -->
    <?php echo $message; ?>
</div>

</body>
</html>
