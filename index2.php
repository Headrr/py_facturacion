<?php
// Conexion a la base de datos
include 'db.php';
//header('location: log_in/index.php');
?>

<?php
// Header
include 'header.php';
?>
<div class="container p-5" align="center">
<?php   if(isset($_SESSION['mensaje'])){ ?>
    <div class="alert alert-<?= $_SESSION['mensaje_tipo']?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['mensaje'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php  session_unset(); }?>

    <img src="img/logo.png" class="img-fluid">
</div>
<?php
// Footer
include 'footer.php';
?>