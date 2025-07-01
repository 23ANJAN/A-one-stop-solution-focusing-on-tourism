<?php
// Sample Indian homestay data
$homestays = [
    [
        "id" => 1,
        "name" => "Heritage Haveli Stay",
        "host_name" => "Sharma Family",
        "host_rating" => 4.8,
        "location" => "Jaipur, Rajasthan",
        "state" => "rajasthan",
        "price_per_night" => 3500.00,
        "description" => "Experience royal Rajasthani hospitality in our 150-year-old restored haveli with traditional architecture and modern amenities",
        "amenities" => ["Air Conditioning", "Traditional Indian Breakfast", "WiFi", "Courtyard Garden", "Evening Tea Service", "Cultural Activities"],
        "house_rules" => ["No smoking", "Pure vegetarian kitchen", "Quiet hours 10PM-6AM", "Guests must remove shoes inside"],
        "rooms_available" => 1,
        "guests_max" => 3,
        "total_reviews" => 127,
        "meal_type" => "veg",
        "images" => [
            "/api/placeholder/800/400?text=Haveli+Main+View",
            "/api/placeholder/800/400?text=Haveli+Courtyard",
            "/api/placeholder/800/400?text=Haveli+Room",
            "/api/placeholder/800/400?text=Haveli+Garden"
        ],
        "thumbnail" => "/api/placeholder/400/300?text=Haveli+Thumbnail"
    ],
    [
        "id" => 2,
        "name" => "Kerala Backwater Villa",
        "host_name" => "Thomas & Mary Kurian",
        "host_rating" => 4.9,
        "location" => "Alleppey, Kerala",
        "state" => "kerala",
        "price_per_night" => 4500.00,
        "description" => "Traditional Kerala house overlooking backwaters with homemade Kerala cuisine and ayurvedic treatments available",
        "amenities" => ["Waterfront View", "Kerala Breakfast", "WiFi", "Boat Rides", "Ayurvedic Massage", "Cooking Classes"],
        "house_rules" => ["No alcohol", "Dress modestly", "Quiet hours after 9PM"],
        "rooms_available" => 2,
        "guests_max" => 4,
        "total_reviews" => 89,
        "meal_type" => "non-veg",
        "images" => [
            "/api/placeholder/800/400?text=Kerala+Villa+Waterfront",
            "/api/placeholder/800/400?text=Kerala+Villa+Interior",
            "/api/placeholder/800/400?text=Kerala+Villa+Room",
            "/api/placeholder/800/400?text=Kerala+Villa+Garden"
        ],
        "thumbnail" => "/api/placeholder/400/300?text=Kerala+Villa+Thumbnail"
    ],
    [
        "id" => 3,
        "name" => "Himachali Mountain Cottage",
        "host_name" => "Singh Family",
        "host_rating" => 4.7,
        "location" => "Manali, Himachal Pradesh",
        "state" => "himachal",
        "price_per_night" => 2800.00,
        "description" => "Cozy wooden cottage with stunning Himalayan views, traditional Pahadi architecture, and home-cooked mountain cuisine",
        "amenities" => ["Mountain View", "Bonfire", "WiFi", "Himalayan Breakfast", "Guided Treks", "Indoor Heating"],
        "house_rules" => ["No smoking indoors", "No loud music", "Local culture respect", "Eco-friendly practices"],
        "rooms_available" => 1,
        "guests_max" => 2,
        "total_reviews" => 156,
        "meal_type" => "both",
        "images" => [
            "/api/placeholder/800/400?text=Mountain+Cottage+View",
            "/api/placeholder/800/400?text=Mountain+Cottage+Interior",
            "/api/placeholder/800/400?text=Mountain+Cottage+Room",
            "/api/placeholder/800/400?text=Mountain+Cottage+Surroundings"
        ],
        "thumbnail" => "/api/placeholder/400/300?text=Mountain+Cottage+Thumbnail"
    ],
    [
        "id" => 4,
        "name" => "Goan Portuguese Villa",
        "host_name" => "Fonseca Family",
        "host_rating" => 4.8,
        "location" => "Fontainhas, Goa",
        "state" => "goa",
        "price_per_night" => 5000.00,
        "description" => "Historic Portuguese villa in Goa's Latin Quarter with antique furniture and traditional Goan hospitality",
        "amenities" => ["Swimming Pool", "Colonial Architecture", "WiFi", "Beach Proximity", "Goan Breakfast", "Heritage Tours"],
        "house_rules" => ["Respectful behavior", "Pool hours 8AM-7PM", "No parties", "Quiet neighborhood"],
        "rooms_available" => 2,
        "guests_max" => 5,
        "total_reviews" => 143,
        "meal_type" => "non-veg",
        "images" => [
            "/api/placeholder/800/400?text=Goan+Villa+Exterior",
            "/api/placeholder/800/400?text=Goan+Villa+Pool",
            "/api/placeholder/800/400?text=Goan+Villa+Room",
            "/api/placeholder/800/400?text=Goan+Villa+Garden"
        ],
        "thumbnail" => "/api/placeholder/400/300?text=Goan+Villa+Thumbnail"
    ]
];

