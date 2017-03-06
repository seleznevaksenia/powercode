<?php include ROOT . '/view/layouts/header.php'; ?>
    <body>
    <header id="header"><!--header-->
        <section>
            <div class="container">
                <div class="row">
                    <div id="search" class="col-sm-2">
                        <div class="input-group">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
      </span>
                            <input type="text" class="form-control" placeholder="Search for...">
                        </div><!-- /input-group -->

                    </div>
                    <div  id="auth" class="col-sm-9 " >

                                    <div class="row ">
                                        <div  class="col-sm-3">
                                            <div class="dropdown">
                                                <button class="btn btn-block btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    USER
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                    <li><a href="#">Login</a></li>
                                                    <li><a href="#">Register</a></li>
                                                                                                   </ul>
                                            </div>
                                        </div>
                                        <div  class="col-sm-3 col-sm-offset-1">
                                            <button type="button" class="btn btn-block btn-default">SYSTEM</button>
                                        </div>
                                        <div  class="col-sm-4 col-sm-offset-1">
                                            <div class="row ">
                                                <div  class="col-sm-6">
                                            <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                                <div class="form-group" >
                                                    <span class="btn btn-default btn-file">
                            <i class="icon-plus"></i><span>Choose picture...</span>
                            <input type="file" name="image" id="picture" />
                                                </span>
                                                </div>
                                        </div>
                                        <div  class="col-sm-6">
                                                <button type="submit" class="btn btn-default">Submit</button>


                                            </form>
                                            </div>
                </div>
                    </div>
                </div>
                                        </div>
        </section>
        </div><!--/header_top-->

    </header><!--/header-->
<section>
    <div class="container">
        <div class="row">
            <div id="folder" class="col-sm-2">
    <?php echo $list;?>
            </div>
            <div id="image" class="col-sm-9 " >
            </div>
        </div>
    </div>

</section>


<?php include ROOT . '/view/layouts/footer.php'; ?>