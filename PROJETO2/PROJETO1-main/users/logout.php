<?php
session_unset();
session_destroy();
header("Location: ../?pg=users/login"); // Redireciona para a página de login de cliente
exit();
?>
