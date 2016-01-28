<?php
/**
 * Created by Aries92
 *
 * @dev: Taras Tymovskyi
 * @date: 27/01/15
 * @package starter
 */

/**
 * Advanced Custom Fields Integration
 *
 * To access the ACF fields interface in the admin section, make sure you
 * set the below constant ACF_LITE to "FALSE" from the default "TRUE"
 *
 * Paste in your custom fields exported PHP code into inc/advanced-custom-fields/acf-fields.php
 *
 * We check ACF isn't already installed first or we get errors.
 *
 */

if (!defined('ACF_LITE'))
{

    require_once '/advanced-custom-fields/pro/acf-pro.php';

}




