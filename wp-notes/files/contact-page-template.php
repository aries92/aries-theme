<?php

/*

Template Name: Contact Page Template

*/

?>



<?php get_header(); ?>

<div class="contact-page">



    <script>
        function getUrlParameter(sParam)
        {
            var sPageURL = window.location.search.substring(1);
            var sURLVariables = sPageURL.split('&');
            for (var i = 0; i < sURLVariables.length; i++)
            {
                var sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] == sParam)
                {
                    return sParameterName[1];
                }
            }
        }

        var product_url = getUrlParameter("product_link");

        document.getElementById('js-add-link').innerHTML = product_url ;

        //    var product_url = getUrlParameter("product_link");
        //    alert(product_url);

        //    var product_url = getUrlParameter("product_id");
        //    alert(product_url);

    </script>


    <!--PRINT ALL OBJECT-->
<!--    --><?php //print_r(wc_get_product($_GET['product_id'])); ?>

    <!--GET PARAMS FROM URL OF PRODUCT-->
    <?php $_product_content = wc_get_product($_GET['product_id']); ?>

    <div id="product-content">
        <h3>Ваше замовлення</h3>
        <ul>
            <li>
                <div class="product-image">
                    <?php
                        echo $img = '<img src="' . wp_get_attachment_url( get_post_thumbnail_id($_product_content->id) ) . '" alt="" />';
                    ?>
                </div>
            </li>
            <li>
                <div class="product-title">
                    <?php
                        echo $link = '<a href="' . $_product_content->post->guid . '">' . $_product_content->post->post_title . '</a> ';
                    ?>
                </div>
                <div class="product-price">
                    <?php
                        echo $price = get_post_meta( $_product_content->id, '_regular_price', true);
                    ?>
                </div>
            </li>
        </ul>
    </div>







    <?php echo do_shortcode( '[contact-form-7 id="114" title="Оформлення замовлення"]' ); ?>



</div>


















<?php get_footer(); ?>