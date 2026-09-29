<?php
/**
 * The original copy for each section layout, in the shape of its fields
 * =============================================================================
 * One place for it, used twice:
 *   · the seed writes it into a page's section fields, so the content lives
 *     in ACF, images included (imported into the Media Library)
 *   · each partial falls back to it when a field is left empty
 *
 * Images are the file names in assets/images. Links are ACF link arrays
 * (title, url). Everything is the flat build's copy, word for word.
 * =============================================================================
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function rankinai_section_defaults( $layout ) {
	$L = array(

		'home_hero' => array(
			'eyebrow' => 'Digital marketing for firms that sell expertise',
			'title'   => 'Be the firm they find. And the one they choose.',
			'sub'     => 'You&rsquo;ve built a business on doing good work. We help more of the right people discover it, understand why you&rsquo;re worth choosing, and get in touch.',
			'sub2'    => 'Search, content, advertising, your website and follow-up, working together to bring you better business.',
			'button'  => array( 'title' => 'Get your growth audit', 'url' => '/growth-audit/' ),
			'link'    => array( 'title' => 'Book a 20-minute call', 'url' => '/call/' ),
		),

		'stories' => array(
			'title' => 'What changed when more people could see the value.',
			'note'  => 'Behind every result is a business with something worth choosing. Our job is to help the right buyers see it, and make their next step easier.',
			'cases' => array(
				array(
					'photo'        => 'case-pine-tree-lane.webp',
					'photo_alt'    => 'Pine Tree Lane kitchen: pale blue cabinetry and a marble island in a Dubai villa',
					'name'         => 'Pine Tree Lane',
					'meta'         => 'Interior design and bespoke joinery | UAE',
					'metric'       => '4&times;',
					'metric_label' => 'organic traffic in three months',
					'text'         => 'A factory, a showroom and a ten-year warranty that almost nobody could find. Rebuilding the pages around what buyers actually search for put their work in front of them.',
					'link'         => array( 'title' => 'Read their story', 'url' => '/success-stories/' ),
				),
				array(
					'photo'        => 'case-sweetrush.webp',
					'photo_alt'    => 'Two SweetRush colleagues working through a problem on a laptop',
					'name'         => 'SweetRush',
					'meta'         => 'Learning and development | USA',
					'metric'       => '',
					'metric_label' => '',
					'text'         => 'Twenty years of standing among learning and development buyers, behind a website that read like a much smaller firm. Making the expertise visible changed who got in touch.',
					'link'         => array( 'title' => 'Read their story', 'url' => '/success-stories/' ),
				),
				array(
					'photo'        => 'case-studio-ubique.webp',
					'photo_alt'    => 'Three Studio Ubique team members with coffee in the Zwolle office',
					'name'         => 'Studio Ubique',
					'meta'         => 'Digital agency | Netherlands | Our partner',
					'metric'       => '',
					'metric_label' => '',
					'text'         => 'Strong work with no public record a prospect could check. Publishing the proof gave people outside their network a reason to make contact.',
					'link'         => array( 'title' => 'Read their story', 'url' => '/success-stories/' ),
				),
			),
			'more'  => array( 'title' => 'Explore our success stories', 'url' => '/success-stories/' ),
		),

		'opportunity' => array(
			'title' => 'Your next client won&rsquo;t always come through someone you know.',
			'intro' => 'Referrals have helped build your business. A stronger online presence gives people outside that network a way to find you, and a reason to trust you. They might search for a specialist, ask AI for recommendations, or compare a few firms before making contact.',
			'lead'  => 'At each step, there&rsquo;s a question to answer.',
			'items' => array(
				array( 'icon' => 'search', 'name' => 'Can they find you?', 'text' => 'Show up for the services, problems and locations that matter to your business.' ),
				array( 'icon' => 'eye', 'name' => 'Can they see why you&rsquo;re right for them?', 'text' => 'Make your experience, approach and results easy to understand.' ),
				array( 'icon' => 'chat', 'name' => 'Can they take the next step?', 'text' => 'Give them a clear route to enquire, followed by a response that keeps the conversation moving.' ),
			),
			'close' => 'That&rsquo;s the journey we work on.',
		),

		'services_scroll' => array(
			'title'    => 'Give every step a job to do.',
			'note'     => 'Six connected specialisms, brought together around the work you want to win.',
			'services' => array(
				array( 'image' => 'service-search.webp', 'kicker' => 'AI and search visibility', 'title' => 'Be there when the search begins.', 'lead' => 'Help buyers discover your firm on Google, Maps and AI search. We improve the pages, business information and supporting evidence that make your expertise easier to find.', 'link' => array( 'title' => 'Explore AI and search visibility', 'url' => '/ai-visibility/' ) ),
				array( 'image' => 'service-paid.webp', 'kicker' => 'Paid advertising', 'title' => 'Reach people ready to take a closer look.', 'lead' => 'Put your offer in front of relevant buyers, with campaigns and landing pages built around a clear next step. Track which enquiries become worthwhile sales conversations.', 'link' => array( 'title' => 'Explore paid advertising', 'url' => '/paid-advertising/' ) ),
				array( 'image' => 'service-content.webp', 'kicker' => 'Content', 'title' => 'Give buyers a reason to put you on the shortlist.', 'lead' => 'Turn your team&rsquo;s knowledge and completed work into persuasive service pages, useful answers and credible case studies. Help prospects understand what makes your firm right for their situation.', 'link' => array( 'title' => 'Explore content', 'url' => '/content/' ) ),
				array( 'image' => 'service-web.webp', 'kicker' => 'Website and conversion', 'title' => 'Make your website easier to say yes to.', 'lead' => 'Help visitors find the information they need, see the value in your work, and enquire with confidence. We identify what needs improving and build what&rsquo;s missing.', 'link' => array( 'title' => 'Explore website and conversion', 'url' => '/website-conversion/' ) ),
				array( 'image' => 'service-reputation.webp', 'kicker' => 'Reviews and reputation', 'title' => 'Let your clients&rsquo; experience support the decision.', 'lead' => 'Make it easier to collect genuine reviews, keep business profiles accurate, and put relevant client feedback where prospects are deciding whether to contact you.', 'link' => array( 'title' => 'Explore reviews and reputation', 'url' => '/reputation/' ) ),
				array( 'image' => 'service-crm.webp', 'kicker' => 'CRM and automation', 'title' => 'Keep a promising enquiry moving.', 'lead' => 'Give every enquiry an owner and a next step. Connect your forms, CRM, reminders and follow-up so your team can spend more time having useful conversations.', 'link' => array( 'title' => 'Explore CRM and automation', 'url' => '/crm/' ) ),
			),
		),

		'method' => array(
			'title'   => 'A clear plan starts with understanding your business.',
			'steps'   => array(
				array( 'name' => 'Understand', 'lead' => 'Understand the work you want to win.', 'text' => 'We start with your goals, ideal clients and capacity. Which services do you want to grow? What makes an enquiry worth pursuing? How do you win that work today?' ),
				array( 'name' => 'Find the gaps', 'lead' => 'Find the gaps.', 'text' => 'We review your visibility, messaging and enquiry journey. Where access is available, we also examine performance data and follow-up to understand what needs attention.' ),
				array( 'name' => 'Agree priorities', 'lead' => 'Agree the priorities.', 'text' => 'You get a plan with a defined scope, fee and measures of progress. We agree what to fix first, what to build next, and where each channel fits.' ),
				array( 'name' => 'Deliver and improve', 'lead' => 'Deliver, measure and improve.', 'text' => 'We put the plan into action, review the results and adjust the work as we learn. You see what has been delivered, what is changing, and what happens next.' ),
			),
			'marks'   => array( array( 'mark' => 'One plan' ), array( 'mark' => 'Connected expertise' ), array( 'mark' => 'Clear priorities' ) ),
			'plan'    => '<p>Your monthly plan draws on the six specialisms above. The mix and level of work are agreed around your goals, budget and starting point.</p><p>Any initial setup work and ongoing fees are explained before you commit.</p>',
			'button'  => array( 'title' => 'Explore our approach', 'url' => '/how-we-work/' ),
			'button2' => array( 'title' => 'View packages', 'url' => '/pricing/' ),
		),

		'team' => array(
			'title' => 'You bring the expertise. We help it reach further.',
			'lead'  => '<p>You know your clients, your work and the questions that come up before someone buys, and we turn that knowledge into marketing your business can use: clearer pages, stronger proof, relevant campaigns and a better path from first visit to first conversation.</p>',
			'link'  => array( 'title' => 'Meet the people behind RankinAI', 'url' => '/about/' ),
		),

		'commitments' => array(
			'title' => 'Know what you&rsquo;re paying for. Know how it&rsquo;s going.',
			'items' => array(
				array( 'icon' => 'clipboard', 'name' => 'A defined scope', 'text' => 'We agree the work, responsibilities, fees and any separate costs before starting. Additional work is discussed before it is added.' ),
				array( 'icon' => 'shield', 'name' => 'Accounts under your control', 'text' => 'Your website, advertising and analytics accounts stay under your ownership. Access and handover arrangements are clear from the outset.' ),
				array( 'icon' => 'chart', 'name' => 'Reporting that connects to the business', 'text' => 'We track relevant enquiries and, where your sales data allows, the opportunities and clients they produce. Gaps in measurement are explained.' ),
				array( 'icon' => 'chat', 'name' => 'Straight conversations about progress', 'text' => 'You&rsquo;ll hear what is working, what needs attention, and what we recommend changing. Reviews lead to decisions about the next round of work.' ),
			),
		),

		'faq' => array(
			'title' => 'A few things you may be wondering.',
			'link'  => array( 'title' => 'Every question, answered in full', 'url' => '/questions/' ),
			'items' => array(
				array( 'q' => 'What does the free growth audit include?', 'a' => '<p>A focused review of your website, search presence and the path a prospect takes to enquire. We highlight the clearest opportunities and suggest where to start. Findings that need access to your accounts are identified separately.</p>' ),
				array( 'q' => 'Do we need all six services?', 'a' => '<p>Your business may need more attention in some areas than others. We agree the priorities and level of work within your plan, with a clear explanation of what is included.</p>' ),
				array( 'q' => 'How much does it cost?', 'a' => '<p>The investment depends on your starting point, goals and the scope of work. Our packages provide a starting point, and your proposal confirms the setup and monthly fees. Advertising spend and any additional software costs are identified separately.</p>' ),
				array( 'q' => 'How soon can we expect results?', 'a' => '<p>It depends on what needs improving. Some website and follow-up changes can be implemented early; building search visibility takes sustained work. We agree realistic milestones and review progress against them.</p>' ),
				array( 'q' => 'Can you work with our existing marketing team or agency?', 'a' => '<p>Yes. We can support the areas where you need additional expertise. Responsibilities, approvals and reporting are agreed so everyone knows who is doing what.</p>' ),
				array( 'q' => 'What commitment are we making?', 'a' => '<p>Requesting an audit creates no obligation to hire us. If we work together, the proposal sets out the scope, term, review points and cancellation arrangements before you agree.</p>' ),
			),
		),
	);
	return $L[ $layout ] ?? array();
}

/** The fields a layout stores as images, for the seed. Paths: 'field' or
 *  'repeater.field'. */
function rankinai_section_image_fields( $layout ) {
	$map = array(
		'stories'         => array( 'cases.photo' ),
		'services_scroll' => array( 'services.image' ),
	);
	return $map[ $layout ] ?? array();
}

/** A layout's defaults ready to store: images imported, as attachment IDs. */
function rankinai_section_values( $layout ) {
	$v = rankinai_section_defaults( $layout );
	foreach ( rankinai_section_image_fields( $layout ) as $path ) {
		$parts = explode( '.', $path );
		if ( 1 === count( $parts ) ) {
			if ( ! empty( $v[ $parts[0] ] ) ) { $v[ $parts[0] ] = rankinai_import_image( $v[ $parts[0] ] ); }
		} else {
			foreach ( (array) ( $v[ $parts[0] ] ?? array() ) as $i => $row ) {
				if ( ! empty( $row[ $parts[1] ] ) ) { $v[ $parts[0] ][ $i ][ $parts[1] ] = rankinai_import_image( $row[ $parts[1] ] ); }
			}
		}
	}
	return $v;
}
