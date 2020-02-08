<?php
session_start();
include('header.php');
session_destroy();
echo "<div id='message'><p class='success'>Erfolgreich ausgeloggt. Tschüssi!</p></div>";
