<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    <h2 style="margin-bottom: 30px;">Checkout</h2>

    <?php if(isset($_SESSION['flash_error'])): ?>
        <div style="background: #fee; border-left: 4px solid #e74c3c; padding: 15px; margin-bottom: 20px;">
            <?php echo e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>/checkout/process" method="POST">
        <?php echo csrf_field(); ?>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            <!-- Left Column: Address & Payment -->
            <div>
                <!-- Shipping Address -->
                <div class="card" style="margin-bottom: 20px;">
                    <h3 style="margin-bottom: 15px;">Shipping Address</h3>
                    
                    <?php if(empty($data['addresses'])): ?>
                        <p style="color: #999;">No shipping address found. <a href="<?php echo BASE_URL; ?>/user/add_address">Add address</a></p>
                    <?php else: ?>
                        <?php foreach($data['addresses'] as $address): ?>
                            <label style="display: block; padding: 15px; border: 2px solid #eee; border-radius: 8px; margin-bottom: 10px; cursor: pointer;">
                                <input type="radio" name="shipping_address_id" value="<?php echo $address->id; ?>" 
                                       <?php echo ($address->is_default) ? 'checked' : ''; ?> required>
                                <strong><?php echo e($address->recipient_name); ?></strong> - <?php echo e($address->phone); ?><br>
                                <small><?php echo e($address->address_line1); ?>, <?php echo e($address->city); ?>, <?php echo e($address->province); ?> <?php echo e($address->postal_code); ?></small>
                            </label>
                        <?php endforeach; ?>
                        <a href="<?php echo BASE_URL; ?>/user/add_address" class="btn btn-outline" style="margin-top: 10px;">+ Add New Address</a>
                    <?php endif; ?>
                </div>

                <!-- Payment Method -->
                <div class="card" style="margin-bottom: 20px;">
                    <h3 style="margin-bottom: 15px;">Payment Method</h3>
                    
                    <?php if(empty($data['payment_methods'])): ?>
                        <p style="color: #999;">No payment methods available</p>
                    <?php else: ?>
                        <?php foreach($data['payment_methods'] as $index => $method): ?>
                            <label style="display: block; padding: 15px; border: 2px solid #eee; border-radius: 8px; margin-bottom: 10px; cursor: pointer;">
                                <input type="radio" name="payment_method_id" value="<?php echo $method->id; ?>" 
                                       <?php echo ($index === 0) ? 'checked' : ''; ?> required>
                                <strong><?php echo e($method->name); ?></strong><br>
                                <?php if($method->instructions): ?>
                                    <small style="color: #666;"><?php echo e($method->instructions); ?></small>
                                <?php endif; ?>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Order Summary -->
                <div class="card">
                    <h3 style="margin-bottom: 20px;">Order Items</h3>
                    
                    <?php foreach($data['products'] as $product): ?>
                        <div style="display: flex; align-items: center; gap: 15px; padding: 10px; border-bottom: 1px solid #eee;">
                            <img src="<?php echo $product->image; ?>" alt="<?php echo e($product->name); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                            <div style="flex: 1;">
                                <strong><?php echo e($product->name); ?></strong><br>
                                <small style="color: #666;">Qty: <?php echo $product->qty; ?> × <?php echo format_rupiah($product->price); ?></small>
                            </div>
                            <div style="font-weight: 600;">
                                <?php echo format_rupiah($product->subtotal); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right Column: Order Total -->
            <div>
                <div class="card" style="position: sticky; top: 100px;">
                    <h3 style="margin-bottom: 20px;">Order Summary</h3>
                    
                    <div style="padding: 15px 0; border-bottom: 1px solid #eee;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Subtotal:</span>
                            <span><?php echo format_rupiah($data['subtotal']); ?></span>
                        </div>
                        
                        <?php if($data['discount'] > 0): ?>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: #27ae60;">
                                <span>Discount:</span>
                                <span>-<?php echo format_rupiah($data['discount']); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Shipping:</span>
                            <span style="color: #27ae60;">FREE</span>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; padding: 15px 0; font-size: 1.2rem; font-weight: 700; color: var(--accent-color);">
                        <span>Total:</span>
                        <span><?php echo format_rupiah($data['total']); ?></span>
                    </div>

                    <!-- Promo Code -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Promo Code</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" id="promo_code" placeholder="Enter code" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            <button type="button" onclick="applyPromo()" class="btn btn-outline">Apply</button>
                        </div>
                        <div id="promo_message" style="margin-top: 10px; font-size: 0.9rem;"></div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem;">
                        <i class="fas fa-check-circle"></i> Place Order
                    </button>

                    <a href="<?php echo BASE_URL; ?>/cart" class="btn btn-outline" style="width: 100%; text-align: center; margin-top: 10px; display: block; padding: 10px;">
                        <i class="fas fa-arrow-left"></i> Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function applyPromo() {
    const code = document.getElementById('promo_code').value;
    const total = <?php echo $data['subtotal']; ?>;
    
    if(!code) {
        document.getElementById('promo_message').innerHTML = '<span style="color: #e74c3c;">Please enter a promo code</span>';
        return;
    }
    
    // AJAX request to apply promo
    fetch('<?php echo BASE_URL; ?>/checkout/apply_promo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'code=' + encodeURIComponent(code) + '&total=' + total
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            document.getElementById('promo_message').innerHTML = '<span style="color: #27ae60;">✓ Promo applied! Discount: ' + data.discount_formatted + '</span>';
            // Reload page to apply discount
            setTimeout(() => location.reload(), 1000);
        } else {
            document.getElementById('promo_message').innerHTML = '<span style="color: #e74c3c;">✗ ' + data.message + '</span>';
        }
    });
}
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
