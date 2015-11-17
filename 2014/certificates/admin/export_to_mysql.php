<?php

require ('db.php');
$local = connect();

$remote = mysql_connect('tchelinux.org','tchelinu_CtnYT', 'coRZjRxHquCrZg27tBvVFyuZh6preQ5CxgPuDYph') or die("Could not localect to database.\n".mysql_error());

mysql_select_db('tchelinu_EUxSs',$remote) or die("Could not select db.\n".mysql_error());


mysql_query("DROP TABLE participacao",$remote);
mysql_query("DROP TABLE certificados",$remote);
mysql_query("DROP TABLE participantes",$remote);
mysql_query("DROP TABLE palestras",$remote);

$query = <<<SQL
CREATE TABLE IF NOT EXISTS palestras (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(150),
    duracao INTEGER
)
SQL;
mysql_query($query, $remote) or die("Could not create PALESTRAS.\n".mysql_error());

$rs = pg_query($local, "SELECT * FROM palestras");
$base = "INSERT INTO palestras VALUES ";
while ($row = pg_fetch_assoc($rs)) {
    $query = $base."(".$row['id'].",'".utf8_encode(mysql_escape_string(utf8_decode($row['titulo'])))."',".$row['duracao'].")";
    echo "Query: ".$query."\n";
    if (mysql_query($query, $remote))
        echo "Inserted: ".$row['titulo']."\n";
    else
        die(mysql_error()."\n");
}

$query = <<<SQL
CREATE TABLE IF NOT EXISTS participantes (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(255) UNIQUE
)
SQL;
mysql_query($query, $remote) or die("Could not create PARTICIPANTES.\n".mysql_error());

$rs = pg_query($local, "SELECT * FROM participantes");
$base = "INSERT INTO participantes VALUES ";
while ($row = pg_fetch_assoc($rs)) {
    $query = $base."(".$row['id'].",'".utf8_encode(mysql_escape_string(utf8_decode($row['nome'])))."','".utf8_encode(mysql_escape_string(utf8_decode($row['email'])))."')";
    echo "Query: ".$query."\n";
    if (mysql_query($query, $remote))
        echo "Inserted: ".$row['nome']."\n";
    else
        die(mysql_error()."\n");
}

$query = <<<SQL
CREATE TABLE IF NOT EXISTS certificados (
    certificate_code VARCHAR(32),
    event DATE,
    participante_id INTEGER,
    FOREIGN KEY (participante_id)
        REFERENCES participantes(id)
        ON DELETE CASCADE,
    PRIMARY KEY (event, participante_id)
)
SQL;
mysql_query($query, $remote) or die("Could not create CERTIFICADOS.\n".mysql_error());

$rs = pg_query($local, "SELECT * FROM certificados");
$base = "INSERT INTO certificados VALUES ";
while ($row = pg_fetch_assoc($rs)) {
    $query = $base."('".$row['certificate_code']."','".$row['event']."',".$row['participante_id'].")";
    echo "Query: ".$query."\n";
    if (mysql_query($query, $remote))
        echo "Inserted: ".$row['key']."\n";
    else
        die(mysql_error()."\n");
}

$query = <<<SQL
CREATE TABLE IF NOT EXISTS participacao (
    participante_id INTEGER,
    palestra_id INTEGER,
    FOREIGN KEY (participante_id)
        REFERENCES participantes(id)
        ON DELETE CASCADE,
    FOREIGN KEY (palestra_id)
        REFERENCES palestras(id)
        ON DELETE CASCADE,
    PRIMARY KEY (participante_id, palestra_id)
)
SQL;
mysql_query($query, $remote) or die("Could not create PARTICIPACAO.\n".mysql_error());

$rs = pg_query($local, "SELECT * FROM participacao");
$base = "INSERT INTO participacao VALUES ";
while ($row = pg_fetch_assoc($rs)) {
    $query = $base."(".$row['participante_id'].",".$row['palestra_id'].')';
    echo "Query: ".$query."\n";
    if (mysql_query($query, $remote))
        echo "Inserted.";
    else
        die(mysql_error()."\n");
}

mysql_close($remote);


pg_close($local);

?>
