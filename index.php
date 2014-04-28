<?php
$connect = mysql_connect('localhost', 'root', '');
if (!$connect) {
    die('No DB connection: ' . mysql_error() . '. Please send email at root@domain...');
}

mysql_select_db('oop');
mysql_query('SET NAMES utf8');
mysql_query('DEFAULT CHARSET=utf8');

if (isset($_POST['save'])) {
    $data = $_POST['form'];
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = mysql_query("
            UPDATE author SET
                name = '$data[name]',
                surname = '$data[surname]',
                birth = '$data[birth]',
                death = '$data[death]',
                country = '$data[country]'
            WHERE
                id = $id
            ")
        ;
    } else {
        $query = mysql_query("
            INSERT INTO author
                (name, surname, birth, death, country)
            VALUES
                ('$data[name]', '$data[surname]', '$data[birth]', '$data[death]', '$data[country]')
            ")
        ;
    }
    header('Location: index.php');
} elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysql_query("DELETE FROM author WHERE id = $id");
    header('Location: index.php');
} elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysql_query("SELECT id, name, surname, birth, death, country FROM author WHERE id = $id");
    $guy = mysql_fetch_assoc($query);
}

$authors = [];
$query = mysql_query("SELECT id, name, surname, birth, death, country FROM author");
while ($author = mysql_fetch_assoc($query)) {
    $authors[] = $author;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Spaghetti Code</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
  </head>
  <body>
      <h2>Add or edit data</h2>
      <form method="POST">
      <table>
          <tr><td>Name:</td><td><input type="text" name="form[name]" value="<?php if (isset($guy)) { echo $guy['name']; }?>" /></td></tr>
          <tr><td>Surname:</td><td><input type="text" name="form[surname]" value="<?php if (isset($guy)) { echo $guy['surname']; }?>" /></td></tr>
          <tr><td>Birth:</td><td><input type="text" name="form[birth]" value="<?php if (isset($guy)) { echo $guy['birth']; }?>" /></td></tr>
          <tr><td>Death:</td><td><input type="text" name="form[death]" value="<?php if (isset($guy)) { echo $guy['death']; }?>" /></td></tr>
          <tr><td>Country:</td><td><input type="text" name="form[country]" value="<?php if (isset($guy)) { echo $guy['country']; }?>" /></td></tr>
          <tr><td></td><td><input type="submit" name="save" value="Save" /></td></tr>
      </table>
      </form>
      <p><a href="index.php" title="Na hlavní stránku">Back to main page</a></p>
      <hr />
      <h2>List of Authors</h2>
      <table>
          <tr><th>Id</th><th>Name</th><th>Surname</th><th>Date of Birth</th><th>Date of Death</th><th>Country</th><th></th></tr>
          <?php foreach ($authors as $author) { ?>
          <tr>
            <td><?= $author['id'] ?></td>
            <td><?= $author['name'] ?></td>
            <td><?= $author['surname'] ?></td>
            <td><?= $author['birth'] ?></td>
            <td><?= $author['death'] ?></td>
            <td><?= $author['country'] ?></td>
            <td><a href="?action=edit&id=<?= $author['id'] ?>">Edit</a>&nbsp;&nbsp;
                <a href="?action=delete&id=<?= $author['id'] ?>">Delete</a>
            </td>
          </tr>
          <?php } ?>
      </table>
  </body>
</html>