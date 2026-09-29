<?php
/**
 * Model: the about page, /about/. Content from the flat build's about.php,
 * rendered by page-templates/about.php. The team photo marquee is
 * template-parts/team-marquee.php and its tiles stay there. The office line
 * in the hero card is the site setting ($SITE['offices']), as on the flat
 * build.
 *
 * THE PEOPLE are team members, their own post type (inc/models/team.php),
 * listed under this page's "Meet the team" heading in their Order. This page
 * holds only the heading, the note and the card labels.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

return array(
	'name'      => 'about',
	'post_type' => 'page',
	'template'  => 'page-templates/about.php',
	'label'     => 'About page',
	'prefix'    => 'pg_about',
	'schema'    => array(
		'hero'    => array( 'group', 'Hero', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Headline' ),
			'sub'     => array( 'area', 'Subhead' ),
			'sub2'    => array( 'area', 'Second line' ),
			'btn'     => array( 'slots', 'Button', array( 'Label', 'Link' ) ),
			'link'    => array( 'slots', 'Quiet link', array( 'Label', 'Link' ) ),
			'shots'   => array( 'rows', 'Collage photographs (wide, second, low)', array( 'image', 'Description' ) ),
			'place'   => array( 'text', 'Office card label', 'The office line under it is the site setting.' ),
		) ),
		'why'     => array( 'group', 'Why we exist', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Heading' ),
			'sub'     => array( 'area', 'Note' ),
			'lead'    => array( 'area', 'Lead line' ),
			'paras'   => array( 'paras', 'Paragraphs' ),
		) ),
		'mission' => array( 'area', 'Mission (under the photographs)' ),
		'think'   => array( 'group', 'How we think', array(
			'eyebrow'    => array( 'text', 'Eyebrow' ),
			'title'      => array( 'text', 'Heading' ),
			'note'       => array( 'area', 'Note' ),
			'lead'       => array( 'area', 'Lead line' ),
			'paras'      => array( 'paras', 'Paragraphs' ),
			'rulesLabel' => array( 'text', 'Principles label' ),
			'rules'      => array( 'rows', 'Principles', array( 'Name', 'Text' ) ),
		) ),
		'working' => array( 'group', 'Working with us', array(
			'eyebrow' => array( 'text', 'Eyebrow' ),
			'title'   => array( 'text', 'Heading' ),
			'note'    => array( 'area', 'Note' ),
			'cards'   => array( 'rows', 'Cards', array( 'icon', 'Name', 'Text' ) ),
		) ),
		'team'    => array( 'group', 'Meet the team', array(
			'title'     => array( 'text', 'Heading', 'The people under it are edited under Team in the admin menu, and appear in their Order.' ),
			'note'      => array( 'area', 'Note' ),
			'labels'    => array( 'slots', 'Card labels', array( 'Experience', 'Delivered', 'LinkedIn link text' ) ),
		) ),
	),
	'items'     => array(
		'about' => array(
			'title' => 'About',
			'seo'   => array(
				'About RankinAI, marketing for firms that sell expertise | RankinAI',
				'Why RankinAI exists, how we think about growth, what guides the work, and the named people who would actually do it.',
			),
			'data'  => array(
				'hero'    => array(
					'eyebrow' => 'About RankinAI',
					'title'   => 'You&rsquo;ve spent years getting good. We help the right people see it.',
					'sub'     => 'Your best clients understand the value you bring. They&rsquo;ve worked with you, seen your thinking, and experienced the difference.',
					'sub2'    => 'We help that understanding travel further, so people who haven&rsquo;t met you yet have a reason to choose you.',
					'btn'     => array( 'Meet your team', '#team' ),
					'link'    => array( 'Book a 20-minute call', '/call/' ),
					'shots'   => array(
						array( 'team-group.webp', 'The RankinAI team together in the office' ),
						array( 'team-office.webp', 'The office, people at work at their desks' ),
						array( 'team-talking.webp', 'Colleagues talking in the office on Holi, colour on their shirts' ),
					),
					'place'   => 'Our office',
				),
				'why'     => array(
					'eyebrow' => 'Why we exist',
					'title'   => 'A referral gives you a head start. Your marketing should do the same.',
					'sub'     => 'Think about what happens when a happy client recommends you. They explain what you do well. They share their experience. They give someone a reason to trust you before the first conversation.',
					'lead'    => 'That&rsquo;s a useful standard for your marketing.',
					'paras'   => array(
						'Your website should make your value clear. Your content should demonstrate how you think. Your client stories should give your promises weight. And your business should be visible when someone starts looking.',
						'RankinAI brings those pieces together for firms that sell expertise.',
						'We help you reach beyond the people who already know you, and give your next client something meaningful to go on.',
					),
				),
				'mission' => 'We&rsquo;re on a mission to make expertise easier to find, by helping service businesses communicate their value, reach relevant buyers, and build a clearer path from interest to enquiry. The goal is a business with more opportunities to win the work it wants.',
				'think'   => array(
					'eyebrow'    => 'How we think',
					'title'      => 'Marketing starts with the business you want to build.',
					'note'       => 'More enquiries can be useful. So can fewer unsuitable ones.',
					'lead'       => 'We start by understanding what growth means for you.',
					'paras'      => array(
						'For one firm, progress means winning larger projects. For another, it means reaching a new market, growing a particular service, or reducing dependence on the founder&rsquo;s network.',
						'That shapes the audience, the message, the channels and the work we recommend.',
					),
					'rulesLabel' => 'What guides the work',
					'rules'      => array(
						array( 'Get close to the business.', 'We ask about your clients, sales conversations, capacity and commercial priorities. Your answers shape the plan.' ),
						array( 'Make the expertise visible.', 'The details that feel ordinary to your team can be the very things a buyer needs to hear. We help draw them out and explain why they matter.' ),
						array( 'Give every claim something behind it.', 'Useful examples, approved client stories and clearly defined results make a stronger case than a page full of promises.' ),
						array( 'Take responsibility for the work.', 'We agree who is doing what, keep you informed, and raise issues while there is still time to address them.' ),
						array( 'Stay willing to change direction.', 'Results give us something to learn from. We use that learning to improve the next round of work.' ),
					),
				),
				'working' => array(
					'eyebrow' => 'Working with us',
					'title'   => 'Connected expertise. People you can talk to.',
					'note'    => 'Search, advertising, content, websites, reputation and follow-up all influence the same buying journey. We bring those specialisms into one plan, with clear priorities and a named person coordinating the work, so you know what is being delivered, who needs your input, and what we are measuring.',
					'cards'   => array(
						array( 'clipboard', 'A plan you can understand', 'We explain the priorities, the reasoning behind them, and the scope your budget covers.' ),
						array( 'people', 'A useful role for your team', 'You bring the knowledge of your business. We organise the interviews, questions and approvals needed to turn it into marketing.' ),
						array( 'chart', 'A clear view of progress', 'Reports explain what changed and what it means. Reviews help us decide what to continue, improve or reconsider.' ),
						array( 'shield', 'Ownership and access', 'Your business accounts stay under your control. Deliverables, access and handover arrangements are agreed from the start.' ),
					),
				),
				'team'    => array(
					'title'  => 'Put names to the people behind the work.',
					'note'   => 'A good working relationship starts with knowing who you&rsquo;re speaking to, and what they&rsquo;re responsible for.',
					'labels' => array( 'Experience', 'Delivered', 'View LinkedIn profile' ),
				),
			),
		),
	),
);
