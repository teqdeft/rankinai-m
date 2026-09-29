<?php
/**
 * Site details, navigation and footer links
 * =============================================================================
 * The site details come from the "Website settings" options page (see
 * inc/acf-fields.php). Every one falls back to the flat build's value, so the
 * site reads correctly before the options page has been saved once.
 *
 * $NAV and $FOOTER are the flat build's arrays, copied from includes/config.php
 * unchanged. The header and footer are now edited on Website settings
 * (inc/chrome.php), and these are their defaults: what shows when a field is
 * empty. A three-column mega panel with an offer is not something
 * wp_nav_menu() can express, so the menu is a repeater there, not a WP menu.
 * $FOOTER is also read by the service and industry templates, to group pages.
 * =============================================================================
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/** One site detail: the options page value, or the flat build's value. */
function rankinai_site( string $key ): string {
	static $defaults = array(
		'name'    => 'RankinAI',
		'tagline' => 'Digital marketing for firms that sell expertise',
		'email'   => 'hello@rankinai.com',
		'phone'   => '+91 906 9710 000',
		'offices' => 'Zirakpur, Punjab, India',
		'address' => 'Office No. 303, Tricity Plaza, Zirakpur, Punjab 160104, India',
		'hours'   => 'Monday to Friday, 9am–6pm IST',
	);
	if ( 'year' === $key ) { return date( 'Y' ); }
	$v = function_exists( 'get_field' ) ? get_field( 'site_' . $key, 'option' ) : '';
	$v = is_string( $v ) ? trim( $v ) : '';
	return '' !== $v ? $v : ( $defaults[ $key ] ?? '' );
}

/** The flat build's $SITE, rebuilt from rankinai_site() for the partials that
 *  read it directly. Filled on template_redirect so options are loaded. */
add_action( 'template_redirect', function () {
	global $SITE;
	$SITE = array();
	foreach ( array( 'name', 'tagline', 'email', 'phone', 'offices', 'address', 'hours', 'year' ) as $k ) {
		$SITE[ $k ] = rankinai_site( $k );
	}
} );

global $NAV, $FOOTER;
$NAV = [
    /* ONE services panel, not two.
     *
     * This used to be split into "Get found" and "Get booked". Two problems
     * with that: they are verb phrases where a buyer scanning a nav expects
     * a category, and the split was not true. Paid advertising sat under
     * "get found" although its entire purpose is booked work, and reviews
     * sat under "get booked" although they are one of the largest drivers of
     * local and AI visibility. Six services, one list, in the order they
     * matter to most firms. */
    [
        'label' => 'Services',
        'id'    => 'panel-services',
        'cols'  => [
            ['label' => 'Getting found', 'items' => [
                ['AI and search visibility', '/ai-visibility/',        'Named when someone asks who does what you do.'],
                ['Content',                  '/content/',              'The pages that answer what buyers ask.'],
                ['Paid advertising',         '/paid-advertising/',     'Priced against the work it actually wins.'],
            ]],
            ['label' => 'Getting booked', 'items' => [
                ['Website and conversion',   '/website-conversion/',   'Sites and enquiry flows built to be measured.'],
                ['Reviews and reputation',   '/reputation/',           'The reviews that decide a call.'],
                ['CRM and automation',       '/crm/',                  'Follow-up, reminders and fewer lost enquiries.'],
            ]],
        ],
        'offer' => [
            'label'  => 'Not sure where your work comes from?',
            'text'   => 'We’ll show you which channels are earning the enquiries, and which are spending.',
            'cta'    => ['Get your growth audit', '/growth-audit/'],
        ],
    ],
    [
        'label' => 'Industries',
        'id'    => 'panel-industries',
        'cols'  => [
            ['label' => 'Design and construction', 'link' => '/build-and-design/', 'items' => [
                ['Interior design studios', '/interior-design/', 'Showrooms, portfolios and the enquiry that follows.'],
                ['Construction companies',  '/construction/',    'Quote requests worth quoting for.'],
                ['Architecture firms',      '/architecture/',    'Feasibility conversations, not fee shoppers.'],
            ]],
            ['label' => 'HR and recruitment', 'link' => '/hr-and-recruitment/', 'items' => [
                ['Recruitment and staffing',   '/recruitment-agencies/', 'Two audiences, one funnel: clients and candidates.'],
                ['HR outsourcing and payroll', '/hr-outsourcing/',       'A trust purchase, decided long before the call.'],
            ]],
            ['label' => 'Professional services', 'link' => '/professional-services/', 'items' => [
                ['Legal and corporate law', '/law-firms/',      'Matter type and city, where the intent is highest.'],
                ['Accounting and tax',      '/accounting/',     'Referrals built the firm. They will not grow it alone.'],
                ['Business consulting',     '/consulting/',     'You sell judgment. Your site describes a method.'],
                ['IT consulting and MSPs',  '/it-consulting/',  'Nobody looks until something breaks.'],
            ]],
        ],
        'offer' => [
            'label'  => 'Losing enquiries after hours?',
            'text'   => 'Twenty minutes, and we’ll tell you where they are going and what it is costing you.',
            'cta'    => ['Book a 20-minute call', '/call/'],
        ],
    ],
    ['label' => 'Success stories', 'link' => '/success-stories/'],
    ['label' => 'Pricing',         'link' => '/pricing/'],
    ['label' => 'Blog',            'link' => '/blog/'],
    ['label' => 'About',           'link' => '/about/'],
];

/* Footer link groups ------------------------------------------------------- */
$FOOTER = [
    'found' => [
        ['AI and search visibility', '/ai-visibility/'],
        ['Paid advertising',               '/paid-advertising/'],
        ['Content',     '/content/'],
    ],
    'booked' => [
        ['Website and conversion', '/website-conversion/'],
        ['Reviews and reputation', '/reputation/'],
        ['CRM and automation', '/crm/'],
    ],
    'company' => [
        ['About',           '/about/'],
        ['How we work',     '/how-we-work/'],
        ['Questions',       '/questions/'],
        ['Success stories', '/success-stories/'],
        ['Blog',            '/blog/'],
        ['Pricing',         '/pricing/'],
        ['Contact',         '/contact/'],
    ],
    'industries' => [
        ['Design and construction', '/build-and-design/', [
            ['Interior design studios', '/interior-design/'],
            ['Construction companies',  '/construction/'],
            ['Architecture firms',      '/architecture/'],
        ]],
        ['HR and recruitment', '/hr-and-recruitment/', [
            ['Recruitment and staffing agencies', '/recruitment-agencies/'],
            ['HR outsourcing and payroll',          '/hr-outsourcing/'],
        ]],
        ['Professional services', '/professional-services/', [
            ['Accounting and tax',        '/accounting/'],
            ['IT consulting and MSPs',    '/it-consulting/'],
            ['Legal and corporate law',   '/law-firms/'],
            ['Business consulting',       '/consulting/'],
        ]],
    ],
];

