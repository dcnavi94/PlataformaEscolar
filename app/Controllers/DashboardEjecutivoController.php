<?php
// app/Controllers/DashboardEjecutivoController.php

require_once '../app/Core/Controller.php';
require_once '../app/Helpers/AnalyticsHelper.php';
require_once '../app/Helpers/ChartDataHelper.php';

class DashboardEjecutivoController extends Controller {

    public function __construct() {
        $this->requireRole('ADMIN');
    }

    /**
     * Main dashboard
     */
    public function index() {
        // Get all KPIs
        $alumnosStats = AnalyticsHelper::getAlumnosStats();
        $financialKPIs = AnalyticsHelper::getFinancialKPIs('mes_actual');
        $academicKPIs = AnalyticsHelper::getAcademicKPIs();
        $desercion = AnalyticsHelper::getDesercionRate(12);
        
        // Get chart data
        $ingresosData = AnalyticsHelper::getIngresosPorMes(6);
        $ingresosData = ChartDataHelper::formatMonthLabels($ingresosData, 'mes');
        $ingresosChart = ChartDataHelper::formatLineChart($ingresosData, 'mes', 'total', 'Ingresos');
        
        $programasData = AnalyticsHelper::getDistribucionPorPrograma();
        $programasChart = ChartDataHelper::formatPieChart($programasData, 'programa', 'total');
        
        $estadoPagosData = AnalyticsHelper::getEstadoPagosDistribution();
        $estadoPagosChart = ChartDataHelper::formatBarChart($estadoPagosData, 'estatus', 'monto_total', 'Monto');
        
        $this->view('layouts/header', ['title' => 'Dashboard Ejecutivo']);
        $this->view('admin/dashboard_ejecutivo/index', [
            'alumnosStats' => $alumnosStats,
            'financialKPIs' => $financialKPIs,
            'academicKPIs' => $academicKPIs,
            'desercion' => $desercion,
            'ingresosChart' => json_encode($ingresosChart),
            'programasChart' => json_encode($programasChart),
            'estadoPagosChart' => json_encode($estadoPagosChart)
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Financial detailed view
     */
    public function financiero() {
        $financialKPIs = AnalyticsHelper::getFinancialKPIs('año_actual');
        $ingresosData = AnalyticsHelper::getIngresosPorMes(12);
        $ingresosData = ChartDataHelper::formatMonthLabels($ingresosData, 'mes');
        $ingresosChart = ChartDataHelper::formatLineChart($ingresosData, 'mes', 'total', 'Ingresos');
        
        $this->view('layouts/header', ['title' => 'Dashboard Financiero']);
        $this->view('admin/dashboard_ejecutivo/financiero', [
            'financialKPIs' => $financialKPIs,
            'ingresosChart' => json_encode($ingresosChart)
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Academic detailed view
     */
    public function academico() {
        $academicKPIs = AnalyticsHelper::getAcademicKPIs();
        
        $this->view('layouts/header', ['title' => 'Dashboard Académico']);
        $this->view('admin/dashboard_ejecutivo/academico', [
            'academicKPIs' => $academicKPIs
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Students detailed view
     */
    public function estudiantes() {
        $alumnosStats = AnalyticsHelper::getAlumnosStats();
        $desercion = AnalyticsHelper::getDesercionRate(12);
        $nuevosIngresosData = AnalyticsHelper::getNuevosIngresosPorMes(6);
        $nuevosIngresosData = ChartDataHelper::formatMonthLabels($nuevosIngresosData, 'mes');
        $nuevosIngresosChart = ChartDataHelper::formatLineChart($nuevosIngresosData, 'mes', 'total', 'Nuevos Ingresos');
        
        $this->view('layouts/header', ['title' => 'Dashboard Estudiantes']);
        $this->view('admin/dashboard_ejecutivo/estudiantes', [
            'alumnosStats' => $alumnosStats,
            'desercion' => $desercion,
            'nuevosIngresosChart' => json_encode($nuevosIngresosChart)
        ]);
        $this->view('layouts/footer');
    }

    /**
     * Get chart data (API endpoint)
     */
    public function getChartData($type) {
        header('Content-Type: application/json');
        
        $data = [];
        
        switch ($type) {
            case 'ingresos':
                $rawData = AnalyticsHelper::getIngresosPorMes(6);
                $rawData = ChartDataHelper::formatMonthLabels($rawData, 'mes');
                $data = ChartDataHelper::formatLineChart($rawData, 'mes', 'total', 'Ingresos');
                break;
                
            case 'programas':
                $rawData = AnalyticsHelper::getDistribucionPorPrograma();
                $data = ChartDataHelper::formatPieChart($rawData, 'programa', 'total');
                break;
                
            case 'estado_pagos':
                $rawData = AnalyticsHelper::getEstadoPagosDistribution();
                $data = ChartDataHelper::formatBarChart($rawData, 'estatus', 'monto_total', 'Monto');
                break;
        }
        
        echo json_encode($data);
        exit;
    }
}
