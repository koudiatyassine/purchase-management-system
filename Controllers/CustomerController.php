
<?php
class CustomerController {
    private $customerManagement;

    public function __construct($customerManagement) {
        $this->customerManagement = $customerManagement;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_user'])) {
                $this->customerManagement->addUser($_POST['username'], $_POST['email'], $_POST['phone'], $_POST['password']);
            } elseif (isset($_POST['delete_user'])) {
                $this->customerManagement->deleteUser($_POST['user_id']);
            } elseif (isset($_POST['delete_selected'])) {
                if (isset($_POST['user_ids'])) {
                    foreach ($_POST['user_ids'] as $userId) {
                        $this->customerManagement->deleteUser($userId);
                    }
                }
            } elseif (isset($_POST['edit_user'])) {
                $this->customerManagement->updateUser($_POST['edit_user_id'], $_POST['username'], $_POST['email'], $_POST['phone']);
            }
        }
        return $this->customerManagement->getUsers();
    }
}


?>

