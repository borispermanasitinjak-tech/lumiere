<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px; max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 30px;">Add New Address</h2>

    <div class="card">
        <form action="<?php echo BASE_URL; ?>/user/add_address" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Recipient Name *</label>
                <input type="text" name="recipient_name" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Phone Number *</label>
                <input type="tel" name="phone" class="form-control" placeholder="08123456789" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Address Line 1 *</label>
                <input type="text" name="address_line1" class="form-control" placeholder="Street address, building, unit" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Address Line 2</label>
                <input type="text" name="address_line2" class="form-control" placeholder="Apartment, suite, etc. (optional)" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">City *</label>
                    <input type="text" name="city" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Province *</label>
                    <input type="text" name="province" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Postal Code *</label>
                <input type="text" name="postal_code" class="form-control" placeholder="12345" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="checkbox" name="is_default" value="1" style="margin-right: 10px;">
                    <span>Set as default address</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Save Address</button>
                <a href="<?php echo BASE_URL; ?>/user/addresses" class="btn btn-outline" style="flex: 1;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
