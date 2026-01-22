<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    <h2 style="margin-bottom: 30px;">My Orders</h2>

    <?php if(isset($_SESSION['flash_success'])): ?>
        <div style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 20px; color: #155724;">
            <?php echo e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--background-color); color: var(--accent-color);">
                <tr>
                    <th style="padding: 15px; text-align: left;">Order #</th>
                    <th style="padding: 15px; text-align: left;">Date</th>
                    <th style="padding: 15px; text-align: right;">Total</th>
                    <th style="padding: 15px; text-align: center;">Status</th>
                    <th style="padding: 15px; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['orders'])): ?>
                    <tr>
                        <td colspan="5" style="padding: 60px; text-align: center; color: #999;">
                            <i class="fas fa-shopping-bag" style="font-size: 4rem; margin-bottom: 15px; display: block;"></i>
                            <h3>No orders yet</h3>
                            <p>Start shopping to see your orders here!</p>
                            <a href="<?php echo BASE_URL; ?>/" class="btn btn-primary" style="margin-top: 15px;">
                                <i class="fas fa-shopping-cart"></i> Start Shopping
                            </a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['orders'] as $order): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px; font-weight: 600; color: var(--accent-color);">
                                #<?php echo $order->id; ?>
                            </td>
                            <td style="padding: 15px;">
                                <?php echo date('d M Y', strtotime($order->created_at)); ?><br>
                                <small style="color: #666;"><?php echo date('H:i', strtotime($order->created_at)); ?></small>
                            </td>
                            <td style="padding: 15px; text-align: right; font-weight: 600; color: var(--accent-color);">
                                <?php echo format_rupiah($order->total_amount); ?>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <?php 
                                    $status_colors = [
                                        'pending' => 'background: #fff3cd; color: #856404;',
                                        'completed' => 'background: #d4edda; color: #155724;',
                                        'cancelled' => 'background: #f8d7da; color: #721c24;'
                                    ];
                                    $color = $status_colors[$order->status] ?? 'background: #e2e3e5; color: #383d41;';
                                ?>
                                <span style="padding: 5px 15px; border-radius: 20px; font-size: 0.85rem; <?php echo $color; ?>">
                                    <?php echo ucfirst($order->status); ?>
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="<?php echo BASE_URL; ?>/user/order/<?php echo $order->id; ?>" class="btn btn-outline" style="padding: 5px 15px; font-size: 0.85rem;">
                                    <i class="fas fa-eye"></i> View Details
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
