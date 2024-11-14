

<?php
// Iniciar la sesión y conectar a la base de datos
session_start();
include('db.php');

// Consultas SQL para generar los datos del Dashboard

// 1. Total de ventas diarias, semanales, mensuales
// Ventas diarias
$ventasDiariasQuery = "SELECT DATE(fecha) as fecha, SUM(total) as total_ventas FROM pedido WHERE estado = 'entregado' GROUP BY DATE(fecha)";
$result_ventas_diarias = $conexion->query($ventasDiariasQuery);
$ventas_diarias_data = [];
while($row = $result_ventas_diarias->fetch_assoc()) {
    $ventas_diarias_data[] = $row;
}

// Ventas semanales
$ventasSemanalesQuery = "SELECT WEEK(fecha) as semana, SUM(total) as total_ventas FROM pedido WHERE estado = 'entregado' GROUP BY WEEK(fecha)";
$result_ventas_semanales = $conexion->query($ventasSemanalesQuery);
$ventas_semanales_data = [];
while($row = $result_ventas_semanales->fetch_assoc()) {
    $ventas_semanales_data[] = $row;
}

// Ventas mensuales
$ventasMensualesQuery = "SELECT MONTH(fecha) as mes, SUM(total) as total_ventas FROM pedido WHERE estado = 'entregado' GROUP BY MONTH(fecha)";
$result_ventas_mensuales = $conexion->query($ventasMensualesQuery);
$ventas_mensuales_data = [];
while($row = $result_ventas_mensuales->fetch_assoc()) {
    $ventas_mensuales_data[] = $row;
}

// 2. Productos más vendidos
$productosVendidosQuery = "SELECT p.nombre, SUM(dp.cantidad) as total_vendido FROM detalle_pedido dp
JOIN producto p ON dp.ID_producto = p.ID_producto
GROUP BY dp.ID_producto ORDER BY total_vendido DESC LIMIT 5";
$result_productos_vendidos = $conexion->query($productosVendidosQuery);
$productos_vendidos_data = [];
while($row = $result_productos_vendidos->fetch_assoc()) {
    $productos_vendidos_data[] = $row;
}

// 3. Ingresos generados
$ingresosQuery = "SELECT SUM(total) as ingresos_totales FROM pedido WHERE estado = 'entregado'";
$result_ingresos = $conexion->query($ingresosQuery);
$ingresos_data = $result_ingresos->fetch_assoc();

// 4. Estado del inventario
$inventarioQuery = "SELECT nombre, stock FROM producto";
$result_inventario = $conexion->query($inventarioQuery);
$inventario_data = [];
while($row = $result_inventario->fetch_assoc()) {
    $inventario_data[] = $row;
}

// 5. Alertas de bajo inventario
$bajoInventarioQuery = "SELECT nombre, stock FROM producto WHERE stock < 10";
$result_bajo_inventario = $conexion->query($bajoInventarioQuery);
$bajo_inventario_data = [];
while($row = $result_bajo_inventario->fetch_assoc()) {
    $bajo_inventario_data[] = $row;
}

