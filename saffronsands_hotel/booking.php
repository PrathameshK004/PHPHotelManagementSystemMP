<?php
$conn = new mysqli("localhost", "root", "", "hotel_management");
$bill = ""; // Variable to store bill details

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $aadhar = $_POST['aadhar'];
    $mobile = $_POST['mobile'];
    $days = $_POST['days'];
    $room_type = $_POST['room_type'];

    // Fetch an available room
    $room_query = $conn->query("SELECT room_number FROM rooms WHERE room_type='$room_type' AND is_occupied=0 LIMIT 1");

    if ($room_query->num_rows > 0) {
        $room = $room_query->fetch_assoc();
        $room_number = $room['room_number'];
        $price_per_day = ($room_type == 'AC') ? 1000 : 600;
        $total_amount = $days * $price_per_day * 1.18;

        // Insert user details into users table
        $conn->query("INSERT INTO users (name, aadhar_number, mobile_number, days, room_number, room_type, total_amount) 
                      VALUES ('$name', '$aadhar', '$mobile', '$days', '$room_number', '$room_type', '$total_amount')");

        // Update room table with user name
        $conn->query("UPDATE rooms SET occupied_by='$name', is_occupied=1 WHERE room_number=$room_number");

        // Generate Bill UI
        $bill = "
        <div class='container bill-container'>
            <h2>SaffronSands Hotel - Invoice</h2>
            <p><strong>Guest Name:</strong> $name</p>
            <p><strong>Aadhar Number:</strong> $aadhar</p>
            <p><strong>Mobile Number:</strong> $mobile</p>
            <p><strong>Room Type:</strong> $room_type</p>
            <p><strong>Room Number:</strong> $room_number</p>
            <p><strong>Stay Duration:</strong> $days Days</p>
            <p><strong>Price Per Day:</strong> ₹$price_per_day</p>
            <p><strong>Total Amount (with 18% GST):</strong> ₹$total_amount</p>
        </div>";
    } else {
        $bill = "<div class='container bill-container' style='text-align:center; color:red;'>
                    <h3>No $room_type rooms available</h3>
                 </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaffronSands Hotel - Check-In</title>
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
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100vh;
            margin: 0;
            overflow: hidden; /* Prevents extra scrolling */
        }

        .content-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            max-width: 450px;
            flex-grow: 1;
            overflow-y: auto; /* Enables scrolling within the wrapper */
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
            background: white;
            max-height: 50vh; /* Prevents taking full height */
            overflow-y: auto; /* Enables scrolling inside the container */
        }

        .bill-container {
            border: 2px solid #ff9800;
            padding: 15px;
            width: 100%;
            max-width: 450px;
            background: white;
            max-height: 50vh; /* Ensures bill stays within the viewport */
            overflow-y: auto; /* Allows scrolling inside bill */
            margin-top: 50px
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

        input, select, button {
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
    </style>
</head>
<body>

<div class="content-wrapper">
    <div class="container">
        <h2>Hotel Check-In</h2>
        <form method="POST">
            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="aadhar">Aadhar Number:</label>
            <input type="text" name="aadhar" id="aadhar" pattern="\d{12}" required>

            <label for="mobile">Mobile Number:</label>
            <input type="text" name="mobile" id="mobile" pattern="\d{10}" required>

            <label for="days">Number of Days:</label>
            <input type="number" name="days" id="days" min="1" required>

            <label for="room_type">Room Type:</label>
            <select name="room_type" id="room_type">
                <option value="AC">AC</option>
                <option value="Non-AC">Non-AC</option>
            </select>

            <button type="submit">Book Room</button>
        </form>
    </div>

    <!-- Display Bill if Booking is Successful -->
    <?php echo $bill; ?>
</div>

</body>
</html>
