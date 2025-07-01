<?php
// Sample Indian train data
$trains = [
    [
        "id" => 1,
        "name" => "Rajdhani Express (12951)",
        "from" => "Mumbai",
        "to" => "Delhi",
        "departure" => "16:35",
        "arrival" => "08:35",
        "duration" => "16h 00m",
        "price" => [
            "3A" => 2250.00,
            "2A" => 3200.00,
            "1A" => 5400.00
        ],
        "seats_available" => [
            "3A" => 45,
            "2A" => 25,
            "1A" => 12
        ],
        "type" => "Rajdhani",
        "runs_on" => ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        "pantry" => true
    ],
    [
        "id" => 2,
        "name" => "Shatabdi Express (12009)",
        "from" => "Mumbai",
        "to" => "Ahmedabad",
        "departure" => "06:25",
        "arrival" => "13:10",
        "duration" => "6h 45m",
        "price" => [
            "CC" => 1250.00,
            "EC" => 2300.00
        ],
        "seats_available" => [
            "CC" => 120,
            "EC" => 65
        ],
        "type" => "Shatabdi",
        "runs_on" => ["Daily"],
        "pantry" => true
    ],
    [
        "id" => 3,
        "name" => "Duronto Express (12223)",
        "from" => "Mumbai",
        "to" => "Pune",
        "departure" => "07:00",
        "arrival" => "10:25",
        "duration" => "3h 25m",
        "price" => [
            "SL" => 450.00,
            "3A" => 1200.00,
            "2A" => 2100.00
        ],
        "seats_available" => [
            "SL" => 180,
            "3A" => 65,
            "2A" => 32
        ],
        "type" => "Duronto",
        "runs_on" => ["Daily"],
        "pantry" => true
    ],
    [
        "id" => 4,
        "name" => "Tejas Express (22119)",
        "from" => "Mumbai",
        "to" => "Goa",
        "departure" => "05:50",
        "arrival" => "16:00",
        "duration" => "10h 10m",
        "price" => [
            "CC" => 1550.00,
            "EC" => 2800.00
        ],
        "seats_available" => [
            "CC" => 90,
            "EC" => 45
        ],
        "type" => "Tejas",
        "runs_on" => ["Except Thursday"],
        "pantry" => true
    ]
];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Location: train-payment.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indian Railways Booking</title>
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f5f5;
        }

        .app-container {
            background: white;
            min-height: 100vh;
        }

        .header {
            background: #2563eb;
            color: white;
            padding: 1rem;
            text-align: center;
            position: relative;
        }

        .back-button {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1.5rem;
            text-decoration: none;
        }

        .search-container {
            padding: 1rem;
            background: #f8f9fa;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .search-form {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .input-group {
            display: flex;
            flex-direction: column;
        }

        .input-group label {
            margin-bottom: 0.5rem;
            color: #4b5563;
            font-size: 0.9rem;
        }

        .input-field {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .search-btn {
            background: #2563eb;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            grid-column: 1 / -1;
        }

        .train-list {
            padding: 1rem;
        }

        .train-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .train-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .train-name {
            font-size: 1.25rem;
            color: #1f2937;
        }

        .train-price {
            color: #2563eb;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .train-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .detail-group {
            text-align: center;
        }

        .detail-label {
            color: #4b5563;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-weight: 600;
            color: #1f2937;
        }

        .train-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }

        .seats-available {
            color: #059669;
            font-size: 0.875rem;
        }

        .class-badge {
            background: #e5e7eb;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.875rem;
            color: #4b5563;
        }

        .book-btn {
            width: 100%;
            background: #059669;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1rem;
        }

        .notification {
            background: #059669;
            color: white;
            padding: 1rem;
            margin: 1rem;
            border-radius: 8px;
            text-align: center;
            display: <?php echo isset($message) ? 'block' : 'none'; ?>;
        }
    </style>

