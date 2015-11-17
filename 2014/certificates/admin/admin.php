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

</style>
</head>
<body>

<h1>Novo Evento</h1>
<form action="add_event.php" method="POST">
<label>Cidade</label><input type="text" name="location" size="40"><br>
<label>Data</label><input type="text" name="date" size="40"><br>
<label>Institui&ccedil;&atilde;o</label><input type="text" name="intitution" size="40"><br>
<div class="centralizador"><input type="submit" value="Criar" /></div>
</form>


<h1>Novo Palestrante</h1>
<form action="add_speaker.php" method="POST">
<label>Nome</label><input type="text" name="name" size="40"><br>
<label>Curr&iacute;culo</label><input type="text" name="resume" size="40"><br>
<div class="centralizador"><input type="submit" value="Criar" /></div>
</form>

<h1>Nova Palestra</h1>
<form action="add_event.php" method="POST">
<label></label><input type="text" name="" size="40"><br>
<div class="centralizador"><input type="submit" value="Criar" /></div>
</form>


</body>
</html>

