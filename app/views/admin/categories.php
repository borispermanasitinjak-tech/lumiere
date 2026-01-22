<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Manage Categories</h2>
        <div>
             <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn btn-outline" style="margin-right: 10px;">&larr; Back to Dashboard</a>
            <a href="<?php echo BASE_URL; ?>/admin/add_category" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Category</a>
        </div>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--background-color); color: var(--accent-color);">
                <tr>
                    <th style="padding: 15px; text-align: left;">ID</th>
                    <th style="padding: 15px; text-align: left;">Name</th>
                    <th style="padding: 15px; text-align: left;">Description</th>
                    <th style="padding: 15px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['categories'] as $category) : ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px;"><?php echo $category->id; ?></td>
                        <td style="padding: 15px; font-weight: 500;"><?php echo $category->name; ?></td>
                        <td style="padding: 15px;"><?php echo $category->description; ?></td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="<?php echo BASE_URL; ?>/admin/edit_category/<?php echo $category->id; ?>" class="btn btn-outline" style="padding: 5px 15px; font-size: 0.8rem;">Edit</a>
                            <form action="<?php echo BASE_URL; ?>/admin/delete_category/<?php echo $category->id; ?>" method="post" style="display: inline;" onsubmit="return confirm('Are you sure? This will delete ALL products in this category!');">
                                <button type="submit" class="btn btn-primary" style="padding: 5px 15px; font-size: 0.8rem; background: #e74c3c;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
