<?php
require_once "pdo.php";
// Demand a GET parameter
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}
$failure = false; 
// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}
function inputCheck() {
    $year = isset($_POST["year"]) ? $_POST['year'] : '';
    $mileage = isset($_POST["mileage"]) ? $_POST['mileage'] : ''; 
    $make = isset($_POST["make"]) ? $_POST['make'] : '';
    if ( !is_numeric($year) || !is_numeric($mileage) ) {
        $failure = "Mileage and year must be numeric";
        echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
        return;
    }else if (strlen($make) < 1  ) {
        $failure = "Make is required";
        echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
        return;
    }
    return true;   
}
if ( isset($_POST['add'])){
    $testInput = inputCheck();
    if ($testInput){
        $stmt = $pdo->prepare('INSERT INTO autos
            (make, year, mileage) VALUES ( :mk, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage'])
        );
        echo ('<p style="color: green;">'.'Record inserted'."</p>\n");
    }
}

$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<html>
<head>
<title>Praveen Nellihela Tracking Autos</title>
<?php require_once "pdo.php"; ?>
</head>
<body>
    <h1>Tracking autos for <?php echo htmlentities($_GET['name']) ?></h1>

    <?php
    if ( $failure !== false ) {
        echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
    }
    ?>

    <form method="POST">
    <p>
    <label for="make">Make</label>
    <input type="text" name="make" id="make">
    </p><p>
    <label for="year">Year</label>
    <input type="text" name="year" id="year">
    </p><p>
    <label for="mileage">Mileage</label>
    <input type="text" name="mileage" id="mileage">
    </p>
    <p>
    <input type="submit" value="Add" name="add" >
    <input type="submit" value="Logout" name="logout">
    </p>
    <h2>Automobiles</h2>
    </form>

    <?php
    foreach( $rows as $row ) {
        echo "<ul><li>";
        echo(htmlentities($row['year']).' '.htmlentities($row['make'])."/ ".htmlentities($row['mileage']) );
        echo("</li></ul>\n");
    }
    ?>

    </body>
</html>