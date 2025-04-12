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

        // Matriks Perbandingan Berpasangan
        $pairwiseMatrix = [
            [$c1 / $c1, $c1 / $c2, $c1 / $c3],
            [$c2 / $c1, $c2 / $c2, $c2 / $c3],
            [$c3 / $c1, $c3 / $c2, $c3 / $c3]
        ];

        // Matriks Nilai Kriteria (Normalisasi)
        $sumColumns = array_map(null, ...$pairwiseMatrix);
        $sumColumns = array_map('array_sum', $sumColumns);
        $normalizedMatrix = array_map(function($row) use ($sumColumns) {
            return array_map(function($value, $sum) {
                return $value / $sum;
            }, $row, $sumColumns);
        }, $pairwiseMatrix);

        // Matriks Penjumlahan Setiap Baris
        $rowSums = array_map('array_sum', $normalizedMatrix);
        $criteriaWeights = array_map(function($sum) {
            return $sum / 3;
        }, $rowSums);

        // Perhitungan Rasio Konsistensi
        $lambdaMax = array_sum(array_map(function($weight, $sum) {
            return $weight * $sum;
        }, $criteriaWeights, $sumColumns));
        $consistencyIndex = ($lambdaMax - 3) / (3 - 1);
        $randomIndex = 0.58; // RI for n=3
        $consistencyRatio = $consistencyIndex / $randomIndex;

        echo "<div class='card'><h3>Matriks Perbandingan Berpasangan</h3>";
        echo "<div class='matrix'><table><tr><th></th><th>C1</th><th>C2</th><th>C3</th></tr>";
        foreach ($pairwiseMatrix as $i => $row) {
            echo "<tr><th>C" . ($i + 1) . "</th>";
            foreach ($row as $value) {
                echo "<td>" . round($value, 3) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table></div></div>";

        echo "<div class='card'><h3>Matriks Nilai Kriteria (Normalisasi)</h3>";
        echo "<div class='matrix'><table><tr><th></th><th>C1</th><th>C2</th><th>C3</th></tr>";
        foreach ($normalizedMatrix as $i => $row) {
            echo "<tr><th>C" . ($i + 1) . "</th>";
            foreach ($row as $value) {
                echo "<td>" . round($value, 3) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table></div></div>";

        echo "<div class='card'><h3>Matriks Penjumlahan Setiap Baris</h3>";
        echo "<div class='matrix'><table><tr><th>Kriteria</th><th>Jumlah</th></tr>";
        foreach ($rowSums as $i => $sum) {
            echo "<tr><th>C" . ($i + 1) . "</th><td>" . round($sum, 3) . "</td></tr>";
        }
        echo "</table></div></div>";

        echo "<div class='card'><h3>Perhitungan Rasio Konsistensi</h3>";
        echo "<p><strong>λmax:</strong> " . round($lambdaMax, 3) . "</p>";
        echo "<p><strong>CI:</strong> " . round($consistencyIndex, 3) . "</p>";
        echo "<p><strong>CR:</strong> " . round($consistencyRatio, 3) . "</p>";
        echo "<p>" . ($consistencyRatio < 0.1 ? "Konsisten" : "Tidak Konsisten") . "</p>";
        echo "</div>";
    } else {
        echo "<div class='container'><p style='color:red;'>Error: Pemilihan tidak valid.</p></div>";
    }
}
?>
