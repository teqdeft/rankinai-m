<?php
/**
 * Model: the how we work page, /how-we-work/. Content from the flat build's
 * how-we-work.php, rendered by page-templates/how-we-work.php.
 *
 * NO PRICES ON THIS PAGE. The numbers live on /pricing/ only. See the flat
 * file's header comment.
 *
 * The four steps are one list: the hero card prints each step's number, name
 * and lead, and the process slider prints them in full, so the two cannot
 * drift apart. The hero photograph is from Unsplash, hotlinked, no on-page
 * credit. See CLAIMS.md. The flat build does not record its photographer, so
 * those two boxes are left empty rather than guessed.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$slots2 = array( 'Label', 'Link' );

return array(
	'name'      => 'how-we-work',
	'post_type' => 'page',
	'template'  => 'page-templates/how-we-work.php',
	'label'     => 'How we work page',
	'prefix'    => 'pg_hww',
	'schema'    => array(
		'hero'    => array( 'group', 'Hero', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Headline' ),
			'sub'     => array( 'area', 'Subhead' ),
			'sub2'    => array( 'area', 'Second line' ),
			'btn'     => array( 'slots', 'Button', $slots2 ),
			'link'    => array( 'slots', 'Quiet link', $slots2 ),
			'photo'   => array( 'photo', 'Photograph (Unsplash)' ),
		) ),
		'begin'   => array( 'group', 'Where we begin', array(
			'eyebrow'   => array( 'text', 'Eyebrow' ),
			'title'     => array( 'text', 'Heading' ),
			'note'      => array( 'area', 'Note' ),
			'askLabel'  => array( 'text', 'Questions label' ),
			'questions' => array( 'lines', 'Questions we ask first (one per line)' ),
			'stepLabel' => array( 'text', 'First step label' ),
			'lead'      => array( 'text', 'First step lead' ),
			'paras'     => array( 'paras', 'First step paragraphs' ),
			'btn'       => array( 'slots', 'Button', $slots2 ),
			'link'      => array( 'slots', 'Quiet link', $slots2 ),
			'footnote'  => array( 'area', 'Note under the buttons' ),
		) ),
		'process' => array( 'group', 'The process: heading', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Heading' ),
		) ),
		'steps'   => array( 'blocks', 'The steps (also listed in the hero)', array(
			'icon'  => array( 'text', 'Icon key', 'One of: ' . implode( ', ', array_keys( rankinai_icon_choices() ) ) ),
			'n'     => array( 'text', 'Number' ),
			'title' => array( 'text', 'Name' ),
			'lead'  => array( 'text', 'Lead' ),
			'paras' => array( 'paras', 'Paragraphs' ),
			'label' => array( 'text', 'List label' ),
			'list'  => array( 'lines', 'List (one per line)' ),
		) ),
		'measure' => array( 'group', 'How we measure progress', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Heading' ),
			'cards'   => array( 'rows', 'Stages', array( 'icon', 'Name', 'Text' ) ),
			'tail'    => array( 'area', 'Closing line' ),
		) ),
		'faq'     => array( 'group', 'Questions', array(
			'eyebrow'   => array( 'text', 'Eyebrow' ),
			'title'     => array( 'text', 'Heading' ),
			'questions' => array( 'qs', 'Questions and answers' ),
		) ),
	),
	'items'     => array(
		'how-we-work' => array(
			'title' => 'How we work',
			'seo'   => array(
				'How we work, the right work in the right order | RankinAI',
				'Where we begin, what the free audit covers, the four steps, your first month, how progress is measured, and what we need from your team.',
			),
			'data'  => array(
				'hero'    => array(
					'eyebrow' => 'How we work',
					'title'   => 'The right work. In the right order.',
					'sub'     => 'Your marketing should have a clear connection to the business you want to build.',
					'sub2'    => 'We start with the clients and projects you want more of, find what&rsquo;s standing in the way, and put a practical plan into action. You know what we&rsquo;re doing, why it matters, and what happens next.',
					'btn'     => array( 'Get your growth audit', '/growth-audit/' ),
					'link'    => array( 'Book a 20-minute call', '/call/' ),
					'photo'   => array( 'photo' => 'photo-1722232934077-9c188cefef16', 'user' => '', 'by' => '', 'alt' => 'Stepping stones crossing still water' ),
				),
				'begin'   => array(
					'eyebrow'   => 'Where we begin',
					'title'     => 'Before we recommend a channel, we ask about the business.',
					'note'      => 'Your answers shape the plan. They help us decide where to focus, what needs fixing, and which opportunities are worth pursuing.',
					'askLabel'  => 'What we ask first',
					'questions' => array(
						'Which work is most valuable to you?',
						'What makes someone a good client?',
						'Where do your strongest enquiries come from today?',
						'And if more opportunities arrived, which ones would you actually want?',
					),
					'stepLabel' => 'The first step',
					'lead'      => 'Start with a clearer picture.',
					'paras'     => array(
						'Your free growth audit is a focused review of how a prospective client discovers, understands and contacts your business. We look at your public website, your search presence, your business profiles and your enquiry journey, and you receive the clearest findings and suggested priorities in writing.',
						'Some questions need a closer look at your accounts or sales process. We identify those separately, so observations and assumptions stay clear.',
					),
					'btn'       => array( 'Get your growth audit', '/growth-audit/' ),
					'link'      => array( 'Book a 20-minute call', '/call/' ),
					'footnote'  => 'Read the audit, discuss it with your team, or talk it through with us. There&rsquo;s no obligation to continue.',
				),
				'process' => array(
					'eyebrow' => 'The process',
					'title'   => 'Four steps. One connected plan.',
				),
				'steps'   => array(
					array(
						'icon'  => 'chat',
						'n'     => '01',
						'title' => 'Understand',
						'lead'  => 'Define the work you want to win.',
						'paras' => array(
							'We begin with a conversation about your goals, services and ideal clients. We want to understand what makes your business valuable, how buyers make their decisions, and what happens between an enquiry arriving and a client saying yes.',
							'We also look at your capacity. A plan to attract larger projects can require different work from a plan to increase enquiry volume.',
						),
						'label' => 'We agree',
						'list'  => array(
							'The services and markets to prioritise.',
							'What makes an enquiry relevant.',
							'Your commercial goals and delivery capacity.',
							'Who needs to be involved on your side.',
						),
					),
					array(
						'icon'  => 'search',
						'n'     => '02',
						'title' => 'Investigate',
						'lead'  => 'Find where the opportunity is being lost.',
						'paras' => array(
							'If we work together, we build on the initial audit with the access and information needed for a deeper review. That may include your analytics, search performance, advertising, website content and follow-up process, depending on the agreed scope.',
							'We trace the journey from discovery to enquiry, and, where your data allows, through to the work won.',
						),
						'label' => 'You get',
						'list'  => array(
							'A baseline of the available performance data.',
							'The gaps affecting visibility, trust or conversion.',
							'Tracking issues that need attention.',
							'A prioritised view of what to improve.',
						),
					),
					array(
						'icon'  => 'clipboard',
						'n'     => '03',
						'title' => 'Plan',
						'lead'  => 'Give every activity a reason to be there.',
						'paras' => array(
							'We turn the findings into a delivery plan. It explains what we recommend, the order of work, who is responsible, and how progress will be assessed. You can see how the activities connect to your goals.',
							'Some priorities may be straightforward: a clearer service page, a better enquiry form, or more consistent follow-up. Others require sustained work, such as building visibility in a competitive market.',
						),
						'label' => 'Before delivery begins, we agree',
						'list'  => array(
							'The scope, fees and separate costs.',
							'The initial priorities and delivery schedule.',
							'The information and approvals we need.',
							'The measures and review points.',
						),
					),
					array(
						'icon'  => 'sliders',
						'n'     => '04',
						'title' => 'Deliver',
						'lead'  => 'Put the plan to work. Learn from what happens.',
						'paras' => array(
							'Our specialists carry out the agreed work across search, content, advertising, your website, reputation and follow-up. The mix depends on your plan and priorities.',
							'We review performance, bring you the findings, and recommend the next actions. As the evidence develops, we refine where the effort goes.',
						),
						'label' => 'You stay informed through',
						'list'  => array(
							'Updates on completed and upcoming work.',
							'Clear requests for input or approval.',
							'Reports at the cadence included in your plan.',
							'Reviews that lead to decisions.',
						),
					),
				),
				'measure' => array(
					'eyebrow' => 'How we measure progress',
					'title'   => 'Follow the journey from being found to winning work.',
					'cards'   => array(
						array( 'eye', 'Visibility', 'Are you appearing for relevant searches, locations and buyer questions? Is the right audience reaching your business?' ),
						array( 'mail', 'Enquiries', 'Are more suitable prospects contacting you? Which pages and channels contribute to those conversations?' ),
						array( 'split', 'Opportunities', 'Which enquiries move forward? Where are prospects dropping out, and what can we learn from that?' ),
						array( 'star', 'Clients won', 'Where your sales data allows, we connect marketing activity with signed work and acquisition costs.' ),
					),
					'tail'    => 'We explain what the data shows, what remains uncertain, and what we recommend doing next.',
				),
				'faq'     => array(
					'eyebrow'   => 'Frequently asked questions',
					'title'     => 'What happens after we get in touch?',
					'questions' => array(
						array( 'Can we start with the audit alone?', array( 'Yes. The free audit gives you an initial view of the opportunities. You can decide whether a conversation or further work would be useful after reading it.' ) ),
						array( 'How much time will you need from us?', array( 'We agree that during scoping. Expect a kickoff conversation, access to relevant information, and scheduled input for content and approvals. Your delivery plan makes those requirements clear.' ) ),
						array( 'Will you need access to everything?', array( 'We request the access needed for the agreed work. The initial public audit does not require account access; deeper analysis and delivery usually do. Your accounts remain under your control.' ) ),
						array( 'What if our priorities change?', array( 'Tell us. We review the effect on the plan and agree any changes to delivery, timing or scope before proceeding.' ) ),
						array( 'How soon should we expect progress?', array( 'Different activities move at different speeds. Website and follow-up improvements may be implemented early. Search visibility and a stronger content presence require sustained work. Your plan sets realistic milestones for each.' ) ),
						array( 'What if something isn&rsquo;t working?', array( 'We examine the evidence, explain what we&rsquo;re seeing, and recommend a response. That could involve improving the execution, revising an assumption or shifting effort to another priority.' ) ),
					),
				),
			),
		),
	),
);
