<?php

require('fpdf.php');
require('db.php');

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

function generate($FULANO,$palestras,$finger) {
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

    $msg = "O Grupo de Usuários de Software Livre TcheLinux certifica que @".$FULANO.
        "@ esteve presente no evento realizado no dia 6 de Dezembro de 2014 ".
        "nas dependências da Faculdade SENAC de Porto Alegre e assistiu as ".
        "palestras:";

    $pdf->SetY(85);
    wiki($pdf, windownize($msg));

    $count = 125;
    foreach($palestras as $_ => $p) {
        $pdf->SetY($count);
        $pdf->SetX(40);
        $msg = $p[0]." (".$p[1].":00h)";
        $pdf->Cell(200,1,windownize($msg),0,0,'L');
        $count = $count + 8;
    }

    $pdf->SetFont('Times','',12);

    $pdf->SetY(-30.5);
    $pdf->SetX(-80);
    $pdf->Cell(20,0,windownize("Código de Confirmação:"),0,0,'R');
    $pdf->SetY(-25);
    $pdf->SetX(-114);
    $pdf->SetFont('','B');
    $pdf->Cell(10,0,$finger,0,1,'L');
    $pdf->SetFont('','');
    $pdf->SetY(-20.5);
    $pdf->SetX(-65.5);
    $pdf->Cell(20,0,'http://poa.tchelinux.org/certificado.php',0,0,'R');

    $pdf->Output();
}

function retrieve($code) {
    var_dump($code);
}

function consult_db($name) {
    $conn = connect();
    $pid_query = "SELECT id FROM participantes WHERE nome = $1";
    $st = pg_prepare($conn, 'pid', $pid_query);
    $rs = pg_execute($conn, 'pid', array($name));
    if ($row = pg_fetch_assoc($rs)) {
        $pid = $row['id'];
        if ($pid) {
            $palestras = array();
            $q_palestras = <<<SQL
SELECT * FROM palestras WHERE id IN
(SELECT palestra_id FROM participacao WHERE participante_id = $1);
SQL;
            $st = pg_prepare($conn,'palestras',$q_palestras);
            $rs = pg_execute($conn, 'palestras', array($pid));
            while ($row = pg_fetch_assoc($rs)) {
                array_push($palestras, array($row['titulo'],$row['duracao']));
            }
            # generate fingerprint - TODO: date is still hard coded (YYYY-MM-DD).
            $event_date = '2014-12-06';
            $p = "";
            foreach ($palestras as $_ => $data)
                $p .= $data[0];
            $finger = hash("md5",$name.$p.$event_date);
            # store on database.
            $q_certificado = "SELECT * FROM certificados where event = '2014-12-06' AND participante_id = $1";
            $st = pg_prepare($conn, 'certificados', $q_certificado);
            $rs = pg_execute($conn, 'certificados', array($pid));
            if ($row = pg_fetch_assoc($rs)) {
                $q_update = "UPDATE certificados SET key = $1 WHERE event = $2 and participante_id = $3";
                $st = pg_prepare($conn, 'update', $q_update);
                pg_execute($conn, 'update', array($finger, '2014-12-06', $pid));
            } else {
                $q_insert = "INSERT INTO certificados VALUES ($1, $2, $3)";
                $st = pg_prepare($conn, 'insert', $q_insert);
                pg_execute($conn, 'insert', array($finger, '2014-12-06', $pid));
            }

            # generate PDF
            generate(utf8_decode($name), $palestras, $finger);
        }
    }

    pg_close($conn);
}

#$_POST["name"] = utf8_encode("Marcelo Baldissera Cure");

# main()

if ( isset($_POST["code"]) && $_POST["code"] != "") {
    retrieve($_POST["code"]);
} else if ( isset($_POST["name"]) && $_POST["name"] != "") {
    consult_db($_POST["name"]);
} else
    generate("Rafael Guterres Jeffman",array("Python do Zero ao Minority Report"),"2cf24dba5fb0a30e26e83b2ac5b9e29e1b161e5c1fa7425e730");

?>
