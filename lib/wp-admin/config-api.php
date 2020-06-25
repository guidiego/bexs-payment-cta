<?php
    function bcb_api_init()
    {
        return bcb_create_init(
            'bcbApiPlugin',
            'bcb_api',
            'Checkout Configuration',
            [
                [
                    'name' => 'auth_token',
                    'label' => 'Bexs Authorization Token',
                ],
                [
                    'name' => 'redirect_url',
                    'label' => 'Success Redirect URL',
                ],
                [
                    'name' => 'default_max_installments',
                    'label' => 'Default Max Installments',
                    'default' => '5',
                ],
            ]
        );
    }

    function bcb_api_section_callback(  ) {
        echo __( 'Você pode resgatar seu token de API aqui:', 'wordpress' );
    }

    function bcb_get_redirect_url()
    {
        return bcb_get_option('bcb_api', 'redirect_url');
    }

    function bcb_get_default_max_installments()
    {
        return bcb_get_option('bcb_api', 'default_max_installments');
    }

    function bcb_api_options_page()
    {
        return bcb_create_options_page('bcbApiPlugin');
    }