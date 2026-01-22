<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Promo Codes</h2>
        <a href="<?php echo BASE_URL; ?>/admin/add_promo" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Promo Code
        </a>
    </div>

    <?php if(isset($_SESSION['flash_success'])): ?>
        <div style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 20px;">
            <?php echo e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--background-color);">
                <tr>
                    <th style="padding: 15px; text-align: left;">Code</th>
                    <th style="padding: 15px; text-align: left;">Discount</th>
                    <th style="padding: 15px; text-align: center;">Usage</th>
                    <th style="padding: 15px; text-align: center;">Valid Until</th>
                    <th style="padding: 15px; text-align: center;">Status</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['codes'])): ?>
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #999;">No promo codes yet</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['codes'] as $promo): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px;">
                                <strong style="font-size: 1.1rem; color: var(--accent-color);"><?php echo e($promo->code); ?></strong><br>
                                <small style="color: #666;"><?php echo e($promo->description); ?></small>
                            </td>
                            <td style="padding: 15px;">
                                <?php if($promo->discount_type == 'percentage'): ?>
                                    <strong><?php echo $promo->discount_value; ?>%</strong>
                                <?php else: ?>
                                    <strong><?php echo format_rupiah($promo->discount_value); ?></strong>
                                <?php endif; ?>
                                <br>
                                <small style="color: #666;">Min: <?php echo format_rupiah($promo->min_purchase); ?></small>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <?php echo $promo->used_count; ?> / <?php echo $promo->usage_limit ?? '∞'; ?>
                            </td>
                            <td style="padding: 15px; text-align: center; font-size: 0.9rem;">
                                <?php echo date('d M Y', strtotime($promo->valid_until)); ?>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <?php if($promo->is_active && strtotime($promo->valid_until) > time()): ?>
                                    <span style="padding: 5px 15px; background: #d4edda; color: #155724; border-radius: 20px; font-size: 0.85rem;">Active</span>
                                <?php else: ?>
                                    <span style="padding: 5px 15px; background: #f8d7da; color: #721c24; border-radius: 20px; font-size: 0.85rem;">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="<?php echo BASE_URL; ?>/admin/edit_promo/<?php echo $promo->id; ?>" class="btn btn-outline" style="padding: 5px 10px; font-size: 0.85rem; margin-right: 5px;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?php echo BASE_URL; ?>/admin/delete_promo/<?php echo $promo->id; ?>" 
                                   onclick="return confirm('Delete this promo code?')" 
                                   class="btn btn-outline" style="padding: 5px 10px; font-size: 0.85rem; color: #e74c3c; border-color: #e74c3c;">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
