<?php
session_start();
require_once 'db_connect.php';

// Handle payment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_payment'])) {
    try {
        // Start transaction
        $pdo->beginTransaction();

        // Generate booking ID
        $booking_id = 'HOTEL' . rand(10000, 99999);
        
        // Insert into bookings table
        $stmt = $pdo->prepare("
            INSERT INTO hotel_bookings (
                booking_id, 
                customer_name, 
                customer_email, 
                customer_phone,
                pickup_address,
                destination_address,
                total_amount,
                payment_method,
                card_last_four,
                booking_date,
                status
            ) VALUES (
                :booking_id,
                :customer_name,
                :customer_email,
                :customer_phone,
                :pickup_address,
                :destination_address,
                :total_amount,
                :payment_method,
                :card_last_four,
                NOW(),
                'confirmed'
            )
        ");

        // Get last 4 digits of card if card payment
        $card_last_four = null;
        if (isset($_POST['card_number'])) {
            $card_last_four = substr(str_replace(' ', '', $_POST['card_number']), -4);
        }

        // Execute the insert
        $stmt->execute([
            'booking_id' => $booking_id,
            'customer_name' => $_POST['name'],
            'customer_email' => $_POST['email'],
            'customer_phone' => $_POST['phone'],
            'pickup_address' => $_POST['pickup_address'],
            'destination_address' => $_POST['destination_address'],
            'total_amount' => 12500.00, // Fixed amount for demo, you can modify this
            'payment_method' => $_POST['payment_method'],
            'card_last_four' => $card_last_four
        ]);

        // Insert payment details if using card
        if ($_POST['payment_method'] === 'card') {
            $stmt = $pdo->prepare("
                INSERT INTO payment_details (
                    booking_id,
                    card_holder,
                    card_last_four,
                    expiry_date,
                    payment_status
                ) VALUES (
                    :booking_id,
                    :card_holder,
                    :card_last_four,
                    :expiry_date,
                    'completed'
                )
            ");

            $stmt->execute([
                'booking_id' => $booking_id,
                'card_holder' => $_POST['card_name'],
                'card_last_four' => $card_last_four,
                'expiry_date' => $_POST['expiry']
            ]);
        }

        // Commit transaction
        $pdo->commit();

        // Store booking details in session for confirmation page
        $_SESSION['booking_confirmed'] = true;
        $_SESSION['booking_details'] = [
            'booking_id' => $booking_id,
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'pickup_address' => $_POST['pickup_address'],
            'destination_address' => $_POST['destination_address'],
            'total_amount' => 12500.00 // Fixed amount for demo
        ];

        header('Location: confirmation.php');
        exit;
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        $error_message = "An error occurred while processing your payment. Please try again.";
    }
}
?>

<?php

require_once 'db_connect.php';

// Handle payment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_payment'])) {
    try {
        // Start transaction
        $pdo->beginTransaction();

        // Generate booking ID
        $booking_id = 'hotel' . rand(10000, 99999);
        
        // Get last 4 digits of card if card payment
        $card_last_four = null;
        if (isset($_POST['card_number'])) {
            $card_last_four = substr(str_replace(' ', '', $_POST['card_number']), -4);
        }

        // Insert payment details
        $stmt = $pdo->prepare("
            INSERT INTO payment (
                booking_id,
                card_holder,
                card_last_four,
                expiry_date,
                payment_status,
                created_at
            ) VALUES (
                :booking_id,
                :card_holder,
                :card_last_four,
                :expiry_date,
                'completed',
                NOW()
            )
        ");

        // Execute the payment insert
        $stmt->execute([
            'booking_id' => $booking_id,
            'card_holder' => $_POST['card_name'],
            'card_last_four' => $card_last_four,
            'expiry_date' => $_POST['expiry']
        ]);

        // Commit transaction
        $pdo->commit();

        // Store booking details in session for confirmation page
        $_SESSION['booking_confirmed'] = true;
        $_SESSION['booking_details'] = [
            'booking_id' => $booking_id,
            'name' => $_POST['card_name'],
            'card_last_four' => $card_last_four
        ];

        header('Location: confirmation.php');
        exit;
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        $error_message = "An error occurred while processing your payment. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>hotel Payment - Travel App</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f5f5;
            margin: 0 auto;
            min-height: 100vh;
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

        .payment-container {
            padding: 1rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .booking-summary {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 1.5rem 0 1rem;
            color: #374151;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4b5563;
        }

        .input-field {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .card-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 0.5rem;
        }

        .submit-btn {
            background: #059669;
            color: white;
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem;
        }

        .payment-methods {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .payment-method {
            flex: 1;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method.active {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .error {
            color: #dc2626;
            font-size: 0.9rem;
            margin-top: 0.25rem;
            padding: 0.5rem;
            background: #fee2e2;
            border-radius: 4px;
        }

        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: #059669;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <header class="header">
            <a href="hotel.php" class="back-button">←</a>
            <h1>hotel Payment</h1>
        </header>

        <div class="payment-container">
            <?php if (isset($error_message)): ?>
                <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <form method="POST" id="paymentForm" onsubmit="return validateForm()">
                <input type="hidden" name="payment_method" id="payment_method" value="card">
                
                <h2 class="section-title">Trip Details</h2>
                <div class="form-group">
                    <label for="pickup_address">Pickup Address</label>
                    <input type="text" id="pickup_address" name="pickup_address" class="input-field" required>
                </div>

                <div class="form-group">
                    <label for="destination_address">Destination Address</label>
                    <input type="text" id="destination_address" name="destination_address" class="input-field" required>
                </div>

                <h2 class="section-title">Personal Information</h2>
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="input-field" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="input-field" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="input-field" required>
                </div>

                <h2 class="section-title">Payment Method</h2>
                <div class="payment-methods">
                    <div class="payment-method active" onclick="selectPaymentMethod(this, 'card')">
                        <div>💳</div>
                        <div>Card</div>
                    </div>
                    <div class="payment-method" onclick="selectPaymentMethod(this, 'upi')">
                        <div>📱</div>
                        <div>UPI</div>
                    </div>
                    <div class="payment-method" onclick="selectPaymentMethod(this, 'netbanking')">
                        <div>🏦</div>
                        <div>Net Banking</div>
                    </div>
                </div>

                <div id="cardDetails">
                    <div class="form-group">
                        <label for="card_number">Card Number</label>
                        <input type="text" id="card_number" name="card_number" class="input-field" placeholder="1234 5678 9012 3456" required>
                    </div>

                    <div class="card-row">
                        <div class="form-group">
                            <label for="card_name">Name on Card</label>
                            <input type="text" id="card_name" name="card_name" class="input-field" required>
                        </div>
                        <div class="form-group">
                            <label for="expiry">Expiry</label>
                            <input type="text" id="expiry" name="expiry" class="input-field" placeholder="MM/YY" required>
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="password" id="cvv" name="cvv" class="input-field" placeholder="123" required maxlength="3">
                        </div>
                    </div>
                </div>

                <button type="submit" name="submit_payment" class="submit-btn">
                    Pay  ₹12500.00
                </button>

                <div class="secure-badge">
                    🔒 Secure Payment
                </div>
            </form>
        </div>
    </div>

    <script>
        function selectPaymentMethod(element, method) {
            document.querySelectorAll('.payment-method').forEach(method => {
                method.classList.remove('active');
            });
            element.classList.add('active');
            document.getElementById('payment_method').value = method;
            
            const cardDetails = document.getElementById('cardDetails');
            cardDetails.style.display = method === 'card' ? 'block' : 'none';

            const cardInputs = cardDetails.querySelectorAll('input');
            cardInputs.forEach(input => {
                input.required = method === 'card';
            });
        }

        function validateForm() {
            const paymentMethod = document.getElementById('payment_method').value;
            if (paymentMethod !== 'card') return true;

            const cardNumber = document.getElementById('card_number');
            const expiry = document.getElementById('expiry');
            const cvv = document.getElementById('cvv');

            if (cardNumber.value) {
                if (cardNumber.value.replace(/\s/g, '').length !== 16) {
                    alert('Please enter a valid 16-digit card number');
                    return false;
                }
            }

            if (expiry.value) {
                if (!/^\d\d\/\d\d$/.test(expiry.value)) {
                    alert('Please enter expiry in MM/YY format');
                    return false;
                }
            }

            if (cvv.value) {
                if (cvv.value.length !== 3) {
                    alert('Please enter a valid 3-digit CVV');
                    return false;
                }
            }

            return true;
        }

        document.getElementById('card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            if (value.length > 16) value = value.substr(0, 16);
            e.target.value = value.replace(/(\d{4})/g, '$1 ').trim();
        });

        document.getElementById('expiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substr(0, 2) + '/' + value.substr(2, 2);
            }
            e.target.value = value;
        });

        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) value = value.substr(0, 10);
            if (value.length >= 6) {
                value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
            } else if (value.length >= 3) {
                value = value.replace(/(\d{3})(\d{0,3})/, '($1) $2');
            }
            e.target.value = value;
        });

        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

 
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.querySelector('.search-form');
        const hotelsList = document.querySelectorAll('.hotel-card');

        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get search values
            const location = document.getElementById('location').value.toLowerCase();
            const guests = parseInt(document.getElementById('guests').value);
            const mealPref = document.getElementById('meal-plan').value;

            // Loop through each hotel card and check if it matches search criteria
            hotelsList.forEach(hotel => {
                const hotelLocation = hotel.querySelector('.hotel-location').textContent.toLowerCase();
                const maxGuests = parseInt(hotel.querySelector('[name="guests"]')?.value || 4);
                
                // Check if hotel matches search criteria
                const locationMatch = !location || hotelLocation.includes(location);
                const guestsMatch = !guests || maxGuests >= guests;

                // Show/hide based on matches
                if (locationMatch && guestsMatch) {
                    hotel.style.display = 'block';
                } else {
                    hotel.style.display = 'none';
                }
            });

            // Check if any hotels are visible
            const visibleHotels = document.querySelectorAll('.hotel-card[style="display: block"]');
            const noResultsDiv = document.querySelector('.no-results') || document.createElement('div');
            noResultsDiv.className = 'no-results';
            
            if (visibleHotels.length === 0) {
                noResultsDiv.innerHTML = '<p>No hotels found matching your criteria.</p>';
                document.querySelector('.hotels-list').appendChild(noResultsDiv);
            } else if (document.querySelector('.no-results')) {
                document.querySelector('.no-results').remove();
            }
        });

        // Reset button functionality (optional)
        const resetButton = document.createElement('button');
        resetButton.textContent = 'Reset Search';
        resetButton.className = 'search-btn';
        resetButton.style.marginTop = '10px';
        resetButton.onclick = function() {
            hotelsList.forEach(hotel => hotel.style.display = 'block');
            searchForm.reset();
            const noResults = document.querySelector('.no-results');
            if (noResults) noResults.remove();
        };
        searchForm.appendChild(resetButton);
    });
</script>
   
</body>
</html>