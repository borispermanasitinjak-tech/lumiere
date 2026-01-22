<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container" style="padding: 40px 20px;">
    <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn btn-outline" style="margin-bottom: 20px;">&larr; Back</a>
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <h2>Add New Category</h2>
        <form action="<?php echo BASE_URL; ?>/admin/add_category" method="post">
            <div class="form-group">
                <label>Category Name: <sup>*</sup></label>
                <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" class="form-control"><?php echo $data['description']; ?></textarea>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
        </form>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
