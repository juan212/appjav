<?php add('head'); ?>
<?php add('menu'); ?>
<?php add('sidemenu'); ?>


<?php CategoryController::ImageArt($_POST); ?>
<?php  $controller->RecArticleClean($_POST); ?>
<?php //print_r($_FILES); ?>

<div class="span9">
    <div class="content">
        <div class="module"> 
            <div class="module-head">
                <h3>Creation de Categorie</h3>
            </div>
            <div class="module-body">

                <!--<div class="alert">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Warning!</strong> Something fishy here!
                </div>
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Oh snap!</strong> Whats wrong with you? 
                </div>
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Well done!</strong> Now you are listening me :) 
                </div>-->

                <br />

                <form enctype="multipart/form-data" method="post"  action="/category" class="form-horizontal row-fluid">

                    <div class="control-group">
                        <label class="control-label" for="nom">Nom</label>
                        <div class="controls">
                            <input type="text" id="basicinput" name="nom" placeholder="Nom de Categorie..." class="span8">
                            <span class="help-inline">Minimum 5 Characters</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <input type="hidden" name="MAX_FILE_SIZE" value="300000000" />
                        <label class="control-label" for="image">Image</label>
                        <div class="controls">
                            <input data-title="A tooltip for the input" type="file" name="image" data-original-title="" class="btn">
                        </div>
                    </div>

                    <div class="control-group">
                        <input type="hidden" name="MAX_FILE_SIZE" value="300000000" />
                        <label class="control-label" for="image">Color</label>
                        <div class="controls">
                            <button class="jscolor {valueElement:'chosen-value', onFineChange:'setTextColor(this)'}">
                                Color
                            </button>
                            <input type="hidden" id="chosen-value" name="color" value="000000">
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" name="submit" class="btn">Submit Form</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<?php add('footer'); ?>