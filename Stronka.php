<html>
<head></head>
<body>
<!--jdbc:mysql://@czaplinek.home.pl:3306", "00018732_kw", "Kajet@nW0j25"-->
<?php
session_start();
$connection =mysqli_connect("mysql:host=@czaplinek.home.pl:3306","00018732_kw", "Kajet@nW0j25");
if(connection->connect_error){
    die("zdechł jak szmata");
}
echo "aha";
?>
<div style="height:100px;width:100px;border:1px solid black; overflow: scroll">



</div>

</body>
</html>
