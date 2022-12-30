<?php

/**
 * SearchWP OptionsView.
 *
 * @package SearchWP
 * @author  Jon Christopher
 */

namespace SearchWP\Admin;

use SearchWP\Utils;
use SearchWP\Settings;
use SearchWP\Statistics;
use SearchWP\Admin\Views\EnginesView;
use SearchWP\Admin\Views\SupportView;
use SearchWP\Admin\Views\SettingsView;
use SearchWP\Admin\Views\AdvancedView;
use SearchWP\Admin\Views\StatisticsView;

/**
 * Class OptionsView is responsible for implementing the options screen into the WordPress Admin area.
 *
 * @since 4.0
 */
class OptionsView {

	/**
	 * Slug for this view.
	 *
	 * @since 4.0
     *
	 * @var string
	 */
	private static $slug;

	/**
	 * Extensions registry.
	 *
	 * @since 4.0
     *
	 * @var array
	 */
	private $extensions;

	/**
	 * OptionsView constructor.
	 *
	 * @since 4.0
	 */
	public function __construct() {

		self::$slug = Utils::$slug;

		self::hooks();
		self::option_views();

        // Add Extensions Tab and callbacks.
        // TODO: Extension handling can (should) be its own class.
        $this->init_extensions();

		do_action( 'searchwp\settings\init' );
	}

