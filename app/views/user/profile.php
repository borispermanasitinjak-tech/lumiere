<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    <h2 style="margin-bottom: 30px;">My Profile</h2>

    <?php if(isset($_SESSION['flash_success'])): ?>
        <div style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 20px; color: #155724;">
            <?php echo e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['flash_error'])): ?>
        <div style="background: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; margin-bottom: 20px; color: #721c24;">
            <?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <!-- Profile Information -->
        <div class="card">
            <h3 style="margin-bottom: 20px;">Profile Information</h3>
            <form action="<?php echo BASE_URL; ?>/user/update_profile" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Username</label>
                    <input type="text" name="username" value="<?php echo e($data['username']); ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                    <?php if(!empty($data['username_err'])): ?>
                        <small style="color: #e74c3c;"><?php echo $data['username_err']; ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Email</label>
                    <input type="email" name="email" value="<?php echo e($data['email']); ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                    <?php if(!empty($data['email_err'])): ?>
                        <small style="color: #e74c3c;"><?php echo $data['email_err']; ?></small>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Update Profile</button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="card">
            <h3 style="margin-bottom: 20px;">Change Password</h3>
            <form action="<?php echo BASE_URL; ?>/user/change_password" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Current Password</label>
                    <input type="password" name="current_password" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">New Password</label>
                    <input type="password" name="new_password" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Change Password</button>
            </form>
        </div>
    </div>

    <!-- Quick Links -->
    <div style="margin-top: 30px; display: flex; gap: 15px;">
        <a href="<?php echo BASE_URL; ?>/user/addresses" class="btn btn-outline">
            <i class="fas fa-map-marker-alt"></i> Manage Addresses
        </a>
        <a href="<?php echo BASE_URL; ?>/user/dashboard" class="btn btn-outline">
            <i class="fas fa-shopping-bag"></i> My Orders
        </a>
        <a href="<?php echo BASE_URL; ?>/wishlist" class="btn btn-outline">
            <i class="fas fa-heart"></i> My Wishlist
        </a>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