// Handle search functionality
$filtered_homestays = $homestays;

if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
    $location = isset($_GET['location']) ? strtolower($_GET['location']) : '';
    $guests = isset($_GET['guests']) ? (int)$_GET['guests'] : 0;
    $meal_preference = isset($_GET['meal_preference']) ? $_GET['meal_preference'] : '';

    if (!empty($location)) {
        $filtered_homestays = array_filter($filtered_homestays, function($homestay) use ($location) {
            return $homestay['state'] === $location;
        });
    }

    if ($guests > 0) {
        $filtered_homestays = array_filter($filtered_homestays, function($homestay) use ($guests) {
            return $homestay['guests_max'] >= $guests;
        });
    }

    if (!empty($meal_preference)) {
        $filtered_homestays = array_filter($filtered_homestays, function($homestay) use ($meal_preference) {
            if ($meal_preference === 'veg') {
                return $homestay['meal_type'] === 'veg';
            } elseif ($meal_preference === 'non-veg') {
                return $homestay['meal_type'] === 'non-veg' || $homestay['meal_type'] === 'both';
            }
            return true;
        });
    }
}

// Handle booking submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Location: homestay-payment.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indian Homestay Booking</title>
    <!-- Original CSS remains unchanged -->
    <style>
        .no-results {
            text-align: center;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 1rem;
        }


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

        .homestays-list {
            padding: 1rem;
        }

        .homestay-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .homestay-image {
            width: 100%;
            height: 200px;
            background-color: #e5e7eb;
            position: relative;
        }

        .homestay-content {
            padding: 1rem;
        }

        .homestay-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .homestay-title {
            flex: 1;
        }

        .homestay-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .homestay-location {
            color: #4b5563;
            font-size: 0.9rem;
        }

        .homestay-price {
            color: #2563eb;
            font-weight: bold;
            font-size: 1.2rem;
            text-align: right;
        }

        .price-text {
            font-size: 0.8rem;
            color: #4b5563;
        }

        .host-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1rem 0;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .host-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .host-details {
            flex: 1;
        }

        .host-name {
            font-weight: 500;
            color: #1f2937;
        }

        .host-rating {
            color: #4b5563;
            font-size: 0.875rem;
        }

        .homestay-description {
            color: #4b5563;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .section-title {
            font-weight: 600;
            color: #1f2937;
            margin: 1rem 0 0.5rem 0;
            font-size: 1rem;
        }

        .amenities, .house-rules {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .amenity, .rule {
            background: #f3f4f6;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.875rem;
            color: #4b5563;
        }

        .rule {
            background: #fee2e2;
            color: #991b1b;
        }

        .homestay-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #f3f4f6;
        }

        .availability {
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

        .rating-stars {
            color: #f59e0b;
        }

        .reviews-count {
            color: #4b5563;
            font-size: 0.875rem;
        }

    </style>
</head>
<body>
    <div class="app-container">
        <header class="header">
            <a href="main.php" class="back-button">←</a>
            <h1>Indian Homestay Experience</h1>
        </header>

        <?php if (isset($message)): ?>
        <div class="notification">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <div class="search-container">
            <form class="search-form" method="GET" action="">
                <div class="input-group">
                    <label for="location">State/Region</label>
                    <select id="location" name="location" class="input-field">
                        <option value="">Select location</option>
                        <option value="rajasthan" <?php echo isset($_GET['location']) && $_GET['location'] === 'rajasthan' ? 'selected' : ''; ?>>Rajasthan</option>
                        <option value="kerala" <?php echo isset($_GET['location']) && $_GET['location'] === 'kerala' ? 'selected' : ''; ?>>Kerala</option>
                        <option value="himachal" <?php echo isset($_GET['location']) && $_GET['location'] === 'himachal' ? 'selected' : ''; ?>>Himachal Pradesh</option>
                        <option value="goa" <?php echo isset($_GET['location']) && $_GET['location'] === 'goa' ? 'selected' : ''; ?>>Goa</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="check-in">Check-in</label>
                    <input type="date" id="check-in" name="check_in" class="input-field" required>
                </div>

                <div class="input-group">
                    <label for="check-out">Check-out</label>
                    <input type="date" id="check-out" name="check_out" class="input-field" required>
                </div>

                <div class="input-group">
                    <label for="guests">Guests</label>
                    <select id="guests" name="guests" class="input-field">
                        <option value="1" <?php echo isset($_GET['guests']) && $_GET['guests'] == '1' ? 'selected' : ''; ?>>1 Guest</option>
                        <option value="2" <?php echo isset($_GET['guests']) && $_GET['guests'] == '2' ? 'selected' : ''; ?>>2 Guests</option>
                        <option value="3" <?php echo isset($_GET['guests']) && $_GET['guests'] == '3' ? 'selected' : ''; ?>>3 Guests</option>
                        <option value="4" <?php echo isset($_GET['guests']) && $_GET['guests'] == '4' ? 'selected' : ''; ?>>4+ Guests</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="meal_preference">Meal Preference</label>
                    <select id="meal_preference" name="meal_preference" class="input-field">
                        <option value="">Any</option>
                        <option value="veg" <?php echo isset($_GET['meal_preference']) && $_GET['meal_preference'] === 'veg' ? 'selected' : ''; ?>>Vegetarian</option>
                        <option value="non-veg" <?php echo isset($_GET['meal_preference']) && $_GET['meal_preference'] === 'non-veg' ? 'selected' : ''; ?>>Non-Vegetarian</option>
                    </select>
                </div>

                <button type="submit" class="search-btn">Search Homestays</button>
            </form>
        </div>

        <div class="homestays-list">
            <?php if (empty($filtered_homestays)): ?>
                <div class="no-results">
                    <p>No homestays found matching your criteria. Please try adjusting your search.</p>
                </div>
            <?php else: ?>
                <?php foreach ($filtered_homestays as $homestay): ?>
                    <div class="homestay-card">
                        <div class="homestay-image">
                            <img src="/api/placeholder/800/400?text=<?php echo urlencode($homestay['name']); ?>" 
                                 alt="<?php echo htmlspecialchars($homestay['name']); ?>" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="homestay-content">
                            <div class="homestay-header">
                                <div class="homestay-title">
                                    <h3 class="homestay-name"><?php echo htmlspecialchars($homestay['name']); ?></h3>
                                    <div class="homestay-location"><?php echo htmlspecialchars($homestay['location']); ?></div>
                                </div>
                                <div class="homestay-price">
                                    ₹<?php echo number_format($homestay['price_per_night'], 2); ?>
                                    <div class="price-text">per night</div>
                                </div>
                            </div>

                            <div class="host-info">
                                <div class="host-avatar">👥</div>
                                <div class="host-details">
                                    <div class="host-name">Hosted by <?php echo htmlspecialchars($homestay['host_name']); ?></div>
                                    <div class="host-rating">
                                        <span class="rating-stars">
                                            <?php
                                            $rating = $homestay['host_rating'];
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $rating ? '★' : '☆';
                                            }
                                            ?>
                                        </span>
                                        <span class="reviews-count"><?php echo $homestay['total_reviews']; ?> reviews</span>
                                    </div>
                                </div>
                            </div>

                            <div class="homestay-description">
                                <?php echo htmlspecialchars($homestay['description']); ?>
                            </div>

                            <div class="section-title">Amenities & Experiences</div>
                            <div class="amenities">
                                <?php foreach ($homestay['amenities'] as $amenity): ?>
                                    <span class="amenity"><?php echo htmlspecialchars($amenity); ?></span>
                                <?php endforeach; ?>
                            </div>

                            <div class="section-title">House Guidelines</div>
                            <div class="house-rules">
                                <?php foreach ($homestay['house_rules'] as $rule): ?>
                                    <span class="rule"><?php echo htmlspecialchars($rule); ?></span>
                                <?php endforeach; ?>
                            </div>

                            <div class="homestay-footer">
                                <div class="availability">
                                    Can accommodate up to <?php echo htmlspecialchars($homestay['guests_max']); ?> guests
                                </div>
                            </div>

                            <form method="POST" action="">
    <button type="submit" class="book-btn">Book Your Stay</button>
</form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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