<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 60px 20px; text-align: center;">
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="font-size: 4rem; color: #27ae60; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1 style="color: var(--accent-color); margin-bottom: 15px;">Order Confirmed!</h1>
        <p style="font-size: 1.1rem; color: #666; margin-bottom: 30px;">
            Thank you for your order. We've received your order and will process it shortly.
        </p>

        <div class="card" style="text-align: left; margin-bottom: 30px;">
            <h3 style="margin-bottom: 20px; border-bottom: 2px solid var(--accent-color); padding-bottom: 10px;">
                Order Details
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                <div>
                    <strong>Order Number:</strong><br>
                    <span style="font-size: 1.2rem; color: var(--accent-color);">#<?php echo $data['order']->id; ?></span>
                </div>
                <div>
                    <strong>Order Date:</strong><br>
                    <?php echo date('d M Y, H:i', strtotime($data['order']->created_at)); ?>
                </div>
                <div>
                    <strong>Status:</strong><br>
                    <span style="padding: 4px 12px; background: #fff3cd; color: #856404; border-radius: 20px; font-size: 0.9rem;">
                        <?php echo ucfirst($data['order']->status); ?>
                    </span>
                </div>
                <div>
                    <strong>Total Amount:</strong><br>
                    <span style="font-size: 1.2rem; font-weight: 700; color: var(--accent-color);">
                        <?php echo format_rupiah($data['order']->total_amount); ?>
                    </span>
                </div>
            </div>

            <h4 style="margin: 20px 0 15px;">Items Ordered:</h4>
            <?php foreach($data['order']->items as $item): ?>
                <div style="display: flex; align-items: center; gap: 15px; padding: 10px; border-bottom: 1px solid #eee;">
                    <img src="<?php echo $item->product_image; ?>" alt="<?php echo e($item->product_name); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                    <div style="flex: 1;">
                        <strong><?php echo e($item->product_name); ?></strong><br>
                        <span style="font-size: 0.9rem; color: #666;">Qty: <?php echo $item->quantity; ?> × <?php echo format_rupiah($item->price); ?></span>
                    </div>
                    <div style="font-weight: 600;">
                        <?php echo format_rupiah($item->price * $item->quantity); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="display: flex; gap: 15px; justify-content: center;">
            <a href="<?php echo BASE_URL; ?>/user/order/<?php echo $data['order']->id; ?>" class="btn btn-outline">
                <i class="fas fa-file-alt"></i> View Order Details
            </a>
            <a href="<?php echo BASE_URL; ?>/" class="btn btn-primary">
                <i class="fas fa-home"></i> Continue Shopping
            </a>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
