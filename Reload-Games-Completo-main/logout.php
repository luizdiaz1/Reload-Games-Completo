<?php
session_start();
session_unset();
session_destroy();
header("Location: principal.php");  // redireciona para página inicial SEM jogos
exit;
