<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px; max-width: 700px; margin: 0 auto;">
    <h2 style="margin-bottom: 30px;">Edit Payment Method</h2>

    <div class="card">
        <form action="<?php echo BASE_URL; ?>/admin/edit_payment_method/<?php echo $data['method']->id; ?>" method="POST">
            <div class="form-group" style="margin-bottom: 15px;">
                <label>Method Name *</label>
                <input type="text" name="name" value="<?php echo e($data['method']->name); ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Type *</label>
                <select name="type" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                    <option value="bank_transfer" <?php echo $data['method']->type == 'bank_transfer' ? 'selected' : ''; ?>>Bank Transfer</option>
                    <option value="e_wallet" <?php echo $data['method']->type == 'e_wallet' ? 'selected' : ''; ?>>E-Wallet</option>
                    <option value="cash_on_delivery" <?php echo $data['method']->type == 'cash_on_delivery' ? 'selected' : ''; ?>>Cash on Delivery (COD)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Account Name</label>
                <input type="text" name="account_name" value="<?php echo e($data['method']->account_name); ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Account Number</label>
                <input type="text" name="account_number" value="<?php echo e($data['method']->account_number); ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Instructions</label>
                <textarea name="instructions" rows="3" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"><?php echo e($data['method']->instructions); ?></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="is_active" value="1" <?php echo $data['method']->is_active ? 'checked' : ''; ?> style="margin-right: 10px;">
                    <span>Active</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Update</button>
                <a href="<?php echo BASE_URL; ?>/admin/payment_methods" class="btn btn-outline" style="flex: 1;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
