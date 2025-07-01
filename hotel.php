<?php
// Sample Indian hotel data
$hotels = [
    [
        "id" => 1,
        "name" => "Taj Palace Hotel",
        "location" => "New Delhi",
        "rating" => 4.8,
        "price_per_night" => 15999.00,
        "amenities" => ["Free WiFi", "Swimming Pool", "Ayurvedic Spa", "Multi-Cuisine Restaurant", "24/7 Room Service", "Airport Transfer"],
        "rooms_available" => 15,
        "description" => "Luxury 5-star hotel offering the perfect blend of traditional Indian hospitality and modern comfort",
        "image" => "/api/placeholder/800/400?text=Taj+Palace+Hotel",
        "room_types" => ["Deluxe", "Premium", "Suite"],
        "meal_plans" => ["Room Only", "Breakfast Included", "Half Board", "Full Board"]
    ],
    [
        "id" => 2,
        "name" => "The Oberoi Udaivilas",
        "location" => "Udaipur, Rajasthan",
        "rating" => 4.9,
        "price_per_night" => 35999.00,
        "amenities" => ["Lake View", "Heritage Architecture", "Luxury Spa", "Private Pool", "Royal Butler Service", "Traditional Welcome"],
        "rooms_available" => 8,
        "description" => "Experience royal Rajasthani luxury in this palatial hotel overlooking Lake Pichola",
        "image" => "/api/placeholder/800/400?text=Oberoi+Udaivilas",
        "room_types" => ["Luxury", "Premier", "Royal Suite"],
        "meal_plans" => ["Bed & Breakfast", "Half Board", "Full Board"]
    ],
    [
        "id" => 3,
        "name" => "Leela Palace",
        "location" => "Bengaluru, Karnataka",
        "rating" => 4.7,
        "price_per_night" => 12999.00,
        "amenities" => ["Business Center", "Conference Rooms", "Rooftop Pool", "Multiple Restaurants", "Luxury Spa", "Airport Shuttle"],
        "rooms_available" => 25,
        "description" => "Perfect blend of business and luxury in the heart of India's Silicon Valley",
        "image" => "/api/placeholder/800/400?text=Leela+Palace",
        "room_types" => ["Business Suite", "Executive Room", "Royal Club"],
        "meal_plans" => ["Room Only", "Breakfast", "All Inclusive"]
    ],
    [
        "id" => 4,
        "name" => "Kumarakom Lake Resort",
        "location" => "Kumarakom, Kerala",
        "rating" => 4.8,
        "price_per_night" => 18999.00,
        "amenities" => ["Backwater Views", "Ayurvedic Center", "Houseboat Cruise", "Infinity Pool", "Kerala Cuisine", "Cultural Shows"],
        "rooms_available" => 12,
        "description" => "Traditional Kerala architecture meets luxury in this stunning backwater resort",
        "image" => "/api/placeholder/800/400?text=Kumarakom+Resort",
        "room_types" => ["Heritage Villa", "Pool Villa", "Luxury Pavilion"],
        "meal_plans" => ["Breakfast Included", "Kerala Special", "All Meals"]
    ]
];

