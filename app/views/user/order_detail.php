<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    <a href="<?php echo BASE_URL; ?>/user/dashboard" class="btn btn-outline" style="margin-bottom: 20px;">
        <i class="fas fa-arrow-left"></i> Back to My Orders
    </a>

    <h2 style="margin-bottom: 30px;">Order #<?php echo $data['order']->id; ?></h2>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Order Items -->
        <div>
            <div class="card">
                <h3 style="margin-bottom: 20px; border-bottom: 2px solid var(--accent-color); padding-bottom: 10px;">
                    Order Items
                </h3>
                
                <?php foreach($data['order']->items as $item): ?>
                    <div style="display: flex; align-items: center; gap: 15px; padding: 15px; border-bottom: 1px solid #eee;">
                        <img src="<?php echo $item->product_image; ?>" alt="<?php echo e($item->product_name); ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                        <div style="flex: 1;">
                            <h4 style="margin-bottom: 5px;"><?php echo e($item->product_name); ?></h4>
                            <div style="color: #666; font-size: 0.9rem;">
                                Quantity: <strong><?php echo $item->quantity; ?></strong> × <?php echo format_rupiah($item->price); ?>
                            </div>
                        </div>
                        <div style="font-size: 1.1rem; font-weight: 600; color: var(--accent-color);">
                            <?php echo format_rupiah($item->price * $item->quantity); ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div style="display: flex; justify-content: space-between; padding: 20px 15px 0; font-size: 1.3rem; font-weight: 700; color: var(--accent-color);">
                    <span>Total:</span>
                    <span><?php echo format_rupiah($data['order']->total_amount); ?></span>
                </div>
            </div>
        </div>

        <!-- Order Info -->
        <div>
            <div class="card">
                <h3 style="margin-bottom: 15px;">Order Information</h3>
                
                <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                    <strong style="display: block; margin-bottom: 5px; color: #666;">Order Number:</strong>
                    <span style="font-size: 1.2rem; font-weight: 700;">#<?php echo $data['order']->id; ?></span>
                </div>

                <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                    <strong style="display: block; margin-bottom: 5px; color: #666;">Order Date:</strong>
                    <?php echo date('d F Y', strtotime($data['order']->created_at)); ?><br>
                    <small style="color: #999;"><?php echo date('H:i', strtotime($data['order']->created_at)); ?> WIB</small>
                </div>

                <div style="margin-bottom: 15px;">
                    <strong style="display: block; margin-bottom: 8px; color: #666;">Order Status:</strong>
                    <?php 
                        $status_info = [
                            'pending' => [
                                'color' => 'background: #fff3cd; color: #856404;',
                                'icon' => 'fa-clock',
                                'text' => 'Your order is being processed'
                            ],
                            'completed' => [
                                'color' => 'background: #d4edda; color: #155724;',
                                'icon' => 'fa-check-circle',
                                'text' => 'Order completed successfully'
                            ],
                            'cancelled' => [
                                'color' => 'background: #f8d7da; color: #721c24;',
                                'icon' => 'fa-times-circle',
                                'text' => 'Order has been cancelled'
                            ]
                        ];
                        $status = $status_info[$data['order']->status] ?? $status_info['pending'];
                    ?>
                    <div style="padding: 15px; border-radius: 10px; <?php echo $status['color']; ?>">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <i class="fas <?php echo $status['icon']; ?>" style="font-size: 1.5rem;"></i>
                            <span style="font-weight: 700; font-size: 1.1rem;"><?php echo ucfirst($data['order']->status); ?></span>
                        </div>
                        <small style="opacity: 0.9;"><?php echo $status['text']; ?></small>
                    </div>
                </div>
            </div>

            <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 10px;">
                <p style="font-size: 0.9rem; color: #666; margin-bottom: 10px;">
                    <i class="fas fa-info-circle"></i> Need help with your order?
                </p>
                <p style="font-size: 0.85rem; color: #999;">
                    Contact our customer service for assistance.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
