<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embed JotForm in Center of Page</title>
    <style>
        /* Reset default margin and padding */
        body, html {
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Prevents horizontal scrolling */
        }
        
        /* Adjust the container to ensure it's centered and can show the form properly */
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: 100vh; /* Ensures it's at least as tall as the viewport */
        }
        
        .jotform-form {
            margin: 0 auto; /* Centers the form horizontally */
            width: 100%; /* Ensures the form takes the full width of its container */
            max-width: 600px; /* Adjusts the form's maximum width */
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="jotform-form">
        <!-- Your JotForm Embed Code -->
        <script type="text/javascript" src="https://form.jotform.com/jsform/241013928761960"></script>
    </div>
</div>

</body>
</html>
