<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    <h2 style="margin-bottom: 30px;"><i class="fas fa-exclamation-triangle" style="color: #f39c12;"></i> Low Stock Alert</h2>

    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--background-color);">
                <tr>
                    <th style="padding: 15px; text-align: left;">Product</th>
                    <th style="padding: 15px; text-align: center;">Current Stock</th>
                    <th style="padding: 15px; text-align: center;">Status</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['products'])): ?>
                    <tr>
                        <td colspan="4" style="padding: 40px; text-align: center; color: #999;">
                            <i class="fas fa-check-circle" style="font-size: 3rem; color: #27ae60; display: block; margin-bottom: 10px;"></i>
                            All products have sufficient stock!
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['products'] as $product): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <img src="<?php echo $product->image; ?>" alt="<?php echo e($product->name); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    <div>
                                        <strong><?php echo e($product->name); ?></strong><br>
                                        <small style="color: #666;"><?php echo format_rupiah($product->price); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 15px; text-align: center; font-size: 1.2rem; font-weight: 700;">
                                <?php 
                                    $color = $product->stock <= 5 ? '#e74c3c' : ($product->stock <= 10 ? '#f39c12' : '#000');
                                ?>
                                <span style="color: <?php echo $color; ?>"><?php echo $product->stock; ?></span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <?php if($product->stock <= 5): ?>
                                    <span style="padding: 5px 15px; background: #f8d7da; color: #721c24; border-radius: 20px; font-size: 0.85rem;">
                                        Critical
                                    </span>
                                <?php else: ?>
                                    <span style="padding: 5px 15px; background: #fff3cd; color: #856404; border-radius: 20px; font-size: 0.85rem;">
                                        Low
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="<?php echo BASE_URL; ?>/admin/edit_product/<?php echo $product->id; ?>" class="btn btn-primary" style="padding: 5px 15px; font-size: 0.85rem;">
                                    <i class="fas fa-edit"></i> Restock
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
