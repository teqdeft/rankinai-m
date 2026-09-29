<?php
/**
 * Model: the questions page, /questions/. Content from the flat build's
 * questions.php, rendered by page-templates/questions.php.
 *
 * THE PRICES HERE MUST MATCH /pricing/. Foundation $750, Growth $1,250,
 * Accelerate $1,750, Custom from $2,500, setup $500 waived on a six-month
 * commitment. Change one, change both. This is also the only place the site
 * says the prices are in USD.
 *
 * The groups are one list: the hero's jump list and the answers are built
 * from it, so they cannot drift apart.
 *
 * A LIST INSIDE AN ANSWER. An answer is paragraphs separated by a blank line.
 * A paragraph whose every line starts with "- " prints as a bulleted list,
 * which only the pricing question uses.
 *
 * The email address in the closing line is the site setting ($SITE['email']),
 * as on the flat build.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$slots2 = array( 'Label', 'Link' );

return array(
	'name'      => 'questions',
	'post_type' => 'page',
	'template'  => 'page-templates/questions.php',
	'label'     => 'Questions page',
	'prefix'    => 'pg_questions',
	'schema'    => array(
		'hero'   => array( 'group', 'Hero', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Headline' ),
			'sub'     => array( 'area', 'Subhead' ),
			'sub2'    => array( 'area', 'Second line' ),
			'btn'     => array( 'slots', 'Button', $slots2 ),
			'link'    => array( 'slots', 'Quiet link', $slots2 ),
			'jump'    => array( 'text', 'Jump list label' ),
		) ),
		'groups' => array( 'blocks', 'Question groups', array(
			'id'        => array( 'text', 'Anchor (letters and hyphens)' ),
			'label'     => array( 'text', 'Name in the jump list' ),
			'eyebrow'   => array( 'text', 'Eyebrow' ),
			'title'     => array( 'text', 'Heading' ),
			'questions' => array( 'qs', 'Questions and answers', 'A paragraph whose every line starts with "- " prints as a list.' ),
		) ),
		'ask'    => array( 'group', 'Still have a question?', array(
			'label' => array( 'text', 'Label' ),
			'text'  => array( 'slots', 'Line (the site email address prints between the two parts)', array( 'Before the email address', 'After the email address' ) ),
		) ),
	),
	'items'     => array(
		'questions' => array(
			'title' => 'Questions',
			'seo'   => array(
				'Questions, answered straight | RankinAI',
				'What it costs, what you will need to give us, what happens if the plan changes, who owns the accounts, and how long you are committed for.',
			),
			'data'  => array(
				'hero'   => array(
					'eyebrow' => 'Your questions, answered',
					'title'   => 'Good questions. Straight answers.',
					'sub'     => 'What does it cost? What will you need from us? What happens if the plan needs to change?',
					'sub2'    => 'Here&rsquo;s what to expect before we start, and while we&rsquo;re working together.',
					'btn'     => array( 'Get your growth audit', '/growth-audit/' ),
					'link'    => array( 'Book a 20-minute call', '/call/' ),
					'jump'    => 'Jump to',
				),
				'groups' => array(
					array(
						'id' => 'getting-started', 'label' => 'Getting started',
						'eyebrow' => 'Getting started',
						'title' => 'Before you decide.',
						'questions' => array(
							array( 'What does RankinAI actually do?', array(
								'We help service businesses attract relevant enquiries and improve the journey from first interest to a sales conversation.',
								'Our work brings together search and AI visibility, content, paid advertising, website improvements, reputation and follow-up. Your plan determines the priorities and level of support.',
							) ),
							array( 'Who do you work with?', array(
								'Our focus is on design and construction, recruitment and HR, and professional services.',
								'These businesses sell expertise, often through a considered buying process. Their prospects need to understand the experience, people and evidence behind the offer before choosing a firm.',
							) ),
							array( 'What is included in the free growth audit?', array(
								'A focused review of your public website, search presence, business profiles and enquiry journey.',
								'We highlight the clearest opportunities and suggest where to start. Findings that require analytics, advertising or CRM access are identified separately.',
							) ),
							array( 'Do we have to book a call to receive the audit?', array(
								'No. You receive the findings in writing. If you&rsquo;d like to discuss them, you can book a 20-minute call.',
								'Requesting the audit creates no obligation to work with us.',
							) ),
							array( 'We already get enquiries. Can you help us attract better ones?', array(
								'That can be a useful starting point.',
								'We look at which services you promote, how you describe your ideal clients, what your pages promise, and how enquiries are qualified. The goal is to give suitable prospects stronger reasons to contact you while making your fit clearer to everyone else.',
							) ),
							array( 'What if we only need one service?', array(
								'We can propose a focused engagement around a specific need, such as search, advertising or website conversion.',
								'Our packages suit businesses that want coordinated ongoing support. We&rsquo;ll explain which arrangement fits the work you need.',
							) ),
						),
					),
					array(
						'id' => 'pricing', 'label' => 'Pricing',
						'eyebrow' => 'Pricing',
						'title' => 'Understand the investment.',
						'questions' => array(
							array( 'How much does it cost?', array(
								'Our monthly plans are:',
								implode( "\n", array( '- Foundation: $750', '- Growth: $1,250', '- Accelerate: $1,750', '- Custom: from $2,500' ) ),
								'All prices are in USD. Your proposal confirms the deliverables, fees, separate costs and any applicable taxes.',
							) ),
							array( 'Is there a setup fee?', array(
								'Foundation, Growth and Accelerate have a one-time $500 setup fee.',
								'This covers the agreed kickoff, baseline, standard tracking setup or corrections, and initial action plan. Substantial website repairs, CRM migrations and custom integrations are quoted separately.',
								'Custom-plan setup is scoped individually.',
							) ),
							array( 'Can the setup fee be waived?', array(
								'Yes. We waive the $500 setup fee when you choose a six-month initial commitment.',
								'Alternatively, you can pay the setup fee and start on a monthly arrangement with 30 days&rsquo; notice. Both options are explained before you choose.',
							) ),
							array( 'Does the monthly fee include advertising spend?', array(
								'No. The fee covers our work. Your advertising budget is separate, stays in your account, and is agreed before campaigns launch.',
								'We also identify any required software subscriptions, messaging charges or other third-party costs.',
							) ),
							array( 'Does every package mean all six services run every month?', array(
								'The work follows the priorities agreed for your business.',
								'Some activities happen during setup, some run regularly, and others become relevant later. Your proposal explains what is included, and the delivery plan shows where the effort goes.',
							) ),
							array( 'Will we be charged for work outside the package?', array(
								'Only after the additional work and its price have been discussed and approved.',
								'If a request changes the scope, we explain the impact before proceeding.',
							) ),
						),
					),
					array(
						'id' => 'results', 'label' => 'Results',
						'eyebrow' => 'Results',
						'title' => 'What progress looks like.',
						'questions' => array(
							array( 'How soon should we expect results?', array(
								'It depends on your starting point and the work involved.',
								'Some website, tracking and follow-up improvements can be implemented early. Search visibility and a stronger content presence take sustained effort.',
								'We agree milestones for the activities in your plan and review progress against them. Implementation dates and commercial results are tracked separately.',
							) ),
							array( 'Do you guarantee leads, rankings or revenue?', array(
								'We commit to the agreed delivery, measurement and review process.',
								'Results also depend on your market, offer, budget, competition and sales process. We set objectives with those factors in mind and explain the assumptions behind them.',
							) ),
							array( 'How will we know whether the work is paying off?', array(
								'We track relevant enquiries and how they move through your sales process.',
								'Where your data allows, we connect those enquiries with opportunities, clients won and acquisition costs. We also monitor the earlier indicators that help explain performance, such as search visibility and website conversion.',
								'Any gaps in measurement are made clear.',
							) ),
							array( 'What happens if a channel underperforms?', array(
								'We investigate before deciding what to change.',
								'The issue could be the targeting, message, page, offer, follow-up or an assumption in the plan. We share the findings and recommend the next action.',
								'If the ongoing scope changes materially, we review the fee with you.',
							) ),
							array( 'Can you get our business recommended by AI assistants?', array(
								'We work on making your business and expertise easier to discover and understand, then monitor an agreed set of relevant buyer questions.',
								'We report where your firm appears and what changes over time. Inclusion in an AI answer cannot be guaranteed by anyone.',
							) ),
						),
					),
					array(
						'id' => 'working-together', 'label' => 'Working together',
						'eyebrow' => 'Working together',
						'title' => 'How we fit into your team.',
						'questions' => array(
							array( 'We already have a marketing manager. Where do they fit?', array(
								'They remain central to the work.',
								'We agree priorities, responsibilities and reporting with them, then add the specialist capacity the plan needs. They have a named contact and visibility into delivery.',
							) ),
							array( 'Can you work with our existing agency or developer?', array(
								'Yes. We define the responsibilities and dependencies together.',
								'For example, we may handle search strategy and content while your developer implements website changes. Everyone should understand who owns each task and how its outcome will be measured.',
							) ),
							array( 'How much time will you need from us?', array(
								'We need your business knowledge, access to relevant information, and timely decisions.',
								'At the start, that means a kickoff conversation and gathering the necessary materials. During delivery, it means scheduled interviews, reviews and approvals.',
								'We explain the expected involvement when scoping the work.',
							) ),
							array( 'Who writes the content?', array(
								'We turn your team&rsquo;s knowledge into drafts, then work through your feedback.',
								'You review factual and technical claims before publication. For regulated or specialist subjects, we agree who on your side is responsible for the necessary review.',
							) ),
							array( 'Do you use AI tools?', array(
								'We may use tools to support research, organisation, analysis and drafting.',
								'Our team remains responsible for checking facts, shaping the message and reviewing the finished work. Content follows the agreed approval process before publication.',
							) ),
							array( 'Will we need a new website?', array(
								'We assess that before recommending one.',
								'Your existing site may support the improvements you need. If a rebuild is justified, we explain why and provide a separate scope and price.',
							) ),
							array( 'What if our priorities change?', array(
								'Tell us as early as you can.',
								'We review the effect on the plan and agree any changes to scope, timing or fees. The work should stay connected to the direction of your business.',
							) ),
						),
					),
					array(
						'id' => 'ownership', 'label' => 'Ownership and commitment',
						'eyebrow' => 'Ownership and commitment',
						'title' => 'Know where you stand.',
						'questions' => array(
							array( 'Who controls our accounts?', array(
								'You retain control of your business accounts, including advertising, analytics and your website.',
								'We request the access needed for delivery. Responsibilities for commissioned assets, documentation and handover are recorded in the proposal.',
							) ),
							array( 'How long are we committing for?', array(
								'With the standard monthly arrangement, cancellation requires 30 days&rsquo; notice.',
								'If you choose the setup-fee waiver, you commit to an initial six months. After that, the arrangement continues monthly with 30 days&rsquo; notice.',
								'Your agreement confirms the dates and notice process.',
							) ),
							array( 'What happens if we stop working together?', array(
								'We arrange the handover of agreed files, documentation and access, and confirm the status of outstanding work.',
								'Your business accounts remain under your control. Any continuing third-party subscriptions or costs are identified during handover.',
							) ),
							array( 'Will you work with a competitor?', array(
								'We discuss potential conflicts before starting.',
								'If exclusivity matters to your business, raise it during the initial conversation. Any exclusivity arrangement needs a clearly defined service area, market and duration, recorded in the agreement.',
							) ),
						),
					),
				),
				'ask'    => array(
					'label' => 'Still have a question?',
					'text'  => array(
						'Ask us directly. Email',
						'with what you&rsquo;d like to know. If it helps us understand your question, include your website and a little context about your business.',
					),
				),
			),
		),
	),
);
