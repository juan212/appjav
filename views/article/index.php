<?php add('head'); ?>
<?php add('menu'); ?>
<?php add('sidemenu'); ?>

<?php //print_r($_POST); ?>

<?php ArticleController::ImageArt($_POST); ?>

<?php $controller->RecArticleClean($_POST); ?>

<div class="span9">
    <div class="content">
        <div class="module"> 
            <div class="module-head">
                <h3>Creation d'article</h3>
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

                <form enctype="multipart/form-data" method="post"  action="/article" class="form-horizontal row-fluid">



                    <div class="control-group">
                        <label class="control-label" for="title">Title</label>
                        <div class="controls">
                            <input type="text" id="basicinput" name="title" placeholder="Title..." class="span8" required="required">
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
                        <label class="control-label">Public</label>
                        <div class="controls">
                            <select name="public" required="required">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="basicinput">Textarea</label>
                        <div class="controls">
                            <textarea class="span8" name="subtitle" rows="5" required="required"></textarea>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="basicinput">Textarea</label>
                        <div class="controls">
                            <textarea class="span8" name="content" rows="5" required="required"></textarea>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label">Category</label>
                        <div class="controls">
                            <select name="id_category" required="required">
                                <?php foreach (Category::SelCat() as $value) : ?>
                                    <option value="<?php print_r($value['id']); ?>"><?php print_r($value['nom']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div></br></br>

                    <input type="hidden" name="creator" value="<?php print_r($controller->client->infosClient['prenom'] . ' ' . $controller->client->infosClient['nom']) ?>" />

                    <!--<div class="control-group">
                        <label class="control-label" for="basicinput">Prepended Input</label>
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on">#</span><input class="span8" type="text" placeholder="prepend">       
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="basicinput">Appended Input</label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" placeholder="5.000" class="span8"><span class="add-on">$</span>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="basicinput">Dropdown Button</label>
                        <div class="controls">
                            <div class="dropdown">
                                <a class="dropdown-toggle btn" data-toggle="dropdown" href="#">Dropdown Button <i class="icon-caret-down"></i></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                    <li><a href="#">First Row</a></li>
                                    <li><a href="#">Second Row</a></li>
                                    <li><a href="#">Third Row</a></li>
                                    <li><a href="#">Fourth Row</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="basicinput">Dropdown</label>
                        <div class="controls">
                            <select tabindex="1" data-placeholder="Select here.." class="span8">
                                <option value="">Select here..</option>
                                <option value="Category 1">First Row</option>
                                <option value="Category 2">Second Row</option>
                                <option value="Category 3">Third Row</option>
                                <option value="Category 4">Fourth Row</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Radiobuttons</label>
                        <div class="controls">
                            <label class="radio">
                                <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
                                Option one
                            </label> 
                            <label class="radio">
                                <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                Option two
                            </label> 
                            <label class="radio">
                                <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3">
                                Option three
                            </label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Inline Radiobuttons</label>
                        <div class="controls">
                            <label class="radio inline">
                                <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
                                Option one
                            </label> 
                            <label class="radio inline">
                                <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                Option two
                            </label> 
                            <label class="radio inline">
                                <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3">
                                Option three
                            </label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Checkboxes</label>
                        <div class="controls">
                            <label class="checkbox">
                                <input type="checkbox" value="">
                                Option one
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" value="">
                                Option two
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" value="">
                                Option three
                            </label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Inline Checkboxes</label>
                        <div class="controls">
                            <label class="checkbox inline">
                                <input type="checkbox" value="">
                                Option one
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" value="">
                                Option two
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" value="">
                                Option three
                            </label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="basicinput">Textarea</label>
                        <div class="controls">
                            <textarea class="span8" rows="5"></textarea>
                        </div>
                    </div>-->

                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" name="submit" class="btn">Submit Form</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php
        ?>

    </div><!--/.content-->
</div><!--/.span9-->
</div>
</div><!--/.container-->
</div><!--/.wrapper-->

<div class="footer">
    <div class="container">


        <b class="copyright">&copy; 2014 Edmin - EGrappler.com </b> All rights reserved.
    </div>
</div>

<script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
</body>