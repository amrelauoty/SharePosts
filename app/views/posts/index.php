<?php require APPROOT . '/views/inc/header.php';?>
<div class="row mb-3">
    <div class="col-md-6">
        <h1>Posts</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT;?>/posts/add" class="btn btn-primary float-right">
            <i class="fas fa-pencil-alt">Add Post</i>
        </a>
    </div>

</div>
<?php flash('added_success');?>
<?php $i = 0;
    foreach ($data['posts'] as $post) : ?>
        <div class="card card-body mb-3">
        <h4 class="card-title"><?php echo $post->title?></h4>
        <div class="bg-light p-2 mb-3">written by <?php echo $post->name;?> on <?php echo $post->post_created;?></div>
        <div class="card-text mb-3"><?php echo $post->body?></div>
        <a href="<?php echo URLROOT;?>/posts/show/<?php echo $post->postId;?>" class="btn btn-dark">More</a>
        </div>
    <?php $i++;?>
<?php endforeach;?>
<?php if($i==0):?>
    <h4 class="bg-light p-3 mb-3 text-center" style="border-radius:25px;border:3px dashed #f2f2f2">Write your first post</h4>
<?php endif;?>
<?php require APPROOT . '/views/inc/footer.php';?>