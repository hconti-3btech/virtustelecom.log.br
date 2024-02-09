<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'EstoqueVirtus',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>ESTOQUE</b>VIRTUS',
    'logo_img' => 'vendor/adminlte/dist/img/logo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/logo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/logo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 100,
            'height' => 100,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */
    'menu' => [
        // Navbar items:
        [
            'type'         => 'navbar-search',
            'text'         => 'search',
            'topnav_right' => true,
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],
        [
            'text'    => 'Cadastro',
            'icon'    => 'nav-icon fas fa-solid fa-book',
            'can'         => 'ativo',
            'submenu' => [
                [
                    'text'  => 'Produtos',
                    'route' => 'viewProduto',
                    'icon'  => 'nav-icon fas fa-solid fa-certificate',
                    'can'         => 'admin',
                ],
                [
                    'text'  => 'Servicos',
                    'route'   => 'viewServico',
                    'icon'  => 'nav-icon fas fa-solid fa-cog',
                    'can'         => 'admin',
                ],
                [
                    'text'  => 'Usuarios',
                    'route'   => 'viewUsuario',
                    'icon'  => 'nav-icon fa fa-solid fa-users',
                    'can'         => 'ativo',
                ],
                [
                    'text'  => 'Valores de Servico',
                    'route'   => 'viewValorServico',
                    'icon'  => 'nav-icon fa fa-file-invoice-dollar',
                    'can'         => 'admin',
                ],
                [
                    'text'  => 'Link Container',
                    'route'   => 'viewLinkContainer',
                    'icon'  => 'nav-icon fa fa-link',
                    'can'         => 'admin',
                ],
                [
                    'text'  => 'Custos',
                    'route'   => 'viewCusto',
                    'icon'  => 'nav-icon fa fa-dollar-sign',
                    'can'         => 'admin',
                ],
                [
                    'text'  => 'Metas',
                    'route'   => 'viewMeta',
                    'icon'  => 'nav-icon fa fa-arrow-circle-up',
                    'can'         => 'admin',
                ],
                [
                    'text'  => 'Regras',
                    'route'   => 'regra.index',
                    'icon'  => 'nav-icon fa fa-ruler',
                    'can'         => 'admin',
                ],
                [
                    'text'  => 'Pontos',
                    'route'   => 'ponto.index',
                    'icon'  => 'nav-icon fa fa-coins',
                    'can'         => 'admin',
                ],
                [
                    'text'  => 'Indicadores',
                    'route'   => 'indicador.index',
                    'icon'  => 'nav-icon fa fa-times-circle',
                    'can'         => 'admin',
                ],
            ],
        ],
        [
            'text'        => 'Estoque',
            'icon'        => 'nav-icon fas fa-solid fa-warehouse',
            'can'         => 'ativo',
            'submenu' => [
                [
                    'text' => 'Container',
                    'route'  => 'viewContainer',
                    'icon'        => 'nav-icon fas fa-solid fa-box',
                    'can'         => 'ativo',
                ],
                [
                    'text'  => 'Entrada',
                    'route'   => 'addEstoque',
                    'icon'  => 'nav-icon fas fa-solid fa-cart-arrow-down',
                    'can'         => 'controle',
                ],
                [
                    'text'  => 'Movimentos',
                    'route'   => 'viewMovimento',
                    'icon'  => 'nav-icon fa fa-solid fa-retweet',
                    'can'         => 'almox_controle_supervisor',
                ],
                [
                    'text'  => 'Requisição',
                    'route'   => 'addRequisicao',
                    'icon'  => 'nav-icon fa fa-solid fa-file',
                    'can'         => 'tecnico_controle',
                ],
                [
                    'text'  => 'Liberar Requisição',
                    'route'   => 'viewRequisicao',
                    'icon'  => 'nav-icon fa fa-solid fa-clipboard-check',
                    'can'         => 'almox_controle_supervisor',
                ],
                
            ],
        ],
        [
            'text'        => 'Ordem de Serviço',
            'icon'        => 'nav-icon fa fa-solid fa-tasks',
            'can'         => 'tecnico_controle_supervisor_apoio',
            'submenu' => [
                [
                    'text'  => 'Adicionar OS',
                    'route' => 'viewOrdem',
                    'icon'  => 'nav-icon fas fa-solid fa-bell',
                    'can'   => 'controle_supervisor_apoio',
                ],
                [
                    'text'  => 'Executar OS',
                    'route' => 'viewExecOrdem',
                    'icon'  => 'nav-icon fas fa-hands-helping',
                    'can'   => 'tecnico_controle_supervisor_apoio',
                ],
            ],
        ],
        [
            'text'        => 'Importação',
            'icon'        => 'nav-icon fa fa-solid fa-upload',
            'can'         => 'controle_apoio',
            'submenu' => [
                [
                    'text'  => 'Importar OS',
                    'route'   => 'importOrdem',
                    'icon'  => 'nav-icon fas fa-solid fa-tasks',
                    'can'         => 'controle_apoio',
                ],
                [
                    'text'  => 'Importar Material',
                    'route'   => 'importMaterial',
                    'icon'  => 'nav-icon fas fa-solid fa-retweet',
                    'can'         => 'controle_apoio',
                ],
            ],
        ],
        [
            'text'        => 'Relatorios',
            'icon'        => 'nav-icon fa fa-solid fa-chart-pie',
            'can'         => 'ativo',
            'submenu' => [
                [
                    'text'  => 'Financeiro',
                    'route'   => 'reportFinanceiro',
                    'icon'  => 'nav-icon fas fa-solid fa-piggy-bank',
                    'can'         => 'controle_supervisor',
                ],
                [
                    'text'  => 'Ordem',
                    'route'   => 'reportOrdem',
                    'icon'  => 'nav-icon fas fa-solid fa-chart-line',
                    'can'         => 'controle_supervisor_apoio',
                ],
                [
                    'text'  => 'Producao',
                    'route'   => 'reportProducao',
                    'icon'  => 'nav-icon fas fa-solid fa-wrench',
                    'can'         => 'controle_supervisor_apoio',
                ],
                [
                    'text'  => 'Comissão',
                    'route'   => 'reportComissao',
                    'icon'  => 'nav-icon fas fa-solid fa-umbrella-beach',
                    'can'         => 'ativo',
                ],
                [
                    'text'  => 'Ultima Posiçao',
                    'route'   => 'reportUltimaPosicao',
                    'icon'  => 'nav-icon fas fa-solid fa-map-pin',
                    'can'         => 'ativo',
                ]
            ],
        ],
        
        // [
        //     'text'        => 'Produtos',
        //     'route'         => 'viewProduto',
        //     'icon'        => 'nav-icon fas fa-solid fa-certificate',
        // ],
        // [
        //     'text'        => 'Container',
        //     'route'         => 'viewContainer',
        //     'icon'        => 'nav-icon fas fa-solid fa-box',
        // ],
        // [
        //     'text'        => 'Movimento',
        //     'route'         => 'viewMovimento',
        //     'icon'        => 'nav-icon fas fa-solid fa-share',
        // ],
        // [
        //     'text'        => 'Centro de Custo',
        //     'route'         => 'viewCentroDeCusto',
        //     'icon'        => 'nav-icon fas fa-solid fa-street-view',
        // ],
    ],

    ########## Menu padrao 
    // 'menu' => [
    //     // Navbar items:
    //     [
    //         'type'         => 'navbar-search',
    //         'text'         => 'search',
    //         'topnav_right' => true,
    //     ],
    //     [
    //         'type'         => 'fullscreen-widget',
    //         'topnav_right' => true,
    //     ],

    //     // Sidebar items:
    //     [
    //         'type' => 'sidebar-menu-search',
    //         'text' => 'search',
    //     ],
    //     [
    //         'text' => 'blog',
    //         'url'  => 'admin/blog',
    //         'can'  => 'manage-blog',
    //     ],
    //     [
    //         'text'        => 'pages',
    //         'url'         => 'admin/pages',
    //         'icon'        => 'far fa-fw fa-file',
    //         'label'       => 4,
    //         'label_color' => 'success',
    //     ],
    //     ['header' => 'account_settings'],
    //     [
    //         'text' => 'profile',
    //         'url'  => 'admin/settings',
    //         'icon' => 'fas fa-fw fa-user',
    //     ],
    //     [
    //         'text' => 'change_password',
    //         'url'  => 'admin/settings',
    //         'icon' => 'fas fa-fw fa-lock',
    //     ],
    //     [
    //         'text'    => 'multilevel',
    //         'icon'    => 'fas fa-fw fa-share',
    //         'submenu' => [
    //             [
    //                 'text' => 'level_one',
    //                 'url'  => '#',
    //             ],
    //             [
    //                 'text'    => 'level_one',
    //                 'url'     => '#',
    //                 'submenu' => [
    //                     [
    //                         'text' => 'level_two',
    //                         'url'  => '#',
    //                     ],
    //                     [
    //                         'text'    => 'level_two',
    //                         'url'     => '#',
    //                         'submenu' => [
    //                             [
    //                                 'text' => 'level_three',
    //                                 'url'  => '#',
    //                             ],
    //                             [
    //                                 'text' => 'level_three',
    //                                 'url'  => '#',
    //                             ],
    //                         ],
    //                     ],
    //                 ],
    //             ],
    //             [
    //                 'text' => 'level_one',
    //                 'url'  => '#',
    //             ],
    //         ],
    //     ],
    //     ['header' => 'labels'],
    //     [
    //         'text'       => 'important',
    //         'icon_color' => 'red',
    //         'url'        => '#',
    //     ],
    //     [
    //         'text'       => 'warning',
    //         'icon_color' => 'yellow',
    //         'url'        => '#',
    //     ],
    //     [
    //         'text'       => 'information',
    //         'icon_color' => 'cyan',
    //         'url'        => '#',
    //     ],
    // ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                // ############################ JS
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//code.jquery.com/jquery-3.5.1.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js',
                ],
                // ############################ CSS
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        'pdfMake' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/vfs_fonts.min.js',
                ],
            ],
        ],
        'Ajax' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js',
                ],
            ],
        ],
        'Inputmask' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js',
                ],
            ],
        ],
        'Chart' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/chart.js/Chart.bundle.min.js',
                ],
            ],
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
