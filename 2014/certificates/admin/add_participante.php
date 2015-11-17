<?php

include('db.php');

$nome=pg_escape_string(utf8_encode($_POST['name']));

$query =  "insert into participantes (id,nome) values(DEFAULT,'".$nome."')";

echo "<p>".$query."</p>";

$conn = connect();
if (! pg_query($conn,$query) ) {
    echo pg_last_error($conn);
    die("Opss... an error occured...");   
}
pg_close($conn);

header('Location:participantes.php');

?>

