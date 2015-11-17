<html>
<head>
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

<h1>Nova Palestra</h1>
<form action="add_palestra.php" method="POST">
<label>T&iacute;tulo</label><input type="text" name="name" size="40"><br>
<label>Dura&ccedil;&atilde;o</label><input type="text" name="hours" size="40" value="1"><br>
<div class="centralizador"><input type="submit" value="Criar" /></div>
</form>

<div id="table">
<?php
include('db.php');

list_table('palestras');

?>
</div>

</body>
</html>

