<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<!-- Hero Section -->
<section class="hero" style="position: relative; height: 600px; display: flex; align-items: center; justify-content: center; text-align: center; color: var(--white); background: url('https://images.unsplash.com/photo-1596462502278-27bfdd403cc2?auto=format&fit=crop&q=80&w=1600') no-repeat center center/cover;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4);"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <h1 style="font-size: 3.5rem; margin-bottom: 20px; font-weight: 700;">Radiate Confidence</h1>
        <p style="font-size: 1.2rem; margin-bottom: 30px;">Discover the beauty within with our premium collection.</p>
        <a href="#catalog" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem;">Shop Now</a>
    </div>
</section>

<!-- Catalog Section -->
<section id="catalog" class="container" style="padding: 60px 20px;">
    <div style="text-align: center; margin-bottom: 50px;">
        <h2>Our Collection</h2>
        <div style="width: 60px; height: 3px; background: var(--accent-color); margin: 10px auto;"></div>
    </div>

    <!-- Search & Filter -->
    <div style="margin-bottom: 40px;">
        <form method="GET" action="<?php echo BASE_URL; ?>/" style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center;">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo e($data['search'] ?? ''); ?>" style="flex: 1; min-width: 250px; max-width: 400px; padding: 12px 20px; border: 1px solid #ddd; border-radius: 25px; font-size: 1rem;">
            
            <select name="category" style="padding: 12px 20px; border: 1px solid #ddd; border-radius: 25px;">
                <option value="">All Categories</option>
                <?php if(isset($data['categories'])): ?>
                    <?php foreach($data['categories'] as $cat): ?>
                        <option value="<?php echo $cat->id; ?>" <?php echo (isset($data['selected_category']) && $data['selected_category'] == $cat->id) ? 'selected' : ''; ?>>
                            <?php echo e($cat->name); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>

    <div class="row" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;">
        <?php if(empty($data['products'])): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                <i class="fas fa-search" style="font-size: 4rem; color: #ddd; margin-bottom: 20px; display: block;"></i>
                <h3>No products found</h3>
                <p style="color: #999;">Try adjusting your search or filter</p>
            </div>
        <?php else: ?>
            <?php foreach($data['products'] as $product) : ?>
                <div class="card product-card" style="position: relative;">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo BASE_URL; ?>/wishlist/toggle/<?php echo $product->id; ?>" style="position: absolute; top: 10px; right: 10px; background: white; color: #e74c3c; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); z-index: 1;">
                            <i class="fas fa-heart"></i>
                        </a>
                    <?php endif; ?>
                    
                    <div class="product-img" style="height: 250px; overflow: hidden; border-radius: 10px; margin-bottom: 15px;">
                        <img src="<?php echo $product->image; ?>" alt="<?php echo e($product->name); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;">
                    </div>
                    <div class="product-info">
                        <h3 style="font-size: 1.2rem;"><?php echo e($product->name); ?></h3>
                        
                        <?php if(isset($product->average_rating) && $product->average_rating > 0): ?>
                            <div style="color: #f39c12; margin: 5px 0; font-size: 0.9rem;">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star<?php echo $i <= $product->average_rating ? '' : '-o'; ?>"></i>
                                <?php endfor; ?>
                                <span style="color: #666; margin-left: 5px;">(<?php echo $product->review_count; ?>)</span>
                            </div>
                        <?php endif; ?>
                        
                        <p style="color: var(--accent-color); font-weight: 600;"><?php echo format_rupiah($product->price); ?></p>
                        <div style="display: flex; gap: 10px; margin-top: 15px;">
                            <a href="<?php echo BASE_URL; ?>/home/product/<?php echo $product->id; ?>" class="btn btn-outline" style="flex: 1; text-align: center; padding: 8px 0; font-size: 0.9rem;">View</a>
                            <a href="<?php echo BASE_URL; ?>/cart/add/<?php echo $product->id; ?>" class="btn btn-primary" style="flex: 1; text-align: center; padding: 8px 0; font-size: 0.9rem;"><i class="fas fa-cart-plus"></i> Add</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Features Section -->
<section style="background: var(--white); padding: 60px 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; text-align: center;">
            <div>
                <i class="fas fa-shipping-fast" style="font-size: 3rem; color: var(--accent-color); margin-bottom: 15px;"></i>
                <h3>Free Shipping</h3>
                <p style="color: #666;">On all orders</p>
            </div>
            <div>
                <i class="fas fa-shield-alt" style="font-size: 3rem; color: var(--accent-color); margin-bottom: 15px;"></i>
                <h3>100% Original</h3>
                <p style="color: #666;">Authentic products</p>
            </div>
            <div>
                <i class="fas fa-headset" style="font-size: 3rem; color: var(--accent-color); margin-bottom: 15px;"></i>
                <h3>24/7 Support</h3>
                <p style="color: #666;">Always here to help</p>
            </div>
        </div>
    </div>
</section>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