</head>
<body>
    <div class="app-container">
        <header class="header">
            <a href="main.php" class="back-button">←</a>
            <h1>IRCTC Train Bookings</h1>
        </header>

        <div class="search-container">
            <form class="search-form">
                <div class="input-group">
                    <label for="from">From Station</label>
                    <select id="from" class="input-field">
                        <option value="">Select Station</option>
                        <option value="Mumbai">Mumbai (CSMT)</option>
                        <option value="Delhi">Delhi (NDLS)</option>
                        <option value="Bangalore">Bangalore (SBC)</option>
                        <option value="Chennai">Chennai (MAS)</option>
                        <option value="Kolkata">Kolkata (KOAA)</option>
                        <option value="Hyderabad">Hyderabad (SC)</option>
                        <option value="Ahmedabad">Ahmedabad (ADI)</option>
                        <option value="Pune">Pune (PUNE)</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="to">To Station</label>
                    <select id="to" class="input-field">
                        <option value="">Select Station</option>
                        <option value="Delhi">Delhi (NDLS)</option>
                        <option value="Mumbai">Mumbai (CSMT)</option>
                        <option value="Bangalore">Bangalore (SBC)</option>
                        <option value="Chennai">Chennai (MAS)</option>
                        <option value="Kolkata">Kolkata (KOAA)</option>
                        <option value="Hyderabad">Hyderabad (SC)</option>
                        <option value="Ahmedabad">Ahmedabad (ADI)</option>
                        <option value="Pune">Pune (PUNE)</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="date">Travel Date</label>
                    <input type="date" id="date" class="input-field" required>
                </div>

                <div class="input-group">
                    <label for="class">Travel Class</label>
                    <select id="class" class="input-field">
                        <option value="any">All Classes</option>
                        <option value="1A">First AC (1A)</option>
                        <option value="2A">Second AC (2A)</option>
                        <option value="3A">Third AC (3A)</option>
                        <option value="SL">Sleeper Class (SL)</option>
                        <option value="CC">Chair Car (CC)</option>
                        <option value="EC">Executive Chair Car (EC)</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="quota">Quota</label>
                    <select id="quota" class="input-field">
                        <option value="GN">General (GN)</option>
                        <option value="TQ">Tatkal</option>
                        <option value="LD">Ladies (LD)</option>
                        <option value="SS">Senior Citizen (SS)</option>
                        <option value="DQ">Duty Pass (DQ)</option>
                    </select>
                </div>

                <button type="submit" class="search-btn">Search Trains</button>
            </form>
        </div>

        <div class="train-list">
            <?php foreach ($trains as $train): ?>
            <div class="train-card">
                <div class="train-header">
                    <h3 class="train-name"><?php echo htmlspecialchars($train['name']); ?></h3>
                    <span class="train-type"><?php echo htmlspecialchars($train['type']); ?></span>
                </div>

                <div class="train-details">
                    <div class="detail-group">
                        <div class="detail-label">Departure</div>
                        <div class="detail-value">
                            <?php echo htmlspecialchars($train['from']); ?>
                            <br>
                            <?php echo htmlspecialchars($train['departure']); ?>
                        </div>
                    </div>
                    <div class="detail-group">
                        <div class="detail-label">Arrival</div>
                        <div class="detail-value">
                            <?php echo htmlspecialchars($train['to']); ?>
                            <br>
                            <?php echo htmlspecialchars($train['arrival']); ?>
                        </div>
                    </div>
                    <div class="detail-group">
                        <div class="detail-label">Duration</div>
                        <div class="detail-value"><?php echo htmlspecialchars($train['duration']); ?></div>
                    </div>
                    <div class="detail-group">
                        <div class="detail-label">Runs On</div>
                        <div class="detail-value"><?php echo htmlspecialchars(implode(", ", $train['runs_on'])); ?></div>
                    </div>
                </div>

                <div class="availability-section">
                    <?php foreach ($train['price'] as $class => $price): ?>
                    <div class="class-availability">
                        <span class="class-name"><?php echo htmlspecialchars($class); ?></span>
                        <span class="class-price">₹<?php echo number_format($price, 2); ?></span>
                        <span class="seats-available"><?php echo htmlspecialchars($train['seats_available'][$class]); ?> seats</span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($train['pantry']): ?>
                <div class="train-features">
                    <span class="feature">E-Catering Available</span>
                </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <input type="hidden" name="train_id" value="<?php echo $train['id']; ?>">
                    <button type="submit" class="book-btn">Book Now</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Set minimum date to today
        const dateInput = document.getElementById('date');
        const today = new Date().toISOString().split('T')[0];
        dateInput.min = today;
        dateInput.value = today;

        // Prevent selecting same station
        const fromSelect = document.getElementById('from');
        const toSelect = document.getElementById('to');

        fromSelect.addEventListener('change', function() {
            Array.from(toSelect.options).forEach(option => {
                option.disabled = option.value === this.value;
            });
        });

        toSelect.addEventListener('change', function() {
            Array.from(fromSelect.options).forEach(option => {
                option.disabled = option.value === this.value;
            });
        });
    </script>
</body>
</html>