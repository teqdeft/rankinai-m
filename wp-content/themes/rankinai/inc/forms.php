<?php
/**
 * Forms, through Contact Form 7
 * =============================================================================
 * Added 29 Sep 2026. The site has two forms:
 *
 *   audit    the growth audit request: website and email. In the modal on
 *            every page and on /growth-audit/ (template-parts/auditform.php).
 *   contact  the message form on /contact/: name, email, company, website,
 *            message.
 *
 * Both are Contact Form 7 forms, created by the seed and chosen on Website
 * settings. Their fields, wording and the email they send are edited in
 * Contact → Contact Forms. The form templates reproduce the flat build's
 * markup (the .field labels, .field__msg slots and the arrow button), so the
 * design is unchanged.
 *
 * THE SITE'S OWN BEHAVIOUR STAYS. Each form carries data-auditform, so
 * script.js still validates it in place before sending, and after Contact
 * Form 7 reports the mail sent it shows the same in-place confirmation, worded
 * by the <span hidden data-sent> at the end of each form template. Without
 * JavaScript, Contact Form 7 posts the form normally and shows its response
 * message, so nothing depends on script.
 *
 * FIELD NAMES. The contact form's name field is "your-name", not "name":
 * WordPress reads posted fields as query variables, and "name" would send a
 * form submitted without JavaScript to a 404.
 *
 * LOCAL DEVELOPMENT has no mail server. With RANKINAI_FORMS_SKIP_MAIL true in
 * wp-config.php, submissions are not emailed but written to debug.log, and the
 * form reports success. Leave it undefined on the live site.
 * =============================================================================
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** True when Contact Form 7 is active. */
function rankinai_cf7_active() {
	return class_exists( 'WPCF7_ContactForm' );
}

/** The Contact Form 7 form ID for 'audit' or 'contact', from Website settings,
 *  falling back to the one the seed created. */
function rankinai_form_id( $which ) {
	$id = function_exists( 'get_field' ) ? get_field( 'site_form_' . $which, 'option' ) : 0;
	if ( is_object( $id ) ) { $id = $id->ID; }
	if ( is_array( $id ) ) { $id = $id['ID'] ?? 0; }
	return (int) ( $id ?: get_option( 'rankinai_cf7_' . $which, 0 ) );
}

/**
 * A form's HTML. $id is the <form>'s id attribute, $class its classes.
 * Returns an HTML comment when the form or the plugin is missing, so a page
 * never shows a broken shortcode.
 */
function rankinai_form( $which, $id = '', $class = '' ) {
	$form_id = rankinai_form_id( $which );
	if ( ! rankinai_cf7_active() || ! $form_id || ! get_post( $form_id ) ) {
		return '<!-- The ' . esc_html( $which ) . ' form is not set up: activate Contact Form 7 and run the seed. -->';
	}
	return do_shortcode( sprintf(
		'[contact-form-7 id="%d" html_id="%s" html_class="%s"]',
		$form_id,
		esc_attr( $id ),
		esc_attr( $class )
	) );
}

/* Contact Form 7 wraps form templates in <p> and <br> by default. These
   templates carry exact markup, so no. */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/* Every form gets data-auditform, which is what script.js binds its
   validation and confirmation to. Contact Form 7 already adds novalidate. */
add_filter( 'wpcf7_form_additional_atts', function ( $atts ) {
	$atts['data-auditform'] = '';
	return $atts;
} );

/* Local: log instead of mail. See the header. */
add_filter( 'wpcf7_skip_mail', function ( $skip, $form ) {
	if ( defined( 'RANKINAI_FORMS_SKIP_MAIL' ) && RANKINAI_FORMS_SKIP_MAIL ) {
		$sub = class_exists( 'WPCF7_Submission' ) ? WPCF7_Submission::get_instance() : null;
		error_log( '[RankinAI form, mail skipped] ' . $form->title() . ': ' . wp_json_encode( $sub ? $sub->get_posted_data() : array() ) );
		return true;
	}
	return $skip;
}, 10, 2 );

/* -----------------------------------------------------------------------------
 * The two forms, as the seed creates them.
 * -------------------------------------------------------------------------- */
