<?php
/**
 * Model: the book-a-call page, /call/. Content from the flat build's call.php,
 * rendered by page-templates/call.php.
 *
 * The email address, phone number, hours and offices are site-wide settings
 * ($SITE), not page content. Where the copy prints one, the field holds a
 * placeholder the template fills: {email}, {phone}, {hours}, {offices}.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

return array(
	'name'      => 'call',
	'post_type' => 'page',
	'template'  => 'page-templates/call.php',
	'label'     => 'Call page',
	'prefix'    => 'pg_call',
	'close'     => array(
		'btn'  => array( 'Get your growth audit', '/growth-audit/' ),
		'link' => array( 'Or pick a time above', '#book' ),
	),
	'schema'    => array(
		'hero'   => array( 'group', 'Hero', array(
			'title' => array( 'text', 'Headline' ),
			'sub'   => array( 'area', 'Standfirst' ),
			'ticks' => array( 'lines', 'Ticks' ),
			'proof' => array( 'rows', 'Proof figures', array( 'Figure', 'Label' ) ),
		) ),
		'booker' => array( 'group', 'Booker', array(
			'label'     => array( 'text', 'Label' ),
			'embed'     => array( 'text', 'Scheduler placeholder text' ),
			'alt_title' => array( 'text', 'Fallback heading' ),
			'alt_text'  => array( 'area', 'Fallback text', 'Use {email} and {phone} for the site-wide email address and phone number.' ),
			'note'      => array( 'area', 'Hours note', 'Use {hours} for the site-wide office hours.' ),
		) ),
		'cover'  => array( 'group', 'What we cover', array(
			'title' => array( 'text', 'Heading' ),
			'note'  => array( 'area', 'Note' ),
			'terms' => array( 'rows', 'Topics', array( 'Name', 'Text' ) ),
		) ),
		'who'    => array( 'group', 'Who you will speak to', array(
			'title' => array( 'text', 'Heading' ),
			'photo' => array( 'text', 'Photograph caption' ),
			'lead'  => array( 'area', 'Lead' ),
			'text'  => array( 'area', 'Text' ),
			'facts' => array( 'rows', 'Facts', array( 'Label', 'Value' ), 'Use {offices} for the site-wide offices.' ),
		) ),
		'faq'    => array( 'group', 'Questions', array(
			'title'     => array( 'text', 'Heading' ),
			'more'      => array( 'slots', 'Link to every question', array( 'Label', 'Link' ) ),
			'questions' => array( 'blocks', 'Questions and answers', array(
				'q'    => array( 'text', 'Question' ),
				'fact' => array( 'slots', 'Fact above the answer (optional)', array( 'Label', 'Value' ) ),
				'a'    => array( 'paras', 'Answer' ),
			) ),
		) ),
	),
	'items'     => array(
		'call' => array(
			'title' => 'Book a 20-minute call',
			'seo'   => array(
				'Book a 20-minute call | RankinAI',
				'Twenty minutes with the person who would run the work. No deck, no pitch team, nothing to prepare. Bring the problem and we will tell you what we would do about it.',
			),
			'data'  => array(
				'hero'   => array(
					'title' => 'Twenty minutes. No deck, no pitch team.',
					'sub'   => 'Bring the problem you actually have &mdash; enquiries drying up, a site that doesn&rsquo;t convert, an agency you&rsquo;re no longer sure is working. We&rsquo;ll tell you what we&rsquo;d do about it, and whether it needs us at all.',
					'ticks' => array(
						'Twenty minutes, and held to twenty',
						'You speak to the person who&rsquo;d run the work',
						'Nothing to read or prepare beforehand',
						'No proposal afterwards unless you ask for one',
					),
					'proof' => array(
						array( '2009', 'Working with businesses since' ),
						array( '200+', 'Clients served' ),
					),
				),
				'booker' => array(
					'label'     => 'Pick a time',
					'embed'     => 'Scheduler',
					'alt_title' => 'Or book it without the calendar',
					'alt_text'  => 'Email {email} with two or three times that suit you and we&rsquo;ll confirm one. If it&rsquo;s quicker to talk now, call {phone}.',
					'note'      => '{hours}. Outside those hours, say when suits you and we&rsquo;ll work around it &mdash; our clients are in three countries and none of them are in ours.',
				),
				'cover'  => array(
					'title' => 'What we cover',
					'note'  => 'Twenty minutes is enough for four things if nobody is presenting. Nobody will be presenting.',
					'terms' => array(
						array( 'Where the work comes from now', 'Which channels are actually producing enquiries, which ones you believe are, and how you&rsquo;d tell the difference if you had to.' ),
						array( 'What&rsquo;s in the way', 'The one thing standing between you and more of the work you want. In our experience it is rarely the thing people book the call about.' ),
						array( 'What we&rsquo;d do first', 'If we worked together: the first thing we&rsquo;d change, roughly what it would cost, and roughly when you&rsquo;d see it move.' ),
						array( 'Whether it should be us', 'Sometimes the answer is a hire, a different agency, or nothing at all this quarter. You&rsquo;ll hear that on the call, not in a proposal three weeks later.' ),
					),
				),
				'who'    => array(
					'title' => 'Who you&rsquo;ll speak to',
					'photo' => 'Kulwant Singh, founder',
					'lead'  => 'Kulwant Singh has been running digital marketing for service businesses since 2009, through Teqdeft, for more than two hundred clients. RankinAI is the same work, aimed squarely at firms that sell expertise.',
					'text'  => 'There is no SDR, no qualification call before the real call, and nobody on the line who has to check with someone else and come back to you. If the work starts, the person you spoke to stays on it.',
					'facts' => array(
						array( 'Since', '2009' ),
						array( 'Clients served', '200+' ),
						array( 'Offices', '{offices}' ),
					),
				),
				'faq'    => array(
					'title'     => 'Frequently asked questions',
					'more'      => array( 'Every question, answered in full', '/questions/' ),
					'questions' => array(
						array(
							'q' => 'Is this a sales call?',
							'a' => array( 'It&rsquo;s a call with the person who would do the work. We&rsquo;ll tell you what we&rsquo;d do about your situation whether or not you ever hire us. Whether you want us to do it is a different conversation, and we won&rsquo;t fold it into this one.' ),
						),
						array(
							'q'    => 'Do I need to prepare anything?',
							'fact' => array( 'To prepare', 'Nothing at all' ),
							'a'    => array( 'Not your analytics, not your numbers, not a brief. If you have them to hand it makes the call sharper. If you don&rsquo;t, we ask four questions and get to the same place.' ),
						),
						array(
							'q' => 'I&rsquo;d rather see something in writing first.',
							'a' => array( 'Then start with the growth audit instead &mdash; free, back within a working day, and written rather than spoken. You can book a call after reading it, or not. Plenty of people don&rsquo;t, and that&rsquo;s a fine outcome.' ),
						),
						array(
							'q' => 'Will you send a proposal afterwards?',
							'a' => array( 'Only if you ask for one. Sending an unrequested proposal is how an agency fills its own pipeline; it is not how anyone actually makes a decision. If you want one, say so on the call and it comes with the numbers it was built from.' ),
						),
					),
				),
			),
		),
	),
);
