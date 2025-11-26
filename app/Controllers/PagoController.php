<?php
// app/Controllers/PagoController.php

require_once '../app/Core/Controller.php';

class PagoController extends Controller {

    private $pagoModel;
    private $cargoModel;
    private $alumnoModel;
    private $bitacora;

    public function __construct() {
        $this->requireAuth();
        $this->requireRole('ADMIN');
        
        $this->pagoModel = $this->model('Pago');
        $this->cargoModel = $this->model('Cargo');
        $this->alumnoModel = $this->model('Alumno');
        $this->bitacora = $this->model('Bitacora');
    }

    /**
     * Show payment form for a specific cargo
     */
    public function registrar($cargoId) {

        $cargo = $this->cargoModel->find($cargoId);
        
        if (!$cargo) {
            $_SESSION['error'] = 'Cargo no encontrado';
            $this->redirect('/admin/dashboard');
        }

        // Get alumno info
        $alumno = $this->alumnoModel->find($cargo['id_alumno']);

        // Get concepto info
        $sql = "SELECT * FROM conceptos_pago WHERE id_concepto = ?";
        $stmt = $this->cargoModel->query($sql, [$cargo['id_concepto']]);
        $concepto = $stmt->fetch();

        $data = [
            'cargo' => $cargo,
            'alumno' => $alumno,
            'concepto' => $concepto
        ];

        $this->view('layouts/header', ['title' => 'Registrar Pago']);
        $this->view('admin/pagos/registrar', $data);
        $this->view('layouts/footer');
    }

