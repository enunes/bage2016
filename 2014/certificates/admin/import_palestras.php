<?php

include('db.php');

$errors = array();

$query = <<<SQL
INSERT INTO palestras (id, titulo, duracao)
VALUES (DEFAULT, $1, 1)
SQL;

$conn = connect();
$st = pg_prepare($conn, "insert", $query);

$csv = explode(";;;;",file_get_contents('palestras.csv'));
$header = 1;
foreach ($csv as $_ => $data) {
    if ($header) {
        $header = 0;
        continue;
    }

    $fields = explode(";",$data);
    if (count($fields) != 12) continue;

    $titulo = utf8_encode($fields[4]);
    echo $titulo."\n";
    @pg_execute($conn, "insert", array($titulo))
        or array_push($errors, $titulo);
}

pg_close($conn); 

#echo "Erros: ".count($errors)."\n";
#print_r(array_unique($errors));

?>
