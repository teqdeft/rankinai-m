<?php
/**
 * Model: the success stories index (/success-stories/). Page template
 * page-templates/success-stories.php, the flat build's success-stories.php.
 *
 * THE RULES THAT MUST SURVIVE (from the flat file). Every figure carries a
 * period. Where a number is not signed off, the card shows it is pending,
 * never an adjective and never quietly removed: a card's Figure left empty
 * renders the pending state. The Studio Ubique card discloses the partnership.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

return array(
	'name'      => 'success-stories',
	'post_type' => 'page',
	'template'  => 'page-templates/success-stories.php',
	'label'     => 'Success stories page',
	'prefix'    => 'pg_stories',
	'schema'    => array(
		'eyebrow' => array( 'text', 'Eyebrow' ),
		'h1'      => array( 'text', 'Headline' ),
		'sub'     => array( 'area', 'Subhead' ),
		'sub2'    => array( 'area', 'Second line' ),
		'btn'     => array( 'slots', 'Button', array( 'Label', 'Link' ) ),
		'link'    => array( 'slots', 'Quiet link', array( 'Label', 'Link' ) ),
		'title'   => array( 'text', 'Stories: heading' ),
		'note'    => array( 'area', 'Stories: note' ),
		'cards'   => array( 'blocks', 'Stories', array(
			'name'      => array( 'text', 'Client', 'Only real, signed-off clients.' ),
			'where'     => array( 'lines', 'Place and sector (one per line)' ),
			'metric'    => array( 'text', 'Figure', 'Only a real, sourced figure with its period. Empty = pending.' ),
			'metricKey' => array( 'text', 'What the figure measures (with its period)' ),
			'text'      => array( 'area', 'Summary' ),
			'tags'      => array( 'lines', 'Services (one per line)' ),
			'href'      => array( 'text', 'Link to the story' ),
			'linkText'  => array( 'text', 'Link text' ),
			'photo'     => array( 'image', 'Photograph', 'The client\'s own photograph needs their written okay before launch. See CLAIMS.md.' ),
			'photoAlt'  => array( 'text', 'Photograph description' ),
			'dark'      => array( 'bool', 'Dark card' ),
		) ),
		'more'    => array( 'slots', 'More stories panel', array( 'Label', 'Heading', 'Text' ) ),
	),
	'items'     => array(
		'success-stories' => array(
			'title' => 'Success stories',
			'seo'   => array( 'Success stories | RankinAI', 'See what changed, and what made the difference. The business, the challenge, the decisions we took and the progress they helped create.' ),
			'data'  => array(
				'eyebrow' => 'Success stories',
				'h1'      => 'See what changed. And what made the difference.',
				'sub'     => 'Behind every result is a business, a challenge and a series of decisions.',
				'sub2'    => 'Explore the work we delivered, why we took that approach, and the progress it helped create.',
				'btn'     => array( 'Get your growth audit', '/growth-audit/' ),
				'link'    => array( 'Book a 20-minute call', '/call/' ),
				'title'   => 'Client results',
				'note'    => 'Different countries, different trades, and the same problem underneath: good businesses that were hard to find.',
				'cards'   => array(
					array(
						'name'      => 'Pine Tree Lane',
						'where'     => array( 'Dubai, UAE', 'Interior design &amp; bespoke joinery' ),
						'metric'    => '4&times;',
						'metricKey' => 'organic traffic in three months',
						'text'      => 'A factory, a showroom and a ten-year warranty &mdash; and almost nobody finding them. The searches that mattered were going to firms who outsourced the making. The results for custom kitchens in Dubai are now theirs.',
						'tags'      => array( 'AI and search visibility', 'Content', 'Website and conversion' ),
						'href'      => '/success-stories/pine-tree-lane/',
						'linkText'  => 'Read the full story',
						'photo'     => 'case-pine-tree-lane.webp',
						'photoAlt'  => 'Pine Tree Lane kitchen: pale blue cabinetry and a marble island in a Dubai villa',
						'dark'      => false,
					),
					array(
						'name'      => 'SweetRush',
						'where'     => array( 'San Francisco, USA', 'Learning &amp; development consultancy' ),
						'metric'    => '',
						'metricKey' => '',
						'text'      => 'A twenty-year reputation among learning and development buyers, and a website that read like a much smaller firm&rsquo;s brochure. The reputation existed. Nothing online carried it.',
						'tags'      => array( 'AI and search visibility', 'Content' ),
						'href'      => '/success-stories/sweetrush/',
						'linkText'  => 'Read the full story',
						'photo'     => 'case-sweetrush.webp',
						'photoAlt'  => 'Two SweetRush colleagues working through a problem on a laptop',
						'dark'      => true,
					),
					array(
						'name'      => 'Studio Ubique',
						'where'     => array( 'Zwolle, Netherlands', 'Digital agency &mdash; our partner' ),
						'metric'    => '',
						'metricKey' => '',
						'text'      => 'Excellent work, invisible outside their own network, and no record of it that a prospect could check. We&rsquo;re a partner in the business, which is why it&rsquo;s said on the card rather than in a footnote.',
						'tags'      => array( 'AI and search visibility', 'Paid advertising' ),
						'href'      => '/success-stories/studio-ubique/',
						'linkText'  => 'Read the full story',
						'photo'     => 'case-studio-ubique.webp',
						'photoAlt'  => 'Three Studio Ubique team members with coffee in the Zwolle office',
						'dark'      => false,
					),
				),
				'more'    => array( 'More stories', 'More on the way.', 'Recruitment, construction and professional services, written up in the same shape as the three above.' ),
			),
		),
	),
);
