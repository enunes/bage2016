<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script>
function reload(obj)
{
    var url = location.protocol + "://" + location.hostname + location.pathname;
    url = "registro.php?pid=" + obj.value;
    location.replace(url);
}
</script>

</head>
<body>
<?php
include('db.php');

$conn = connect();

$q_participantes = "select * from participantes order by nome";
$q_palestras = "select * from palestras order by titulo";

$q_palestras_participantes = <<<SQL
SELECT participante_id FROM participacao
WHERE palestra_id = $1
ORDER BY participante_id
SQL;
$st_participacao = pg_prepare($conn,'ids',$q_palestras_participantes);


$pid = 8;
if (isset($_GET['pid']))
    $pid = $_GET['pid'];

$options = array();
$rs = pg_query($conn, $q_palestras);
while ($row = pg_fetch_assoc($rs))
{
    $entry = '<option value="'.$row['id'].'"';
    
    if ($row['id'] == $pid)
        $entry .= ' selected';
    
    $entry .= '>'.$row['titulo'].'</option>';
    array_push($options,$entry);
}

$participantes = array();
$rs = pg_execute($conn,"ids",array($pid));
while ($row = pg_fetch_assoc($rs)) {
    array_push($participantes, (int)($row['participante_id']));
}

$pessoas = array();
$rs = pg_query($conn, $q_participantes);
while ($row = pg_fetch_assoc($rs))
{
    $entry = '<input type="checkbox" name="'.$row['id'].'" value="'.$row['id'].'"';
    if (in_array($row['id'],$participantes))
        $entry .= ' checked';
    $entry .= '>'.utf8_decode($row['nome']).'</input><br/>';
    array_push($pessoas,$entry);
}

pg_close($conn);

echo '<form method="POST" action="cfgpalestra.php">'."\n".
    '    <select name="palestra" onchange="reload(this)">'."\n    ".
    join("\n        ",$options).
    "\n    </select>\n        ";
echo '<input type="submit" value="Enviar" />';
echo "<table><tr>";
$count = 0;
foreach ($pessoas as $p) {
    echo "<td>$p</td>";
    $count += 1;
    if ($count % 4 == 0)
        echo "</tr><tr>";
}
if ($count %4 != 0)
    echo "</tr>";
echo "</table>";
echo "\n</form>\n";

?>
</body>
<html>