function rankinai_form_arrow() {
	return '<svg class="btn__arrow" viewBox="0 0 16 16" width="16" height="16" fill="none" aria-hidden="true" focusable="false"><path class="btn__arrow-shaft" d="M1.5 8H12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path class="btn__arrow-head" d="M8.6 4.6 12 8l-3.4 3.4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

function rankinai_form_definitions() {
	$to    = rankinai_site( 'email' );
	$arrow = rankinai_form_arrow();
	return array(
		'audit' => array(
			'title' => 'Growth audit request',
			'form'  => '<div class="afx__fields">
<label class="field">
<span class="label">Website URL</span>
[text* website autocomplete:url placeholder "Your website address"]
<span class="field__msg" data-msg></span>
</label>
<label class="field">
<span class="label">Email address</span>
[email* email autocomplete:email placeholder "Where should we send your audit?"]
<span class="field__msg" data-msg></span>
</label>
</div>
<button class="btn btn--cream btn--lg" type="submit">Get your growth audit ' . $arrow . '</button>
<p class="auditform__note">Free. No obligation. No call required.</p>
<span hidden data-sent data-sent-label="Sent" data-sent-text="Your audit is on its way. We&rsquo;ll reply from a real address within one working day."></span>',
			'mail'  => array(
				'subject'            => 'Growth audit request: [website]',
				'sender'             => '[_site_title] <wordpress@[_site_domain]>',
				'recipient'          => $to,
				'body'               => "A growth audit has been requested.\n\nWebsite: [website]\nEmail: [email]\n\n--\nSent from [_url]",
				'additional_headers' => 'Reply-To: [email]',
				'attachments'        => '',
				'use_html'           => false,
				'exclude_blank'      => false,
			),
		),
		'contact' => array(
			'title' => 'Contact message',
			'form'  => '<div class="auditform__grid">
<label class="field">
<span class="label">Your name</span>
[text* your-name autocomplete:name placeholder "Full name"]
<span class="field__msg" data-msg></span>
</label>
<label class="field">
<span class="label">Work email</span>
[email* email autocomplete:email placeholder "Email address"]
<span class="field__msg" data-msg></span>
</label>
<label class="field">
<span class="label">Company</span>
[text company autocomplete:organization placeholder "Company name"]
<span class="field__msg" data-msg></span>
</label>
<label class="field">
<span class="label">Website</span>
[text website autocomplete:url placeholder "yourcompany.com"]
<span class="field__msg" data-msg></span>
</label>
<label class="field field--wide">
<span class="label">What would you like to discuss?</span>
[textarea* message x5 placeholder "Tell us what you want to achieve, what needs attention, or what you’d like to ask."]
<span class="field__msg" data-msg></span>
</label>
</div>
<button class="btn btn--primary" type="submit">Send your message ' . $arrow . '</button>
<p class="auditform__note">We&rsquo;ll review your message and reply by email. Sending an enquiry creates no obligation to proceed.</p>
<span hidden data-sent data-sent-label="Message received" data-sent-text="Your message has reached our team, and we&rsquo;ll reply to the email address you provided."></span>',
			'mail'  => array(
				'subject'            => 'Message from [your-name]',
				'sender'             => '[_site_title] <wordpress@[_site_domain]>',
				'recipient'          => $to,
				'body'               => "Name: [your-name]\nEmail: [email]\nCompany: [company]\nWebsite: [website]\n\n[message]\n\n--\nSent from [_url]",
				'additional_headers' => 'Reply-To: [email]',
				'attachments'        => '',
				'use_html'           => false,
				'exclude_blank'      => false,
			),
		),
	);
}

/** Create the two forms if missing (or rewrite them when $force), and point
 *  Website settings at them. */
function rankinai_seed_forms( $force = false ) {
	if ( ! rankinai_cf7_active() ) { return 'forms: Contact Form 7 is not active'; }
	$log = array();
	foreach ( rankinai_form_definitions() as $which => $def ) {
		$id = (int) get_option( 'rankinai_cf7_' . $which, 0 );
		if ( $id && get_post( $id ) && ! $force ) {
			$log[] = "form/$which: exists, left alone";
		} else {
			$cf = ( $id && get_post( $id ) ) ? WPCF7_ContactForm::get_instance( $id ) : WPCF7_ContactForm::get_template();
			$cf->set_title( $def['title'] );
			$props         = $cf->get_properties();
			$props['form'] = $def['form'];
			$props['mail'] = array_merge( (array) $props['mail'], $def['mail'] );
			$cf->set_properties( $props );
			$id = (int) $cf->save();
			update_option( 'rankinai_cf7_' . $which, $id );
			$log[] = "form/$which: " . ( $force ? 'written' : 'created' ) . " (#$id)";
		}
		if ( function_exists( 'update_field' ) ) { update_field( 'site_form_' . $which, $id, 'option' ); }
	}
	return implode( "\n", $log );
}
