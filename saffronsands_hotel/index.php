<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaffronSands Hotel</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #f4f4f4; }
        .tab { display: inline-block; margin: 10px; padding: 15px; background: #ff9800; color: white; cursor: pointer; border-radius: 10px; }
        .tab:hover { background: #e68900; }
        iframe { width: 100%; height: 150vh; border: none; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Welcome to SaffronSands Hotel</h1>
    <div>
        <div class="tab" onclick="loadPage('booking.php')">Booking</div>
        <div class="tab" onclick="loadPage('checkout.php')">Checkout</div>
        <div class="tab" onclick="loadPage('database.php')">Database</div>
    </div>
    <iframe id="contentFrame" src="booking.php"></iframe>

    <script>
        function loadPage(page) {
            document.getElementById("contentFrame").src = page;
        }
    </script>
</body>
</html>
