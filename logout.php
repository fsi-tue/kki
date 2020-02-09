<?php
/**
 * This file, when called, destroys the PHP session and in turn logs out the user.
 * Not really much else to say here.
 */
session_start();
include('header.php');
session_destroy();
echo "<div id='message'><p class='success'>Erfolgreich ausgeloggt. Tschüssi!</p></div>";
