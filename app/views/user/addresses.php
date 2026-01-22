<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>My Addresses</h2>
        <a href="<?php echo BASE_URL; ?>/user/add_address" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Address
        </a>
    </div>

    <?php if(isset($_SESSION['flash_success'])): ?>
        <div style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 20px; color: #155724;">
            <?php echo e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <?php if(empty($data['addresses'])): ?>
        <div class="card" style="text-align: center; padding: 60px 20px;">
            <i class="fas fa-map-marker-alt" style="font-size: 4rem; color: #ddd; margin-bottom: 20px;"></i>
            <h3>No Addresses Yet</h3>
            <p style="color: #999; margin-bottom: 20px;">Add a shipping address to complete your orders</p>
            <a href="<?php echo BASE_URL; ?>/user/add_address" class="btn btn-primary">Add Your First Address</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
            <?php foreach($data['addresses'] as $address): ?>
                <div class="card" style="position: relative;">
                    <?php if($address->is_default): ?>
                        <div style="position: absolute; top: 10px; right: 10px; background: #27ae60; color: white; padding: 3px 10px; border-radius: 15px; font-size: 0.75rem;">
                            DEFAULT
                        </div>
                    <?php endif; ?>
                    
                    <h4 style="margin-bottom: 10px;"><?php echo e($address->recipient_name); ?></h4>
                    <p style="margin-bottom: 5px;"><i class="fas fa-phone"></i> <?php echo e($address->phone); ?></p>
                    <p style="color: #666; line-height: 1.6;">
                        <?php echo e($address->address_line1); ?><br>
                        <?php if($address->address_line2): ?>
                            <?php echo e($address->address_line2); ?><br>
                        <?php endif; ?>
                        <?php echo e($address->city); ?>, <?php echo e($address->province); ?><br>
                        <?php echo e($address->postal_code); ?>
                    </p>

                    <div style="display: flex; gap: 10px; margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                        <?php if(!$address->is_default): ?>
                            <a href="<?php echo BASE_URL; ?>/user/set_default_address/<?php echo $address->id; ?>" class="btn btn-outline" style="flex: 1; font-size: 0.85rem;">
                                Set Default
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo BASE_URL; ?>/user/edit_address/<?php echo $address->id; ?>" class="btn btn-outline" style="flex: 1; font-size: 0.85rem;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?php echo BASE_URL; ?>/user/delete_address/<?php echo $address->id; ?>" 
                           onclick="return confirm('Delete this address?')" 
                           class="btn btn-outline" style="flex: 1; font-size: 0.85rem; color: #e74c3c; border-color: #e74c3c;">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
