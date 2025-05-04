<!-- resources/views/goods/index.blade.php -->


<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Products Catalog</h1>
    
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    
    <div class="row">
        <?php $__currentLoopData = $goods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $good): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="<?php echo e(asset('storage/' . $good->photo_path)); ?>" class="card-img-top" alt="<?php echo e($good->name); ?>">
                    <div class="card-body">
                        <span class="badge bg-primary mb-2"><?php echo e($good->category->name); ?></span>
                        <h5 class="card-title"><?php echo e($good->name); ?></h5>
                        <p class="card-text">Rp. <?php echo e(number_format($good->price, 0, ',', '.')); ?></p>
                        
                        <?php if($good->quantity > 0): ?>
                            <form action="<?php echo e(route('cart.add')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?php echo e($good->id); ?>">
                                <div class="input-group mb-3">
                                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?php echo e($good->quantity); ?>">
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                </div>
                                <small class="text-muted"><?php echo e($good->quantity); ?> items available</small>
                            </form>
                        <?php else: ?>
                            <p class="text-danger">The item is out of stock, please wait until the item is restocked.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fearg\Documents\Uni\BNCC\chipi3\inventory-app\resources\views/goods/index.blade.php ENDPATH**/ ?>