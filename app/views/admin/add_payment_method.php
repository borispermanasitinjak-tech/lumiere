<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px; max-width: 700px; margin: 0 auto;">
    <h2 style="margin-bottom: 30px;">Add Payment Method</h2>

    <div class="card">
        <form action="<?php echo BASE_URL; ?>/admin/add_payment_method" method="POST">
            <div class="form-group" style="margin-bottom: 15px;">
                <label>Method Name *</label>
                <input type="text" name="name" placeholder="e.g., BCA Transfer" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Type *</label>
                <select name="type" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="e_wallet">E-Wallet</option>
                    <option value="cash_on_delivery">Cash on Delivery (COD)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Account Name</label>
                <input type="text" name="account_name" placeholder="Account holder name" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Account Number</label>
                <input type="text" name="account_number" placeholder="Account/phone number" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Instructions</label>
                <textarea name="instructions" rows="3" placeholder="Payment instructions for customers" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="is_active" value="1" checked style="margin-right: 10px;">
                    <span>Active</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Save</button>
                <a href="<?php echo BASE_URL; ?>/admin/payment_methods" class="btn btn-outline" style="flex: 1;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
