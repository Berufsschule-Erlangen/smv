<?php
require_once('classes/Database.class.php');


?>

<div class="row">
    <div class="col-md-12">
      <table class="table">
        <thead>
          <tr>
            <th>Montag</th>
            <th>Dienstag</th>
            <th>Mittwoch</th>
            <th>Donnerstag</th>
            <th>Freitag</th>
          </tr>
        </thead>
        <tbody>
<?php
$db = new Database();
$con = $db->connectUser();
$result = $db->select_duty($con, $kw);

while ($row = $result->fetch_assoc()) {
         echo "<tr>";
         echo "<td>".$row['vorname']. " ". $row['name']."</td>";
         echo "</tr>";
}
$db->close($con);
?>
        </tbody>
      </table>
    </div>
</div>
