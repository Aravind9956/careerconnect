<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'careerconnect', 3306);
if ($mysqli->connect_errno) {
    echo 'ERR:' . $mysqli->connect_error . PHP_EOL;
    exit(1);
}
$res = $mysqli->query("SHOW CREATE TABLE otp_verifications");
if (!$res) {
    echo 'SHOWERR:' . $mysqli->error . PHP_EOL;
    exit(1);
}
var_dump($res->fetch_assoc());
$res = $mysqli->query("DESCRIBE otp_verifications");
if (!$res) {
    echo 'DESCRIBEERR:' . $mysqli->error . PHP_EOL;
    exit(1);
}
while ($row = $res->fetch_assoc()) {
    echo implode(' | ', $row) . PHP_EOL;
}
