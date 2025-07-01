<?php
session_start();

// Redirect if no booking confirmation exists
if (!isset($_SESSION['booking_confirmed']) || !$_SESSION['booking_details']) {
    header('Location: cab.php');
    exit;
}

// Get booking details from session
$booking = $_SESSION['booking_details'];

// Clear the session data after displaying
unset($_SESSION['booking_confirmed']);
unset($_SESSION['booking_details']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - Travel App</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f5f5;
            min-height: 100vh;
        }

        .app-container {
            background: white;
            min-height: 100vh;
         
            margin: 0 auto;
        }

        .header {
            background: #2563eb;
            color: white;
            padding: 1rem;
            text-align: center;
            position: relative;
        }

        .home-button {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .confirmation-container {
            padding: 2rem 1rem;
            text-align: center;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #059669;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2.5rem;
        }

        .confirmation-title {
            color: #059669;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .booking-details {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            max-width: 500px;
            margin: 2rem auto;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .detail-label {
            color: #6b7280;
            font-weight: 500;
        }

        .detail-value {
            color: #111827;
            font-weight: 600;
        }

        .actions {
            margin-top: 2rem;
            display: grid;
            gap: 1rem;
            max-width: 500px;
            margin: 2rem auto;
        }

        .action-button {
            padding: 1rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            text-align: center;
        }

        .primary-button {
            background: #2563eb;
            color: white;
        }

        .secondary-button {
            background: #f3f4f6;
            color: #374151;
        }

        .info-text {
            color: #6b7280;
            margin-top: 2rem;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        @media print {
            .no-print {
                display: none;
            }
            
            body {
                background: white;
            }

            .app-container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <header class="header no-print">
            <a href="main.php" class="home-button">
                <span>←</span>
                <span>Home</span>
            </a>
            <h1>Booking Confirmation</h1>
        </header>

        <div class="confirmation-container">
            <div class="success-icon">✓</div>
            <h2 class="confirmation-title">Booking Confirmed!</h2>
            <p>Your cab booking has been successfully confirmed. Details are below:</p>

            <div class="booking-details">
                <div class="detail-row">
                    <span class="detail-label">Booking ID</span>
                    <span class="detail-value"><?php echo htmlspecialchars($booking['booking_id']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Name</span>
                    <span class="detail-value"><?php echo htmlspecialchars($booking['name']); ?></span>
                </div>

                <?php if (isset($booking['card_last_four'])): ?>
                <div class="detail-row">
                    <span class="detail-label">Payment Method</span>
                    <span class="detail-value">Card ending in <?php echo htmlspecialchars($booking['card_last_four']); ?></span>
                </div>
                <?php endif; ?>

                <div class="detail-row">
                    <span class="detail-label">Booking Date</span>
                    <span class="detail-value"><?php echo date('d M Y, h:i A'); ?></span>
                </div>
            </div>

            <div class="actions no-print">
                <button onclick="window.print()" class="action-button primary-button">
                    Print Confirmation
                </button>
                <a href="main.php" class="action-button secondary-button">
                    Return to Home
                </a>
            </div>

            <p class="info-text">
                A confirmation email has been sent to your registered email address.<br>
                For any queries, please contact our support team.
            </p>
        </div>
    </div>

    <script>
        // Prevent form resubmission on refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>