<?php

include('db.php');

$palestra = $_POST['palestra'];

unset($_POST['palestra']);

$query="INSERT INTO participacao (palestra_id, participante_id) VALUES ($1, $2)";

$conn = connect();
$st = pg_prepare($conn, 'insert', $query);

foreach ($_POST as $_ => $id) {
    pg_execute($conn, 'insert', array($palestra, $id));
}

pg_close($conn);

header("Location: registro.php?pid=".($palestra + 1));

?>
