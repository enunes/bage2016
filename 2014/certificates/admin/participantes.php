<html>
<head>
<meta charset="UTF-8">
<style>
label {
    display: inline-block;
    width: 10em;
    text-align: right;
}
.centralizador {
    text-align: center;
    width: 40em;
    margin-top: 10px;
}
th,td {
    border : solid thin #999;
}
/*
#table {
    height: 10em;
    overflow: auto;
}
*/
</style>
</head>
<body>

<h1>Novo Participante</h1>
<form action="add_participante.php" method="POST">
<label>Nome</label><input type="text" name="name" size="40" autofocus><br>
<div class="centralizador"><input type="submit" value="Criar" /></div>
</form>

<div id="table">
<?php
include('db.php');

list_table('participantes');

?>
</div>

</body>
</html>

