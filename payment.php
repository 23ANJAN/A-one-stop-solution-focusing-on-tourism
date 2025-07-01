<?php
session_start();

// Sample route data - in real application, this would come from a database
$routes = [
    1 => ["name" => "New York → Boston", "price" => 45.00],
    2 => ["name" => "New York → Washington DC", "price" => 55.00],
    3 => ["name" => "New York → Philadelphia", "price" => 35.00]
];

// Get route details from POST or default to empty
$route_id = $_POST['route_id'] ?? null;
$route = $route_id && isset($routes[$route_id]) ? $routes[$route_id] : null;

// Handle payment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_payment'])) {
    // In a real application, you would:
    // 1. Validate payment details
    // 2. Process payment through payment gateway
    // 3. Store booking details in database
    // 4. Send confirmation email
    
    // For demo, just redirect to confirmation
    $_SESSION['booking_confirmed'] = true;
    $_SESSION['booking_details'] = [
        'route' => $route['name'],
        'price' => $route['price'],
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'booking_id' => 'BK' . rand(10000, 99999)
    ];
    header('Location: confirmation.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Travel App</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f5f5;
            max-width: 480px;
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
        }

        .booking-summary {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            color: #4b5563;
        }

        .total-row {
            border-top: 2px solid #e5e7eb;
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            font-weight: bold;
            color: #000;
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

        .payment-method img {
            width: 40px;
            height: 40px;
            margin-bottom: 0.5rem;
        }

        .error {
            color: #dc2626;
            font-size: 0.9rem;
            margin-top: 0.25rem;
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
            <a href="bus.php" class="back-button">←</a>
            <h1>Payment</h1>
        </header>

        <div class="payment-container">
            <?php if ($route): ?>
            <div class="booking-summary">
                <h2 class="section-title">Booking Summary</h2>
                <div class="summary-row">
                    <span>Route</span>
                    <span><?php echo htmlspecialchars($route['name']); ?></span>
                </div>
                <div class="summary-row">
                    <span>Date</span>
                    <span><?php echo date('d M Y'); ?></span>
                </div>
                <div class="summary-row">
                    <span>Passengers</span>
                    <span>1</span>
                </div>
                <div class="summary-row total-row">
                    <span>Total Amount</span>
                    <span>$<?php echo number_format($route['price'], 2); ?></span>
                </div>
            </div>

            <form method="POST" id="paymentForm" onsubmit="return validateForm()">
                <input type="hidden" name="route_id" value="<?php echo htmlspecialchars($route_id); ?>">
                
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
                    <div class="payment-method active" onclick="selectPaymentMethod(this)">
                        <div>💳</div>
                        <div>Card</div>
                    </div>
                    <div class="payment-method" onclick="selectPaymentMethod(this)">
                        <div>📱</div>
                        <div>UPI</div>
                    </div>
                    <div class="payment-method" onclick="selectPaymentMethod(this)">
                        <div>🏦</div>
                        <div>Net Banking</div>
                    </div>
                </div>

                <div id="cardDetails">
                    <div class="form-group">
                        <label for="card_number">Card Number</label>
                        <input type="text" id="card_number" class="input-field" placeholder="1234 5678 9012 3456" required>
                    </div>

                    <div class="card-row">
                        <div class="form-group">
                            <label for="card_name">Name on Card</label>
                            <input type="text" id="card_name" class="input-field" required>
                        </div>
                        <div class="form-group">
                            <label for="expiry">Expiry</label>
                            <input type="text" id="expiry" class="input-field" placeholder="MM/YY" required>
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="password" id="cvv" class="input-field" placeholder="123" required maxlength="3">
                        </div>
                    </div>
                </div>

                <button type="submit" name="submit_payment" class="submit-btn">
                    Pay $<?php echo number_format($route['price'], 2); ?>
                </button>

                <div class="secure-badge">
                    🔒 Secure Payment
                </div>
            </form>
            <?php else: ?>
            <div style="padding: 2rem; text-align: center;">
                <p>Invalid route selected. Please go back and try again.</p>
                <a href="bus.php" style="color: #2563eb; text-decoration: none;">← Back to Bus Booking</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function selectPaymentMethod(element) {
            document.querySelectorAll('.payment-method').forEach(method => {
                method.classList.remove('active');
            });
            element.classList.add('active');
            
            // Show/hide card details based on selection
            const cardDetails = document.getElementById('cardDetails');
            cardDetails.style.display = element.innerText.includes('Card') ? 'block' : 'none';
        }

        function validateForm() {
            const cardNumber = document.getElementById('card_number');
            const expiry = document.getElementById('expiry');
            const cvv = document.getElementById('cvv');

            // Basic validation
            if (cardNumber.value) {
                // Remove spaces and check length
                if (cardNumber.value.replace(/\s/g, '').length !== 16) {
                    alert('Please enter a valid 16-digit card number');
                    return false;
                }
            }

            if (expiry.value) {
                // Check MM/YY format
                if (!/^\d\d\/\d\d$/.test(expiry.value)) {
                    alert('Please enter expiry in MM/YY format');
                    return false;
                }
            }

            if (cvv.value) {
                // Check CVV length
                if (cvv.value.length !== 3) {
                    alert('Please enter a valid 3-digit CVV');
                    return false;
                }
            }

            return true;
        }

        // Format card number as user types
        document.getElementById('card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            if (value.length > 16) value = value.substr(0, 16);
            e.target.value = value.replace(/(\d{4})/g, '$1 ').trim();
        });

        // Format expiry date as user types
        document.getElementById('expiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substr(0, 2) + '/' + value.substr(2, 2);
            }
            e.target.value = value;
        });
    </script>
</body>
</html>