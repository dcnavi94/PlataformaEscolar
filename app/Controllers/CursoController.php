<?php
// app/Controllers/CursoController.php

require_once '../app/Core/Controller.php';
require_once '../app/Models/Modulo.php';
require_once '../app/Models/Tema.php';
require_once '../app/Models/Actividad.php';
require_once '../app/Models/RecursoClase.php';
require_once '../app/Models/Asignacion.php';
require_once '../app/Models/Profesor.php';
require_once '../app/Models/Anuncio.php';

class CursoController extends Controller {
    private $moduloModel;
    private $temaModel;
    private $actividadModel;
    private $recursoModel;
    private $asignacionModel;
    private $profesorModel;
    private $anuncioModel;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->moduloModel = new Modulo();
        $this->temaModel = new Tema();
        $this->actividadModel = new Actividad();
        $this->recursoModel = new RecursoClase();
        $this->asignacionModel = new Asignacion();
        $this->profesorModel = new Profesor();
        $this->anuncioModel = new Anuncio();
    }

    /**
     * Main course view (Classwork/Stream)
     */
    public function index($id_asignacion) {
        $this->requireRole('PROFESOR');

        // Verify ownership
        $asignacion = $this->asignacionModel->getWithDetails($id_asignacion);
        $profesor = $this->profesorModel->getByUserId($_SESSION['user_id']);
        
        if (!$asignacion || $asignacion['id_profesor'] != $profesor['id_profesor']) {
            $_SESSION['error'] = 'No tiene permiso para ver este curso';
            $this->redirect('/profesor/dashboard');
            return;
        }

        // Get course structure (Modules -> Topics -> Content)
        $modulos = $this->moduloModel->getCourseStructure($id_asignacion);
        
        // Populate content for each topic (for Classwork tab)
        foreach ($modulos as &$modulo) {
            foreach ($modulo['temas'] as &$tema) {
                $temaData = $this->temaModel->getWithContent($tema['id_tema']);
                $tema['contenido'] = array_merge($temaData['actividades'], $temaData['recursos']);
                
                // Sort content by date (newest first)
                usort($tema['contenido'], function($a, $b) {
                    return strtotime($b['fecha_publicacion']) - strtotime($a['fecha_publicacion']);
                });
            }
        }

        // Build Stream (All content + Announcements)
        $stream = [];
        
        // Get all activities for this assignment
        $actividades = $this->actividadModel->getByAsignacion($id_asignacion);
        foreach ($actividades as $actividad) {
            $actividad['item_type'] = 'ACTIVIDAD';
            $stream[] = $actividad;
        }

        // Get all resources for this assignment
        $recursos = $this->recursoModel->getByAsignacion($id_asignacion);
        foreach ($recursos as $recurso) {
            $recurso['item_type'] = 'RECURSO';
            $stream[] = $recurso;
        }

        // Get announcements
        $anuncios = $this->anuncioModel->getByAsignacion($id_asignacion);
        foreach ($anuncios as $anuncio) {
            $anuncio['item_type'] = 'ANUNCIO';
            $anuncio['titulo'] = 'Anuncio'; // For display consistency
            $stream[] = $anuncio;
        }

        // Sort stream by date
        usort($stream, function($a, $b) {
            return strtotime($b['fecha_publicacion']) - strtotime($a['fecha_publicacion']);
        });

        // Filter orphaned content (no topic)
        $orphanedContent = [];
        foreach ($stream as $item) {
            if (($item['item_type'] == 'ACTIVIDAD' || $item['item_type'] == 'RECURSO') && empty($item['id_tema'])) {
                $orphanedContent[] = $item;
            }
        }

        // Get students (People)
        $alumnos = $this->asignacionModel->getStudentList($id_asignacion);

        $this->view('profesor/curso/index', [
            'title' => $asignacion['materia_nombre'] . ' - ' . $asignacion['grupo_nombre'],
            'asignacion' => $asignacion,
            'modulos' => $modulos,
            'stream' => $stream,
            'orphanedContent' => $orphanedContent,
            'alumnos' => $alumnos
        ]);
    }

    /**
     * Store new Module
     */
    public function storeModulo() {
        $this->requireRole('PROFESOR');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_asignacion' => $_POST['id_asignacion'],
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'] ?? '',
                'orden' => $_POST['orden'] ?? 0
            ];
            
            $this->moduloModel->insert($data);
            $_SESSION['success'] = 'Módulo creado exitosamente';
            $this->redirect('/curso/index/' . $_POST['id_asignacion']);
        }
    }

    /**
     * Store new Topic
     */
    public function storeTema() {
        $this->requireRole('PROFESOR');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_modulo' => $_POST['id_modulo'],
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'] ?? '',
                'orden' => $_POST['orden'] ?? 0
            ];
            
            $this->temaModel->insert($data);
            $_SESSION['success'] = 'Tema creado exitosamente';
            // Need to find asignacion ID to redirect back
            $modulo = $this->moduloModel->find($_POST['id_modulo']);
            $this->redirect('/curso/index/' . $modulo['id_asignacion']);
        }
    }

    /**
     * Create Content Form (Unified)
     */
    public function createContent($id_asignacion) {
        $this->requireRole('PROFESOR');
        
        $asignacion = $this->asignacionModel->getWithDetails($id_asignacion);
        $modulos = $this->moduloModel->getCourseStructure($id_asignacion);
        
        $this->view('profesor/curso/create_content', [
            'title' => 'Crear Contenido',
            'asignacion' => $asignacion,
            'modulos' => $modulos
        ]);
    }

    /**
     * Store Content (Activity or Resource)
     */
    public function storeContent() {
        $this->requireRole('PROFESOR');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $type = $_POST['content_type']; // 'ACTIVITY' or 'RESOURCE'
        $id_asignacion = $_POST['id_asignacion'];
        
        try {
            if ($type === 'ACTIVITY') {
                $data = [
                    'id_asignacion' => $id_asignacion,
                    'id_tema' => $_POST['id_tema'] ?: null,
                    'titulo' => $_POST['titulo'],
                    'descripcion' => $_POST['descripcion'],
                    'tipo' => $_POST['tipo_actividad'], // TAREA, EXAMEN, QUIZ, etc.
                    'fecha_publicacion' => date('Y-m-d H:i:s'),
                    'fecha_limite' => !empty($_POST['fecha_limite']) ? $_POST['fecha_limite'] : null,
                    'puntos_max' => $_POST['puntos_max'] ?? 100,
                    'link_video' => $_POST['link_video'] ?? null,
                    'estado' => 'ACTIVA'
                ];
                
                $this->actividadModel->create($data);
                
            } elseif ($type === 'RESOURCE') {
                $data = [
                    'id_asignacion' => $id_asignacion,
                    'id_tema' => $_POST['id_tema'] ?: null,
                    'titulo' => $_POST['titulo'],
                    'descripcion' => $_POST['descripcion'],
                    'tipo' => $_POST['tipo_recurso'], // VIDEO, DOCUMENTO, etc.
                    'url' => $_POST['url'] ?? null,
                    'fecha_publicacion' => date('Y-m-d H:i:s'),
                    'visible' => 1
                ];
                
                $this->recursoModel->create($data);
            }
            
            $_SESSION['success'] = 'Contenido creado exitosamente';
            $this->redirect('/curso/index/' . $id_asignacion);
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear contenido: ' . $e->getMessage();
            $this->redirect('/curso/createContent/' . $id_asignacion);
        }
    }
    /**
     * Delete Module
     */
    public function deleteModulo($id) {
        $this->requireRole('PROFESOR');
        
        $modulo = $this->moduloModel->find($id);
        if ($modulo) {
            // Soft delete or hard delete? Let's do hard delete for now as per schema cascade
            // But schema says ON DELETE CASCADE, so deleting module deletes topics?
            // Wait, schema says: FOREIGN KEY (id_modulo) REFERENCES modulos(id_modulo) ON DELETE CASCADE
            // So deleting module deletes topics.
            // And topics? FOREIGN KEY (id_tema) REFERENCES temas(id_tema) ON DELETE SET NULL
            // So activities become orphaned (id_tema = NULL).
            
            $this->moduloModel->delete($id);
            $_SESSION['success'] = 'Módulo eliminado';
            $this->redirect('/curso/index/' . $modulo['id_asignacion']);
        } else {
            $this->redirect('/profesor/dashboard');
        }
    }

    /**
     * Delete Topic
     */
    public function deleteTema($id) {
        $this->requireRole('PROFESOR');
        
        $tema = $this->temaModel->find($id);
        if ($tema) {
            $modulo = $this->moduloModel->find($tema['id_modulo']);
            $this->temaModel->delete($id);
            $_SESSION['success'] = 'Tema eliminado';
            $this->redirect('/curso/index/' . $modulo['id_asignacion']);
        } else {
            $this->redirect('/profesor/dashboard');
        }
    }

    /**
     * Delete Content (Activity or Resource)
     */
    public function deleteContent($type, $id) {
        $this->requireRole('PROFESOR');
        
        $id_asignacion = null;

        if ($type === 'activity') {
            $item = $this->actividadModel->find($id);
            if ($item) {
                $id_asignacion = $item['id_asignacion'];
                $this->actividadModel->delete($id);
            }
        } elseif ($type === 'resource') {
            $item = $this->recursoModel->find($id);
            if ($item) {
                $id_asignacion = $item['id_asignacion'];
                $this->recursoModel->delete($id);
            }
        }

        if ($id_asignacion) {
            $_SESSION['success'] = 'Contenido eliminado';
            $this->redirect('/curso/index/' . $id_asignacion);
        } else {
            $this->redirect('/profesor/dashboard');
        }
    }

    /**
     * Edit Topic (View)
     */
    public function editTema($id) {
        $this->requireRole('PROFESOR');
        
        $tema = $this->temaModel->find($id);
        if (!$tema) {
            $this->redirect('/profesor/dashboard');
            return;
        }
        
        // We can reuse a simple view or just use a modal in index. 
        // For now, let's assume we use a modal in index, but if we needed a separate page:
        // $this->view('profesor/curso/edit_tema', ['tema' => $tema]);
        
        // Actually, let's implement the update action directly assuming the modal sends POST to updateTema
    }

    /**
     * Update Topic
     */
    public function updateTema() {
        $this->requireRole('PROFESOR');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_tema'];
            $tema = $this->temaModel->find($id);
            
            if ($tema) {
                $data = [
                    'titulo' => $_POST['titulo'],
                    'descripcion' => $_POST['descripcion'] ?? ''
                ];
                $this->temaModel->update($id, $data);
                
                $modulo = $this->moduloModel->find($tema['id_modulo']);
                $_SESSION['success'] = 'Tema actualizado';
                $this->redirect('/curso/index/' . $modulo['id_asignacion']);
            }
        }
    }
    /**
     * Store Announcement
     */
    public function storeAnuncio() {
        $this->requireRole('PROFESOR');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $profesor = $this->profesorModel->getByUserId($_SESSION['user_id']);
            
            $data = [
                'id_asignacion' => $_POST['id_asignacion'],
                'id_profesor' => $profesor['id_profesor'],
                'mensaje' => $_POST['mensaje'],
                'fecha_publicacion' => date('Y-m-d H:i:s')
            ];
            
            $this->anuncioModel->insert($data);
            $_SESSION['success'] = 'Anuncio publicado';
            $this->redirect('/curso/index/' . $_POST['id_asignacion']);
        }
    }

    /**
     * Update Course Theme
     */
    public function updateTheme() {
        $this->requireRole('PROFESOR');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_asignacion = $_POST['id_asignacion'];
            $banner_img = $_POST['banner_img'];
            
            $this->asignacionModel->update($id_asignacion, ['banner_img' => $banner_img]);
            $_SESSION['success'] = 'Tema actualizado';
            $this->redirect('/curso/index/' . $id_asignacion);
        }
    }

    /**
    * Edit Content (View)
    */
   public function editContent($type, $id) {
       $this->requireRole('PROFESOR');
       
       $content = null;
       $id_asignacion = null;
       $content_type = strtoupper($type) === 'ACTIVITY' ? 'ACTIVITY' : 'RESOURCE';

       if ($content_type === 'ACTIVITY') {
           $content = $this->actividadModel->findById($id);
       } else {
           $content = $this->recursoModel->find($id);
       }

       if (!$content) {
           $_SESSION['error'] = 'Contenido no encontrado';
           $this->redirect('/profesor/dashboard');
           return;
       }

       $id_asignacion = $content['id_asignacion'];
       $asignacion = $this->asignacionModel->getWithDetails($id_asignacion);
       $modulos = $this->moduloModel->getCourseStructure($id_asignacion);

       $this->view('profesor/curso/edit_content', [
           'title' => 'Editar Contenido',
           'asignacion' => $asignacion,
           'modulos' => $modulos,
           'content' => $content,
           'content_type' => $content_type
       ]);
   }

   /**
    * Update Content
    */
   public function updateContent() {
       $this->requireRole('PROFESOR');
       
       if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
       
       $type = $_POST['content_type']; // 'ACTIVITY' or 'RESOURCE'
       $id_content = $_POST['id_content'];
       $id_asignacion = $_POST['id_asignacion'];
       
       try {
           if ($type === 'ACTIVITY') {
               $data = [
                   'id_tema' => $_POST['id_tema'] ?: null,
                   'titulo' => $_POST['titulo'],
                   'descripcion' => $_POST['descripcion'],
                   'tipo' => $_POST['tipo_actividad'],
                   'fecha_limite' => !empty($_POST['fecha_limite']) ? $_POST['fecha_limite'] : null,
                   'puntos_max' => $_POST['puntos_max'] ?? 100,
                   'link_video' => $_POST['link_video'] ?? null
               ];
               
               $this->actividadModel->update($id_content, $data);
               
           } elseif ($type === 'RESOURCE') {
               $data = [
                   'id_tema' => $_POST['id_tema'] ?: null,
                   'titulo' => $_POST['titulo'],
                   'descripcion' => $_POST['descripcion'],
                   'tipo' => $_POST['tipo_recurso'],
                   'url' => $_POST['url'] ?? null
               ];
               
               $this->recursoModel->update($id_content, $data);
           }
           
           $_SESSION['success'] = 'Contenido actualizado exitosamente';
           $this->redirect('/curso/index/' . $id_asignacion);
           
       } catch (Exception $e) {
           $_SESSION['error'] = 'Error al actualizar contenido: ' . $e->getMessage();
           $this->redirect('/curso/editContent/' . ($type === 'ACTIVITY' ? 'activity' : 'resource') . '/' . $id_content);
       }
   }
}
