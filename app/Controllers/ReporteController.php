<?php
// app/Controllers/ReporteController.php

require_once '../app/Core/Controller.php';

class ReporteController extends Controller {

    private $reporteModel;
    private $periodoModel;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('ADMIN');
        
        $this->reporteModel = $this->model('Reporte');
        $this->periodoModel = $this->model('Periodo');
    }

    public function index() {

        $this->view('layouts/header', ['title' => 'Reportes']);
        $this->view('admin/reportes/index');
        $this->view('layouts/footer');
    }

    /**
     * Unified Report Generation
     */
    public function generar() {
        
        $programas = $programaModel->all();
        $grupos = $grupoModel->all();
        $periodos = $this->periodoModel->all();

        // Generate report data based on type
        $data = $this->obtenerDatosReporte($tipoReporte, $_GET);
        $data['tipo_reporte'] = $tipoReporte;
        $data['filtros'] = $_GET;
        $data['programas'] = $programas;
        $data['grupos'] = $grupos;
        $data['periodos'] = $periodos;

        // Handle export formats
        if ($formato === 'pdf') {
            $this->exportarPdf($tipoReporte, $data);
        } elseif ($formato === 'excel') {
            $this->exportarExcel($tipoReporte, $data);
        } else {
            // Show in browser
            $this->view('layouts/header', ['title' => 'Reporte - ' . $this->getNombreReporte($tipoReporte)]);
            $this->view('admin/reportes/resultado', $data);
            $this->view('layouts/footer');
        }
    }

    private function obtenerDatosReporte($tipo, $filtros) {
        switch($tipo) {
            case 'alumnos_pendientes':
                return $this->reporteAlumnosPendientes($filtros);
            case 'ingresos_periodo':
                return $this->reporteIngresos($filtros);
            case 'estado_cuenta_grupo':
                return $this->reporteEstadoCuentaGrupo($filtros);
            case 'alumnos_becados':
                return $this->reporteAlumnosBecados($filtros);
            case 'pagos_por_metodo':
                return $this->reportePagosPorMetodo($filtros);
            case 'morosidad':
                return $this->reporteMorosidad($filtros);
            case 'cargos_generados':
                return $this->reporteCargosGenerados($filtros);
            default:
                return ['resultados' => [], 'titulo' => 'Reporte'];
        }
    }

    private function reporteAlumnosPendientes($filtros) {
        $sql = "SELECT a.id_alumno, a.nombre, a.apellidos, a.correo, 
                       p.nombre as programa, g.nombre as grupo,
                       SUM(c.saldo_pendiente) as total_adeudo,
                       COUNT(CASE WHEN c.estatus = 'VENCIDO' THEN 1 END) as cargos_vencidos
                FROM alumnos a
                LEFT JOIN programas p ON a.id_programa = p.id_programa
                LEFT JOIN grupos g ON a.id_grupo = g.id_grupo
                LEFT JOIN cargos c ON a.id_alumno = c.id_alumno
                WHERE a.estatus = 'INSCRITO' AND c.estatus IN ('PENDIENTE', 'VENCIDO', 'PARCIAL')";
        
        $params = [];
        if (!empty($filtros['id_programa'])) {
            $sql .= " AND a.id_programa = ?";
            $params[] = $filtros['id_programa'];
        }
        if (!empty($filtros['id_grupo'])) {
            $sql .= " AND a.id_grupo = ?";
            $params[] = $filtros['id_grupo'];
        }
        
        $sql .= " GROUP BY a.id_alumno HAVING total_adeudo > 0 ORDER BY total_adeudo DESC";
        
        $stmt = $this->reporteModel->query($sql, $params);
        return [
            'titulo' => 'Alumnos con Adeudos',
            'resultados' => $stmt->fetchAll(),
            'columnas' => ['Nombre', 'Correo', 'Programa', 'Grupo', 'Total Adeudo', 'Cargos Vencidos']
        ];
    }

    private function reporteIngresos($filtros) {
        $fechaInicio = $filtros['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $filtros['fecha_fin'] ?? date('Y-m-t');
        
        $sql = "SELECT DATE(p.fecha_pago) as fecha, p.metodo_pago, 
                       COUNT(*) as num_pagos, SUM(p.monto_total) as total
                FROM pagos p
                WHERE p.estado = 'COMPLETADO' 
                  AND DATE(p.fecha_pago) BETWEEN ? AND ?";
        
        $params = [$fechaInicio, $fechaFin];
        if (!empty($filtros['metodo_pago'])) {
            $sql .= " AND p.metodo_pago = ?";
            $params[] = $filtros['metodo_pago'];
        }
        
        $sql .= " GROUP BY DATE(p.fecha_pago), p.metodo_pago ORDER BY fecha DESC";
        
        $stmt = $this->reporteModel->query($sql, $params);
        return [
            'titulo' => 'Ingresos por Periodo',
            'resultados' => $stmt->fetchAll(),
            'columnas' => ['Fecha', 'Método de Pago', 'Núm. Pagos', 'Total']
        ];
    }

    private function reporteEstadoCuentaGrupo($filtros) {
        $idGrupo = $filtros['id_grupo'] ?? null;
        if (!$idGrupo) {
            return ['titulo' => 'Estado de Cuenta por Grupo', 'resultados' => [], 'columnas' => []];
        }
        
        $sql = "SELECT a.nombre, a.apellidos, 
                       SUM(CASE WHEN c.estatus IN ('PENDIENTE', 'PARCIAL') THEN c.saldo_pendiente ELSE 0 END) as pendiente,
                       SUM(CASE WHEN c.estatus = 'PAGADO' THEN c.monto ELSE 0 END) as pagado
                FROM alumnos a
                LEFT JOIN cargos c ON a.id_alumno = c.id_alumno
                WHERE a.id_grupo = ?
                GROUP BY a.id_alumno";
        
        $stmt = $this->reporteModel->query($sql, [$idGrupo]);
        return [
            'titulo' => 'Estado de Cuenta por Grupo',
            'resultados' => $stmt->fetchAll(),
            'columnas' => ['Nombre', 'Apellidos', 'Pendiente', 'Pagado']
        ];
    }

    private function reporteAlumnosBecados($filtros) {
        $becaMinima = $filtros['beca_minima'] ?? 0;
        
        $sql = "SELECT a.nombre, a.apellidos, a.correo, p.nombre as programa,
                       a.porcentaje_beca, pr.monto_colegiatura,
                       (pr.monto_colegiatura * a.porcentaje_beca / 100) as descuento_mensual
                FROM alumnos a
                JOIN programas pr ON a.id_programa = pr.id_programa
                LEFT JOIN programas p ON a.id_programa = p.id_programa
                WHERE a.porcentaje_beca >= ? AND a.estatus = 'INSCRITO'";
        
        $params = [$becaMinima];
        if (!empty($filtros['id_programa'])) {
            $sql .= " AND a.id_programa = ?";
            $params[] = $filtros['id_programa'];
        }
        
        $sql .= " ORDER BY a.porcentaje_beca DESC";
        
        $stmt = $this->reporteModel->query($sql, $params);
        return [
            'titulo' => 'Alumnos Becados',
            'resultados' => $stmt->fetchAll(),
            'columnas' => ['Nombre', 'Apellidos', 'Correo', 'Programa', '% Beca', 'Colegiatura', 'Descuento Mensual']
        ];
    }

    private function reportePagosPorMetodo($filtros) {
        $fechaInicio = $filtros['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $filtros['fecha_fin'] ?? date('Y-m-t');
        
        $sql = "SELECT p.metodo_pago, COUNT(*) as cantidad, SUM(p.monto_total) as total
                FROM pagos p
                WHERE p.estado = 'COMPLETADO' AND DATE(p.fecha_pago) BETWEEN ? AND ?
                GROUP BY p.metodo_pago";
        
        $stmt = $this->reporteModel->query($sql, [$fechaInicio, $fechaFin]);
        return [
            'titulo' => 'Pagos por Método de Pago',
            'resultados' => $stmt->fetchAll(),
            'columnas' => ['Método de Pago', 'Cantidad', 'Total']
        ];
    }

    private function reporteMorosidad($filtros) {
        $sql = "SELECT a.nombre, a.apellidos, a.correo, COUNT(*) as cargos_vencidos,
                       SUM(c.saldo_pendiente) as monto_vencido,
                       MIN(c.fecha_limite) as fecha_primer_vencimiento
                FROM alumnos a
                JOIN cargos c ON a.id_alumno = c.id_alumno
                WHERE c.estatus = 'VENCIDO' AND a.estatus = 'INSCRITO'";
        
        $params = [];
        if (!empty($filtros['id_programa'])) {
            $sql .= " AND a.id_programa = ?";
            $params[] = $filtros['id_programa'];
        }
        if (!empty($filtros['id_grupo'])) {
            $sql .= " AND a.id_grupo = ?";
            $params[] = $filtros['id_grupo'];
        }
        
        $sql .= " GROUP BY a.id_alumno ORDER BY monto_vencido DESC";
        
        $stmt = $this->reporteModel->query($sql, $params);
        return [
            'titulo' => 'Reporte de Morosidad',
            'resultados' => $stmt->fetchAll(),
            'columnas' => ['Nombre', 'Apellidos', 'Correo', 'Cargos Vencidos', 'Monto Vencido', 'Primer Vencimiento']
        ];
    }

    private function reporteCargosGenerados($filtros) {
        $sql = "SELECT per.nombre as periodo, pr.nombre as programa,
                       COUNT(*) as total_cargos, SUM(c.monto) as monto_total
                FROM cargos c
                JOIN periodos per ON c.id_periodo = per.id_periodo
                JOIN alumnos a ON c.id_alumno = a.id_alumno
                JOIN programas pr ON a.id_programa = pr.id_programa
                WHERE 1=1";
        
        $params = [];
        if (!empty($filtros['id_periodo'])) {
            $sql .= " AND c.id_periodo = ?";
            $params[] = $filtros['id_periodo'];
        }
        if (!empty($filtros['id_programa'])) {
            $sql .= " AND a.id_programa = ?";
            $params[] = $filtros['id_programa'];
        }
        
        $sql .= " GROUP BY per.id_periodo, pr.id_programa";
        
        $stmt = $this->reporteModel->query($sql, $params);
        return [
            'titulo' => 'Cargos Generados por Periodo',
            'resultados' => $stmt->fetchAll(),
            'columnas' => ['Periodo', 'Programa', 'Total Cargos', 'Monto Total']
        ];
    }

    private function getNombreReporte($tipo) {
        $nombres = [
            'alumnos_pendientes' => 'Alumnos con Adeudos',
            'ingresos_periodo' => 'Ingresos por Periodo',
            'estado_cuenta_grupo' => 'Estado de Cuenta por Grupo',
            'alumnos_becados' => 'Alumnos Becados',
            'pagos_por_metodo' => 'Pagos por Método',
            'morosidad' => 'Reporte de Morosidad',
            'cargos_generados' => 'Cargos Generados'
        ];
        return $nombres[$tipo] ?? 'Reporte';
    }

    /**
     * Reporte de Alumnos con Pagos Pendientes
     */
    public function pendientes() {

        $filtros = [
            'grupo_id' => $_GET['grupo_id'] ?? null,
            'periodo_id' => $_GET['periodo_id'] ?? null
        ];

        $resultados = $this->reporteModel->getAlumnosPendientes($filtros);
        $periodos = $this->periodoModel->getAll();
        
        // Get groups for filter (could be optimized to load only active)
        $grupoModel = $this->model('Grupo');
        $grupos = $grupoModel->getAllWithRelations();

        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $this->exportarCsv('alumnos_pendientes_' . date('Y-m-d'), $resultados);
            return;
        }

        $data = [
            'resultados' => $resultados,
            'periodos' => $periodos,
            'grupos' => $grupos,
            'filtros' => $filtros
        ];

        $this->view('layouts/header', ['title' => 'Reporte de Pendientes']);
        $this->view('admin/reportes/pendientes', $data);
        $this->view('layouts/footer');
    }

    /**
     * Reporte de Ingresos
     */
    public function ingresos() {

        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-t');

        $resultados = $this->reporteModel->getIngresosPorPeriodo($fechaInicio, $fechaFin);
        $totales = $this->reporteModel->getTotalesIngresos($fechaInicio, $fechaFin);

        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $this->exportarCsv('ingresos_' . date('Y-m-d'), $resultados);
            return;
        }

        $data = [
            'resultados' => $resultados,
            'totales' => $totales,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ];

        $this->view('layouts/header', ['title' => 'Reporte de Ingresos']);
        $this->view('admin/reportes/ingresos', $data);
        $this->view('layouts/footer');
    }

    /**
     * Helper to export data to CSV
     */
    private function exportarCsv($filename, $data) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for Excel UTF-8 compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        if (!empty($data)) {
            // Headers
            fputcsv($output, array_keys($data[0]));
            
            // Rows
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
    }

    /**
     * Export to Excel (CSV format)
     */
    private function exportarExcel($tipoReporte, $data) {
        $filename = $this->getNombreReporte($tipoReporte) . '_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for Excel UTF-8 compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        if (!empty($data['resultados'])) {
            // Headers
            fputcsv($output, $data['columnas']);
            
            // Rows
            foreach ($data['resultados'] as $row) {
                fputcsv($output, array_values($row));
            }
        }
        
        fclose($output);
        exit;
    }

    /**
     * Export to PDF (Uses browser print)
     */
    private function exportarPdf($tipoReporte, $data) {
        // Create a printable HTML page
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . htmlspecialchars($data['titulo']) . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .header { text-align: right; color: #666; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <strong>Generado:</strong> ' . date('d/m/Y H:i') . '
    </div>
    <h1>' . htmlspecialchars($data['titulo']) . '</h1>
    <table>
        <thead>
            <tr>';
        
        foreach ($data['columnas'] as $columna) {
            $html .= '<th>' . htmlspecialchars($columna) . '</th>';
        }
        
        $html .= '</tr>
        </thead>
        <tbody>';
        
        foreach ($data['resultados'] as $fila) {
            $html .= '<tr>';
            foreach ($fila as $valor) {
                if (is_numeric($valor) && strpos($valor, '.') !== false) {
                    $html .= '<td>$' . number_format((float)$valor, 2) . '</td>';
                } else {
                    $html .= '<td>' . htmlspecialchars($valor) . '</td>';
                }
            }
            $html .= '</tr>';
        }
        
        $html .= '</tbody>
    </table>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>';
        
        echo $html;
        exit;
    }
}
