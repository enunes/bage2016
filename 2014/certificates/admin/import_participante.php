<?php

include('db.php');

$duplicates = array();

$query = <<<SQL
INSERT INTO participantes (id, nome, email)
VALUES (DEFAULT, $1, $2)
SQL;

$conn = connect();
$st = pg_prepare($conn, "insert", $query);

$csv = explode("\r",file_get_contents('inscricoes.csv'));
$header = 1;
foreach ($csv as $_ => $data) {
    if ($header) {
        $header = 0;
        continue;
    }
    $fields = explode(";",$data);
    $name = utf8_encode($fields[1]);
    $email = utf8_encode($fields[2]);
    @pg_execute($conn, "insert", array($name,$email))
        or array_push($duplicates, $email.":".pg_last_error($conn));
}

pg_close($conn); 

echo "Duplicatas: ".count($duplicates)."\n";
print_r(array_unique($duplicates));

?>
