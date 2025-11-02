<?php
require_once '../models/AdminLoginProcessModel.php';

class AdminLoginProcessController {
    private $model;
    private $error_message;
    private $semaphore_file;

    public function __construct() {
        $this->model = new AdminLoginProcessModel();
    }

    public function processLogin($email, $password) {
        try {
            if (empty($email) || empty($password)) {
                throw new Exception("Email et mot de passe sont requis !");
            }

            $admin = $this->model->findAdminByEmail($email);

            if ($admin) {
                if (hash('sha256', $password) === $admin['password']) {
                    $this->setSemaphoreFile($admin['role']);
                    if ($this->acquireSemaphore($email, $admin['role'])) {
                        $this->initializeSession($admin);
                        $this->redirectUserByRole($admin['role']);
                    } else {
                        throw new Exception("Un autre utilisateur utilise actuellement cette page.");
                    }
                } else {
                    throw new Exception("Email ou mot de passe invalide !");
                }
            } else {
                throw new Exception("Email ou mot de passe invalide !");
            }
        } catch (Exception $e) {
            $this->error_message = $e->getMessage();
            $this->showErrorView();
        }
    }

    private function setSemaphoreFile($role) {
        $semaphoreFiles = [
            'super_admin' => '../sessions/page_semaphore_super_admin.lock',
            'customer_manager' => '../sessions/page_semaphore_customer_manager.lock',
            'product_manager' => '../sessions/page_semaphore_product_manager.lock',
            'invoice_manager' => '../sessions/page_semaphore_invoice_manager.lock',
        ];

        if (isset($semaphoreFiles[$role])) {
            $this->semaphore_file = $semaphoreFiles[$role];
        } else {
            throw new Exception("Rôle inconnu ou non défini.");
        }
    }

    private function initializeSession($admin) {
        session_start();
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_role'] = $admin['role'];
        $_SESSION['admin_logged_in'] = true;
    }

    private function redirectUserByRole($role) {
        $dashboardMap = [
            'super_admin' => '../Views/admin_dashboard.php',
            'customer_manager' => '../Views/customer_dashboard.php',
            'product_manager' => '../Views/product_dashboard.php',
            'invoice_manager' => '../Views/invoice_dashboard.php',
        ];

        if (isset($dashboardMap[$role])) {
            header('Location: ' . $dashboardMap[$role]);
            exit();
        } else {
            throw new Exception("Rôle inconnu ou non défini.");
        }
    }

    public function logout() {
        session_start();

        if (isset($_SESSION['admin_role'])) {
            $this->setSemaphoreFile($_SESSION['admin_role']);
            $this->clearSemaphoreFile();
        }

        session_unset();
        session_destroy();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        header('Location: ../Views/admin_login.php');
        exit();
    }

    private function showErrorView() {
        $error_message = $this->error_message;
        require '../views/admin_login_error_view.php';
    }

    private function acquireSemaphore($email, $role) {
        if (!file_exists($this->semaphore_file)) {
            $this->initializeSemaphoreFile();
        }

        $semaphoreData = json_decode(file_get_contents($this->semaphore_file), true);

        if ($semaphoreData['locked'] === true) {
            if ($semaphoreData['role'] === $role && $semaphoreData['user'] === $email) {
                return true; // Allow re-entry for the same user and role
            }
            return false;
        }

        $semaphoreData['locked'] = true;
        $semaphoreData['user'] = $email;
        $semaphoreData['role'] = $role;
        $semaphoreData['timestamp'] = time();

        file_put_contents($this->semaphore_file, json_encode($semaphoreData, JSON_PRETTY_PRINT));
        return true;
    }

    private function initializeSemaphoreFile() {
        $defaultSemaphore = [
            'locked' => false,
            'user' => null,
            'role' => null,
            'timestamp' => null,
        ];
        if (file_put_contents($this->semaphore_file, json_encode($defaultSemaphore, JSON_PRETTY_PRINT)) === false) {
            throw new Exception("Erreur lors de l'initialisation du fichier de sémaphore.");
        }
    }

    private function clearSemaphoreFile() {
        if (file_exists($this->semaphore_file)) {
            try {
                $semaphoreData = [
                    'locked' => false,
                    'user' => null,
                    'role' => null,
                    'timestamp' => null,
                ];

                file_put_contents($this->semaphore_file, json_encode($semaphoreData, JSON_PRETTY_PRINT));
            } catch (Exception $e) {
                error_log("Erreur lors de la réinitialisation du fichier de sémaphore : " . $e->getMessage());
            }
        }
    }
}
?>
