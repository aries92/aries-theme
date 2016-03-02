<?php

/*

Template Name: Search True

*/

?>



<?php get_header(); ?>


                    <div class="container-search">
                        <div id="containerSearchFormDesign">

                            <form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
                                <div>

                                    <input type="text" value="<?php echo wp_specialchars($s, 1); ?>" name="s" id="s" class="form-text">
                                    <input type="submit" id="searchsubmit" value="" class="btn over form-submit"  />
                                </div>
                            </form>


                        </div>
                    </div>


<?php get_footer(); ?>