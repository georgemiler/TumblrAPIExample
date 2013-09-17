<?php
$cakeDescription = __d('cake_dev', 'Via Studio Coding Challenge!');

error_reporting(!E_NOTICE && (E_ERROR | E_WARNING));

if(!is_array($posts)) {
    $posts = array();
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
            <?php echo $cakeDescription ?>:
            <?php echo $title_for_layout; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <?php
            echo $this->Html->meta('icon');

            echo $this->Html->css('cake.generic');

            echo $this->fetch('meta');
            echo $this->fetch('css');
            echo $this->fetch('script');
    ?>
    <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.3.0/respond.js"></script>
    <![endif]-->
    <script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3 class="text-muted"><?= $blog_name?></h3>
        </div>

        <div class="jumbotron">
            <div class="row">
                <div class="col-lg-5">
                    <?php
                        echo $this->Form->create(false, array('type'=> 'post'));
                        echo $this->Form->input('blog_name', array('class'=> 'form-control', "default"=> $blog_name));
                        echo $this->Form->submit('submit', array('class'=> 'btn btn-default'));
                        echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>

        <div class="row pager">
            <div class="col-lg-5">
                <?php
                    if(count($posts) > 0) {
                        echo $this->Paginator->prev(' << ' . __('previous'), array(), null, array('class' => 'prev disabled')) . '&nbsp;';
                        echo $this->Paginator->numbers() . '&nbsp;';
                        echo $this->Paginator->next(__('next') . ' >> ', array(), null, array('class' => 'next disabled')) . '&nbsp;';
                    }
                ?>
            </div>
        </div>

        <div class="row marketing">
            <div class="col-lg-12">
                <?php foreach($posts as $item): ?>
                    <?php $post = $item['Home']; ?>
                    <div class="post">
                    <h4><a href="<?= $post['url']?>" target="_blank"><?= $post['title'] ? $post['title'] : '(no title)'?></a></h4>
                        <p class="post-meta">
                            <?= $post['post_date']?><br />
                            Post ID: <?= $post['post_id'] ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

        <div class="footer">
            <p>&copy; Via Studio 2013</p>
        </div>

    </div> <!-- /container -->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
</html>
