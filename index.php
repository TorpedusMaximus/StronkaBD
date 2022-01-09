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

    </style>
</head>
<body>

<div style="margin: 0 auto; height : 40px; width: 194px;  padding-top: 150px;">
    <form method="post">
        <input style="width: 95px" type="text" id="tytul" name="tytul">
        <input style="width: 95px" type="submit" <?php
        error_reporting(null);
        ?>
               value="Szukaj">
    </form>
</div>

<div style="margin: 0 auto; width: 1410px;margin-top: 10px;">

    <div style="background-color: white; float: left; margin-right: 6px">
        <table style="width:700px;">
            <tr class="naglowki">
                <th style="width:44px">
                    idGry
                </th>
                <th style="width:232px">
                    nazwa
                </th>
                <th style="width:45px">
                    rok
                </th>
                <th style="width:232px">
                    wydawca
                </th>
                <th style="width:148px">
                    gatunek
                </th>
            </tr>
        </table>

        <div style="height:500px;width:700px;border:1px solid black; overflow: scroll">
            <table style="width:100%">
                <?php
                error_reporting(null);
                $tytul = '%'.$_POST["tytul"].'%';
                if($tytul=="%%"){
                    $sql = "SELECT idGry, nazwa, rokWydania, wydawca, gatunek FROM Gry;";
                }else{
                    $sql = "SELECT idGry, nazwa, rokWydania, wydawca, gatunek FROM Gry WHERE nazwa LIKE '$tytul';";
                }
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo sprintf("<tr onclick=\"location.href='index.php?gra=%s'\"><td style=\"width:45px\">%s</td><td style=\"width:235px\">%s</td><td style=\"width:45px\">%s</td><td style=\"width:243px\">%s</td><td style=\"width:132px\">%s</td></tr>", $row["idGry"], $row["idGry"], $row["nazwa"], $row["rokWydania"], $row["wydawca"], $row["gatunek"]);
                    }
                } else {
                    echo "0 results";
                }
                ?>
            </table>
        </div>
    </div>

    <div style="background-color: white; float : left">
        <table style="width:700px">
            <tr class="naglowki">
                <th style="width: 120px">
                    idEgzemplarza
                </th>
                <th style="width: 210px">
                    stan
                </th>
                <th style="width: 100px">
                    cena
                </th>
                <th style="width: auto">
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
                            $stan = "fatalny";
                            switch ($row["stan"]) {
                                case 1:
                                    $stan = "zły";
                                    break;
                                case 2:
                                    $stan = "używany";
                                    break;
                                case 3:
                                    $stan = "dobry";
                                    break;
                                case 4:
                                    $stan = "wzorowy";
                                    break;
                            }
                            echo sprintf("<tr onclick=\"location.href='index.php?gra=%s&egzemplarz=%s'\"><td style='width: 120px'>%s</td><td style='width: 210px'>%s</td><td style='width: 100px'>%szł</td><td style='width: auto'>%s</td></tr>", $gra, $row["idEgzemplarza"], $row["idEgzemplarza"], $stan, $row["cena"], $row["miasto"]);
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

        $sql = sprintf("SELECT idZamowienia FROM Zamowienia WHERE idEgzemplarza=%s;", $egzemplarz);
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $id = $row["idZamowienia"];
    }
    ?>
</div>

<div style="width: 450px;margin: 0 auto ; padding-top: 20px; clear: left">
    <?php
    if ($odb != "") {
        echo "<marquee><span style='font-family: \"Comic Sans MS\";color: white;text-align: center ;font-size: 23px; '> <br> IDTransakcji: " . $id . ". Podaj ten numer sprzedawcy.</span></marquee>";
    }
    ?>
</div>

</body>
</html>