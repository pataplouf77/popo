

<?php
echo 'hello';
 $pdo = new pdo('mysql:host=pataploufclub.mysql.db;dbname=pataploufclub','pataploufclub','pataplouf');
	//$pdo = new PDO('mysql:host=localhost:3306;dbname=security', 'root', '');
	if ( $pdo == false ) { echo ' false '; } else { echo ' true '; } ;
 $sql = 'SELECT * FROM jos_categories';
 $req = $pdo->query($sql);
 while($row = $req->fetch()) {
 echo '<a href="membre-'.$row['id'].'.html">'.$row['title'].'</a><br/>';
 }
 $req->closeCursor();
?>