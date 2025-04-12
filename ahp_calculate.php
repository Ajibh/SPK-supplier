<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AHP Input Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Pilih Nilai AHP</h2>
        <form method="POST">
            <label>C1:</label>
            <div class="radio-group" id="group-c1"></div>

            <label>C2:</label>
            <div class="radio-group" id="group-c2"></div>

            <label>C3:</label>
            <div class="radio-group" id="group-c3"></div>

            <p id="error-message"></p>
            <button type="submit" id="submit-button" disabled>Hitung</button>
        </form>
    </div>

    <script src="script.js"></script>

    <?php include 'process.php'; ?>
</body>
</html>
