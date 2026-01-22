<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Order Management</h2>
    </div>

    <!-- Order Statistics -->
    <?php if(isset($data['stats'])): ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div style="font-size: 0.9rem; opacity: 0.9;">Total Orders</div>
            <div style="font-size: 2rem; font-weight: 700; margin: 10px 0;">
                <?php echo $data['stats']->total_orders ?? 0; ?>
            </div>
        </div>
        <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div style="font-size: 0.9rem; opacity: 0.9;">Pending</div>
            <div style="font-size: 2rem; font-weight: 700; margin: 10px 0;">
                <?php echo $data['stats']->pending_orders ?? 0; ?>
            </div>
        </div>
        <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div style="font-size: 0.9rem; opacity: 0.9;">Completed</div>
            <div style="font-size: 2rem; font-weight: 700; margin: 10px 0;">
                <?php echo $data['stats']->completed_orders ?? 0; ?>
            </div>
        </div>
        <div class="card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
            <div style="font-size: 0.9rem; opacity: 0.9;">Total Revenue</div>
            <div style="font-size: 1.5rem; font-weight: 700; margin: 10px 0;">
                <?php echo format_rupiah($data['stats']->total_revenue ?? 0); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Orders Table -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--background-color); color: var(--accent-color);">
                <tr>
                    <th style="padding: 15px; text-align: left;">Order ID</th>
                    <th style="padding: 15px; text-align: left;">Customer</th>
                    <th style="padding: 15px; text-align: left;">Date</th>
                    <th style="padding: 15px; text-align: right;">Total</th>
                    <th style="padding: 15px; text-align: center;">Status</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($data['orders'])): ?>
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #999;">
                            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 10px; display: block;"></i>
                            No orders yet
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data['orders'] as $order): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px; font-weight: 600;">#<?php echo $order->id; ?></td>
                            <td style="padding: 15px;">
                                <div>
                                    <strong><?php echo e($order->username); ?></strong><br>
                                    <small style="color: #666;"><?php echo e($order->email); ?></small>
                                </div>
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
                                <a href="<?php echo BASE_URL; ?>/admin/order_detail/<?php echo $order->id; ?>" class="btn btn-outline" style="padding: 5px 15px; font-size: 0.85rem;">
                                    <i class="fas fa-eye"></i> View
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