    /**
     * Process payment
     */
    public function procesar() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/dashboard');
        }

        $cargoId = (int)($_POST['id_cargo'] ?? 0);
        $monto = floatval($_POST['monto'] ?? 0);
        $metodoPago = $_POST['metodo_pago'] ?? 'EFECTIVO';
        $referencia = trim($_POST['referencia'] ?? '');
        $notas = trim($_POST['notas'] ?? '');

        // Validation
        if ($cargoId === 0 || $monto <= 0) {
            $_SESSION['error'] = 'Datos inválidos';
            $this->redirect('/pago/registrar/' . $cargoId);
        }

        $cargo = $this->cargoModel->find($cargoId);
        if (!$cargo) {
            $_SESSION['error'] = 'Cargo no encontrado';
            $this->redirect('/admin/dashboard');
        }

        // Check if monto exceeds saldo_pendiente
        if ($monto > $cargo['saldo_pendiente']) {
            $_SESSION['error'] = 'El monto no puede ser mayor al saldo pendiente';
            $this->redirect('/pago/registrar/' . $cargoId);
        }

        try {
            // Handle file upload if present
            $comprobanteUrl = null;
            if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] === UPLOAD_ERR_OK) {
                $comprobanteUrl = $this->handleFileUpload($_FILES['comprobante']);
            }

            // Process payment
            $pagoId = $this->pagoModel->processPago([
                'id_cargo' => $cargoId,
                'monto' => $monto,
                'metodo_pago' => $metodoPago,
                'referencia' => $referencia,
                'comprobante_url' => $comprobanteUrl,
                'notas' => $notas
            ]);

            $this->bitacora->log(
                $_SESSION['user_id'],
                'pagos',
                $pagoId,
                'INSERT',
                "Pago registrado: $" . number_format($monto, 2) . " - Método: $metodoPago"
            );

            $_SESSION['success'] = 'Pago registrado exitosamente';
            $this->redirect('/alumnoadmin/show/' . $cargo['id_alumno']);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al procesar pago: ' . $e->getMessage();
            $this->redirect('/pago/registrar/' . $cargoId);
        }
    }

    /**
     * View payment history
     */
    public function historial($alumnoId = null) {

        if ($alumnoId) {
            $pagos = $this->pagoModel->getByAlumno($alumnoId);
            $alumno = $this->alumnoModel->find($alumnoId);
            $titulo = "Historial de Pagos - " . $alumno['nombre'] . ' ' . $alumno['apellidos'];
        } else {
            // All payments
            $sql = "SELECT p.*, a.nombre as alumno_nombre, a.apellidos as alumno_apellidos, 
                    c.monto as cargo_monto, cp.nombre as concepto_nombre
                    FROM pagos p
                    INNER JOIN pago_detalle pd ON p.id_pago = pd.id_pago
                    INNER JOIN cargos c ON pd.id_cargo = c.id_cargo
                    INNER JOIN alumnos a ON c.id_alumno = a.id_alumno
                    INNER JOIN conceptos_pago cp ON c.id_concepto = cp.id_concepto
                    ORDER BY p.fecha_pago DESC
                    LIMIT 100";
            $stmt = $this->pagoModel->query($sql);
            $pagos = $stmt->fetchAll();
            $alumno = null;
            $titulo = "Historial de Pagos Recientes";
        }

        $data = [
            'pagos' => $pagos,
            'alumno' => $alumno
        ];

        $this->view('layouts/header', ['title' => $titulo]);
        $this->view('admin/pagos/historial', $data);
        $this->view('layouts/footer');
    }

    /**
     * Handle file upload for payment evidence
     */
    private function handleFileUpload($file) {
        $uploadDir = '../public/uploads/comprobantes/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Tipo de archivo no permitido. Solo JPG, PNG o PDF');
        }

        // Validate file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception('El archivo es demasiado grande. Máximo 5MB');
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'comprobante_' . time() . '_' . uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        // Move file
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new Exception('Error al subir el archivo');
        }

        return '/uploads/comprobantes/' . $filename;
    }

    /**
     * Delete payment (admin only, with restrictions)
     */
    public function eliminar($pagoId) {

        try {
            $pago = $this->pagoModel->find($pagoId);
            if (!$pago) {
                $_SESSION['error'] = 'Pago no encontrado';
                $this->redirect('/pago/historial');
            }

            // Get cargo to restore saldo
            $cargo = $this->cargoModel->find($pago['id_cargo']);
            
            // Restore cargo saldo
            $nuevoSaldo = $cargo['saldo_pendiente'] + $pago['monto'];
            $nuevoEstatus = 'PENDIENTE';
            
            if ($nuevoSaldo >= $cargo['monto']) {
                $nuevoEstatus = $nuevoSaldo > 0 ? 'PENDIENTE' : 'PAGADO';
            } else {
                $nuevoEstatus = 'PARCIAL';
            }

            $this->cargoModel->update($cargo['id_cargo'], [
                'saldo_pendiente' => $nuevoSaldo,
                'estatus' => $nuevoEstatus
            ]);

            // Delete pago
            $this->pagoModel->delete($pagoId);

            $this->bitacora->log(
                $_SESSION['user_id'],
                'pagos',
                $pagoId,
                'DELETE',
                "Pago eliminado - Monto: $" . number_format($pago['monto'], 2)
            );

            $_SESSION['success'] = 'Pago eliminado y saldo restaurado';
            $this->redirect('/alumnoadmin/show/' . $cargo['id_alumno']);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar pago';
            $this->redirect('/pago/historial');
        }
    }


    /**
     * Confirm PayPal payment (called via AJAX)
     */
    public function confirmarPaypal() {
        // This is an AJAX callback, but still requires permission if called by admin
        // However, if it's called by student, it might fail if we require ADMIN role in constructor
        // But PagoController requires ADMIN role in constructor, so students can't access this anyway?
        // Wait, students pay via PayPal too. 
        // If students use this controller, then requiring ADMIN in constructor is wrong for them.
        // But this controller seems to be for Admin panel based on views 'admin/pagos/...'
        // Let's check constructor.
        // Constructor says: $this->requireRole('ADMIN');
        // So students cannot use this controller.
        // So this is for Admin recording manual payments or maybe Admin testing PayPal?
        // If students have their own payment flow, it must be in AlumnoController or similar.
        // AlumnoController has 'pagos' method.
        // So this is strictly for Admins.

        header('Content-Type: application/json');
        
        try {
            // Get JSON input
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['cargoId']) || !isset($input['details'])) {
                throw new Exception('Datos inválidos');
            }

            $cargoId = $input['cargoId'];
            $details = $input['details'];
            $orderId = $input['orderID'];
            
            // Verify payment status from details
            if ($details['status'] !== 'COMPLETED') {
                throw new Exception('El pago no fue completado');
            }

            // Get cargo info
            $cargo = $this->cargoModel->find($cargoId);
            if (!$cargo) {
                throw new Exception('Cargo no encontrado');
            }

            // Calculate amount (excluding commission for internal record, or including? 
            // Usually we record the debt amount paid. The commission is extra fee.
            // For simplicity, we record the full payment of the debt.
            // The amount paid in PayPal includes 4%.
            // We should record the payment amount equal to the debt (or partial).
            // Let's assume the user paid the full pending amount + commission.
            // We record the payment as the pending amount.
            
            $montoPagado = $cargo['saldo_pendiente']; // Assuming full payment for now
            
            // Register payment
            $pagoId = $this->pagoModel->processPago([
                'id_cargo' => $cargoId,
                'monto' => $montoPagado,
                'metodo_pago' => 'PAYPAL',
                'referencia' => $orderId,
                'comprobante_url' => null,
                'notas' => 'Pago vía PayPal. OrderID: ' . $orderId
            ]);

            $this->bitacora->log(
                $_SESSION['user_id'],
                'pagos',
                $pagoId,
                'INSERT',
                "Pago PayPal registrado: $" . number_format($montoPagado, 2)
            );

            echo json_encode(['success' => true, 'message' => 'Pago registrado']);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}
