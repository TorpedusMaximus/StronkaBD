<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "00018732_kw");
?>
<html lang="pl">
<head>
    <style>
        table, th, td {
            border: 1px solid black
        }

        tr:hover {
            background-color: lightblue;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .chujoweTo:hover {
            background-color: white
        }
    </style>
    <title>Gram Repsodia</title>
</head>
<body>

<table style="width:800px;">
    <tr name=chujoweTo>
        <th>
            idGry
        </th>
        <th>
            nazwa
        </th>
        <th>
            rok
        </th>
        <th>
            wydawca
        </th>
        <th>
            gatunek
        </th>
    </tr>
</table>

<div style="height:500px;width:800px;border:1px solid black; overflow: scroll">
    <table style="width:100%">
        <?php
        $idEgzemplarza = -1;
        $sql = "SELECT idGry, nazwa, rokWydania, wydawca, gatunek FROM Gry;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["idGry"] . "</td><td>" . $row["nazwa"] . "</td><td>" . $row["rokWydania"] . "</td><td>" . $row["wydawca"] . "</td><td>" . $row["gatunek"] . "</td></tr>";
            }
        } else {
            echo "0 results";
        }
        ?>
    </table>
</div>

<table style="width:800px">
    <tr>
        <th>
            idEgzemplarza
        </th>
        <th>
            stan
        </th>
        <th>
            cena
        </th>
        <th>
            placowka
        </th>
    </tr>
</table>

<div style="height:500px;width:800px;border:1px solid black; overflow: scroll">
    <table style="width:100%">
        <?php
        $sql = "SELECT idEgzemplarza, stan, cena, idPlacówka FROM Gry; WHERE idGry = " . $idEgzemplarza;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["nazwa"] . "</td><td>" . $row["rokWydania"] . "</td><td>" . $row["wydawca"] . "</td><td>" . $row["gatunek"] . "</td></tr>";
            }
        } else {
            echo "0 results";
        }
        ?>
    </table>
</div>

</body>
</html>
