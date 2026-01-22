<?php
class UserController extends Controller {
    private $orderModel;

    public function __construct() {
        // Require authentication
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }

        // Prevent admin from accessing user dashboard
        if ($_SESSION['user_role'] === 'admin') {
            $this->redirect('admin/dashboard');
        }

        $this->orderModel = $this->model('Order');
        $this->userModel = $this->model('User');
        $this->addressModel = $this->model('ShippingAddress');
    }

    /**
     * User dashboard - Order history
     */
    public function dashboard() {
        $orders = $this->orderModel->getUserOrders($_SESSION['user_id']);

        $data = [
            'orders' => $orders
        ];

        $this->view('user/dashboard', $data);
    }

    /**
     * View order detail
     */
    public function order($id) {
        $order = $this->orderModel->getOrderById($id);

        // Verify order belongs to current user
        if (!$order || $order->user_id != $_SESSION['user_id']) {
            $_SESSION['flash_error'] = 'Order not found';
            $this->redirect('user/dashboard');
            return;
        }

        $data = [
            'order' => $order
        ];

        $this->view('user/order_detail', $data);
    }

    /**
     * User profile page
     */
    public function profile() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        $data = [
            'user' => $user,
            'username' => $user->username ?? '',
            'email' => $user->email ?? '',
            'username_err' => '',
            'email_err' => ''
        ];

        $this->view('user/profile', $data);
    }

    /**
     * Update profile
     */
    public function update_profile() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->redirect('user/profile');
            return;
        }

        // Verify CSRF
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['flash_error'] = 'Invalid request';
            $this->redirect('user/profile');
            return;
        }

        $data = [
            'id' => $_SESSION['user_id'],
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'username_err' => '',
            'email_err' => ''
        ];

        // Validate
        if (empty($data['username'])) {
            $data['username_err'] = 'Please enter username';
        }

        if (empty($data['email'])) {
            $data['email_err'] = 'Please enter email';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $data['email_err'] = 'Please enter valid email';
        }

        if (empty($data['username_err']) && empty($data['email_err'])) {
            if ($this->userModel->updateProfile($data)) {
                $_SESSION['user_name'] = $data['username'];
                $_SESSION['user_email'] = $data['email'];
                $_SESSION['flash_success'] = 'Profile updated successfully';
                $this->redirect('user/profile');
            } else {
                $_SESSION['flash_error'] = 'Failed to update profile';
                $this->redirect('user/profile');
            }
        } else {
            $data['user'] = $this->userModel->getUserById($_SESSION['user_id']);
            $this->view('user/profile', $data);
        }
    }

    /**
     * Change password page
     */
    public function change_password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verify CSRF
            if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
                $_SESSION['flash_error'] = 'Invalid request';
                $this->redirect('user/profile');
                return;
            }

            $current_password = trim($_POST['current_password'] ?? '');
            $new_password = trim($_POST['new_password'] ?? '');
            $confirm_password = trim($_POST['confirm_password'] ?? '');

            $errors = [];

            // Validate
            if (empty($current_password)) {
                $errors[] = 'Please enter current password';
            } elseif (!$this->userModel->verifyPassword($_SESSION['user_id'], $current_password)) {
                $errors[] = 'Current password is incorrect';
            }

            if (empty($new_password)) {
                $errors[] = 'Please enter new password';
            } elseif (strlen($new_password) < 6) {
                $errors[] = 'Password must be at least 6 characters';
            }

            if ($new_password !== $confirm_password) {
                $errors[] = 'Passwords do not match';
            }

            if (empty($errors)) {
                if ($this->userModel->updatePassword($_SESSION['user_id'], $new_password)) {
                    $_SESSION['flash_success'] = 'Password changed successfully';
                    $this->redirect('user/profile');
                } else {
                    $_SESSION['flash_error'] = 'Failed to change password';
                    $this->redirect('user/profile');
                }
            } else {
                $_SESSION['flash_error'] = implode('<br>', $errors);
                $this->redirect('user/profile');
            }
        }
    }

    /**
     * List shipping addresses
     */
    public function addresses() {
        $addresses = $this->addressModel->getUserAddresses($_SESSION['user_id']);

        $data = [
            'addresses' => $addresses
        ];

        $this->view('user/addresses', $data);
    }

    /**
     * Add address
     */
    public function add_address() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verify CSRF
            if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
                $_SESSION['flash_error'] = 'Invalid request';
                $this->redirect('user/addresses');
                return;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'recipient_name' => trim($_POST['recipient_name'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address_line1' => trim($_POST['address_line1'] ?? ''),
                'address_line2' => trim($_POST['address_line2'] ?? ''),
                'city' => trim($_POST['city'] ?? ''),
                'province' => trim($_POST['province'] ?? ''),
                'postal_code' => trim($_POST['postal_code'] ?? ''),
                'is_default' => isset($_POST['is_default']) ? 1 : 0
            ];

            if ($this->addressModel->addAddress($data)) {
                $_SESSION['flash_success'] = 'Address added successfully';
                $this->redirect('user/addresses');
            } else {
                $_SESSION['flash_error'] = 'Failed to add address';
                $this->view('user/add_address', $data);
            }
        } else {
            $this->view('user/add_address', []);
        }
    }

    /**
     * Edit address
     */
    public function edit_address($id) {
        $address = $this->addressModel->getAddressById($id);

        // Verify ownership
        if (!$address || $address->user_id != $_SESSION['user_id']) {
            $_SESSION['flash_error'] = 'Address not found';
            $this->redirect('user/addresses');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $id,
                'user_id' => $_SESSION['user_id'],
                'recipient_name' => trim($_POST['recipient_name'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address_line1' => trim($_POST['address_line1'] ?? ''),
                'address_line2' => trim($_POST['address_line2'] ?? ''),
                'city' => trim($_POST['city'] ?? ''),
                'province' => trim($_POST['province'] ?? ''),
                'postal_code' => trim($_POST['postal_code'] ?? ''),
                'is_default' => isset($_POST['is_default']) ? 1 : 0
            ];

            if ($this->addressModel->updateAddress($data)) {
                $_SESSION['flash_success'] = 'Address updated successfully';
                $this->redirect('user/addresses');
            } else {
                $_SESSION['flash_error'] = 'Failed to update address';
                $this->view('user/edit_address', $data);
            }
        } else {
            $data = [
                'address' => $address
            ];
            $this->view('user/edit_address', $data);
        }
    }

    /**
     * Delete address
     */
    public function delete_address($id) {
        $address = $this->addressModel->getAddressById($id);

        // Verify ownership
        if (!$address || $address->user_id != $_SESSION['user_id']) {
            $_SESSION['flash_error'] = 'Address not found';
            $this->redirect('user/addresses');
            return;
        }

        if ($this->addressModel->deleteAddress($id)) {
            $_SESSION['flash_success'] = 'Address deleted successfully';
        } else {
            $_SESSION['flash_error'] = 'Failed to delete address';
        }

        $this->redirect('user/addresses');
    }

    /**
     * Set default address
     */
    public function set_default_address($id) {
        $address = $this->addressModel->getAddressById($id);

        // Verify ownership
        if (!$address || $address->user_id != $_SESSION['user_id']) {
            $_SESSION['flash_error'] = 'Address not found';
            $this->redirect('user/addresses');
            return;
        }

        if ($this->addressModel->setDefault($id, $_SESSION['user_id'])) {
            $_SESSION['flash_success'] = 'Default address updated';
        } else {
            $_SESSION['flash_error'] = 'Failed to update default address';
        }

        $this->redirect('user/addresses');
    }
}
