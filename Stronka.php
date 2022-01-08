<?php
session_start();
$conn = new mysqli("czaplinek.home.pl:3306", "00018732_kw", "Kajet@nW0j25", "00018732_kw");
?>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Gram Repsodia</title>
    <style>

        body {
            background-image: url("gramrepsodia2.png");
            background-position: top;
            background-repeat: no-repeat;
            background-size: 1742px;
            background-color: black;
        }

        table, th, td {
            border: 1px solid black;
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

        .naglowki:hover {
            background-color: white;
        }

        }

    </style>
</head>
<body>
<div style="margin: 0 auto; width: 1410px;margin-top: 150px;">
    <div style="background-color: white; float: left; margin-right: 6px">
        <table style="width:702px;">
            <tr class="naglowki">
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

        <div style="height:500px;width:700px;border:1px solid black; overflow: scroll">
            <table style="width:100%">
                <?php
                $sql = "SELECT idGry, nazwa, rokWydania, wydawca, gatunek FROM Gry;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo sprintf("<tr onclick=\"location.href='Stronka.php?gra=%s'\"><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row["idGry"], $row["idGry"], $row["nazwa"], $row["rokWydania"], $row["wydawca"], $row["gatunek"]);
                    }
                } else {
                    echo "0 results";
                }
                ?>
            </table>
        </div>
    </div>

    <div style="background-color: white; float : left">
        <table style="width:702px">
            <tr class="naglowki">
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

        <div style="height:500px;width:700px;border:1px solid black; overflow: scroll">
            <table style="width:100%">
                <?php
                error_reporting(null);
                $gra = $_GET["gra"];
                if ($gra != "") {
                    $sql = "SELECT idEgzemplarza, stan, cena, miasto, Egzemplarze.status FROM Egzemplarze, Placowki WHERE Egzemplarze.idPlacowki = Placowki.idPlacowki AND idGry = '$gra' AND Egzemplarze.status LIKE 'gotowa do%';";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo sprintf("<tr onclick=\"location.href='Stronka.php?gra=%s&egzemplarz=%s'\"><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $gra, $row["idEgzemplarza"], $row["idEgzemplarza"], $row["stan"], $row["cena"], $row["miasto"]);
                        }
                    }
                }
                ?>
            </table>
        </div>
    </div>

</div>

<div style=" height : 40px;width: 194px; margin: 0 auto ; padding-top: 20px; clear: left">
    <form method="post">
        <select style="width: 95px" id="placowki" name="placowki">
            <?php
            $sql = "SELECT miasto FROM Placowki WHERE status = 1 AND idPlacowki != 1 ;";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo sprintf("<option value='%s'>%s</option>", $row["miasto"], $row["miasto"]);
                }
            }
            ?>
        </select>
        <input style="width: 95px" type="submit" <?php
        error_reporting(null);
        $egzemplarz = $_GET["egzemplarz"];
        echo $egzemplarz;
        if ($egzemplarz == "") {
            echo "disabled";
        } else {
            echo "active";
        }
        ?>
               value="Zamów">
    </form>
    <?php
    error_reporting(null);
    $odb = $_POST["placowki"];
    if ($odb != "") {
        $sql = "SELECT idPlacowki FROM Placowki WHERE miasto ='$odb';";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $idodb = $row["idPlacowki"];

        $sql = "SELECT idPlacowki FROM Egzemplarze WHERE idEgzemplarza ='$egzemplarz';";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $idwys = $row["idPlacowki"];

        $status = "'do wyslania'";
        $statusEgz = "'zamowiona'";

        $sql = sprintf("INSERT INTO Zamowienia (idEgzemplarza, placowkaWysylajaca, placowkaOdbierajaca, status) VALUES (%s,%s,%s,%s);", $egzemplarz, $idwys, $idodb, $status);
        $conn->query($sql);

        $sql = sprintf("UPDATE Egzemplarze SET status= %s WHERE idEgzemplarza=%s;", $statusEgz, $egzemplarz);
        $conn->query($sql);
    }
    ?>
</div>

</body>
</html>