<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 60px 20px;">
    <a href="<?php echo BASE_URL; ?>/" class="btn btn-outline" style="margin-bottom: 30px;">&larr; Back to Catalog</a>
    
    <div class="card" style="display: flex; flex-wrap: wrap; gap: 40px; padding: 40px;">
        <div style="flex: 1; min-width: 300px;">
            <img src="<?php echo $data['product']->image; ?>" alt="<?php echo e($data['product']->name); ?>" style="width: 100%; border-radius: 10px; box-shadow: var(--shadow);">
        </div>
        <div style="flex: 1; min-width: 300px; display: flex; flex-direction: column; justify-content: center;">
            <h1 style="margin-bottom: 10px;"><?php echo e($data['product']->name); ?></h1>
            
            <!-- Rating -->
            <?php if(isset($data['product']->average_rating) && $data['product']->average_rating > 0): ?>
                <div style="color: #f39c12; margin-bottom: 15px; font-size: 1.2rem;">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star<?php echo $i <= $data['product']->average_rating ? '' : '-o'; ?>"></i>
                    <?php endfor; ?>
                    <span style="color: #666; margin-left: 10px; font-size: 1rem;"><?php echo $data['product']->average_rating; ?> (<?php echo $data['product']->review_count; ?> reviews)</span>
                </div>
            <?php endif; ?>
            
            <h2 style="color: var(--accent-color); margin-bottom: 20px;"><?php echo format_rupiah($data['product']->price); ?></h2>
            <p style="margin-bottom: 30px; font-size: 1.1rem; color: #666;"><?php echo e($data['product']->description); ?></p>
            
            <form action="<?php echo BASE_URL; ?>/cart/add/<?php echo $data['product']->id; ?>" method="post" style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px;">
                <input type="number" name="quantity" value="1" min="1" max="<?php echo $data['product']->stock; ?>" style="width: 80px; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <button type="submit" class="btn btn-primary" <?php echo $data['product']->stock <= 0 ? 'disabled' : ''; ?>><i class="fas fa-cart-plus"></i> Add to Cart</button>
                
                <?php if(isset($_SESSION['user_id']) && isset($data['inWishlist'])): ?>
                    <a href="<?php echo BASE_URL; ?>/wishlist/toggle/<?php echo $data['product']->id; ?>" class="btn btn-outline" style="padding: 10px 20px;">
                        <i class="fas fa-heart" style="color: <?php echo $data['inWishlist'] ? '#e74c3c' : '#999'; ?>;"></i>
                    </a>
                <?php endif; ?>
            </form>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                <p><strong>Stock:</strong> <?php echo $data['product']->stock; ?> units available</p>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="card" style="margin-top: 40px;">
        <h2 style="margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid var(--accent-color);">Customer Reviews</h2>
        
        <?php if(isset($data['canReview']) && $data['canReview'] && isset($_SESSION['user_id'])): ?>
            <!-- Write Review Form -->
            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                <h3 style="margin-bottom: 15px;">Write a Review</h3>
                <form action="<?php echo BASE_URL; ?>/review/add/<?php echo $data['product']->id; ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Rating *</label>
                        <div style="font-size: 2rem; color: #ddd;">
                            <input type="radio" name="rating" value="1" id="star1" required style="display: none;">
                            <input type="radio" name="rating" value="2" id="star2" style="display: none;">
                            <input type="radio" name="rating" value="3" id="star3" style="display: none;">
                            <input type="radio" name="rating" value="4" id="star4" style="display: none;">
                            <input type="radio" name="rating" value="5" id="star5" checked style="display: none;">
                            <label for="star1" style="cursor: pointer;"><i class="fas fa-star"></i></label>
                            <label for="star2" style="cursor: pointer;"><i class="fas fa-star"></i></label>
                            <label for="star3" style="cursor: pointer;"><i class="fas fa-star"></i></label>
                            <label for="star4" style="cursor: pointer;"><i class="fas fa-star"></i></label>
                            <label for="star5" style="cursor: pointer;"><i class="fas fa-star"></i></label>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Your Review</label>
                        <textarea name="review_text" rows="4" placeholder="Share your experience with this product..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Display Reviews -->
        <?php if(empty($data['reviews'])): ?>
            <p style="text-align: center; padding: 40px; color: #999;">No reviews yet. Be the first to review this product!</p>
        <?php else: ?>
            <?php foreach($data['reviews'] as $review): ?>
                <div style="padding: 20px; border-bottom: 1px solid #eee;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                        <div>
                            <strong><?php echo e($review->username); ?></strong>
                            <div style="color: #f39c12; margin-top: 5px;">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star<?php echo $i <=$review->rating ? '' : '-o'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <small style="color: #999;"><?php echo date('d M Y', strtotime($review->created_at)); ?></small>
                    </div>
                    <?php if($review->review_text): ?>
                        <p style="color: #666; line-height: 1.6;"><?php echo e($review->review_text); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
