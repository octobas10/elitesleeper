<?php
/**
 * Privacy policy page markup - June 2026 (TCPA / CCPA aligned).
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Privacy policy section content (rendered at runtime - avoids Gutenberg JSON size limits).
 *
 * @return array<int, array{title: string, content: string}>
 */
function hlm_kadence_privacy_policy_sections(): array {
	return array(
		array(
			'title'   => 'Scope of This Policy',
			'content' => 'This Privacy Policy describes how Higher Learning Marketers ("HLM," "we," "us," or "our") collects, uses, shares, and protects personal information when you visit higherlearningmarketers.com, submit an inquiry form, communicate with us, or otherwise use our services. We may update this policy from time to time. Material changes will be posted on this page with a revised "Last updated" date. We encourage you to review this policy regularly.',
		),
		array(
			'title'   => 'Information We Collect',
			'content' => 'We collect information you provide directly, such as your name, email address, telephone number, mailing address, date of birth, age, education history, program interests, and any other details you submit through our forms or correspondence. We also collect information automatically when you use our site, including IP address, browser type, device identifiers, referring and exit pages, operating system, timestamps, clickstream data, and cookie-related identifiers. Some information is collected through cookies, pixels, and similar technologies as described below.',
		),
		array(
			'title'   => 'How We Use Your Information',
			'content' => 'We use personal information to provide our education marketing and lead generation services; match prospective students with accredited institutions and programs; respond to inquiries; communicate about programs, services, and offers you may request or that relate to your stated interests; improve our website and campaigns; analyze performance and audience trends; prevent fraud; comply with law; and enforce our agreements. We may contact you by email, telephone, text message (SMS/MMS), or other channels permitted by law and your consent preferences.',
		),
		array(
			'title'   => 'How We Share Information',
			'content' => 'We share personal information with accredited universities, colleges, and education program providers when you request information about their programs or otherwise consent to be contacted. We also share information with service providers that help us operate our website, deliver leads, host data, analyze traffic, and support marketing - under contractual confidentiality and security obligations. We may share or sell information to third parties engaged in education marketing, advertising, publishing, or related industries where permitted by law and consistent with the consent you provide at the point of collection. We may disclose information if required by law, to protect rights and safety, or in connection with a business transaction such as a merger or acquisition.',
		),
		array(
			'title'   => 'TCPA Consent and Telemarketing',
			'content' => 'By submitting your information through our forms or otherwise using our services, you acknowledge that your submission may constitute an inquiry or application for purposes of federal and state telemarketing laws, including the Telephone Consumer Protection Act (TCPA). You expressly consent to be contacted by HLM, our partner institutions, and authorized third parties by telephone (including autodialed, prerecorded, or artificial voice calls), text message, email, or other means at the number and address you provide - even if your number is listed on a state or federal Do-Not-Call registry. Consent is not a condition of purchase. You may revoke consent or opt out of marketing communications as described in "Your Rights and Opt-Out Choices."',
		),
		array(
			'title'   => 'Cookies and Tracking Technologies',
			'content' => 'We use cookies, web beacons, pixels, and similar technologies to remember preferences, measure site usage, deliver relevant content, and evaluate advertising effectiveness. Third-party advertising and analytics partners may also use tracking technologies on our site. You can manage cookies through your browser settings. Because industry standards for "Do Not Track" browser signals are not uniform, our site may not respond to all such signals in the same way across browsers and devices.',
		),
		array(
			'title'   => 'Your Rights and Opt-Out Choices',
			'content' => 'Depending on where you live, you may have rights to access, correct, delete, or obtain a copy of personal information we maintain about you, and to opt out of certain sharing for cross-context behavioral advertising. To opt out of marketing emails, use the unsubscribe link in our messages. To opt out of telemarketing or revise consent preferences, contact us using the information below. We may retain certain information as required by law, for fraud prevention, or in backup systems for a limited period after processing your request.',
		),
		array(
			'title'   => 'California Privacy Rights',
			'content' => 'California residents may request information about categories of personal information we have disclosed to third parties for those parties\' direct marketing purposes, subject to California Civil Code Section 1798.83. California Consumer Privacy Act (CCPA) / California Privacy Rights Act (CPRA) residents may also have rights to know, delete, correct, and opt out of the sale or sharing of personal information. To submit a request, email support@higherlearningmarketers.com with the subject line "California Privacy Request." We will verify requests as required by law. We do not knowingly sell personal information of consumers we know are under 16 without appropriate consent.',
		),
		array(
			'title'   => 'Data Security and Retention',
			'content' => 'We implement reasonable administrative, technical, and organizational safeguards designed to protect personal information. No method of transmission or storage is completely secure. We retain information for as long as needed to provide services, meet legal obligations, resolve disputes, and enforce agreements, then delete or de-identify it where practicable.',
		),
		array(
			'title'   => 'Children\'s Privacy',
			'content' => 'Our services are directed to prospective college and career students and institutional partners, not to children under 13. We do not knowingly collect personal information from children under 13. If you believe we have collected such information, contact us and we will take steps to delete it.',
		),
	);
}

/**
 * Build the full privacy policy page block markup.
 *
 * @return string
 */
function hlm_kadence_privacy_page_markup(): string {
	$hero = '<!-- wp:hlm/page-hero {"overline":"Legal","headline":"Privacy Policy","description":"How Higher Learning Marketers collects, uses, and protects your personal information - including TCPA consent, opt-out rights, and California privacy disclosures."} /-->' . "\n\n";

	$policy = '<!-- wp:hlm/policy {"headline":"Privacy Policy","lastUpdated":"June 17, 2026","intro":"Higher Learning Marketers is committed to transparent, consent-based data practices in U.S. education marketing. This policy explains what we collect, how we use it, and the choices available to you.","contactInfo":"Questions, opt-out requests, or California privacy inquiries: email support@higherlearningmarketers.com, call +1 (718) 685-2575, or write to Higher Learning Marketers, 138-07 82nd Drive, Briarwood, NY 11435."} /-->';

	return $hero . $policy;
}
