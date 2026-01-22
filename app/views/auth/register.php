<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-box glass">
        <div class="auth-header">
            <h2>Create Account</h2>
            <p>Join the beauty community</p>
        </div>
        <form action="<?php echo BASE_URL; ?>/auth/register" method="post">
            <div class="form-group">
                <label for="username">Username: <sup>*</sup></label>
                <input type="text" name="username" class="form-control <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['username']; ?>">
                <span class="invalid-feedback"><?php echo $data['username_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email: <sup>*</sup></label>
                <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password: <sup>*</sup></label>
                <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password: <sup>*</sup></label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
                <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
            </div>
            
            <div class="row">
                <div class="col">
                    <input type="submit" value="Register" class="btn btn-primary" style="width: 100%;">
                </div>
            </div>
            <div style="margin-top: 15px; text-align: center;">
                <p>Already have an account? <a href="<?php echo BASE_URL; ?>/auth/login" style="color: var(--accent-color);">Login</a></p>
            </div>
        </form>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
