<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn btn-outline" style="margin-bottom: 20px;">&larr; Back</a>
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <h2>Add New Product</h2>
        <form action="<?php echo BASE_URL; ?>/admin/add_product" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name: <sup>*</sup></label>
                <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
            </div>
            <div class="form-group">
                <label>Category:</label>
                <select name="category_id" class="form-control <?php echo (!empty($data['category_err'])) ? 'is-invalid' : ''; ?>">
                    <option value="" disabled selected>Select Category</option>
                    <?php foreach($data['categories'] as $category) : ?>
                        <option value="<?php echo $category->id; ?>" <?php echo ($data['category_id'] == $category->id) ? 'selected' : ''; ?>>
                            <?php echo $category->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="invalid-feedback"><?php echo $data['category_err']; ?></span>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" class="form-control"><?php echo $data['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Price: <sup>*</sup></label>
                <input type="text" name="price" class="form-control <?php echo (!empty($data['price_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['price']; ?>">
                <span class="invalid-feedback"><?php echo $data['price_err']; ?></span>
            </div>
            <div class="form-group">
                <label>Stock:</label>
                <input type="number" name="stock" class="form-control" value="<?php echo $data['stock']; ?>">
            </div>
            <div class="form-group">
                <label>Product Image:</label>
                <input type="file" name="image" class="form-control <?php echo (!empty($data['image_err'])) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $data['image_err']; ?></span>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
        </form>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
