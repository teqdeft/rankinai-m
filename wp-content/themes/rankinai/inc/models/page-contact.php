<?php
/**
 * Model: the contact page (/contact/), rendered by page-templates/contact.php.
 * Content copied word for word from the flat build's contact.php. The email,
 * phone and address are not here: they are site-wide settings, read from
 * $SITE by the template, as on the flat build.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$two   = array( 'Label', 'Link' );

return array(
	'name'      => 'contact',
	'post_type' => 'page',
	'template'  => 'page-templates/contact.php',
	'label'     => 'Contact page',
	'prefix'    => 'pg_contact',
	'schema'    => array(
		'hero'    => array( 'group', 'Hero', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'h1'      => array( 'text', 'Headline' ),
			'sub'     => array( 'area', 'Subhead' ),
			'sub2'    => array( 'area', 'Second line' ),
		) ),
		'routesHead' => array( 'group', 'Next step: section head', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'heading' => array( 'text', 'Heading' ),
		) ),
		'routes'  => array( 'blocks', 'Next step: cards', array(
			'image'  => array( 'image', 'Image' ),
			'alt'    => array( 'text', 'Image description' ),
			'width'  => array( 'text', 'Image width (px)' ),
			'height' => array( 'text', 'Image height (px)' ),
			'said'   => array( 'text', 'What the reader says' ),
			'name'   => array( 'text', 'Heading' ),
			'text'   => array( 'area', 'Text' ),
			'note'   => array( 'text', 'Note' ),
			'btn'    => array( 'slots', 'Button', $two ),
		) ),
		'message' => array( 'group', 'Send us a message', array(
			'eyebrow'   => array( 'text', 'Eyebrow' ),
			'heading'   => array( 'text', 'Heading' ),
			'note'      => array( 'area', 'Note' ),
			'image'     => array( 'image', 'Photograph' ),
			'alt'       => array( 'text', 'Photograph description' ),
			'width'     => array( 'text', 'Photograph width (px)' ),
			'height'    => array( 'text', 'Photograph height (px)' ),
			// The form's fields, button, note and confirmation are the Contact
			// Form 7 form "Contact message" (inc/forms.php), edited under Contact.
		) ),
		'reach'   => array( 'group', 'Other reasons to get in touch', array(
			'eyebrow'     => array( 'text', 'Eyebrow' ),
			'heading'     => array( 'text', 'Heading' ),
			'emailLabel'  => array( 'text', 'Email label', 'The address itself is a site setting.' ),
			'phoneLabel'  => array( 'text', 'Phone label', 'The number itself is a site setting.' ),
			'officeLabel' => array( 'text', 'Office label', 'The address itself is a site setting.' ),
			'image'       => array( 'image', 'Photograph' ),
			'alt'         => array( 'text', 'Photograph description' ),
			'width'       => array( 'text', 'Photograph width (px)' ),
			'height'      => array( 'text', 'Photograph height (px)' ),
			'reasons'     => array( 'rows', 'Reasons', array( 'icon', 'Name', 'Text' ) ),
		) ),
	),
	'items'     => array(
		'contact' => array(
			'title'      => 'Contact',
			'hide_close' => true,
			'seo'        => array(
				'Contact RankinAI, tell us what you would like to change | RankinAI',
				'Ask a question, request a free growth audit, or book a 20-minute call. Tell us where you are and what you want to achieve.',
			),
			'data'       => array(
				'hero'       => array(
					'eyebrow' => 'Contact RankinAI',
					'h1'      => 'What would you like to change?',
					'sub'     => 'More enquiries for a particular service? Better-fit clients? A marketing plan you can finally see working together?',
					'sub2'    => 'Tell us where you are and what you want to achieve. We&rsquo;ll help you identify a useful next step.',
				),
				'routesHead' => array(
					'eyebrow' => 'Choose your next step',
					'heading' => 'Start wherever feels useful.',
				),
				'routes'     => array(
					array(
						'image'  => 'audit-report.webp',
						'alt'    => 'A printed report with charts and a table, beside a pencil',
						'width'  => '1600',
						'height' => '1067',
						'said'   => '&ldquo;I&rsquo;d like to see what needs improving.&rdquo;',
						'name'   => 'Get your growth audit',
						'text'   => 'Share your website and what you&rsquo;d like to achieve. We&rsquo;ll review your public presence and enquiry journey, then send the clearest opportunities and suggested priorities in writing.',
						'note'   => 'You can decide what to do next after reading it.',
						'btn'    => array( 'Get your growth audit', '/growth-audit/' ),
					),
					array(
						'image'  => 'team-desk.webp',
						'alt'    => 'A member of the RankinAI team at a desk in the office',
						'width'  => '840',
						'height' => '600',
						'said'   => '&ldquo;I&rsquo;d rather talk it through.&rdquo;',
						'name'   => 'Book a 20-minute call',
						'text'   => 'Bring the question, challenge or goal on your mind. We&rsquo;ll discuss your situation, share an initial perspective, and see whether our support could be useful.',
						'note'   => 'A conversation about your business. No presentation to prepare.',
						'btn'    => array( 'Book a 20-minute call', '/call/' ),
					),
				),
				'message'    => array(
					'eyebrow'   => 'Send us a message',
					'heading'   => 'A question first? Go ahead.',
					'note'      => 'You don&rsquo;t need a finished brief. A few details about your business and what you&rsquo;re considering are enough to start.',
					'image'     => 'team-desks.webp',
					'alt'       => 'Colleagues working at their desks in the RankinAI office',
					'width'     => '1000',
					'height'    => '600',
				),
				'reach'      => array(
					'eyebrow'     => 'Other reasons to get in touch',
					'heading'     => 'You can reach us directly.',
					'emailLabel'  => 'Email',
					'phoneLabel'  => 'Phone',
					'officeLabel' => 'Office',
					'image'       => 'team-office.webp',
					'alt'         => 'The RankinAI office, rows of desks and people at work',
					'width'       => '1120',
					'height'      => '600',
					'reasons'     => array(
						array( 'people', 'Already working with us?',
							'Your named contact is the best place to start. If you&rsquo;re unsure who to reach, email us with your company name and we&rsquo;ll direct your message.' ),
						array( 'link', 'Looking for an agency partner?',
							'Tell us which services you need support with, the markets you work in, and how you&rsquo;d like us to fit into your team.' ),
						array( 'spark', 'Interested in joining RankinAI?',
							'Send a short introduction, your area of expertise, and examples of work you&rsquo;re proud of. Tell us what your contribution was and why it mattered.' ),
					),
				),
			),
		),
	),
);
