<?php
/*
Plugin Name: Ad free Google Safe Search for Schools
Plugin URI: http://mclear.co.uk
Description: Put an ad free Google Safe Search for Schools onto your wordpress blog.
Author: John McLear at Primary Blogger
Version: 0.3
Author URI: http://mclear.co.uk

This widget is released under the GNU General Public License (GPL)
http://www.gnu.org/licenses/gpl.txt.

This is a WordPress plugin (http://wordpress.org) and widget
(http://automattic.com/code/widgets/).
*/

// We're putting the plugin's functions in one big function we then
// call at 'plugins_loaded' (add_action() at bottom) to ensure the
// required Sidebar Widget functions are available.
function widget_safewidget_init() {

	// Check to see required Widget API functions are defined...
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return; // ...and if not, exit gracefully from the script.

	// This function prints the sidebar widget--the cool stuff!
	function widget_safewidget($args) {

		// $args is an array of strings which help your widget
		// conform to the active theme: before_widget, before_title,
		// after_widget, and after_title are the array keys.
		extract($args);

		// Collect our widget's options, or define their defaults.
		$options = get_option('widget_safewidget');
		$title = empty($options['title']) ? 'Safe Search' : $options['title'];
		$text = empty($options['text']) ? '<div style="padding-left:10px;">
		<form action="http://www.google.com/cse" id="cse-search-box">
		<div><input type="hidden" name="cx"
		value="000753778363423014722:xie0vouxf9m" />
		<input type="hidden" name="ie" value="UTF-8" />
		<input type="text" name="q" size="20" />
		<input type="submit" name="sa" value="Safe Google Search"/>
		<input type="hidden" name="safe" value="strict"/>
		</div></form><script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&lang=en">
		</script></div>' : $options['text'];


 		// It's important to use the $before_widget, $before_title,
 		// $after_title and $after_widget variables in your output.
		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo $text;
		echo $after_widget;
	}

	// This is the function that outputs the form to let users edit
	// the widget's title and so on. It's an optional feature, but
	// we'll use it because we can!
	function widget_safewidget_control() {

		// Collect our widget's options.
		$options = get_option('widget_safewidget');

		// This is for handing the control form submission.
		if ( $_POST['safewidget-submit'] ) {
			// Clean up control form submission options
			$newoptions['title'] = strip_tags(stripslashes($_POST['safewidget-title']));
			$newoptions['text'] = strip_tags(stripslashes($_POST['safewidget-text']));
		}

		// If original widget options do not match control form
		// submission options, update them.
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_safewidget', $options);
		}

		// Format options as valid HTML. Hey, why not.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$text = htmlspecialchars($options['text'], ENT_QUOTES);

// The HTML below is the control form for editing options.
?>
		<div>
		<label for="safewidget-title" style="line-height:35px;display:block;">Widget title: <input type="text" id="safewidget-title" name="safewidget-title" value="<?php echo $title; ?>" /></label>
		<input type="hidden" name="safewidget-submit" id="safewidget-submit" value="1" />
		</div>
	<?php
	// end of widget_safewidget_control()
	}

	// This registers the widget. About time.
	register_sidebar_widget('Safe Google Search', 'widget_safewidget');

	// This registers the (optional!) widget control form.
	register_widget_control('Safe Google Search', 'widget_safewidget_control');
}

// Delays plugin execution until Dynamic Sidebar has loaded first.
add_action('plugins_loaded', 'widget_safewidget_init');
?>
