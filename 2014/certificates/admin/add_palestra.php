<?php

include('db.php');

$titulo=pg_escape_string(utf8_encode($_POST['name']));
$duracao=pg_escape_string(utf8_encode($_POST['hours']));

$query =  "insert into palestras (id,titulo,duracao) values(DEFAULT,'".$titulo."',".$duracao.")";

echo "<p>".$query."</p>";

$conn = connect();
if (! pg_query($conn,$query) ) {
    echo pg_last_error($conn);
    die("Opss... an error occured...");   
}
pg_close($conn);

header('Location:palestra.php');

?>

