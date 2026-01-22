<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px; max-width: 700px; margin: 0 auto;">
    <h2 style="margin-bottom: 30px;">Edit Promo Code</h2>

    <div class="card">
        <form action="<?php echo BASE_URL; ?>/admin/edit_promo/<?php echo $data['promo']->id; ?>" method="POST">
            <div class="form-group" style="margin-bottom: 15px;">
                <label>Promo Code *</label>
                <input type="text" name="code" value="<?php echo e($data['promo']->code); ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; text-transform: uppercase;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Description</label>
                <input type="text" name="description" value="<?php echo e($data['promo']->description); ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label>Discount Type *</label>
                    <select name="discount_type" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                        <option value="percentage" <?php echo $data['promo']->discount_type == 'percentage' ? 'selected' : ''; ?>>Percentage (%)</option>
                        <option value="fixed" <?php echo $data['promo']->discount_type == 'fixed' ? 'selected' : ''; ?>>Fixed Amount (Rp)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Discount Value *</label>
                    <input type="number" name="discount_value" step="0.01" value="<?php echo $data['promo']->discount_value; ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label>Minimum Purchase</label>
                    <input type="number" name="min_purchase" step="0.01" value="<?php echo $data['promo']->min_purchase; ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>

                <div class="form-group">
                    <label>Max Discount</label>
                    <input type="number" name="max_discount" step="0.01" value="<?php echo $data['promo']->max_discount; ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label>Usage Limit</label>
                <input type="number" name="usage_limit" value="<?php echo $data['promo']->usage_limit; ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <small style="color: #666;">Used: <?php echo $data['promo']->used_count; ?> times</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label>Valid From *</label>
                    <input type="datetime-local" name="valid_from" value="<?php echo date('Y-m-d\TH:i', strtotime($data['promo']->valid_from)); ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                </div>

                <div class="form-group">
                    <label>Valid Until *</label>
                    <input type="datetime-local" name="valid_until" value="<?php echo date('Y-m-d\TH:i', strtotime($data['promo']->valid_until)); ?>" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="is_active" value="1" <?php echo $data['promo']->is_active ? 'checked' : ''; ?> style="margin-right: 10px;">
                    <span>Active</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Update</button>
                <a href="<?php echo BASE_URL; ?>/admin/promo_codes" class="btn btn-outline" style="flex: 1;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
