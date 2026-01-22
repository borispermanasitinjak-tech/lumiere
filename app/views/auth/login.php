<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-box glass">
        <div class="auth-header">
            <h2>Welcome Back</h2>
            <p>Login to your account</p>
        </div>
        <form action="<?php echo BASE_URL; ?>/auth/login" method="post">
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
            <div class="row">
                <div class="col">
                    <input type="submit" value="Login" class="btn btn-primary" style="width: 100%;">
                </div>
            </div>
            <div style="margin-top: 15px; text-align: center;">
                <p>No account? <a href="<?php echo BASE_URL; ?>/auth/register" style="color: var(--accent-color);">Register</a></p>
            </div>
        </form>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