// 6. Información demográfica de los clientes
$edadClientesQuery = "SELECT edad, COUNT(*) as total FROM usuario WHERE edad IS NOT NULL GROUP BY edad";
$result_edad_clientes = $conexion->query($edadClientesQuery);
$edad_clientes_data = [];
while($row = $result_edad_clientes->fetch_assoc()) {
    $edad_clientes_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadisticas en ventas</title>

    <!-- Frameworks CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Frameworks JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-bottom: 30px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        h4 {
            margin-top: 20px;
            color: #007bff;
        }
        .chart-container {
            padding: 20px;
        }
        .alert-inventario {
            background-color: #ffc107;
            color: #212529;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-5">Dashboard de Ventas</h1>

    <!-- Fila de resumen de ventas -->
    <div class="row">
        <!-- Ventas Diarias -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">
                    Ventas Diarias (Bs.)
                </div>
                <div class="card-body chart-container">
                    <canvas id="ventasDiariasChart"></canvas>
                    <p class="text-center">Representa las ventas totales en bolivianos (Bs.) realizadas diariamente.</p>
                </div>
            </div>
        </div>

        <!-- Ventas Semanales -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">
                    Ventas Semanales (Bs.)
                </div>
                <div class="card-body chart-container">
                    <canvas id="ventasSemanalesChart"></canvas>
                    <p class="text-center">Suma total de ventas en bolivianos (Bs.) agrupadas por semana.</p>
                </div>
            </div>
        </div>

        <!-- Ventas Mensuales -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">
                    Ventas Mensuales (Bs.)
                </div>
                <div class="card-body chart-container">
                    <canvas id="ventasMensualesChart"></canvas>
                    <p class="text-center">Total de ventas en bolivianos (Bs.) realizadas cada mes.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos más vendidos e Ingresos -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    Productos Más Vendidos (Unidades)
                </div>
                <div class="card-body chart-container">
                    <canvas id="productosVendidosChart"></canvas>
                    <p class="text-center">Muestra los productos con mayor cantidad de ventas en unidades.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    Ingresos Totales Generados (Bs.)
                </div>
                <div class="card-body chart-container">
                    <canvas id="ingresosChart"></canvas>
                    <p class="text-center">El total de ingresos generados en bolivianos (Bs.) por pedidos entregados.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado del Inventario y Alertas de Bajo Inventario -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    Estado del Inventario (Stock Disponible)
                </div>
                <div class="card-body chart-container">
                    <canvas id="inventarioChart"></canvas>
                    <p class="text-center">Visualiza el stock disponible de los productos.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Demográfica -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    Distribución de Usuarios por Edad
                </div>
                <div class="card-body chart-container">
                    <canvas id="usuariosEdadChart"></canvas>
                    <p class="text-center">Muestra la distribución de los usuarios agrupados por edad.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para los gráficos con Chart.js -->
<script>
    // Datos de ventas diarias
    var ventasDiarias = <?php echo json_encode(array_column($ventas_diarias_data, 'total_ventas')); ?>;
    var fechasDiarias = <?php echo json_encode(array_column($ventas_diarias_data, 'fecha')); ?>;
    var ctxVentasDiarias = document.getElementById('ventasDiariasChart').getContext('2d');
    new Chart(ctxVentasDiarias, {
        type: 'bar',
        data: {
            labels: fechasDiarias,
            datasets: [{
                label: 'Ventas Diarias (Bs.)',
                data: ventasDiarias,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        }
    });

    // Datos de ventas semanales
    var ventasSemanales = <?php echo json_encode(array_column($ventas_semanales_data, 'total_ventas')); ?>;
    var semanas = <?php echo json_encode(array_column($ventas_semanales_data, 'semana')); ?>;
    var ctxVentasSemanales = document.getElementById('ventasSemanalesChart').getContext('2d');
    new Chart(ctxVentasSemanales, {
        type: 'line',
        data: {
            labels: semanas,
            datasets: [{
                label: 'Ventas Semanales (Bs.)',
                data: ventasSemanales,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        }
    });

    // Datos de ventas mensuales
    var ventasMensuales = <?php echo json_encode(array_column($ventas_mensuales_data, 'total_ventas')); ?>;
    var meses = <?php echo json_encode(array_column($ventas_mensuales_data, 'mes')); ?>;
    var ctxVentasMensuales = document.getElementById('ventasMensualesChart').getContext('2d');
    new Chart(ctxVentasMensuales, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [{
                label: 'Ventas Mensuales (Bs.)',
                data: ventasMensuales,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        }
    });

    // Productos más vendidos
    var productos = <?php echo json_encode(array_column($productos_vendidos_data, 'nombre')); ?>;
    var totalVendidos = <?php echo json_encode(array_column($productos_vendidos_data, 'total_vendido')); ?>;
    var ctxProductosVendidos = document.getElementById('productosVendidosChart').getContext('2d');
    new Chart(ctxProductosVendidos, {
        type: 'bar',
        data: {
            labels: productos,
            datasets: [{
                label: 'Total Vendido (Unidades)',
                data: totalVendidos,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        }
    });

    // Ingresos generados
    var ingresos = <?php echo json_encode($ingresos_data['ingresos_totales']); ?>;
    var ctxIngresos = document.getElementById('ingresosChart').getContext('2d');
    new Chart(ctxIngresos, {
        type: 'line',
        data: {
            labels: ['Ingresos (Bs.)'],
            datasets: [{
                label: 'Ingresos Totales',
                data: [ingresos],
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }]
        }
    });

    // Estado del inventario
    var inventario = <?php echo json_encode(array_column($inventario_data, 'stock')); ?>;
    var productosInventario = <?php echo json_encode(array_column($inventario_data, 'nombre')); ?>;
    var ctxInventario = document.getElementById('inventarioChart').getContext('2d');
    new Chart(ctxInventario, {
        type: 'bar',
        data: {
            labels: productosInventario,
            datasets: [{
                label: 'Stock Disponible (Unidades)',
                data: inventario,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        }
    });

    // Distribución por edad
    var edades = <?php echo json_encode(array_column($edad_clientes_data, 'edad')); ?>;
    var totalPorEdad = <?php echo json_encode(array_column($edad_clientes_data, 'total')); ?>;
    var ctxEdadUsuarios = document.getElementById('usuariosEdadChart').getContext('2d');
    new Chart(ctxEdadUsuarios, {
        type: 'pie',
        data: {
            labels: edades,
            datasets: [{
                label: 'Usuarios por Edad',
                data: totalPorEdad,
                backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)'],
                borderWidth: 1
            }]
        }
    });

</script>
<!-- Botones para descargar los reportes -->
<div class="container mt-5 text-center">
    <h3>Generar Reportes</h3>
    <button class="btn btn-primary btn-lg m-3" onclick="window.location.href='reporte_pdf.php'">
        <i class="fas fa-file-pdf"></i> Descargar Reporte en PDF
    </button>
    <button class="btn btn-success btn-lg m-3" onclick="window.location.href='reporte_excel.php'">
        <i class="fas fa-file-excel"></i> Descargar Reporte en Excel
    </button>
</div>


</body>
</html>

