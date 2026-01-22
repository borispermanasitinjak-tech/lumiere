<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 60px 20px;">
    <h2>Shopping Cart</h2>
    
    <?php if(empty($data['products'])): ?>
        <p>Your cart is empty. <a href="<?php echo BASE_URL; ?>">Go Shopping</a></p>
    <?php else: ?>
        <form id="cart-form" action="<?php echo BASE_URL; ?>/cart/update" method="post">
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="border-bottom: 2px solid #eee;">
                        <th style="text-align: left; padding: 10px;">Product</th>
                        <th style="text-align: center; padding: 10px;">Price</th>
                        <th style="text-align: center; padding: 10px;">Quantity</th>
                        <th style="text-align: right; padding: 10px;">Subtotal</th>
                        <th style="padding: 10px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['products'] as $product): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px;">
                                <div style="display: flex; align-items: center;">
                                    <img src="<?php echo $product->image; ?>" width="50" style="margin-right: 15px; border-radius: 5px;">
                                    <?php echo e($product->name); ?>
                                </div>
                            </td>
                            <td style="text-align: center;"><?php echo format_rupiah($product->price); ?></td>
                            <td style="text-align: center;">
                                <input type="number" name="qty[<?php echo $product->id; ?>]" value="<?php echo $product->qty; ?>" min="1" style="width: 60px; padding: 5px; text-align: center;">
                            </td>
                            <td style="text-align: right;"><?php echo format_rupiah($product->price * $product->qty); ?></td>
                            <td style="text-align: center;">
                                <a href="<?php echo BASE_URL; ?>/cart/remove/<?php echo $product->id; ?>" class="btn btn-outline" style="padding: 5px 10px; color: #e74c3c; border-color: #e74c3c;">&times;</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right; padding: 20px; font-weight: bold;">Total:</td>
                        <td style="text-align: right; padding: 20px; font-weight: bold; font-size: 1.2rem;"><?php echo format_rupiah($data['total']); ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            
        </form>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; gap: 20px; flex-wrap: wrap;">
            <a href="<?php echo BASE_URL; ?>/" class="btn btn-outline" style="padding: 12px 25px; font-size: 1rem;">← Continue Shopping</a>
            <div style="display: flex; gap: 15px;">
                <!-- We need a form for Update Cart button, so we can keep it inside a small form or use JS.
                     But actually, the previous structure had one big form for the table.
                     The logic requires the form to wrap the table to submit 'qty'.
                     The 'Update Cart' button submits the form.
                     The 'Checkout' button is just a link.
                -->
                <button type="submit" form="cart-form" class="btn btn-outline" style="padding: 12px 25px; font-size: 1rem;">Update Cart</button>
                <a href="<?php echo BASE_URL; ?>/checkout" class="btn btn-primary" style="padding: 14px 35px; font-size: 1.05rem; font-weight: 600;">Checkout →</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
