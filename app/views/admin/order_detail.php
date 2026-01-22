<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    <a href="<?php echo BASE_URL; ?>/admin/orders" class="btn btn-outline" style="margin-bottom: 20px;">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>

    <h2 style="margin-bottom: 30px;">Order #<?php echo $data['order']->id; ?></h2>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Order Items -->
        <div>
            <div class="card" style="margin-bottom: 20px;">
                <h3 style="margin-bottom: 20px; border-bottom: 2px solid var(--accent-color); padding-bottom: 10px;">
                    Order Items
                </h3>
                
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: var(--background-color);">
                        <tr>
                            <th style="padding: 12px; text-align: left;">Product</th>
                            <th style="padding: 12px; text-align: center;">Quantity</th>
                            <th style="padding: 12px; text-align: right;">Price</th>
                            <th style="padding: 12px; text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['order']->items as $item): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px;">
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <img src="<?php echo $item->product_image; ?>" alt="<?php echo e($item->product_name); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        <strong><?php echo e($item->product_name); ?></strong>
                                    </div>
                                </td>
                                <td style="padding: 15px; text-align: center;"><?php echo $item->quantity; ?></td>
                                <td style="padding: 15px; text-align: right;"><?php echo format_rupiah($item->price); ?></td>
                                <td style="padding: 15px; text-align: right; font-weight: 600;">
                                    <?php echo format_rupiah($item->price * $item->quantity); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" style="padding: 15px; text-align: right; font-weight: 700;">Total:</td>
                            <td style="padding: 15px; text-align: right; font-size: 1.2rem; font-weight: 700; color: var(--accent-color);">
                                <?php echo format_rupiah($data['order']->total_amount); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Info & Status -->
        <div>
            <div class="card" style="margin-bottom: 20px;">
                <h3 style="margin-bottom: 15px;">Order Information</h3>
                
                <div style="margin-bottom: 15px;">
                    <strong style="display: block; margin-bottom: 5px;">Order Date:</strong>
                    <?php echo date('d F Y, H:i', strtotime($data['order']->created_at)); ?>
                </div>

                <div style="margin-bottom: 15px;">
                    <strong style="display: block; margin-bottom: 5px;">Current Status:</strong>
                    <?php 
                        $status_colors = [
                            'pending' => 'background: #fff3cd; color: #856404;',
                            'completed' => 'background: #d4edda; color: #155724;',
                            'cancelled' => 'background: #f8d7da; color: #721c24;'
                        ];
                        $color = $status_colors[$data['order']->status] ?? 'background: #e2e3e5; color: #383d41;';
                    ?>
                    <span style="padding: 8px 16px; border-radius: 20px; display: inline-block; <?php echo $color; ?>">
                        <?php echo ucfirst($data['order']->status); ?>
                    </span>
                </div>
            </div>

            <!-- Update Status Form -->
            <div class="card">
                <h3 style="margin-bottom: 15px;">Update Status</h3>
                
                <?php if(isset($_SESSION['flash_success'])): ?>
                    <div style="background: #d4edda; border-left: 4px solid #28a745; padding: 12px; margin-bottom: 15px; color: #155724;">
                        <?php echo e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['flash_error'])): ?>
                    <div style="background: #f8d7da; border-left: 4px solid #dc3545; padding: 12px; margin-bottom: 15px; color: #721c24;">
                        <?php echo e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?php echo BASE_URL; ?>/admin/update_order_status/<?php echo $data['order']->id; ?>" method="POST">
                    <div class="form-group">
                        <label for="status">Change Status:</label>
                        <select name="status" id="status" class="form-control">
                            <option value="pending" <?php echo $data['order']->status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="completed" <?php echo $data['order']->status == 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo $data['order']->status == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
