<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    <h2 style="margin-bottom: 30px;"><i class="fas fa-heart" style="color: #e74c3c;"></i> My Wishlist</h2>

    <?php if(isset($_SESSION['flash_success'])): ?>
        <div style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 20px; color: #155724;">
            <?php echo e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <?php if(empty($data['wishlist'])): ?>
        <div class="card" style="text-align: center; padding: 60px 20px;">
            <i class="fas fa-heart-broken" style="font-size: 4rem; color: #ddd; margin-bottom: 20px;"></i>
            <h3>Your Wishlist is Empty</h3>
            <p style="color: #999; margin-bottom: 20px;">Save your favorite products here</p>
            <a href="<?php echo BASE_URL; ?>/" class="btn btn-primary">Start Shopping</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;">
            <?php foreach($data['wishlist'] as $item): ?>
                <div class="card" style="position: relative;">
                    <?php if($item->stock <= 0): ?>
                        <div style="position: absolute; top: 10px; left: 10px; background: #e74c3c; color: white; padding: 5px 10px; border-radius: 5px; font-size: 0.8rem; z-index: 1;">
                            Out of Stock
                        </div>
                    <?php endif; ?>

                    <a href="<?php echo BASE_URL; ?>/wishlist/remove/<?php echo $item->product_id; ?>" style="position: absolute; top: 10px; right: 10px; background: white; color: #e74c3c; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); z-index: 1;">
                        <i class="fas fa-times"></i>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/home/product/<?php echo $item->product_id; ?>">
                        <img src="<?php echo $item->image; ?>" alt="<?php echo e($item->name); ?>" style="width: 100%; height: 250px; object-fit: cover; border-radius: 10px; margin-bottom: 15px;">
                    </a>

                    <h3 style="font-size: 1.1rem; margin-bottom: 10px;">
                        <a href="<?php echo BASE_URL; ?>/home/product/<?php echo $item->product_id; ?>" style="color: inherit; text-decoration: none;">
                            <?php echo e($item->name); ?>
                        </a>
                    </h3>

                    <div style="margin-bottom: 10px;">
                        <?php if($item->average_rating > 0): ?>
                            <div style="color: #f39c12; font-size: 0.9rem;">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star<?php echo $i <= $item->average_rating ? '' : '-o'; ?>"></i>
                                <?php endfor; ?>
                                <span style="color: #666; margin-left: 5px;">(<?php echo $item->review_count; ?>)</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <p style="font-size: 1.3rem; font-weight: 700; color: var(--accent-color); margin-bottom: 15px;">
                        <?php echo format_rupiah($item->price); ?>
                    </p>

                    <?php if($item->stock > 0): ?>
                        <a href="<?php echo BASE_URL; ?>/cart/add/<?php echo $item->product_id; ?>" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </a>
                    <?php else: ?>
                        <button disabled class="btn btn-outline" style="width: 100%; opacity: 0.5; cursor: not-allowed;">
                            Out of Stock
                        </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
