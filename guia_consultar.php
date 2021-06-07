<?php
// Conexion a la base de datos
include 'db.php';
?>

<?php
// Header
include 'header.php';
?>

<?php
    $guia = '';
    if (isset($_GET['btnBuscar'])){

        $guia = strtolower($_GET['guia']);
    };
?>

<div class="container p-4">
<?php   if(isset($_SESSION['mensaje'])){ ?>
    <div class="alert alert-<?= $_SESSION['mensaje_tipo']?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['mensaje'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php  session_unset(); };?>

    <div class="col-md-12">
    <h3 align="left">Buscar guía de despacho:</h3>
    <table class="table table-light table-bordered">
        <tbody>
            <tr>
                <td colspan="5">
                    <form action="guia_consultar.php" method="get">
                        <div class="alert alert-primary">
                            <div class="form-group">
                                <label for="my_input">Guía de despacho</label>
                                <input type="text" class="form-control" id="guia" name="guia" maxlength="40" placeholder="N° de Guía de despacho, RUT Cliente, Nombre Cliente, RUT Vendedor">
                            </div>
                        </div>
                        <button class="btn btn-info btn-md btn-block" type="submit" name="btnBuscar" value="buscar"><b class="text-white">Buscar</b></button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
        <h3 align="center">Guías de despacho</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>N° Guía</th>
                    <th>RUT vendedor</th>
                    <th>RUT cliente</th>
                    <th>Nombre cliente </th>
                    <th>Monto Neto</th>
                    <th>IVA</th>
                    <th>Imp Adic</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query_guia = "SELECT * FROM guias_despacho g WHERE (g.n_guia LIKE '%$guia%' OR g.rut_comprador LIKE '%$guia%' OR g.nom_cliente LIKE '%$guia%' OR g.rut_vendedor LIKE '%$guia%') ORDER BY g.id DESC";
                    $result_guia = mysqli_query($conn, $query_guia);

                    while($row = mysqli_fetch_array($result_guia)){?>
                        <tr>
                            <td><?php echo $row['n_guia']?></td>
                            <td><?php echo $row['rut_vendedor']?></td>
                            <td><?php echo $row['rut_comprador']?></td>
                            <td><?php echo $row['nom_cliente']?></td>
                            <td align="right"><?php echo $row['monto_neto']?></td>
                            <td align="right"><?php echo $row['iva_19']?></td>
                            <td align="right"><?php echo $row['imp_adic']?></td>
                            <td align="right"><?php echo $row['total']?></td>
                            <td>
                                <a href="guia_mostrar.php?num_guia=<?php echo $row['n_guia']?>" class="btn btn-sm btn-info"><b class="text-white">VER</b></a>
                                <a href="guia_borrar.php?num_guia=<?php echo $row['n_guia']?>" class="btn btn-sm btn-danger"><b class="text-white">BORRAR</b></a>
                                <a href="guia_pdf.php?num_guia=<?php echo $row['n_guia']?><?php echo '&'?><?php echo 'rut_vendedor='?><?php echo $row['rut_vendedor']?><?php echo '&'?>
                                <?php echo 'rut_comprador='?><?php echo $row['rut_comprador']?><?php echo '&'?><?php echo 'nom_cliente='?><?php echo $row['nom_cliente']?>
                                " class="btn btn-sm btn-success"><b class="text-white">IMPRIMIR</b></a>
                            </td>
                        </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Footer
include 'footer.php';
?>