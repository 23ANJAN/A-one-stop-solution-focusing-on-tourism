<?php
// Sample Indian restaurant data
$restaurants = [
    [
        "id" => 1,
        "name" => "Punjab Dhaba",
        "cuisine" => "North Indian",
        "rating" => 4.7,
        "price_range" => "₹₹",
        "location" => "Connaught Place, Delhi",
        "opening_hours" => "11:00 AM - 11:00 PM",
        "popular_dishes" => ["Butter Chicken", "Dal Makhani", "Naan", "Paneer Tikka"],
        "features" => ["Family Seating", "Pure Veg Section", "Live Tandoor", "Outdoor Seating"],
        "description" => "Authentic Punjabi cuisine with traditional ambiance",
        "tables_available" => 8,
        "total_reviews" => 845,
        "min_booking" => 2,
        "max_booking" => 15,
        "image" => "/api/placeholder/800/400?text=Punjab+Dhaba",
        "meal_type" => ["veg", "non-veg"]
    ],
    [
        "id" => 2,
        "name" => "Dakshin",
        "cuisine" => "South Indian",
        "rating" => 4.8,
        "price_range" => "₹₹₹",
        "location" => "Bengaluru, Karnataka",
        "opening_hours" => "8:00 AM - 10:30 PM",
        "popular_dishes" => ["Masala Dosa", "Idli Sambar", "Filter Coffee", "Thali"],
        "features" => ["Pure Vegetarian", "Traditional Seating", "Live Dosa Counter"],
        "description" => "Premium South Indian vegetarian dining experience",
        "tables_available" => 12,
        "total_reviews" => 689,
        "min_booking" => 1,
        "max_booking" => 8,
        "image" => "/api/placeholder/800/400?text=Dakshin+Restaurant",
        "meal_type" => ["veg"]
    ],
    [
        "id" => 3,
        "name" => "Mughal Darbar",
        "cuisine" => "Mughlai",
        "rating" => 4.6,
        "price_range" => "₹₹₹",
        "location" => "Lucknow, UP",
        "opening_hours" => "12:00 PM - 11:00 PM",
        "popular_dishes" => ["Galouti Kebab", "Biryani", "Nihari", "Shahi Tukda"],
        "features" => ["Family Hall", "Kebab Counter", "Banquet Hall", "Valet Parking"],
        "description" => "Royal Mughlai cuisine with authentic Lucknowi flavors",
        "tables_available" => 15,
        "total_reviews" => 912,
        "min_booking" => 2,
        "max_booking" => 20,
        "image" => "/api/placeholder/800/400?text=Mughal+Darbar",
        "meal_type" => ["non-veg"]
    ],
    [
        "id" => 4,
        "name" => "Coastal Spice",
        "cuisine" => "Coastal Indian",
        "rating" => 4.7,
        "price_range" => "₹₹",
        "location" => "Mumbai, Maharashtra",
        "opening_hours" => "11:30 AM - 11:00 PM",
        "popular_dishes" => ["Fish Curry", "Prawn Masala", "Sol Kadhi", "Malvani Thali"],
        "features" => ["Fresh Seafood", "Family Seating", "Coastal Ambience"],
        "description" => "Authentic coastal cuisine from Maharashtra and Goa",
        "tables_available" => 10,
        "total_reviews" => 567,
        "min_booking" => 2,
        "max_booking" => 12,
        "image" => "/api/placeholder/800/400?text=Coastal+Spice",
        "meal_type" => ["non-veg", "seafood"]
    ]
];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Location: rest-payment.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indian Restaurant Reservations</title>

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

        .restaurants-list {
            padding: 1rem;
        }

        .restaurant-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .restaurant-image {
            width: 100%;
            height: 200px;
            background-color: #e5e7eb;
            position: relative;
        }

        .restaurant-content {
            padding: 1rem;
        }

        .restaurant-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }

        .restaurant-title {
            flex: 1;
        }

        .restaurant-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .restaurant-location {
            color: #4b5563;
            font-size: 0.9rem;
        }

        .price-range {
            color: #2563eb;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .cuisine-type {
            display: inline-block;
            background: #e5e7eb;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.875rem;
            color: #4b5563;
            margin: 0.5rem 0;
        }

        .rating-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .rating-stars {
            color: #f59e0b;
        }

        .review-count {
            color: #4b5563;
            font-size: 0.875rem;
        }

        .restaurant-description {
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

        .popular-dishes {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .dish {
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #4b5563;
        }

        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .feature {
            background: #f3f4f6;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.875rem;
            color: #4b5563;
        }

        .hours-availability {
            background: #f8f9fa;
            padding: 0.75rem;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .hours {
            color: #4b5563;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
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
    </style>
</head>
<body>
    <div class="app-container">
        <header class="header">
            <a href="main.php" class="back-button">←</a>
            <h1>Restaurant Reservations</h1>
        </header>

        <div class="search-container">
            <form class="search-form">
                <div class="input-group">
                    <label for="cuisine">Cuisine Type</label>
                    <select id="cuisine" class="input-field">
                        <option value="">All Cuisines</option>
                        <option value="north-indian">North Indian</option>
                        <option value="south-indian">South Indian</option>
                        <option value="mughlai">Mughlai</option>
                        <option value="coastal">Coastal Indian</option>
                        <option value="gujarati">Gujarati</option>
                        <option value="bengali">Bengali</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="meal-type">Meal Preference</label>
                    <select id="meal-type" class="input-field">
                        <option value="">Any</option>
                        <option value="veg">Pure Vegetarian</option>
                        <option value="non-veg">Non-Vegetarian</option>
                        <option value="jain">Jain</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" class="input-field">
                </div>

                <div class="input-group">
                    <label for="time">Time</label>
                    <select id="time" class="input-field">
                        <option value="">Select Time</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="19:00">7:00 PM</option>
                        <option value="20:00">8:00 PM</option>
                        <option value="21:00">9:00 PM</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="guests">Number of People</label>
                    <select id="guests" class="input-field">
                        <option value="2">2 People</option>
                        <option value="4">4 People</option>
                        <option value="6">6 People</option>
                        <option value="8">8 People</option>
                        <option value="10">10+ People</option>
                    </select>
                </div>

                <button type="submit" class="search-btn">Find Tables</button>
            </form>
        </div>

        <div class="restaurants-list">
            <?php foreach ($restaurants as $restaurant): ?>
            <div class="restaurant-card">
                <div class="restaurant-image">
                    <img src="<?php echo htmlspecialchars($restaurant['image']); ?>" 
                         alt="<?php echo htmlspecialchars($restaurant['name']); ?>" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="restaurant-content">
                    <div class="restaurant-header">
                        <div class="restaurant-title">
                            <h3 class="restaurant-name"><?php echo htmlspecialchars($restaurant['name']); ?></h3>
                            <div class="restaurant-location"><?php echo htmlspecialchars($restaurant['location']); ?></div>
                        </div>
                        <div class="price-range"><?php echo htmlspecialchars($restaurant['price_range']); ?></div>
                    </div>

                    <div class="cuisine-type"><?php echo htmlspecialchars($restaurant['cuisine']); ?></div>

                    <div class="rating-container">
                        <div class="rating-stars">
                            <?php
                            $rating = $restaurant['rating'];
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $rating ? '★' : '☆';
                            }
                            ?>
                        </div>
                        <span class="review-count"><?php echo $restaurant['total_reviews']; ?> reviews</span>
                    </div>

                    <div class="restaurant-description">
                        <?php echo htmlspecialchars($restaurant['description']); ?>
                    </div>

                    <div class="popular-dishes">
                        <h4>Signature Dishes:</h4>
                        <?php foreach ($restaurant['popular_dishes'] as $dish): ?>
                            <span class="dish"><?php echo htmlspecialchars($dish); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="features">
                        <?php foreach ($restaurant['features'] as $feature): ?>
                            <span class="feature"><?php echo htmlspecialchars($feature); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="hours-availability">
                        <div>Hours: <?php echo htmlspecialchars($restaurant['opening_hours']); ?></div>
                        <div><?php echo htmlspecialchars($restaurant['tables_available']); ?> tables available</div>
                    </div>

                    <form method="POST" action="">
                        <input type="hidden" name="restaurant_id" value="<?php echo $restaurant['id']; ?>">
                        <button type="submit" class="book-btn">Book Table</button>
                    </form>
                </div>
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

        // Time slots handling
        const timeSelect = document.getElementById('time');
        const currentHour = new Date().getHours();

        dateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            const timeOptions = timeSelect.options;

            if (selectedDate === today) {
                for (let option of timeOptions) {
                    const timeHour = parseInt(option.value.split(':')[0]);
                    option.disabled = timeHour <= currentHour;
                }
            } else {
                for (let option of timeOptions) {
                    option.disabled = false;
                }
            }
        });
    </script>
</body>
</html>