<?php
/**
 * Plugin Name: Custom Upstream Starter Content
 * Description: Seeds a new site with a home page and starter pages the first time it runs.
 * Author: cms-qa
 * Version: 1.0.0
 *
 * Pages live in the database, not in this repository. A custom upstream only
 * ships code, so every site created from it starts with an empty database.
 * This must-use plugin runs once, on the first request after WordPress is
 * installed, and creates the pages below.
 *
 * It is deliberately conservative: it only seeds a site that still looks
 * untouched, so pulling this upstream into an existing site will not inject
 * pages into live content. See cu_site_looks_fresh().
 */

namespace CustomUpstream\StarterContent;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bumping this does NOT re-seed existing sites. Seeding happens only when the
 * option is absent entirely, so a site can never be seeded twice.
 */
const SEED_VERSION = '1.0.0';
const SEED_OPTION  = 'custom_upstream_seed_version';

/**
 * Move WordPress's own "Sample Page" and "Hello world!" placeholders to the
 * trash after seeding. They are only trashed (recoverable), never force-deleted,
 * and only when they are still the unmodified defaults.
 */
const TRASH_DEFAULT_CONTENT = true;

/** Set pretty permalinks (/%postname%/) on seed. Set to false to leave as-is. */
const SET_PERMALINK_STRUCTURE = true;

/**
 * The pages to create.
 *
 * Edit this array to change what every new site gets. Keys:
 *   slug     - URL slug, also used to detect an already-created page.
 *   title    - Page title.
 *   content  - Block markup. Kept theme-agnostic (no theme preset variables)
 *              so it renders correctly under any theme, not just the default.
 *   front    - true on exactly one page; becomes the site's front page.
 *   blog     - true on at most one page; becomes the posts page.
 *   order    - menu_order, controls navigation ordering.
 *
 * @return array<int, array<string, mixed>>
 */
function pages(): array {
	return [
		[
			'slug'  => 'home',
			'title' => 'Home',
			'front' => true,
			'order' => 0,
			'content' => hero_block()
				. features_block()
				. closing_block(),
		],
		[
			'slug'  => 'about',
			'title' => 'About',
			'order' => 1,
			'content' => simple_page_block(
				'About us',
				'Tell your story here. This page was created automatically when the site was provisioned from the custom upstream, so every new site starts with the same structure.'
			),
		],
		[
			'slug'  => 'services',
			'title' => 'Services',
			'order' => 2,
			'content' => simple_page_block(
				'What we do',
				'Describe your services here. Replace this placeholder copy with the real thing.'
			),
		],
		[
			'slug'  => 'contact',
			'title' => 'Contact',
			'order' => 3,
			'content' => simple_page_block(
				'Get in touch',
				'Add your address, phone number, or a contact form plugin shortcode here.'
			),
		],
		[
			'slug'  => 'blog',
			'title' => 'Blog',
			'blog'  => true,
			'order' => 4,
			// The posts page ignores its own content; WordPress renders the post
			// loop instead. Left empty on purpose.
			'content' => '',
		],
	];
}

/**
 * Hero section for the front page.
 */
function hero_block(): string {
	return <<<'HTML'
<!-- wp:group {"align":"full","style":{"color":{"background":"#111827","text":"#ffffff"},"spacing":{"padding":{"top":"6rem","bottom":"6rem","left":"1.5rem","right":"1.5rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-text-color has-background" style="background-color:#111827;color:#ffffff;padding-top:6rem;padding-right:1.5rem;padding-bottom:6rem;padding-left:1.5rem"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"3rem","lineHeight":"1.1"}}} -->
<h1 class="wp-block-heading has-text-align-center" style="font-size:3rem;line-height:1.1">Welcome to your new site</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.2rem"},"spacing":{"margin":{"top":"1.5rem"}}}} -->
<p class="has-text-align-center" style="margin-top:1.5rem;font-size:1.2rem">This home page shipped with the custom upstream. Every site created from it starts here.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"2.5rem"}}}} -->
<div class="wp-block-buttons" style="margin-top:2.5rem"><!-- wp:button {"backgroundColor":"white","textColor":"black"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-black-color has-white-background-color has-text-color has-background wp-element-button" href="/about/">Learn more</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/contact/">Contact us</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
HTML;
}

/**
 * Three-column feature row for the front page.
 */
