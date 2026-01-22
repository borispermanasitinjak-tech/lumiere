<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 60px 20px;">
    <h2 style="margin-bottom: 30px;">Admin Dashboard</h2>

    <div style="display: flex; justify-content: space-between; margin-bottom: 30px; gap: 15px; flex-wrap: wrap;">
        <a href="<?php echo BASE_URL; ?>/admin/categories" class="btn btn-outline" style="padding: 12px 25px;"><i class="fas fa-th"></i> Manage Categories</a>
        <a href="<?php echo BASE_URL; ?>/admin/add_product" class="btn btn-primary" style="padding: 12px 25px;"><i class="fas fa-plus"></i> Add New Product</a>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--background-color);">
                <tr>
                    <th style="padding: 15px; text-align: left;">ID</th>
                    <th style="padding: 15px; text-align: left;">Image</th>
                    <th style="padding: 15px; text-align: left;">Name</th>
                    <th style="padding: 15px; text-align: left;">Price</th>
                    <th style="padding: 15px; text-align: left;">Stock</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['products'] as $product) : ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px;"><?php echo $product->id; ?></td>
                        <td style="padding: 15px;"><img src="<?php echo $product->image; ?>" width="50" style="border-radius: 5px;"></td>
                        <td style="padding: 15px; font-weight: 500;"><?php echo $product->name; ?></td>
                        <td style="padding: 15px;"><?php echo format_rupiah($product->price); ?></td>
                        <td style="padding: 15px;"><?php echo $product->stock; ?></td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="display: flex; gap: 10px; justify-content: center;">
                                <a href="<?php echo BASE_URL; ?>/admin/edit_product/<?php echo $product->id; ?>" class="btn btn-outline" style="padding: 8px 18px; font-size: 0.9rem;">Edit</a>
                                <form action="<?php echo BASE_URL; ?>/admin/delete_product/<?php echo $product->id; ?>" method="post" style="display: inline;" onsubmit="return confirm('Delete this product?')">
                                    <button type="submit" class="btn btn-outline" style="padding: 8px 18px; font-size: 0.9rem; color: #e74c3c; border-color: #e74c3c;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