	/**
	 * Run OptionsView hooks.
	 *
	 * @since 4.2.0
	 */
	private static function hooks() {

		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'assets' ], 999 );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'legacy_assets' ] );

		add_action( 'admin_menu', [ __CLASS__, 'add_admin_menus' ] );
		add_action( 'admin_menu', [ __CLASS__, 'add_dashboard_stats_link' ] );

		add_action( 'network_admin_menu', [ __CLASS__, 'add_network_admin_menu' ] );

		add_filter( 'admin_body_class', [ __CLASS__, 'admin_body_class' ] );
		add_action( 'in_admin_header', [ __CLASS__, 'admin_header' ], 100 );
		add_action( 'admin_print_scripts', [ __CLASS__, 'admin_hide_unrelated_notices' ] );
	}

	/**
	 * Init option views.
	 *
	 * @since 4.2.0
	 */
	private static function option_views() {

		// Add internal tabs.
		do_action( 'searchwp\settings\nav\before' );

		if ( apply_filters( 'searchwp\settings\nav\engines', true ) ) {
			new EnginesView();
			do_action( 'searchwp\settings\nav\engines' );
		}

		if ( apply_filters( 'searchwp\settings\nav\settings', true ) ) {
			new SettingsView();
			do_action( 'searchwp\settings\nav\settings' );
		}

		if ( apply_filters( 'searchwp\settings\nav\advanced', true ) ) {
			new AdvancedView();
			do_action( 'searchwp\settings\nav\advanced' );
		}

		if ( apply_filters( 'searchwp\settings\nav\statistics', true ) ) {
			new StatisticsView();
			do_action( 'searchwp\settings\nav\statistics' );
		}

		if ( apply_filters( 'searchwp\settings\nav\support', true ) ) {
			new SupportView();
			do_action( 'searchwp\settings\nav\support' );
		}

		do_action( 'searchwp\settings\nav\after' );
    }

	/**
	 * Enqueue the assets related to the OptionsView.
	 *
	 * @since 4.0
	 */
	public static function assets() {

		if ( ! Utils::is_swp_admin_page() ) {
			return;
		}

		wp_enqueue_style(
			self::$slug . '_admin_settings',
			SEARCHWP_PLUGIN_URL . 'assets/styles/settings.css',
			false,
			SEARCHWP_VERSION
		);

		wp_enqueue_script( 'jquery' );
	}

	/**
     * Enqueue legacy assets from a previous version of the settings screen.
     *
	 * @param string $hook_suffix The current admin page.
     *
     * @since 4.2.0
	 *
	 * @return void
	 */
    public static function legacy_assets( $hook_suffix ) {

	    if ( $hook_suffix === 'toplevel_page_searchwp-settings' ) {
		    do_action( 'admin_enqueue_scripts', 'settings_page_searchwp' );
	    }
    }

	/**
	 * Add SearchWP admin menus.
	 *
	 * @since 4.2.0
	 */
	public static function add_admin_menus() {

		if ( ! apply_filters( 'searchwp\options\settings_screen', true ) ) {
			return;
		}

		if ( ! current_user_can( Settings::get_capability() ) ) {
			return;
		}

		$submenu_pages = self::get_submenu_pages_args();

		$page_title = esc_html__( 'SearchWP', 'searchwp' );
        $menu_page  = reset( $submenu_pages );

		// Default SearchWP top level menu item.
		add_menu_page(
			$page_title,
			$page_title,
			Settings::get_capability(),
			$menu_page['menu_slug'],
			[ __CLASS__, 'page' ],
			'data:image/svg+xml;base64,' . base64_encode( self::get_dashicon() ),
			apply_filters( 'searchwp\admin_menu\position', '58.9' )
		);

        foreach ( $submenu_pages as $submenu_page ) {
	        add_submenu_page(
		        $menu_page['menu_slug'],
		        $submenu_page['page_title'] ?? $page_title,
		        $submenu_page['menu_title'],
		        $submenu_page['capability'] ?? Settings::get_capability(),
		        $submenu_page['menu_slug'],
		        $submenu_page['function'] ?? [ __CLASS__, 'page' ]
	        );
        }
	}

	/**
	 * Get SearchWP dashicon SVG.
	 *
	 * @since 4.2.0
	 */
	private static function get_dashicon() {

		return '<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2" viewBox="-6 -6 57 74">
                    <path d="M22.1 17.4v.8h-3c-.6 0-1 .4-1.1 1-.7 3.7-1.2 7.6-1.4 11.4a94 94 0 0 0 1.4 13c0 .6.5 1 1 1h3.4v.8H19a2 2 0 0 1-2-1.6c-.7-4.4-1.2-8.8-1.3-13.2.2-3.9.7-7.8 1.4-11.6a2 2 0 0 1 1.9-1.6h3Zm17.8 0h3c1 0 1.8.7 2 1.6.6 4 1 8 1.3 12a79 79 0 0 1-1.4 12.8 2 2 0 0 1-1.9 1.6h-3.2l-.1-.8h3.3c.5 0 1-.4 1.1-1 .8-4.1 1.2-8.4 1.4-12.6-.2-4-.7-8-1.4-11.9-.1-.5-.6-.9-1.1-.9h-3v-.8Zm0 3.5h.9c.6 0 1 .5 1.2 1 .1 1.4.6 5.8.7 9.1 0 3.2-.4 6.4-1 9.6-.2.5-.7 1-1.2.9h-1.3a1 1 0 0 0-1-.8h2.3a53.2 53.2 0 0 0 .7-18.7c0-.2-.3-.3-.4-.3H21.2c-.2 0-.3.1-.4.3a54.1 54.1 0 0 0 .3 18.4c0 .2.2.3.4.3h16.6H24c-.5 0-1 .4-1.1.8h-1.3c-.5 0-1-.4-1.1-1a53.7 53.7 0 0 1-.4-18.6c.1-.5.6-1 1.2-1h1v.3H40V21Z" style="fill-rule:nonzero" transform="translate(-21.9 -13.8) scale(1.38108)"/>
                    <path d="M6.3 51h29.5l1 .8 1.2 4.4-.6 1-2 .2h-30l-1-.7-.2-1.1 1-4 1.2-.6Zm25.8-7.8v-.3H34l1.2-.5.3-2.3.9-7.8.3-3.3-.4-5.2-.5-6.5-.2-1.2-1-.5h-1.3v-4.8l4.3-.1 1.3.5.7 1.2 1.9 15.7-.5 8.3-.6 4.5-.6 4.7-.4 1.8-1.1.9-4.3.1h-1.2l.3 2.6H8.8l.3-2.6H8l-4.3-.1-1.1-1-.4-1.7-.7-4.7-.5-4.5-.5-8.3 1.9-15.7.7-1.2 1.2-.5 4.4.1v4.8H7.4l-1 .5-.1 1.2-.5 6.5-.4 5.2.3 3.3.9 7.8.3 2.3 1.2.5h1.8l.5-.4 1.2-.1h19.6l1 .8ZM12 9.2h-1.2l-1.5.6-.4 1 .2 4.8 24.1-.1v-5.2L32 9.2h-2V7l-.8-1h-16l-1 .6-.4 1v1.6Z"/>
                    <path d="M21.9 47a1.5 1.5 0 0 1 0-.6l.4-4.7c0-.8.8-1.4 1.6-1.4H38c.8 0 1.5.6 1.6 1.4l.4 4.7a1.5 1.5 0 0 1 0 .5h-.8v-.4l-.4-4.7c0-.4-.4-.7-.8-.7H24c-.4 0-.8.3-.8.7l-.5 4.7.1.4H22Zm14.2-28c-.2.2-.5.4-.8.4h-8.6c-.3 0-.6-.2-.8-.4H36Zm-10.5-4.7v-2.7c0-.9.7-1.6 1.5-1.6H35c.8 0 1.5.7 1.5 1.6v2.7h-2.3v-2H28v2h-2.3Z" style="fill-rule:nonzero" transform="translate(-21.9 -13.8) scale(1.38108)"/>
                    <path d="M38 19.4H24v-4c0-.8.7-1.5 1.6-1.5h10.8c.9 0 1.6.7 1.6 1.6v3.9Zm-13.2-.8h12.4v-3.1c0-.5-.3-.8-.8-.8H25.6c-.5 0-.8.3-.8.8v3Z" style="fill-rule:nonzero" transform="translate(-21.9 -13.8) scale(1.38108)"/>
                    <path d="M37.6 16.2h1.2c.8 0 1.5.7 1.5 1.6v3.9H21.7v-4c0-.8.7-1.5 1.5-1.5h1.2v.8h-1.2c-.4 0-.8.4-.8.8v3.1h17.1v-3.1c0-.4-.3-.8-.7-.8h-1.2v-.8ZM42.1 52H20a1.6 1.6 0 0 1-1.5-2l.6-2.3c.2-.7.8-1.2 1.5-1.2h21c.7 0 1.3.5 1.5 1.2l.6 2.3a1.6 1.6 0 0 1-1.5 2Zm-21.6-4.7c-.3 0-.6.2-.7.6l-.7 2.3a.8.8 0 0 0 .8 1H42a.8.8 0 0 0 .8-1l-.7-2.3c0-.4-.4-.6-.7-.6h-21Z" style="fill-rule:nonzero" transform="translate(-21.9 -13.8) scale(1.38108)"/>
                    <path d="M39.1 21.3a46.3 46.3 0 0 1-1.3 19.4h-.9a41.9 41.9 0 0 0 1.4-19.4h.8Zm-15.4 0A45.3 45.3 0 0 0 25 40.7h-.9A42.6 42.6 0 0 1 23 21.3h.8Z" style="fill-rule:nonzero" transform="translate(-21.9 -13.8) scale(1.38108)"/>
                    <path d="m30.8 38.5-9.9-8.6-9.7 8.5 1 4h17.6l1-4ZM32 21.3l-9.3 7.1 8.6 7.5v-.2l.7-9.8v-4.6Zm-13 7.1-8.4 7.3-1-6.7v-7.7l9.4 7.1Zm13-10-.7-3.5-21 .7-.3 3 10.8 8.3L32 18.3Z"/>
                </svg>';
	}

	/**
     * Get arguments to populate the submenus.
     * Items are sorted by the 'position' value.
     *
     * @since 4.2.0
     *
	 * @return array
	 */
    private static function get_submenu_pages_args() {

	    $submenu_pages = [
		    'settings'   => [
			    'menu_title' => esc_html__( 'Settings', 'searchwp' ),
			    'menu_slug'  => 'searchwp-settings',
			    'position'   => 10,
		    ],
		    'statistics' => [
			    'menu_title' => esc_html__( 'Statistics', 'searchwp' ),
			    'menu_slug'  => 'searchwp-statistics',
			    'position'   => 20,
		    ],
	    ];

	    $submenu_pages = (array) apply_filters( 'searchwp\options\submenu_pages', $submenu_pages );

	    uasort(
		    $submenu_pages,
		    function ( $a, $b ) {
			    if ( ! isset( $a['position'] ) ) {
				    return 1;
			    }
			    if ( ! isset( $b['position'] ) ) {
				    return -1;
			    }

			    return ( $a['position'] < $b['position'] ) ? -1 : 1;
		    }
	    );

        return $submenu_pages;
    }

	/**
	 * Add Search Stats dashboard link.
	 *
	 * @since 4.2.0
	 */
	public static function add_dashboard_stats_link() {

		$user_can = current_user_can( Settings::get_capability() ) ||
		            current_user_can( Statistics::$capability );

		if ( ! apply_filters( 'searchwp\options\dashboard_stats_link', $user_can ) ) {
			return;
		}

		if ( current_user_can( Settings::get_capability() ) ) {
			self::add_stats_dashboard_page_options_redirect();

			return;
		}

		self::add_stats_dashboard_page_standalone();
	}

	/**
	 * Add Search Stats dashboard page and redirect it to Statistics page in plugin options menu.
	 *
	 * @since 4.2.0
	 */
	private static function add_stats_dashboard_page_options_redirect() {

		global $submenu;

		add_dashboard_page(
			__( 'Search Statistics', 'searchwp' ),
			__( 'Search Stats', 'searchwp' ),
			Statistics::$capability,
			self::$slug . '-stats'
		);

		if ( ! is_array( $submenu ) || ! array_key_exists( 'index.php', $submenu ) ) {
			return;
		}

		// Override the link for the Search Stats Admin Menu entry.
		foreach ( $submenu['index.php'] as $index => $dashboard_submenu ) {
			if ( $dashboard_submenu[2] !== 'searchwp-stats' ) {
				continue;
			}

			$submenu['index.php'][ $index ][2] = esc_url_raw( add_query_arg( [
				'page' => 'searchwp-statistics',
			], admin_url( 'admin.php' ) ) );

			break;
		}
	}

	/**
	 * Add Search Stats dashboard standalone page.
	 *
	 * @since 4.2.0
	 */
	private static function add_stats_dashboard_page_standalone() {

		$callback = static function() {
			wp_enqueue_style(
				self::$slug . '_admin_settings',
				SEARCHWP_PLUGIN_URL . 'assets/styles/settings.css',
				false,
				SEARCHWP_VERSION
			);

			wp_enqueue_script( 'jquery' );
			?>
            <div class="searchwp-settings-view">
                <div class="searchwp-settings" style="padding-right: 20px;">
					<?php
					do_action( 'searchwp\settings\view' );
					do_action( 'searchwp\settings\after' );
					?>
                </div>
            </div>
			<?php
		};

		// Current user can view Statistics but not Settings.
		add_dashboard_page(
			__( 'Search Statistics', 'searchwp' ),
			__( 'Search Stats', 'searchwp' ),
			Statistics::$capability,
			self::$slug . '-stats',
			$callback
		);
	}

	/**
	 * Adds SearchWP network admin menu.
	 *
	 * @since 4.0
     *
	 * @return void
	 */
	public static function add_network_admin_menu() {

		$callback = static function() {
			do_action( 'searchwp\debug\log', 'Displaying network options page', 'settings' );
			?>
            <div class="wrap">
                <div style="max-width: 60em;">
                    <h1>SearchWP</h1>
					<?php
					echo wp_kses(
						__( '<p>Cross-site searches are possible in SearchWP. Any Engine from any site can be used for a cross-site search.</p><p><strong>Note</strong>: SearchWP\'s Engines control what is indexed on each sub-site. If the Engine you are using to perform the search has different Sources/Attributes/Rules than the Engine(s) on the sub-sites you are searching the <em>results may not be accurate</em>.</p><p>For example: if Posts have been added to the Engine you are using for the search, but a sub-site does not have an Engine with Posts enabled, <strong>that sub-site will not return Posts</strong>.</p><p>For a comprehensive cross-site search, ensure that <em>all sites</em> share a similar configuration and applicable Engine.</p><p><a href="https://searchwp.com/?p=288269" target="_blank">More information</a></p>', 'searchwp' ),
						[
							'p'      => [],
							'strong' => [],
							'em'     => [],
							'a'      => [
								'href'   => [],
								'target' => [],
							],
						]
					);
					?>
                </div>
            </div>
			<?php
		};

		add_menu_page(
			'SearchWP',
			'SearchWP',
			Settings::get_capability(),
			self::$slug,
			$callback
		);
	}

	/**
	 * Add body class to SearchWP admin pages for easy reference.
	 *
	 * @since 4.2.0
	 *
	 * @param string $classes CSS classes, space separated.
	 *
	 * @return string
	 */
	public static function admin_body_class( $classes ) {

		if ( ! Utils::is_swp_admin_page() ) {
			return $classes;
		}

		return "$classes searchwp-admin-page";
	}

	/**
	 * Output the SearchWP admin header.
	 *
	 * @since 4.2.0
	 */
	public static function admin_header() {

		// Bail if we're not on a SearchWP screen or page.
		if ( ! Utils::is_swp_admin_page() ) {
			return;
		}

		if ( ! apply_filters( 'searchwp\settings\header', true ) ) {
			return;
		}

		self::header();
	}

	/**
	 * Remove non-SearchWP notices from SearchWP pages.
	 *
	 * @since 4.2.0
	 */
	public static function admin_hide_unrelated_notices() {

		if ( ! Utils::is_swp_admin_page() ) {
			return;
		}

		global $wp_filter;

		// Define rules to remove callbacks.
		$rules = [
			'user_admin_notices' => [], // remove all callbacks.
			'admin_notices'      => [],
			'all_admin_notices'  => [],
			'admin_footer'       => [
				'render_delayed_admin_notices', // remove this particular callback.
			],
		];

		$notice_types = array_keys( $rules );

		foreach ( $notice_types as $notice_type ) {
			if ( empty( $wp_filter[ $notice_type ]->callbacks ) || ! is_array( $wp_filter[ $notice_type ]->callbacks ) ) {
				continue;
			}

			$remove_all_filters = empty( $rules[ $notice_type ] );

			foreach ( $wp_filter[ $notice_type ]->callbacks as $priority => $hooks ) {
				foreach ( $hooks as $name => $arr ) {
					if ( is_object( $arr['function'] ) && is_callable( $arr['function'] ) ) {
						if ( $remove_all_filters ) {
							unset( $wp_filter[ $notice_type ]->callbacks[ $priority ][ $name ] );
						}
						continue;
					}

					$class = ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) ? strtolower( get_class( $arr['function'][0] ) ) : '';

					// Remove all callbacks except SearchWP notices.
					if ( $remove_all_filters && strpos( $class, 'searchwp' ) === false ) {
						unset( $wp_filter[ $notice_type ]->callbacks[ $priority ][ $name ] );
						continue;
					}

					$cb = is_array( $arr['function'] ) ? $arr['function'][1] : $arr['function'];

					// Remove a specific callback.
					if ( ! $remove_all_filters && in_array( $cb, $rules[ $notice_type ], true ) ) {
                        unset( $wp_filter[ $notice_type ]->callbacks[ $priority ][ $name ] );
                    }
				}
			}
		}
	}

	/**
	 * Renders the page.
	 *
	 * @since 4.2.0
	 */
	public static function page() {

		do_action( 'searchwp\settings\page' );
		do_action( 'searchwp\debug\log', 'Displaying options page', 'settings' );
		?>
        <div class="searchwp-admin-wrap wrap">
            <div class="searchwp-settings-view">
                <?php
                self::view();
                ?>
            </div>
            <?php self::footer(); ?>
        </div>
		<?php
	}

	/**
	 * Renders the header.
	 *
	 * @since 4.0
     *
	 * @return void
	 */
    private static function header() {

        do_action( 'searchwp\settings\header\before' );

        ?>
        <div class="searchwp-settings-header">
            <p class="searchwp-logo" title="SearchWP">
                <svg width="40" height="54" viewBox="0 0 40 54" fill="none" xmlns="http://www.w3.org/2000/svg"><mask id="searchwp-logo-mask-a" fill="#fff"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.648 10c-.959 0-1.781.68-1.946 1.625C2.175 14.658 1.112 21.34 1 26.5c-.114 5.288 1.134 13.417 1.712 16.864.16.952.985 1.636 1.95 1.636h30.684c.958 0 1.78-.675 1.944-1.619.584-3.339 1.824-11.12 1.71-16.381-.112-5.195-1.194-12.217-1.72-15.36A1.969 1.969 0 0035.33 10H4.648zm2.729 5a.99.99 0 00-.987.873C6.153 17.885 5.593 22.984 5.5 27c-.096 4.116.94 10.066 1.341 12.2.088.468.496.8.972.8H32.18a.983.983 0 00.97-.798c.404-2.134 1.445-8.085 1.349-12.202-.093-4.016-.658-9.117-.897-11.128a.99.99 0 00-.986-.872H7.377z"/></mask><path fill-rule="evenodd" clip-rule="evenodd" d="M4.648 10c-.959 0-1.781.68-1.946 1.625C2.175 14.658 1.112 21.34 1 26.5c-.114 5.288 1.134 13.417 1.712 16.864.16.952.985 1.636 1.95 1.636h30.684c.958 0 1.78-.675 1.944-1.619.584-3.339 1.824-11.12 1.71-16.381-.112-5.195-1.194-12.217-1.72-15.36A1.969 1.969 0 0035.33 10H4.648zm2.729 5a.99.99 0 00-.987.873C6.153 17.885 5.593 22.984 5.5 27c-.096 4.116.94 10.066 1.341 12.2.088.468.496.8.972.8H32.18a.983.983 0 00.97-.798c.404-2.134 1.445-8.085 1.349-12.202-.093-4.016-.658-9.117-.897-11.128a.99.99 0 00-.986-.872H7.377z" fill="#BFCDC2"/><path d="M2.702 11.625l.986.171-.986-.171zM1 26.5l1 .022-1-.022zm1.712 16.864l-.986.166.986-.166zm34.578.017l-.985-.172.985.172zM39 27l1-.022L39 27zm-1.72-15.36l.987-.165-.987.165zM6.39 15.873l.993.117-.993-.117zM5.5 27l-1-.023 1 .023zm1.341 12.2l-.983.185.983-.184zm26.31.002l.983.185-.982-.185zM34.5 27l-1 .023 1-.023zm-.897-11.128l.993-.118-.993.118zM3.688 11.796a.967.967 0 01.96-.796V9a2.967 2.967 0 00-2.93 2.454l1.97.342zM2 26.522c.11-5.077 1.16-11.693 1.688-14.726l-1.97-.342C1.188 14.487.112 21.236 0 26.478l2 .044zm1.698 16.677C3.118 39.74 1.888 31.7 2 26.522l-2-.044c-.116 5.397 1.15 13.617 1.726 17.052l1.972-.33zm.963.801a.965.965 0 01-.963-.8l-1.972.33A2.965 2.965 0 004.66 46v-2zm30.684 0H4.662v2h30.684v-2zm.96-.79a.962.962 0 01-.96.79v2a2.962 2.962 0 002.93-2.447l-1.97-.344zM38 27.021c.111 5.15-1.11 12.837-1.695 16.187l1.97.344c.582-3.328 1.84-11.201 1.725-16.575l-2 .044zm-1.706-15.217C36.82 14.95 37.89 21.907 38 27.022l2-.044c-.114-5.275-1.208-12.362-1.733-15.503l-1.973.33zM35.33 11a.97.97 0 01.964.805l1.973-.33A2.969 2.969 0 0035.33 9v2zM4.648 11H35.33V9H4.648v2zm2.735 4.99v.001a.02.02 0 01-.001.004.013.013 0 01-.003.004h-.002c-.002.001-.002.001 0 .001v-2a1.99 1.99 0 00-1.98 1.756l1.986.234zM6.5 27.023c.092-3.964.646-9.022.883-11.033l-1.986-.234c-.237 2.013-.802 7.154-.897 11.22l2 .047zm1.324 11.993C7.42 36.87 6.407 31.02 6.5 27.023l-2-.046c-.098 4.236.959 10.285 1.358 12.409l1.966-.37zM7.813 39c-.002 0-.002 0 0 0l.003.002a.026.026 0 01.008.014l-1.966.37A1.983 1.983 0 007.813 41v-2zm24.368 0H7.813v2H32.18v-2zm-.012.016a.027.027 0 01.008-.014.014.014 0 01.004-.002c.001 0 .001 0 0 0v2c.945 0 1.774-.664 1.953-1.613l-1.965-.371zM33.5 27.023c.093 3.997-.926 9.848-1.33 11.993l1.964.371c.402-2.123 1.464-8.173 1.366-12.41l-2 .046zm-.89-11.033c.239 2.01.798 7.069.89 11.033l2-.046c-.095-4.069-.665-9.21-.904-11.223l-1.986.236zm.007.01c.001 0 0 0 0 0l-.002-.002-.003-.003a.025.025 0 01-.002-.004v-.001l1.986-.236A1.99 1.99 0 0032.616 14v2zm-25.24 0h25.24v-2H7.377v2z" fill="#839788" mask="url(#searchwp-logo-mask-a)"/><path d="M30.5 23.099c0 6.592-.971 12.613-2.571 17.25-.978 2.835-2.14 3.732-3.34 4.022-.635.154-1.327.148-2.105.095-.214-.014-.437-.033-.667-.052-.579-.047-1.198-.099-1.817-.099s-1.238.052-1.817.1c-.23.018-.453.037-.667.051-.778.053-1.47.059-2.106-.095-1.199-.29-2.36-1.187-3.339-4.022-1.6-4.637-2.571-10.658-2.571-17.25 0-4.879.532-9.447 1.458-13.374.087-.368.276-.584.526-.729.271-.156.637-.24 1.073-.273.431-.033.883-.013 1.316.006h.008l.036.002c.394.017.824.036 1.137-.025 1.419-.276 2.533-.212 3.33-.083.398.066.72.148.97.215l.117.033.212.057c.078.02.202.05.317.05.11 0 .268-.014.453-.031l.122-.012c.176-.016.39-.037.652-.059.65-.054 1.592-.12 2.914-.16.21-.007.51-.044.834-.083l.44-.052c.507-.055 1.064-.1 1.595-.073.535.026 1.002.122 1.353.315.331.182.573.456.679.902.926 3.927 1.458 8.495 1.458 13.374z" fill="#fff" stroke="#839788"/><path d="M9.317 40.85a1.5 1.5 0 011.493-1.35h18.38a1.5 1.5 0 011.493 1.35l.6 6a1.5 1.5 0 01-1.493 1.65H10.21a1.5 1.5 0 01-1.493-1.65l.6-6z" fill="#BFCDC2" stroke="#839788"/><path d="M14.496 2a.5.5 0 01.5-.5h10a.5.5 0 01.5.5v8.5h-11V2z" stroke="#839788" stroke-width="3" stroke-linejoin="round"/><path d="M11.496 7a1.5 1.5 0 011.5-1.5h14a1.5 1.5 0 011.5 1.5v4.5h-17V7z" fill="#BFCDC2" stroke="#839788"/><path d="M8.496 10a1.5 1.5 0 011.5-1.5h20a1.5 1.5 0 011.5 1.5v4.5h-23V10zM5.066 48.588A1.5 1.5 0 016.51 47.5H33.49a1.5 1.5 0 011.443 1.088l.857 3a1.5 1.5 0 01-1.442 1.912H5.65a1.5 1.5 0 01-1.442-1.912l.857-3z" fill="#BFCDC2" stroke="#839788"/><path d="M9.242 35.802l11.084-9.66 11.27-8.63" stroke="#839788" stroke-width="2"/><path d="M30.69 35.747l-11.047-9.63L8.4 17.508" stroke="#839788" stroke-width="2"/></svg>
            </p>
            <nav class="searchwp-settings-header-nav">
                <button class="button searchwp-settings-header-nav-toggle">Menu</button>
                <ul>
                    <?php do_action( 'searchwp\settings\nav\tab' ); ?>
                </ul>
            </nav>
            <script>
                jQuery(document).ready(function($){
                    $('.searchwp-settings-header-nav-toggle').click(function(e){
                        e.preventDefault();
                        $('.searchwp-settings-header-nav > ul').toggle();
                    });
                });
            </script>
        </div>

        <hr class="wp-header-end">
        <?php

		do_action( 'searchwp\settings\header\after' );
    }

	/**
	 * Renders the main view.
	 *
	 * @since 4.0
     *
	 * @return void
	 */
	private static function view() {

		$view = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'default';

		// TODO: Deprecate `'searchwp\settings\...\\' . $view` actions after updating all the dependent code.

		do_action( 'searchwp\settings\before' );
		do_action( 'searchwp\settings\before\\' . $view );

		do_action( 'searchwp\settings\view' );
		do_action( 'searchwp\settings\view\\' . $view );

		do_action( 'searchwp\settings\after' );
		do_action( 'searchwp\settings\after\\' . $view );
	}

	/**
	 * Renders the footer.
	 *
	 * @since 4.0
     *
	 * @return void
	 */
	private static function footer() {
		do_action( 'searchwp\settings\footer' );
	}

	/**
	 * Initialize all registered Extensions.
	 *
	 * @since 4.0
     *
	 * @return void
	 */
	private function init_extensions() {

		if ( ! Utils::is_swp_admin_page( 'settings' ) ) {
            return;
        }

		$this->prime_extensions();

		$extensions = array_filter( (array) $this->extensions, function( $extension ) {
			return ! empty( $extension->public );
		} );

		if ( ! empty( $extensions ) ) {
			new NavTab([
				'tab'   => 'extensions',
				'label' => __( 'Extensions', 'searchwp' ),
				'icon'  => 'dashicons dashicons-arrow-down',
			]);
		}

		add_action( 'searchwp\settings\view\extensions', [ $this, 'render_extension_view' ] );
		add_action( 'searchwp\settings\footer',          [ $this, 'render_extensions_dropdown' ] );
	}

	/**
	 * Prime and prepare registered Extensions.
	 *
	 * @since 4.0
     *
	 * @return void
	 */
	private function prime_extensions() {

		$extensions = apply_filters( 'searchwp\extensions', [] );

		if ( ! is_array( $extensions ) || empty( $extensions ) ) {
			return;
		}

		foreach ( $extensions as $extension => $path ) {
			$class_name = 'SearchWP' . $extension;

			if ( ! class_exists( $class_name ) && file_exists( $path ) ) {
				include_once $path;
			}

			$this->extensions[ $extension ] = new $class_name( $this->extensions );

			// Add plugin row action.
			if ( isset( $this->extensions[ $extension ]->min_searchwp_version )
			     && version_compare( SEARCHWP_VERSION, $this->extensions[ $extension ]->min_searchwp_version, '<' ) ) {
				do_action( 'searchwp\debug\log', 'after_plugin_row_' . plugin_basename( $path ) );
				add_action( 'after_plugin_row_' . plugin_basename( $path ), [ $this, 'plugin_row' ], 11, 3 );
			}
		}
	}

	/**
	 * Renders the Extensions dropdown on the Options screen.
	 *
	 * @since 4.0
     *
	 * @return void
	 */
	public function render_extensions_dropdown() {

		$extensions = array_filter( (array) $this->extensions, function( $extension ) {
			return ! empty( $extension->public );
		} );

		if ( empty( $extensions ) ) {
			return;
		}

		?>
		<div id="searchwp-extensions-dropdown">
			<ul class="swp-dropdown-menu">
				<?php foreach ( $extensions as $extension ) : ?>
					<?php if ( ! empty( $extension->public ) && isset( $extension->slug ) && isset( $extension->name ) ) : ?>
						<?php
						$the_link = add_query_arg(
							[
								'page'      => 'searchwp-settings',
								'tab'       => 'extensions',
								'extension' => $extension->slug,
							],
							admin_url( 'admin.php' )
						);
						?>
						<li><a href="<?php echo esc_url( $the_link ); ?>"><?php echo esc_html( $extension->name ); ?></a></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
		<script type="text/javascript">
		jQuery(document).ready(function($){
			$('.searchwp-settings-nav-tab-extensions').after($('#searchwp-extensions-dropdown').clone());
		});
			jQuery(document).ready(function($){
				var $extensions_toggler = $('.searchwp-settings-nav-tab-extensions');
				var $extensions_dropdown = $('#searchwp-extensions-dropdown');

				$extensions_dropdown.hide();
				$extensions_toggler.data('showing',false);

				// Bind the click.
				$extensions_toggler.click(function(e){
					e.preventDefault();
					if ($extensions_toggler.data('showing')){
						$extensions_dropdown.hide();
						$extensions_toggler.data('showing',false);
						$extensions_toggler.removeClass('searchwp-showing-dropdown');
						$extensions_dropdown.removeClass('searchwp-sub-menu-active');
					} else {
						if ($extensions_toggler.hasClass('nav-tab-active')) {
							$extensions_dropdown.addClass('searchwp-sub-menu-active');
						} else {
							$extensions_dropdown.removeClass('searchwp-sub-menu-active');
						}
						$extensions_dropdown.show();
						$extensions_toggler.data('showing',true);
						$extensions_toggler.addClass('searchwp-showing-dropdown');
					}
				});
			});
		</script>
		<?php
	}

	/**
	 * Renders the view for an Extension.
	 *
	 * @since 4.0
     *
	 * @return void
	 */
	public function render_extension_view() {

		if ( empty( $this->extensions ) || ! isset( $_GET['extension'] ) ) {
			return;
		}

		// Find out which extension we're working with.
		$extension = array_filter( $this->extensions, function( $attributes, $extension ) {
			return isset( $attributes->slug ) && $attributes->slug === $_GET['extension'] && method_exists( $this->extensions[ $extension ], 'view' );
		}, ARRAY_FILTER_USE_BOTH );

		if ( empty( $extension ) ) {
			return;
		}

		reset( $extension );
		$extension  = key( $extension );
		$attributes = $this->extensions[ $extension ];
		?>
		<div class="wrap" id="searchwp-<?php echo esc_attr( $attributes->slug ); ?>-wrapper">
			<div id="icon-options-general" class="icon32"><br /></div>
			<div class="<?php echo esc_attr( $attributes->slug ); ?>-container">
				<h2>SearchWP <?php echo esc_html( $attributes->name ); ?></h2>
				<?php $this->extensions[ $extension ]->view(); ?>
			</div>
			<p class="searchwp-extension-back">
				<a href="<?php echo esc_url( admin_url( 'options-general.php?page=searchwp' ) ); ?>"><?php esc_html_e( 'Back to SearchWP Settings', 'searchwp' ); ?></a>
			</p>
		</div>
		<?php
	}
}
