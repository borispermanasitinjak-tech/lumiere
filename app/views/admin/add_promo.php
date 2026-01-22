<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px; max-width: 700px; margin: 0 auto;">
    <h2 style="margin-bottom: 30px;">Add Promo Code</h2>

    <div class="card">
        <form action="<?php echo BASE_URL; ?>/admin/add_promo" method="POST">
            <div class="form-group" style="margin-bottom: 15px;">
                <label>Promo Code *</label>
                <input type="text" name="code" placeholder="e.g., WELCOME10" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; text-transform: uppercase;" required>
                <small style="color: #666;">Will be converted to uppercase</small>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Description</label>
                <input type="text" name="description" placeholder="Brief description" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label>Discount Type *</label>
                    <select name="discount_type" id="discount_type" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount (Rp)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Discount Value *</label>
                    <input type="number" name="discount_value" step="0.01" placeholder="10" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label>Minimum Purchase</label>
                    <input type="number" name="min_purchase" step="0.01" placeholder="0" value="0" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>

                <div class="form-group">
                    <label>Max Discount (optional)</label>
                    <input type="number" name="max_discount" step="0.01" placeholder="No limit" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Usage Limit (optional)</label>
                <input type="number" name="usage_limit" placeholder="Unlimited" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label>Valid From *</label>
                    <input type="datetime-local" name="valid_from" value="<?php echo date('Y-m-d\TH:i'); ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                </div>

                <div class="form-group">
                    <label>Valid Until *</label>
                    <input type="datetime-local" name="valid_until" value="<?php echo date('Y-m-d\TH:i', strtotime('+30 days')); ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="is_active" value="1" checked style="margin-right: 10px;">
                    <span>Active</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Create Promo Code</button>
                <a href="<?php echo BASE_URL; ?>/admin/promo_codes" class="btn btn-outline" style="flex: 1;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
