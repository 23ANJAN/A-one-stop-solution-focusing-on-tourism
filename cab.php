<?php
// Sample cab data with Indian pricing
$cab_types = [
    [
        "id" => 1,
        "type" => "Standard",
        "capacity" => "4",
        "base_fare" => 150.00,
        "per_km" => 12.00,
        "waiting_time" => "3-5 mins",
        "description" => "Economic and comfortable sedan",
        "available" => 8
    ],
    [
        "id" => 2,
        "type" => "Premium",
        "capacity" => "4",
        "base_fare" => 200.00,
        "per_km" => 15.00,
        "waiting_time" => "4-6 mins",
        "description" => "Luxury sedan with extra comfort",
        "available" => 5
    ],
    [
        "id" => 3,
        "type" => "SUV",
        "capacity" => "6",
        "base_fare" => 250.00,
        "per_km" => 20.00,
        "waiting_time" => "5-8 mins",
        "description" => "Spacious vehicle for groups",
        "available" => 3
    ]
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = "Booking processed successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cab Booking</title>
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

   

        .location-form {
            padding: 1rem;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            z-index: 2;
        }

        .locations {
            display: grid;
            gap: 1rem;
            margin-bottom: 1rem;
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

        .ride-options {
            padding: 1rem;
        }

        .ride-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1rem;
            align-items: center;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .ride-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .ride-card.selected {
            border: 2px solid #2563eb;
        }

        .car-icon {
            font-size: 2rem;
            color: #4b5563;
            padding: 0.5rem;
        }

        .ride-details {
            display: flex;
            flex-direction: column;
        }

        .ride-type {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .ride-info {
            color: #4b5563;
            font-size: 0.875rem;
        }

        .ride-price {
            font-weight: 600;
            color: #2563eb;
        }

        .fare-estimate {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 0 1rem;
        }

        .estimate-header {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .estimate-details {
            display: grid;
            gap: 0.5rem;
            color: #4b5563;
            font-size: 0.9rem;
        }

        .estimate-row {
            display: flex;
            justify-content: space-between;
        }

        .total-row {
            border-top: 1px solid #ddd;
            padding-top: 0.5rem;
            margin-top: 0.5rem;
            font-weight: 600;
            color: #1f2937;
        }

        .book-btn {
            width: calc(100% - 2rem);
            background: #059669;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            margin: 1rem;
            transition: background-color 0.2s;
        }

        .book-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
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

        .waiting-time {
            text-align: right;
            color: #059669;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .map-wrapper {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    height: 0;
    overflow: hidden;
    width: 100%;
    max-width: 100%;
}

.map-wrapper iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100% !important;
    height: 100% !important;
}

/* For taller maps on larger screens */
@media screen and (min-width: 768px) {
    .map-wrapper {
        padding-bottom: 75%; /* 4:3 Aspect Ratio */
    }
}

/* Optional: Maximum height for very large screens */
@media screen and (min-width: 1200px) {
    .map-wrapper {
        max-height: 800px;
        padding-bottom: 50%;
    }
}
    </style>
</head>
<body>
    <div class="app-container">
        <header class="header">
            <a href="main.php" class="back-button">←</a>
            <h1>Book a Cab</h1>
        </header>

        <?php if (isset($message)): ?>
            <div class="notification">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="location-form">
            <div class="locations">
                <div class="input-group">
                    <label for="pickup">Pickup Location</label>
                    <select id="pickup" class="input-field" onchange="calculateFare()">
                        <option value="">Select Pickup Location</option>
                        <option value="Andheri">Andheri</option>
                        <option value="Bandra">Bandra</option>
                        <option value="Borivali">Borivali</option>
                        <option value="Dadar">Dadar</option>
                        <option value="Kurla">Kurla</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="dropoff">Drop-off Location</label>
                    <select id="dropoff" class="input-field" onchange="calculateFare()">
                        <option value="">Select Drop-off Location</option>
                        <option value="Andheri">Andheri</option>
                        <option value="Bandra">Bandra</option>
                        <option value="Borivali">Borivali</option>
                        <option value="Dadar">Dadar</option>
                        <option value="Kurla">Kurla</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="ride-options">
            <?php foreach ($cab_types as $cab): ?>
            <div class="ride-card" onclick="selectCab(this)" data-cab-id="<?php echo $cab['id']; ?>" 
                 data-base-fare="<?php echo $cab['base_fare']; ?>"
                 data-per-km="<?php echo $cab['per_km']; ?>">
                <div class="car-icon">🚗</div>
                <div class="ride-details">
                    <span class="ride-type"><?php echo htmlspecialchars($cab['type']); ?></span>
                    <span class="ride-info"><?php echo htmlspecialchars($cab['description']); ?></span>
                    <span class="ride-info"><?php echo htmlspecialchars($cab['capacity']); ?> seats</span>
                    <div class="waiting-time">
                        <?php echo htmlspecialchars($cab['waiting_time']); ?> waiting time
                    </div>
                </div>
                <div class="ride-price">
                    ₹<?php echo number_format($cab['base_fare'], 2); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="fare-estimate">
            <div class="estimate-header">Fare Estimate</div>
            <div class="estimate-details" id="fareDetails">
                <div class="estimate-row">
                    <span>Base Fare</span>
                    <span id="baseFare">₹0.00</span>
                </div>
                <div class="estimate-row">
                    <span>Distance Fare</span>
                    <span id="distanceFare">₹0.00</span>
                </div>
                <div class="estimate-row total-row">
                    <span>Total</span>
                    <span id="totalFare">₹0.00</span>
                </div>
            </div>
        </div>

        <form id="bookingForm" action="cab-payment.php" method="POST">
            <input type="hidden" name="pickup_location" id="pickup_location">
            <input type="hidden" name="dropoff_location" id="dropoff_location">
            <input type="hidden" name="selected_cab" id="selected_cab">
            <input type="hidden" name="estimated_fare" id="estimated_fare">
            <button type="submit" class="book-btn" disabled id="bookBtn">Book Now</button>
        </form>
    </div>

    <script>
        // Predefined distances between locations (in km)
        const distances = {
            'Andheri-Bandra': 10,
            'Andheri-Borivali': 15,
            'Andheri-Dadar': 12,
            'Andheri-Kurla': 8,
            'Bandra-Borivali': 20,
            'Bandra-Dadar': 7,
            'Bandra-Kurla': 6,
            'Borivali-Dadar': 25,
            'Borivali-Kurla': 22,
            'Dadar-Kurla': 5
        };

        let selectedCab = null;

        function getDistance(pickup, dropoff) {
            if (pickup === dropoff) return 0;
            const key = [pickup, dropoff].sort().join('-');
            return distances[key] || 0;
        }

        function selectCab(cabElement) {
            // Remove selected class from all cabs
            document.querySelectorAll('.ride-card').forEach(cab => {
                cab.classList.remove('selected');
            });
            
            // Add selected class to clicked cab
            cabElement.classList.add('selected');
            selectedCab = cabElement;
            
            calculateFare();
        }

        function calculateFare() {
            const pickup = document.getElementById('pickup').value;
            const dropoff = document.getElementById('dropoff').value;
            
            if (!pickup || !dropoff || !selectedCab) {
                document.getElementById('bookBtn').disabled = true;
                return;
            }

            const distance = getDistance(pickup, dropoff);
            const baseFare = parseFloat(selectedCab.dataset.baseFare);
            const perKm = parseFloat(selectedCab.dataset.perKm);
            
            const distanceCost = distance * perKm;
            const totalFare = baseFare + distanceCost;

            // Update fare display
            document.getElementById('baseFare').textContent = `₹${baseFare.toFixed(2)}`;
            document.getElementById('distanceFare').textContent = `₹${distanceCost.toFixed(2)}`;
            document.getElementById('totalFare').textContent = `₹${totalFare.toFixed(2)}`;

            // Update hidden form fields
            document.getElementById('pickup_location').value = pickup;
            document.getElementById('dropoff_location').value = dropoff;
            document.getElementById('selected_cab').value = selectedCab.dataset.cabId;
            document.getElementById('estimated_fare').value = totalFare.toFixed(2);

            // Enable booking button
            document.getElementById('bookBtn').disabled = false;
        }

        // Prevent selecting same location for pickup and dropoff
        document.getElementById('pickup').addEventListener('change', function() {
            const dropoff = document.getElementById('dropoff');
            Array.from(dropoff.options).forEach(option => {
                option.disabled = option.value === this.value && option.value !== '';
            });
        });

        document.getElementById('dropoff').addEventListener('change', function() {
            const pickup = document.getElementById('pickup');
            Array.from(pickup.options).forEach(option => {
                option.disabled = option.value === this.value && option.value !== '';
            });
        });
    </script>
</body>
</html>