<?php
require('fpdf/fpdf.php');
require_once('includes/init.php');

class PDF extends FPDF {
    // Page header
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Data Hasil', 0, 1, 'C');
        $this->Ln(10);
    }

    // Page footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    // Table
    function BasicTable($header, $data) {
        foreach ($header as $col) {
            $this->Cell(60, 7, $col, 1);
        }
        $this->Ln();
        foreach ($data as $row) {
            foreach ($row as $col) {
                $this->Cell(60, 6, $col, 1);
            }
            $this->Ln();
        }
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

$header = array('Nama Alternatif', 'Nilai', 'Rank');

$query = mysqli_query($koneksi, "SELECT * FROM hasil JOIN alternatif ON hasil.id_alternatif=alternatif.id_alternatif ORDER BY hasil.nilai DESC");
$data = [];
$no = 0;
while ($row = mysqli_fetch_array($query)) {
    $no++;
    $data[] = array($row['nama'], $row['nilai'], $no);
}

$pdf->BasicTable($header, $data);

$filename = 'data_hasil.pdf';
$pdf->Output('F', $filename);

echo json_encode(['filename' => $filename]);
?>
