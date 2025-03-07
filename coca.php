<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AHP Input Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 10px;
            color: #333;
        }
        .radio-group {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }
        .radio-group input {
            display: none;
        }
        .radio-group label {
            background: #e0e0e0;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .radio-group input:checked + label {
            background: #007BFF;
            color: white;
            font-weight: bold;
        }
        .hidden {
            display: none;
        }
        #error-message {
            color: red;
            font-size: 0.9em;
            margin-bottom: 10px;
            min-height: 20px;
        }
        button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: 0.3s;
            width: 100%;
        }
        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
    </style>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const criteria = ["c1", "c2", "c3"];
            const maxSum = 9;
            const submitButton = document.getElementById("submit-button");
            const errorMessage = document.getElementById("error-message");

            // Fungsi untuk membuat radio button dalam bentuk tombol
            function createRadioButtons(name) {
                let container = document.getElementById(`group-${name}`);
                for (let i = 1; i <= 7; i++) {
                    let input = document.createElement("input");
                    input.type = "radio";
                    input.name = name;
                    input.value = i;
                    input.id = `${name}-${i}`;

                    let label = document.createElement("label");
                    label.htmlFor = input.id;
                    label.textContent = i;

                    container.appendChild(input);
                    container.appendChild(label);
                }
            }

            criteria.forEach(createRadioButtons);

            // Fungsi untuk mendapatkan nilai terpilih
            function getSelectedValues() {
                let values = {};
                criteria.forEach(name => {
                    let selected = document.querySelector(`input[name="${name}"]:checked`);
                    values[name] = selected ? parseInt(selected.value) : null;
                });
                return values;
            }

            // Fungsi untuk memperbarui state input
            function updateInputs() {
                let values = getSelectedValues();
                let selectedCount = Object.values(values).filter(v => v !== null).length;
                let remainingValue = maxSum - (values.c1 ?? 0) - (values.c2 ?? 0) - (values.c3 ?? 0);

                // Loop untuk menyesuaikan input yang bisa dipilih
                criteria.forEach(name => {
                    document.querySelectorAll(`input[name="${name}"]`).forEach(input => {
                        let value = parseInt(input.value);
                        let isDisabled = selectedCount === 2 && (values[name] === null && value !== remainingValue);

                        input.disabled = isDisabled;
                        let label = input.nextElementSibling;
                        if (isDisabled) {
                            label.classList.add("hidden");
                        } else {
                            label.classList.remove("hidden");
                        }
                    });
                });

                let isValid = Object.values(values).every(v => v !== null) && (values.c1 + values.c2 + values.c3 === maxSum);
                submitButton.disabled = !isValid;
                errorMessage.textContent = isValid ? "" : "Jumlah C1 + C2 + C3 harus tepat 9.";
            }

            document.querySelectorAll('input[type="radio"]').forEach(input => {
                input.addEventListener("change", updateInputs);
            });
        });
    </script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $c1 = isset($_POST['c1']) ? (int)$_POST['c1'] : 0;
        $c2 = isset($_POST['c2']) ? (int)$_POST['c2'] : 0;
        $c3 = isset($_POST['c3']) ? (int)$_POST['c3'] : 0;

        if ($c1 + $c2 + $c3 === 9 && $c1 >= 1 && $c2 >= 1 && $c3 >= 1) {
            echo "<div class='container'><h2>Hasil Pemilihan</h2>";
            echo "<p><strong>C1:</strong> $c1</p>";
            echo "<p><strong>C2:</strong> $c2</p>";
            echo "<p><strong>C3:</strong> $c3</p>";
            echo "</div>";
        } else {
            echo "<div class='container'><p style='color:red;'>Error: Pemilihan tidak valid.</p></div>";
        }
    }
    ?>
</body>
</html>
