<?php
/**
 * Model: the growth audit page, /growth-audit/. Content from the flat build's
 * growth-audit.php, rendered by page-templates/growth-audit.php. The form
 * itself is template-parts/auditform.php and its copy stays there.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

return array(
	'name'      => 'growth-audit',
	'post_type' => 'page',
	'template'  => 'page-templates/growth-audit.php',
	'label'     => 'Growth audit page',
	'prefix'    => 'pg_audit',
	'close'     => array(
		'btn'  => array( 'Fill in the form', '#audit-form' ),
		'link' => array( 'Or book a 20-minute call', '/call/' ),
	),
	'schema'    => array(
		'hero'  => array( 'group', 'Hero', array(
			'title' => array( 'text', 'Headline' ),
			'sub'   => array( 'area', 'Standfirst' ),
			'ticks' => array( 'lines', 'Ticks' ),
			'proof' => array( 'rows', 'Proof figures', array( 'Figure', 'Label' ) ),
		) ),
		'what'  => array( 'group', 'What the audit includes', array(
			'title' => array( 'text', 'Heading' ),
			'note'  => array( 'area', 'Note' ),
			'cols'  => array( 'blocks', 'Columns', array(
				'title' => array( 'text', 'Column heading' ),
				'items' => array( 'lines', 'Items' ),
			) ),
		) ),
		'faq'   => array( 'group', 'Questions', array(
			'eyebrow'   => array( 'text', 'Eyebrow' ),
			'title'     => array( 'text', 'Heading' ),
			'more'      => array( 'slots', 'Link to every question', array( 'Label', 'Link' ) ),
			'questions' => array( 'qs', 'Questions and answers' ),
		) ),
	),
	'items'     => array(
		'growth-audit' => array(
			'title' => 'Growth audit',
			'seo'   => array(
				'Get your growth audit — free, back in one working day | RankinAI',
				'A person here reads your site, your search and AI visibility and your competitors, then sends you six to eight pages: what is working, where enquiries are leaking, and the three fixes worth doing first. Free, no obligation.',
			),
			'data'  => array(
				'hero' => array(
					'title' => 'See what&rsquo;s bringing you work, and what&rsquo;s quietly costing you.',
					'sub'   => 'Tell us your website. Within one working day a person here sends back a short document &mdash; where you&rsquo;re visible, where you aren&rsquo;t, where enquiries are leaking, and the three fixes worth doing first.',
					'ticks' => array(
						'Free, and it stays free',
						'Back within one working day',
						'Written by a person, not generated',
						'No call booked on your behalf',
					),
					'proof' => array(
						array( '2009', 'Working with businesses since' ),
						array( '200+', 'Clients served' ),
					),
				),
				'what' => array(
					'title' => 'What the audit includes',
					'note'  => 'Not a scan with your logo on the front. Someone here opens your site, searches the way your buyers search, asks the assistants about your category, and writes down what they find.',
					'cols'  => array(
						array(
							'title' => '01 &mdash; Where you stand today',
							'items' => array(
								'Where you rank for the searches that actually produce enquiries, not the ones that produce traffic',
								'Whether ChatGPT, Claude, Gemini and Perplexity name you when someone asks for your category in your city',
								'How your reviews read next to the three firms you lose to most',
								'What your site does with a visitor who has already decided to enquire',
							),
						),
						array(
							'title' => '02 &mdash; Where it&rsquo;s leaking',
							'items' => array(
								'The pages people arrive on and leave from, and what they were looking for',
								'How long an enquiry waits before a human replies',
								'What currently happens to an enquiry that lands at 9pm on a Friday',
								'Which channels you&rsquo;re paying for that aren&rsquo;t producing work',
								'What you can&rsquo;t currently measure, and what that&rsquo;s hiding',
							),
						),
						array(
							'title' => '03 &mdash; What to do first',
							'items' => array(
								'Three fixes, ranked by what they return against what they cost to do',
								'A rough figure for what each one is worth in enquiries a month',
								'Which of them your own team can do, and which they can&rsquo;t',
								'Whether we&rsquo;re the right people for the rest &mdash; including when we&rsquo;re not',
							),
						),
					),
				),
				'faq'  => array(
					'eyebrow'   => 'Frequently asked questions',
					'title'     => 'Before you send it.',
					'more'      => array( 'Every question, answered in full', '/questions/' ),
					'questions' => array(
						array( 'Why is it free?', array(
							'Because it&rsquo;s the fastest way for both of us to find out whether there&rsquo;s work here. We&rsquo;d rather spend three hours and tell you no than spend three months finding out.',
							'Some of the firms we audit become clients. Most don&rsquo;t, and the ones that do arrive knowing exactly what they&rsquo;re buying.',
						) ),
						array( 'What do you need from me?', array(
							'Your website address and a way to reach you. Two minutes, once.',
							'Read-only analytics access if you have it, which makes the numbers real rather than estimated. Nothing else, and nothing after.',
						) ),
						array( 'We already have an agency.', array(
							'Then the audit is worth more, not less. It&rsquo;s an outside read on what you&rsquo;re already paying for.',
							'Take it to them. Most of what we find is fixable by whoever is doing the work now, and we&rsquo;ll say which parts those are.',
						) ),
						array( 'What happens to my details?', array(
							'One person reads them. They aren&rsquo;t added to a list, put into a sequence, or passed to anyone else.',
							'If you never reply to the audit, that is the end of it.',
						) ),
						array( 'What if you tell me nothing is wrong?', array(
							'We say so, in writing.',
							'It happens. Some firms are doing the fundamentals well and their problem is capacity, not demand. Telling you that costs us a client we were never going to keep.',
						) ),
					),
				),
			),
		),
	),
);
