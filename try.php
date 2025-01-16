<?php
    
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
    } // Start the session
  
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Appointment</title>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateSelector = document.getElementById('date');
            const hourSelector = document.getElementById('hour');

            dateSelector.addEventListener('change', function() {
                // Fetch unavailable times for selected date
                fetchUnavailableTimes(dateSelector.value);
            });

            function fetchUnavailableTimes(date) {
                // Assuming the get_unavailable_times.php endpoint returns data correctly
                fetch('payment.php')
                .then(response => response.json())
                .then(data => {
                    const unavailableHours = data[date] || [];
                    disableUnavailableHourOptions(unavailableHours);
                });
            }

            function disableUnavailableHourOptions(unavailableHours) {
                // Loop through all hour options
                for(let i = 0; i < hourSelector.options.length; i++) {
                    const option = hourSelector.options[i];
                    // Enable all options first
                    option.disabled = false;
                    // Then disable the unavailable ones
                    if(unavailableHours.includes(option.value)) {
                        option.disabled = true;
                    }
                }
            }
        });
    </script>
</head>
<body>
    <form>
        <label for="date">Date:</label>
        <input type="date" id="date" name="date">
        
        <label for="hour">Hour:</label>
        <select id="hour" name="hour">
            <!-- Hardcoded hour options -->
            <option value="09:00">09:00</option>
            <option value="10:00">10:00</option>
            <option value="11:00">11:00</option>
            <option value="12:00">12:00</option>
            <option value="13:00">13:00</option>
            <option value="14:00">14:00</option>
            <option value="15:00">15:00</option>
            <option value="16:00">16:00</option>
            <option value="17:00">17:00</option>
        </select>
    </form>
</body>
</html>