// Handle form submission with redirect to payment page
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Location: hotel-payment.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Indian Hotels</title>
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

        .hotels-list {
            padding: 1rem;
        }

        .hotel-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .hotel-image {
            width: 100%;
            height: 200px;
            background-color: #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .hotel-content {
            padding: 1rem;
        }

        .hotel-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }

        .hotel-title {
            flex: 1;
        }

        .hotel-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .hotel-location {
            color: #4b5563;
            font-size: 0.9rem;
        }

        .hotel-price {
            color: #2563eb;
            font-weight: bold;
            font-size: 1.2rem;
            text-align: right;
        }

        .price-text {
            font-size: 0.8rem;
            color: #4b5563;
        }

        .hotel-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0.5rem 0;
        }

        .rating-stars {
            color: #f59e0b;
        }

        .rating-number {
            color: #4b5563;
            font-size: 0.9rem;
        }

        .hotel-description {
            color: #4b5563;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .amenity {
            background: #f3f4f6;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.875rem;
            color: #4b5563;
        }

        .hotel-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #f3f4f6;
        }

        .rooms-available {
            color: #059669;
            font-size: 0.875rem;
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
            <h1>Luxury Indian Hotels</h1>
        </header>

        <div class="search-container">
            <form class="search-form">
                <div class="input-group">
                    <label for="location">Destination</label>
                    <select id="location" class="input-field">
                        <option value="">Select destination</option>
                        <option value="delhi">New Delhi</option>
                        <option value="udaipur">Udaipur</option>
                        <option value="bengaluru">Bengaluru</option>
                        <option value="kerala">Kerala</option>
                        <option value="mumbai">Mumbai</option>
                        <option value="jaipur">Jaipur</option>
                        <option value="goa">Goa</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="check-in">Check-in</label>
                    <input type="date" id="check-in" class="input-field" required>
                </div>

                <div class="input-group">
                    <label for="check-out">Check-out</label>
                    <input type="date" id="check-out" class="input-field" required>
                </div>

                <div class="input-group">
                    <label for="guests">Guests</label>
                    <select id="guests" class="input-field">
                        <option value="1">1 Guest</option>
                        <option value="2">2 Guests</option>
                        <option value="3">3 Guests</option>
                        <option value="4">4 Guests</option>
                        <option value="5">5+ Guests</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="meal-plan">Meal Preference</label>
                    <select id="meal-plan" class="input-field">
                        <option value="veg">Pure Vegetarian</option>
                        <option value="non-veg">Non-Vegetarian</option>
                        <option value="jain">Jain</option>
                    </select>
                </div>

                <button type="submit" class="search-btn">Search Hotels</button>
            </form>
        </div>

        <div class="hotels-list">
            <?php foreach ($hotels as $hotel): ?>
            <div class="hotel-card">
                <div class="hotel-image">
                    <img src="<?php echo htmlspecialchars($hotel['image']); ?>" 
                         alt="<?php echo htmlspecialchars($hotel['name']); ?>" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="hotel-content">
                    <div class="hotel-header">
                        <div class="hotel-title">
                            <h3 class="hotel-name"><?php echo htmlspecialchars($hotel['name']); ?></h3>
                            <div class="hotel-location"><?php echo htmlspecialchars($hotel['location']); ?></div>
                        </div>
                        <div class="hotel-price">
                            ₹<?php echo number_format($hotel['price_per_night'], 2); ?>
                            <div class="price-text">per night</div>
                        </div>
                    </div>

                    <div class="hotel-rating">
                        <div class="rating-stars">
                            <?php
                            $rating = $hotel['rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $rating ? '★' : '☆';
                            }
                            ?>
                        </div>
                        <span class="rating-number"><?php echo $hotel['rating']; ?></span>
                    </div>

                    <div class="hotel-description">
                        <?php echo htmlspecialchars($hotel['description']); ?>
                    </div>

                    <div class="amenities">
                        <?php foreach ($hotel['amenities'] as $amenity): ?>
                        <span class="amenity"><?php echo htmlspecialchars($amenity); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="room-types">
                        <h4>Room Types:</h4>
                        <?php foreach ($hotel['room_types'] as $type): ?>
                        <span class="room-type"><?php echo htmlspecialchars($type); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="meal-plans">
                        <h4>Available Meal Plans:</h4>
                        <?php foreach ($hotel['meal_plans'] as $plan): ?>
                        <span class="meal-plan"><?php echo htmlspecialchars($plan); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="hotel-footer">
                        <div class="rooms-available">
                            <?php echo htmlspecialchars($hotel['rooms_available']); ?> rooms available
                        </div>
                    </div>

                    <form method="POST" action="">
                        <input type="hidden" name="hotel_id" value="<?php echo $hotel['id']; ?>">
                        <button type="submit" class="book-btn">Book Now</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Set minimum dates for check-in and check-out
        const checkIn = document.getElementById('check-in');
        const checkOut = document.getElementById('check-out');
        const today = new Date().toISOString().split('T')[0];
        
        checkIn.min = today;
        checkIn.value = today;
        
        checkIn.addEventListener('change', function() {
            checkOut.min = this.value;
            if (checkOut.value < this.value) {
                checkOut.value = this.value;
            }
        });
        
        checkOut.min = today;
        checkOut.value = today;
    </script>
</body>
</html>