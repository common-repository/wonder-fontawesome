<?php
/**
 * Plugin Name: Wonder FontAwesome
 * Description: Adds Font Awesome 6 Free Shortcodes
 * Version:     0.8
 * Author:      Wonderjar Creative
 * Author URI:  wonderjarcreative.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


// no direct access
defined( 'ABSPATH' ) or die( 'No direct access!' );


/**
 * Wonder FontAwesome Sub-Menu
 *
 * add sub-menu to wp admin menu
 */
function wonder_fontawesome_submenu() {
	add_submenu_page(
		'options-general.php',
		'Wonder FontAwesome',
		'Wonder FontAwesome',
		'manage_options',
		'wonder_fontawesome_menu',
		'wonder_fontawesome_show_menu'
	);
}
add_action( 'admin_menu', 'wonder_fontawesome_submenu' );


/**
 * Submenu Callback
 *
 * hooked: admin_menu
 */
function wonder_fontawesome_show_menu() {
	// check user capabilities
	if ( !current_user_can('manage_options') ) {
		return;
	}

	do_action( 'wonder_fontawesome_menu_form_catch' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<h2><?php echo __( 'Usage:', 'tiresocks-block-theme' ); ?></h2>
		<p><?php echo __( 'Use the base shortcode <code>[wonder-fontawesome]</code> to add a free, solid or brand font awesome icon within your content', 'tiresocks-block-theme' ); ?></p>
		<p><b><?php echo __( 'Default Atts:', 'tiresocks-block-theme' ); ?></b></p>
		<pre><?php echo __( '<strong>icon="plus"<br>tag="i"<br>margin="0 0 0 0"<br>font_size="1em"</strong>', 'tiresocks-block-theme' ); ?></pre>
		<p><?php echo __( 'Example: <code>[wonder-fontawesome icon="facebook" margin="0 0.25em 0 0" font_size="1.1em"]', 'tiresocks-block-theme' ); ?></code></p>
		<br>
		<p><b><?php echo __( 'Resources:', 'tiresocks-block-theme' ); ?></b></p>
		<ul>
			<li><a href="https://fontawesome.com/icons?d=gallery&m=free"><?php echo __( 'FontAwesome Free Icons', 'tiresocks-block-theme' ); ?></a></li>
			<li><a href="https://fontawesome.com/icons?d=gallery&s=brands&m=free"><?php echo __( 'FontAwesome Free Brand Icons', 'tiresocks-block-theme' ); ?></a></li>
		</ul>
		<br>
		<br>
		<h2><?php echo __( 'NEW - Have your own pro kit?', 'tiresocks-block-theme' ); ?></h2>
		<p><?php echo __( 'You can now use font awesome pro kit\'s within wonder-fontaweomse shortcodes. Enter the "id" of your kit below.', 'tiresocks-block-theme' ); ?></p>
		<form method="post">
			<?php 
			$maybe_default = get_option( 'wonder-fontawesome-kit-id' );
			?>
			<label for="kit-id"><?php echo __( 'Kit ID', 'tiresocks-block-theme' ); ?></label>
			<input type="text" name="kit-id" id="kit-id" class="input-text" value="<?php echo $maybe_default; ?>" />
			<input type="submit" value="submit" />
		</form>
	</div>
	<?php
}


/**
 * Menu form catch
 */
function catch_form_options() {
	if ( isset ( $_POST['kit-id'] ) ) {
		update_option( 'wonder-fontawesome-kit-id', sanitize_text_field( $_POST['kit-id'] ) );
	}
}
add_action( 'wonder_fontawesome_menu_form_catch', 'catch_form_options' );


/**
 * Enqueue fontawesome style.
 * 
 * @version 0.8 adding admin_enqueue_scripts
 */
function wonder_fontawesome_enqueue_scripts() {
	$kit_id = get_option( 'wonder-fontawesome-kit-id', false );

	if ( false !== $kit_id ) {
		wp_enqueue_script( 'fontawesome-kit', 'https://kit.fontawesome.com/' . $kit_id . '.js', array(), '', true );
	} else {
		// most current fontawesome cdn
		wp_enqueue_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'wonder_fontawesome_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'wonder_fontawesome_enqueue_scripts' );


/**
 * Wonder FontAwesome Shortcode
 *
 * add shortcode [wonder-fontawesome]
 */
function wonder_fontawesome_shortcode( $atts ) {
	$a = shortcode_atts( array(
		'icon' => 'plus',
		'tag' => 'i',
		'margin' => '0',
		'font_size' => '1em',
		'color' => 'inherit'
	), $atts );

	$icon = esc_attr( $a['icon'] );
	$tag = esc_attr( $a['tag'] );
	$margin = esc_attr( $a['margin'] );
	$font_size = esc_attr( $a['font_size'] );
	$color = esc_attr( $a['color'] );

	$style = 'margin:' . $margin . ';font-size:' . $font_size . ';color:' . $color . ';';

	if ( in_array( $icon, wonder_fontawesome_brand_icons() ) ) {
		$return = '<' . $tag . ' class="fab fa-' . $icon . '" style="' . $style . '"></' . $tag . '>';
	} else {
		$return = '<' . $tag . ' class="fas fa-' . $icon . '" style="' . $style . '"></' . $tag . '>';
	}

	return $return;
}
add_shortcode( 'wonder-fontawesome', 'wonder_fontawesome_shortcode' );


/**
 * Wonder FontAwesome Brands
 *
 * Return array of all brand icons
 * https://fontawesome.com/icons?d=listing&s=brands
 */
function wonder_fontawesome_brand_icons() {

	$brands = array( '500px', 'accessible-icon', 'accusoft', 'acquisitions-incorporated', 'adn', 'adobe', 'adversal', 'affiliatetheme', 'airbnb', 'algolia', 'alipay', 'amazon', 'amazon-pay', 'amilia', 'android', 'angellist', 'angrycreative', 'angular', 'app-store', 'app-store-ios', 'apper', 'apple', 'apple-pay', 'artstation', 'asymmetrik', 'atlassian', 'audible', 'autoprefixer', 'avianex', 'aviato', 'aws', 'bandcamp', 'battle-net', 'behance', 'behance-square', 'square-behance', 'bimobject', 'bitbucket', 'bitcoin', 'bity', 'black-tie', 'blackberry', 'blogger', 'blogger-b', 'bluetooth', 'bluetooth-b', 'bootstrap', 'btc', 'buffer', 'buromobelexperte', 'buy-n-large', 'buysellads', 'canadian-maple-leaf', 'cc-amazon-pay', 'cc-amex', 'cc-apple-pay', 'cc-diners-club', 'cc-discover', 'cc-jcb', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'centercode', 'centos', 'chrome', 'chromecast', 'cloudscale', 'cloudsmith', 'cloudversify', 'codepen', 'codiepie', 'confluence', 'connectdevelop', 'contao', 'cotton-bureau', 'cpanel', 'creative-commons', 'creative-commons-by', 'creative-commons-nc', 'creative-commons-nc-eu', 'creative-commons-nc-jp', 'creative-commons-nd', 'creative-commons-pd', 'creative-commons-pd-alt', 'creative-commons-remix', 'creative-commons-sa', 'creative-commons-sampling', 'creative-commons-sampling-plus', 'creative-commons-share', 'creative-commons-zero', 'critical-role', 'css3', 'css3-alt', 'cuttlefish', 'd-and-d', 'd-and-d-beyond', 'dailymotion', 'dashcube', 'delicious', 'deploydog', 'deskpro', 'dev', 'deviantart', 'dhl', 'diaspora', 'digg', 'digital-ocean', 'discord', 'discourse', 'dochub', 'docker', 'draft2digital', 'dribbble', 'dribbble-square', 'square-dribble', 'dropbox', 'drupal', 'dyalog', 'earlybirds', 'ebay', 'edge', 'elementor', 'ello', 'ember', 'empire', 'envira', 'erlang', 'ethereum', 'etsy', 'evernote', 'expeditedssl', 'facebook', 'facebook-f', 'facebook-messenger', 'facebook-square', 'square-facebook', 'fantasy-flight-games', 'fedex', 'fedora', 'figma', 'firefox', 'firefox-browser', 'first-order', 'first-order-alt', 'firstdraft', 'flickr', 'flipboard', 'fly', 'font-awesome', 'font-awesome-alt', 'font-awesome-flag', 'fonticons', 'fonticons-fi', 'fort-awesome', 'fort-awesome-alt', 'forumbee', 'foursquare', 'free-code-camp', 'freebsd', 'fulcrum', 'galactic-republic', 'galactic-senate', 'get-pocket', 'gg', 'gg-circle', 'git', 'git-alt', 'git-square', 'square-git', 'github', 'github-alt', 'github-square', 'square-github', 'gitkraken', 'gitlab', 'gitter', 'glide', 'glide-g', 'gofore', 'goodreads', 'goodreads-g', 'google', 'google-drive', 'google-play', 'google-plus', 'google-plus-g', 'google-plus-square', 'square-google-plus', 'google-wallet', 'gratipay', 'grav', 'gripfire', 'grunt', 'gulp', 'hacker-news', 'hacker-news-square', 'square-hacker-news', 'hackerrank', 'hips', 'hire-a-helper', 'hooli', 'hornbill', 'hotjar', 'houzz', 'html5', 'hubspot', 'ideal', 'imdb', 'instagram', 'instagram-square', 'square-instagram', 'intercom', 'internet-explorer', 'invision', 'ioxhost', 'itch-io', 'itunes', 'itunes-note', 'java', 'jedi-order', 'jenkins', 'jira', 'joget', 'joomla', 'js', 'js-square', 'square-js', 'jsfiddle', 'kaggle', 'keybase', 'keycdn', 'kickstarter', 'kickstarter-k', 'korvue', 'laravel', 'lastfm', 'lastfm-square', 'square-lastfm', 'leanpub', 'less', 'line', 'linkedin', 'linkedin-in', 'linode', 'linux', 'lyft', 'magento', 'mailchimp', 'mandalorian', 'markdown', 'mastodon', 'maxcdn', 'mdb', 'medapps', 'medium', 'medium-m', 'medrt', 'meetup', 'megaport', 'mendeley', 'microblog', 'microsoft', 'mix', 'mixcloud', 'mixer', 'mizuni', 'modx', 'monero', 'napster', 'neos', 'nimblr', 'node', 'node-js', 'npm', 'ns8', 'nutritionix', 'odnoklassniki', 'odnoklassniki-square', 'square-odnoklassniki', 'old-republic', 'opencart', 'openid', 'opera', 'optin-monster', 'orcid', 'osi', 'page4', 'pagelines', 'palfed', 'patreon', 'paypal', 'penny-arcade', 'periscope', 'phabricator', 'phoenix-framework', 'phoenix-squadron', 'php', 'pied-piper', 'pied-piper-alt', 'pied-piper-hat', 'pied-piper-pp', 'pied-piper-square', 'square-pied-piper', 'pinterest', 'pinterest-p', 'pinterest-square', 'square-pinterest', 'playstation', 'product-hunt', 'pushed', 'python', 'qq', 'quinscape', 'quora', 'r-project', 'raspberry-pi', 'ravelry', 'react', 'reacteurope', 'readme', 'rebel', 'red-river', 'reddit', 'reddit-alien', 'reddit-square', 'square-reddit', 'redhat', 'renren', 'replyd', 'researchgate', 'resolving', 'rev', 'rocketchat', 'rockrms', 'safari', 'salesforce', 'sass', 'schlix', 'scribd', 'searchengin', 'sellcast', 'sellsy', 'servicestack', 'shirtsinbulk', 'shopify', 'shopware', 'simplybuilt', 'sistrix', 'sith', 'sketch', 'skyatlas', 'skype', 'slack', 'slack-hash', 'slideshare', 'snapchat', 'snapchat-ghost', 'snapchat-square', 'square-snapchat', 'soundcloud', 'sourcetree', 'speakap', 'speaker-deck', 'spotify', 'squarespace', 'stack-exchange', 'stack-overflow', 'stackpath', 'staylinked', 'steam', 'steam-square', 'square-steam', 'steam-symbol', 'sticker-mule', 'strava', 'stripe', 'stripe-s', 'studiovinari', 'stumbleupon', 'stumbleupon-circle', 'superpowers', 'supple', 'suse', 'swift', 'symfony', 'teamspeak', 'telegram', 'telegram-plane', 'tencent-weibo', 'the-red-yeti', 'themeco', 'themeisle', 'think-peaks', 'trade-federation', 'trello', 'tripadvisor', 'tumblr', 'tumblr-square', 'square-tumblr', 'twitch', 'twitter', 'twitter-square', 'square-twitter', 'typo3', 'uber', 'ubuntu', 'uikit', 'umbraco', 'uniregistry', 'unity', 'untappd', 'ups', 'usb', 'usps', 'ussunnah', 'vaadin', 'viacoin', 'viadeo', 'viadeo-square', 'square-viadeo', 'viber', 'vimeo', 'vimeo-square', 'square-vimeo', 'vimeo-v', 'vine', 'vk', 'vnv', 'vuejs', 'waze', 'weebly', 'weibo', 'weixin', 'whatsapp', 'whatsapp-square', 'square-whatsapp', 'whmcs', 'wikipedia-w', 'windows', 'wix', 'wizards-of-the-coast', 'wolf-pack-battalion', 'wordpress', 'wordpress-simple', 'wpbeginner', 'wpexplorer', 'wpforms', 'wpressr', 'xbox', 'xing', 'xing-square', 'square-xing', 'y-combinator', 'yahoo', 'yammer', 'yandex', 'yandex-international', 'yarn', 'yelp', 'yoast', 'youtube', 'youtube-square', 'square-youtube', 'zhihu' );

	return $brands;
}