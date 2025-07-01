
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel App - Main</title>
    <style>
          :root {
            --primary-color: #2563eb;
            --background-light: #f5f5f5;
            --text-dark: #333;
            --text-light: #666;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: var(--background-light);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
        }

        .app-container {
            background: var(--white);
            width: 100%;
           
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            position: relative;
        }

        @media screen and (min-width: 768px) {
            body {
                background: linear-gradient(135deg, #e0e7ff 0%, #f5f5f5 100%);
            }

            .app-container {
                border-radius: 20px;
                max-height: 90vh;
                overflow: hidden;
            }
        }

        .header {
            background: var(--primary-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
            position: relative;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        .user-info {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .user-info:hover {
            transform: translateY(-50%) scale(1.1);
        }

        .search-bar {
            padding: 1rem;
            background: #f8f9fa;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            padding: 1rem;
            grid-auto-rows: 1fr;
        }

        .service-card {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #eee;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .service-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
        }

        .service-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .service-description {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-top: 0.5rem;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
          display: none;
            background: white;
          
            justify-content: space-around;
            padding: 0.75rem;
            box-shadow: 0 -2px 15px rgba(0,0,0,0.1);
            z-index: 100;
        }

        .nav-item {
            text-align: center;
            cursor: pointer;
            opacity: 0.6;
            transition: all 0.3s ease;
        }

        .nav-item.active {
            opacity: 1;
        }

        .nav-icon {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .nav-text {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .active .nav-text {
            color: var(--primary-color);
        }

        .notifications {
            position: fixed;
            top: 1rem;
            left: 50%;
            transform: translateX(-50%);
            background: #4CAF50;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="header">
            <h1>Travel Services</h1>
            <div class="user-info">👤</div>
        </div>

        <div class="search-bar">
            <input type="text" class="search-input" placeholder="Search services...">
        </div>

        <div class="notifications" id="notificationBar">
            Booking successful!
        </div>

        <div class="services-grid">
            <div class="service-card" onclick="location.href='bus.php'">
                <div class="service-icon">🚌</div>
                <div class="service-name">Bus</div>
                <div class="service-description">Inter-city bus services</div>
            </div>

            <div class="service-card" onclick="location.href='cab.php'">
                <div class="service-icon">🚕</div>
                <div class="service-name">Cab</div>
                <div class="service-description">Local taxi services</div>
            </div>

            <div class="service-card" onclick="location.href='homestay.php'">
                <div class="service-icon">🏡</div>
                <div class="service-name">Home Stay</div>
                <div class="service-description">Cozy local accommodations</div>
            </div>

            <div class="service-card" onclick="location.href='hotel.php'">
                <div class="service-icon">🏨</div>
                <div class="service-name">Hotel</div>
                <div class="service-description">Luxury stays & resorts</div>
            </div>

            <div class="service-card" onclick="location.href='restaurants.php'">
                <div class="service-icon">🍽️</div>
                <div class="service-name">Restaurants</div>
                <div class="service-description">Local & international cuisine</div>
            </div>

            <div class="service-card" onclick="location.href='train.php'">
                <div class="service-icon">🚂</div>
                <div class="service-name">Train</div>
                <div class="service-description">Railway bookings</div>
            </div>
        </div>

        <div class="bottom-nav">
            <div class="nav-item active">
                <div class="nav-icon">🏠</div>
                <div class="nav-text">Home</div>
            </div>
            <div class="nav-item" onclick="location.href='nearby.php'">
                <div class="nav-icon">📍</div>
                <div class="nav-text">Nearby</div>
            </div>
            <div class="nav-item" onclick="location.href='bookings.php'">
                <div class="nav-icon">📋</div>
                <div class="nav-text">Bookings</div>
            </div>
            <div class="nav-item" onclick="location.href='support.php'">
                <div class="nav-icon">💬</div>
                <div class="nav-text">Support</div>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchText = e.target.value.toLowerCase();
            document.querySelectorAll('.service-card').forEach(card => {
                const serviceName = card.querySelector('.service-name').textContent.toLowerCase();
                const serviceDesc = card.querySelector('.service-description').textContent.toLowerCase();
                if (serviceName.includes(searchText) || serviceDesc.includes(searchText)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // User profile popup
        document.querySelector('.user-info').addEventListener('click', function() {
            alert('User Profile\nName: John Doe\nEmail: john@example.com');
        });

        // Bottom navigation handling
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.nav-item').forEach(nav => {
                    nav.classList.remove('active');
                });
                this.classList.add('active');
            });
        });

        // Show notification (example usage)
        function showNotification(message) {
            const notification = document.getElementById('notificationBar');
            notification.textContent = message;
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        // Example notification trigger
        // showNotification('Welcome back!');
    </script>
</body>
</html>