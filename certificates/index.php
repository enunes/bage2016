<?php

include('fpdf.php');

function wiki($pdf, $text)
{
    $count = 0;
    $bold = true;
    $a = split('@',$text);
    foreach ($a as $i => $e) {
        $bold = ! $bold;
        if ($bold) $pdf->SetFont('','B');
        else $pdf->SetFont('','');
        $pdf->Write(10,$e);
    }
}

function windownize($txt) {
    return iconv('UTF-8','windows-1252', $txt);
}

$MARGIN = 25;

$pdf = new FPDF('L','mm','A4');
$pdf->SetLeftMargin($MARGIN);
$pdf->SetRightMargin($MARGIN);
$pdf->AddPage();

$pdf->Image('background.png',90,-10,220);

$pdf->SetFont('Arial','B',48);
$pdf->Cell(297-2*$MARGIN,25,'CERTIFICADO',0,1,'C');

$pdf->SetFont('Arial','B',24);
$msg = 'Seminário de Software Livre';
$pdf->Cell(297-2*$MARGIN,35,windownize($msg),0,1,'C');
$pdf->SetFont('Arial','B',32);
$pdf->Cell(297-2*$MARGIN,0,'TcheLinux Porto Alegre 2014',0,1,'C');

$pdf->SetFont('Times','',18);

$FULANO = "Rafael Guterres Jeffman";

$msg = "O Grupo de Usuários de Software Livre TcheLinux certifica que @".$FULANO.
    "@ esteve presente no evento realizado no dia 6 de Dezembro de 2014 ".
    "nas dependências da Faculdade SENAC de Porto Alegre e assistiu as ".
    "palestras:";

$pdf->SetY(85);
wiki($pdf, windownize($msg));
#$pdf->MultiCell(297-40,10,$msg);

$palestras = array(array("BigData","1:00h"), array("XSS","1:00h"), array("Yocto","1:00h") );
$count = 125;
for ($i = 0; $i < 2; $i++) {
    foreach($palestras as $_ => $p) {
        $pdf->SetY($count);
        $pdf->SetX(40);
        $msg = $p[0]." (".$p[1].")";
        $pdf->Cell(200,1,windownize($msg),0,0,'L');
        $count = $count + 8;
    }
}


$pdf->SetFont('Times','',12);

$CONFIRMACAO="ABC123";

$pdf->SetY(-26.5);
$pdf->SetX(-90);
$pdf->Cell(20,0,windownize("Código de Confirmação:"),0,0,'R');
$pdf->SetX(-58);
$pdf->SetFont('','B');
$pdf->Cell(10,0,$CONFIRMACAO,0,1,'R');
$pdf->SetFont('','');
$pdf->SetY(-20.5);
$pdf->SetX(-67.5);
$pdf->Cell(20,0,'http://poa.tchelinux.org/validacao.php',0,0,'R');

$pdf->Output();

?>
