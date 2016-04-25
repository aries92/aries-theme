// Handler when DOM is ready
jQuery(document).ready(function($)
    {
        $('#search-result .close-adult-notice').on("click", function(){
            $(this).closest('article').removeClass('is-adult');
        });

    }
);

// Handler when document is fully loaded
jQuery(window).load(function()
    {

    }
);