function features_block(): string {
	return <<<'HTML'
<!-- wp:group {"style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem","left":"1.5rem","right":"1.5rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:4rem;padding-right:1.5rem;padding-bottom:4rem;padding-left:1.5rem"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Consistent</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Every site provisioned from this upstream starts with the same pages and the same front page configuration.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Editable</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>These are ordinary WordPress pages. Edit or delete them per site without touching the upstream.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Safe</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Seeding runs once, only on a site that is still empty, so existing sites are never modified.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
HTML;
}

/**
 * Closing call-to-action for the front page.
 */
function closing_block(): string {
	return <<<'HTML'
<!-- wp:group {"align":"full","style":{"color":{"background":"#f3f4f6"},"spacing":{"padding":{"top":"4rem","bottom":"4rem","left":"1.5rem","right":"1.5rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background" style="background-color:#f3f4f6;padding-top:4rem;padding-right:1.5rem;padding-bottom:4rem;padding-left:1.5rem"><!-- wp:heading {"textAlign":"center","level":2} -->
<h2 class="wp-block-heading has-text-align-center">Ready to customise?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Open the Site Editor to change the design, or edit this page directly to change the words.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
HTML;
}

/**
 * A plain heading + paragraph page body.
 */
function simple_page_block( string $heading, string $body ): string {
	return sprintf(
		'<!-- wp:heading -->' . "\n" . '<h2 class="wp-block-heading">%s</h2>' . "\n" . '<!-- /wp:heading -->'
			. "\n\n" . '<!-- wp:paragraph -->' . "\n" . '<p>%s</p>' . "\n" . '<!-- /wp:paragraph -->',
		esc_html( $heading ),
		esc_html( $body )
	);
}

add_action( 'init', __NAMESPACE__ . '\\maybe_seed', 999 );

/**
 * Decide whether to seed, then do it. Runs on every request but short-circuits
 * on an autoloaded option lookup, so the steady-state cost is negligible.
 */
function maybe_seed(): void {
	// The installer runs before the database is ready for content.
	if ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) {
		return;
	}

	if ( ! is_blog_installed() ) {
		return;
	}

	// Already seeded. This is the normal path on every request after the first.
	if ( get_option( SEED_OPTION ) ) {
		return;
	}

	if ( ! site_looks_fresh() ) {
		// Existing site pulling this upstream in. Record that we were here so we
		// never reconsider, but leave the content alone.
		update_option( SEED_OPTION, SEED_VERSION, true );
		return;
	}

	// Claim the seed before doing the work, so two concurrent requests during
	// the first page load cannot both create the page set.
	if ( ! add_option( SEED_OPTION, SEED_VERSION, '', true ) ) {
		return;
	}

	seed();
}

/**
 * Content WordPress creates by itself during installation. Their presence does
 * not mean the site has been worked on.
 *
 * Note that a fresh install has TWO pages, not one: "Sample Page" plus a draft
 * "Privacy Policy". The privacy page is matched by ID rather than slug because
 * its slug is localised on non-English installs.
 */
const DEFAULT_PAGE_SLUGS = [ 'sample-page' ];
const DEFAULT_POST_SLUGS = [ 'hello-world' ];

/**
 * Is this site still untouched?
 *
 * Returns false as soon as any content exists that WordPress did not create
 * during installation, which means a human has already worked on this site and
 * we must not touch it.
 */
function site_looks_fresh(): bool {
	if ( get_option( 'page_on_front' ) || 'posts' !== get_option( 'show_on_front', 'posts' ) ) {
		return false;
	}

	$privacy_id = (int) get_option( 'wp_page_for_privacy_policy' );

	$pages = (array) get_posts(
		[
			'post_type'        => 'page',
			'post_status'      => 'any',
			'numberposts'      => 10,
			'suppress_filters' => true,
		]
	);

	foreach ( $pages as $page ) {
		if ( $privacy_id && (int) $page->ID === $privacy_id ) {
			continue;
		}

		if ( in_array( $page->post_name, DEFAULT_PAGE_SLUGS, true ) ) {
			continue;
		}

		return false;
	}

	$posts = (array) get_posts(
		[
			'post_type'        => 'post',
			'post_status'      => 'any',
			'numberposts'      => 10,
			'suppress_filters' => true,
		]
	);

	foreach ( $posts as $post ) {
		if ( ! in_array( $post->post_name, DEFAULT_POST_SLUGS, true ) ) {
			return false;
		}
	}

	return true;
}

/**
 * Create the pages and point the site at them.
 */
function seed(): void {
	$front_id = 0;
	$blog_id  = 0;

	foreach ( pages() as $page ) {
		$existing = get_page_by_path( $page['slug'], OBJECT, 'page' );

		if ( $existing instanceof \WP_Post ) {
			$post_id = (int) $existing->ID;
		} else {
			$post_id = wp_insert_post(
				[
					'post_type'      => 'page',
					'post_status'    => 'publish',
					'post_title'     => $page['title'],
					'post_name'      => $page['slug'],
					'post_content'   => $page['content'],
					'menu_order'     => $page['order'],
					'comment_status' => 'closed',
					'ping_status'    => 'closed',
				],
				true
			);
		}

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		if ( ! empty( $page['front'] ) ) {
			$front_id = (int) $post_id;
		}

		if ( ! empty( $page['blog'] ) ) {
			$blog_id = (int) $post_id;
		}
	}

	if ( $front_id ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_id );
	}

	if ( $blog_id ) {
		update_option( 'page_for_posts', $blog_id );
	}

	if ( SET_PERMALINK_STRUCTURE ) {
		set_permalinks();
	}

	if ( TRASH_DEFAULT_CONTENT ) {
		trash_default_content();
	}
}

/**
 * Switch to /%postname%/ permalinks and write the rewrite rules.
 */
function set_permalinks(): void {
	global $wp_rewrite;

	if ( ! $wp_rewrite instanceof \WP_Rewrite ) {
		return;
	}

	$wp_rewrite->set_permalink_structure( '/%postname%/' );
	$wp_rewrite->flush_rules( false );
}

/**
 * Trash WordPress's built-in placeholders, but only if they are untouched.
 *
 * Uses wp_trash_post() rather than wp_delete_post() so the action is
 * recoverable from the trash by the site owner.
 */
function trash_default_content(): void {
	$sample_page = get_page_by_path( 'sample-page', OBJECT, 'page' );

	if ( $sample_page instanceof \WP_Post && 'trash' !== $sample_page->post_status ) {
		wp_trash_post( $sample_page->ID );
	}

	$hello = get_posts(
		[
			'post_type'        => 'post',
			'post_status'      => 'publish',
			'name'             => 'hello-world',
			'numberposts'      => 1,
			'suppress_filters' => true,
		]
	);

	if ( ! empty( $hello[0] ) ) {
		wp_trash_post( $hello[0]->ID );
	}
}
