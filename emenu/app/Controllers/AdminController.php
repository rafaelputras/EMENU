<?php
class AdminController extends Controller {
    
    public function index() {
        header('Location: ' . BASEURL . '/public/admin/categories');
        exit;
    }

    // =========================================================================
    // --- CATEGORY MODULE ---
    // =========================================================================
    public function categories() {
        $categoryModel = $this->model('Category');
        $data = [
            'title' => translate('category_mgmt'),
            'categories' => $categoryModel->getAllCategories()
        ];
        $this->view('admin/categories', $data);
    }

    public function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $categoryModel = $this->model('Category');
            if ($categoryModel->insertCategory($name)) {
                header('Location: ' . BASEURL . '/public/admin/categories');
                exit;
            } else {
                echo "<script>alert('" . translate('add_cat_failed') . "'); window.history.back();</script>";
            }
        } else {
            $data = ['title' => translate('add_new_category')];
            $this->view('admin/add_category', $data);
        }
    }

    public function hideCategory($id = null) {
        if ($id === null) {
            header('Location: ' . BASEURL . '/public/admin/categories');
            exit;
        }
        $categoryModel = $this->model('Category');
        $categoryModel->updateStatus($id, 0); 
        header('Location: ' . BASEURL . '/public/admin/categories');
        exit;
    }

    public function restoreCategory($id = null) {
        if ($id === null) {
            header('Location: ' . BASEURL . '/public/admin/categories');
            exit;
        }
        $categoryModel = $this->model('Category');
        $categoryModel->updateStatus($id, 1); 
        header('Location: ' . BASEURL . '/public/admin/categories');
        exit;
    }

    // Update Category
    public function updateCategory() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 1. Get data from the modal form
            $id = $_POST['id'];
            $name = $_POST['name'];

            // 2. Load Model
            $categoryModel = $this->model('Category');
            
            // 3. Execute Update
            if ($categoryModel->updateCategory($id, $name)) {
                // On success, redirect to categories page
                header('Location: ' . BASEURL . '/public/admin/categories');
                exit;
            } else {
                // On failure, show alert
                echo "<script>alert('" . translate('update_failed') . "'); window.history.back();</script>";
                exit;
            }
        }
    }

    // =========================================================================
    // --- MENU MODULE ---
    // =========================================================================
    public function menus() {
        $menuModel = $this->model('Menu');
        $data = [
            'title' => translate('menu_management'),
            'menus' => $menuModel->getAdminMenus()
        ];
        $this->view('admin/menus', $data);
    }

    public function menuForm() {
        $categoryModel = $this->model('Category');
        $variantModel = $this->model('Variant'); 
        $data = [
            'title' => translate('add_new_menu'),
            'categories' => $categoryModel->getAllCategories(),
            'variants' => $variantModel->getAllVariants() 
        ];
        $this->view('admin/menu_form', $data);
    }

    public function saveMenu() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $menuModel = $this->model('Menu');
            $dataMenu = [
                'id' => $id ?? null,
                'name' => $_POST['name'],
                'category_id' => $_POST['category_id'],
                'price' => $_POST['price'],
                'description' => $_POST['description'] ?? '',
                'image' => $imageName ?? null,
                
                // Promo fields
                'promo_price' => $_POST['promo_price'] ?? 0,
                'promo_start' => !empty($_POST['promo_start']) ? $_POST['promo_start'] : null,
                'promo_end' => !empty($_POST['promo_end']) ? $_POST['promo_end'] : null,
                'promo_quota' => $_POST['promo_quota'] ?? 0
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $originalName = $_FILES['image']['name'];
                $safeName = preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
                $imageName = time() . '_' . $safeName; 
                $dirPath = $_SERVER['DOCUMENT_ROOT'] . '/vietnam/emenu/public/assets/images/';
                if (!is_dir($dirPath)) {
                    mkdir($dirPath, 0777, true);
                }
                $targetDir = $dirPath . $imageName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetDir)) {
                    $dataMenu['image'] = $imageName;
                }
            }

            $menu_id = $menuModel->addMenu($dataMenu); 

            if ($menu_id && !empty($_POST['variant_ids'])) {
                $variant_ids = $_POST['variant_ids'];
                $menuModel->addMenuVariants($menu_id, $variant_ids);
            }

            header('Location: ' . BASEURL . '/public/admin/menus');
            exit;
        }
    }

    public function toggleMenuStatus($id = null) {
        if ($id === null) {
            header('Location: ' . BASEURL . '/public/admin/menus');
            exit;
        }
        
        $menuModel = $this->model('Menu');
        $menuModel->toggleStatus($id); 
        
        header('Location: ' . BASEURL . '/public/admin/menus');
        exit;
    }

    public function editMenu($id = null) {
        if ($id === null) {
            header('Location: ' . BASEURL . '/public/admin/menus');
            exit;
        }
        
        $menuModel = $this->model('Menu');
        $categoryModel = $this->model('Category');
        $variantModel = $this->model('Variant');
        
        $data = [
            'title' => translate('edit') . ' Menu',
            'menu' => $menuModel->getMenuById($id), 
            'categories' => $categoryModel->getAllCategories(),
            'variants' => $variantModel->getAllGroups(),
            'selected_variants' => $menuModel->getMenuVariantGroupIds($id)
        ];
        
        $this->view('admin/edit_menu', $data);
    }

    public function updateMenu() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $menuModel = $this->model('Menu');
            
            // 1. Get current menu data to preserve old image
            $currentMenu = $menuModel->getMenuById($id);
            $imageName = $currentMenu['image']; 

            // 2. Check if user uploaded a new image
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $originalName = $_FILES['image']['name'];
                $safeName = preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
                $imageName = time() . '_' . $safeName; 
                
                $dirPath = $_SERVER['DOCUMENT_ROOT'] . '/vietnam/emenu/public/assets/images/';
                if (!is_dir($dirPath)) {
                    mkdir($dirPath, 0777, true);
                }
                $targetDir = $dirPath . $imageName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetDir)) {
                    // Delete old image file if exists
                    if ($currentMenu['image'] && file_exists($dirPath . $currentMenu['image'])) {
                        unlink($dirPath . $currentMenu['image']);
                    }
                } else {
                    $imageName = $currentMenu['image']; 
                }
            }

            // 3. Prepare data for update
            $dataMenu = [
                'id' => $id, 
                'name' => $_POST['name'],
                'category_id' => $_POST['category_id'],
                'price' => $_POST['price'],
                'description' => $_POST['description'] ?? '',
                'image' => $imageName ?? null,
                'promo_price' => $_POST['promo_price'] ?? 0,
                'promo_start' => !empty($_POST['promo_start']) ? $_POST['promo_start'] : null,
                'promo_end' => !empty($_POST['promo_end']) ? $_POST['promo_end'] : null,
                'promo_quota' => $_POST['promo_quota'] ?? 0
            ];

            // 4. Execute update in database
            if ($menuModel->updateMenu($dataMenu)) {
                
                // Also save variant associations
                $variant_ids = isset($_POST['variant_ids']) ? $_POST['variant_ids'] : [];
                $menuModel->addMenuVariants($id, $variant_ids);

                header('Location: ' . BASEURL . '/public/admin/menus');
                exit;
            } else {
                echo "<script>alert('" . translate('update_menu_failed') . "'); window.history.back();</script>";
            }
        }
    }

    // =========================================================================
    // --- VARIANT GROUP MODULE ---
    // =========================================================================
    public function master_variants() {
        $variantModel = $this->model('Variant');
        $data = [
            'title' => translate('variant_group'),
            'groups' => $variantModel->getAllGroups()
        ];
        $this->view('admin/master_variants', $data);
    }

    public function saveVariantGroup() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $variantModel = $this->model('Variant');
            $variantModel->addGroup($_POST['name'], $_POST['type']);
            header('Location: ' . BASEURL . '/public/admin/master_variants');
            exit;
        }
    }

    // =========================================================================
    // --- VARIANT OPTIONS MODULE ---
    // =========================================================================
    public function variants($group_id = null) {
        if (!$group_id) {
            header('Location: ' . BASEURL . '/public/admin/master_variants');
            exit;
        }
        $variantModel = $this->model('Variant');
        
        $options = $variantModel->getOptionsByGroup($group_id);
        $groupData = $variantModel->getGroupById($group_id);

        $data = [
            'title' => translate('variant_options') . ' - ' . ($groupData['name'] ?? ''),
            'group_id' => $group_id,
            'group' => $groupData,
            'options' => $options
        ];
        $this->view('admin/variants', $data);
    }

    public function updateVariantGroup() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $group_id = $_POST['id'];
            $name = $_POST['name'];
            $type = $_POST['type'];
            
            $variantModel = $this->model('Variant');
            $variantModel->updateGroup($group_id, $name, $type);
            
            header('Location: ' . BASEURL . '/public/admin/variants/' . $group_id);
            exit;
        }
    }

    public function saveVariantOption() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $variantModel = $this->model('Variant');
            $group_id = $_POST['group_id'];
            $variantModel->addOption($group_id, $_POST['name'], $_POST['extra_price']);
            header('Location: ' . BASEURL . '/public/admin/variants/' . $group_id);
            exit;
        }
    }

    public function updateVariantOption() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $group_id = $_POST['group_id'];
            $name = $_POST['name'];
            $extra_price = $_POST['extra_price'];

            $variantModel = $this->model('Variant');
            $variantModel->updateOption($id, $name, $extra_price);

            header('Location: ' . BASEURL . '/public/admin/variants/' . $group_id);
            exit;
        }
    }

    public function deleteVariantOption($id = null, $group_id = null) {
        if ($id && $group_id) {
            $variantModel = $this->model('Variant');
            $variantModel->deleteOption($id);
        }
        header('Location: ' . BASEURL . '/public/admin/variants/' . $group_id);
        exit;
    }

    public function moveVariantOption($id = null, $direction = null, $group_id = null) {
        if ($id && $direction && $group_id) {
            $variantModel = $this->model('Variant');
            $variantModel->moveOptionOrder($id, $direction, $group_id);
        }
        header('Location: ' . BASEURL . '/public/admin/variants/' . $group_id);
        exit;
    }

    // Delete Category
    public function deleteCategory($id = null) {
        if ($id === null) {
            header('Location: ' . BASEURL . '/public/admin/categories');
            exit;
        }

        $categoryModel = $this->model('Category');
        
        if ($categoryModel->deleteCategory($id)) {
            header('Location: ' . BASEURL . '/public/admin/categories');
            exit;
        } else {
            // Category is still in use by menu items
            echo "<script>
                    alert('" . translate('delete_failed_cat') . "'); 
                    window.history.back();
                  </script>";
            exit;
        }
    }

    // Admin Analytics Dashboard
    public function dashboard() {
        $orderModel = $this->model('Order');
        
        // Check for date filter, default to last 7 days
        $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-6 days'));
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        $data = [
            'title' => translate('dashboard_title'),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_sales' => $orderModel->getTotalSales(),
            'favorite_menus' => $orderModel->getFavoriteMenus(),
            'favorite_variants' => $orderModel->getFavoriteVariants(),
            'revenue_data' => $orderModel->getRevenueByDate($startDate, $endDate)
        ];
        
        $this->view('admin/dashboard', $data);
    }
    
}
