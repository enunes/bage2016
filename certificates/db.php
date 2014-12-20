<?php 

function connect() {
    return pg_connect("host=localhost dbname=tchelinux user=rafael");
    #return pg_connect("host=localhost dbname=tchelinu_EUxSs user=tchelinu_CtnYT password=coRZjRxHquCrZg27tBvVFyuZh6preQ5CxgPuDYph") or die("Could not connect to database.");
}

function create_db() {
    $conn = connect();
   pg_query($conn, 'CREATE TABLE IF NOT EXISTS event (id SERIAL PRIMARY KEY, location VARCHAR(30), date DATE, institution VARCHAR(50) );');
   pg_query($conn, 'CREATE TABLE IF NOT EXISTS speaker (id SERIAL PRIMARY KEY, name VARCHAR(30), resume VARCHAR(50) );');
   pg_query($conn, 'CREATE TABLE IF NOT EXISTS speech (id SERIAL PRIMARY KEY, title VARCHAR(30), summary VARCHAR(250));');
   pg_query($conn, 'CREATE TABLE IF NOT EXISTS speech_event (event_id integer references event(id), speech_id integer references speech(id), speaker_id integer references speaker(id), room integer, slot timestamp, duration integer, PRIMARY KEY(event_id, speech_id) );');
   pg_query($conn, 'CREATE TABLE IF NOT EXISTS attendant (id SERIAL PRIMARY KEY, name VARCHAR(30) UNIQUE);');
   pg_query($conn, 'CREATE TABLE IF NOT EXISTS attendant_speech (event_id integer references event(id), speech_id integer references speech(id), attendant_id integer references attendant(id), PRIMARY KEY(event_id, speech_id, attendant_id));');
   pg_close($conn); 
}

function list_table($table_candidate, $fields, $index)
{
    $table = split(" ",$table_candidate)[0];
    $query = "SELECT ".join(",",$fields)." FROM ".$table.' ORDER BY '.$index;

    $conn = connect() or die("Could not connect to database.");
    $rs = pg_query($conn, $query);
    while ( $row = pg_fetch_assoc($rs) ) {
        foreach ($fields as $_ => $f)
            echo '<div class="dbentry">'.utf8_decode($row[$f])."</div>";
    }

    pg_close($conn);
}

?>
