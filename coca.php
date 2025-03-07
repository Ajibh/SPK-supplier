<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AHP Input Form</title>
    <script>
        function getCheckedValue(name) {
            let selected = document.querySelector(`input[name="${name}"]:checked`);
            return selected ? parseInt(selected.value) : null;
        }

        function updateInputs() {
            let c1 = getCheckedValue("c1");
            let c2 = getCheckedValue("c2");
            let c3 = getCheckedValue("c3");

            let selectedCount = [c1, c2, c3].filter(val => val !== null).length;
            let remainingValue = 9 - ((c1 ?? 0) + (c2 ?? 0) + (c3 ?? 0));

            // Menonaktifkan input terakhir setelah dua dipilih
            document.querySelectorAll('input[name="c1"], input[name="c2"], input[name="c3"]').forEach(input => {
                let name = input.name;
                let value = parseInt(input.value);
                
                if (selectedCount === 2) {
                    // Hanya aktif jika sesuai dengan nilai yang tersisa
                    input.disabled = name !== (c1 === null ? "c1" : c2 === null ? "c2" : "c3") || value !== remainingValue;
                    if (!input.disabled) input.checked = true;
                } else {
                    // Reset semua jika belum ada dua yang dipilih
                    input.disabled = false;
                }
            });

            let isValid = (c1 !== null && c2 !== null && c3 !== null) && (c1 + c2 + c3 === 9);
            document.getElementById("submit-button").disabled = !isValid;
            document.getElementById("error-message").innerText = isValid ? "" : "Jumlah C1 + C2 + C3 harus tepat 9.";
        }
    </script>
</head>
<body>
    <form method="POST">
        <?php
        function generateRadio($name) {
            return implode(" ", array_map(fn($i) => "<input type='radio' name='$name' value='$i' onchange='updateInputs()'> $i", range(1, 7)));
        }
        ?>
        
        <label>C1:</label><br><?= generateRadio("c1") ?><br>
        <label>C2:</label><br><?= generateRadio("c2") ?><br>
        <label>C3:</label><br><?= generateRadio("c3") ?><br>

        <p id="error-message" style="color: red;"></p>
        <button type="submit" id="submit-button" disabled>Hitung</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $c1 = $_POST['c1'] ?? 0;
        $c2 = $_POST['c2'] ?? 0;
        $c3 = $_POST['c3'] ?? 0;

        if ($c1 + $c2 + $c3 !== 9 || $c1 < 1 || $c2 < 1 || $c3 < 1) {
            echo "<p style='color:red;'>Error: Pemilihan tidak valid.</p>";
        } else {
            echo "<pre>" . print_r(["C1" => $c1, "C2" => $c2, "C3" => $c3], true) . "</pre>";
        }
    }
    ?>
</body>
</html>
