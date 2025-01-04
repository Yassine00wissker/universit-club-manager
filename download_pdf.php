<?php
require('fpdf.php');
include('db.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Données de la Base de Données', 0, 1, 'C');
$pdf->Ln(10);
$tables = ['membres', 'activites', 'evenment'];

foreach ($tables as $tableName) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, "Table: $tableName", 0, 1);
    $pdf->SetFont('Arial', '', 10);

    $query = "SELECT * FROM $tableName";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $columns = mysqli_fetch_fields($result);
        foreach ($columns as $column) {
            $pdf->Cell(40, 10, $column->name, 1);
        }
        $pdf->Ln();

        while ($row = mysqli_fetch_assoc($result)) {
            foreach ($row as $cell) {
                $pdf->Cell(40, 10, $cell, 1);
            }
            $pdf->Ln();
        }
    } else {
        $pdf->Cell(0, 10, "Erreur: Impossible d'obtenir les données pour $tableName.", 0, 1);
    }
    $pdf->Ln(10);
}

$pdf->Output('D', 'database_data.pdf'); // 'D'download 'I'afficher
exit;
?>
