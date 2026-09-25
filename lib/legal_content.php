<?php

declare(strict_types=1);

/** Legal page copy for CCPA / Do Not Sell and Terms of Service. */

const CCPA_SECTIONS = [
    [
        'title' => 'What "Sell" and "Share" Mean Here',
        'content' => 'Higher Learning Marketers is a college search service. When you request information, we send your details to the schools you ask about and to our lead marketplace and marketing partners, who pay us for those referrals. State privacy laws call this a sale of personal information. We also let advertising partners place cookies and pixels on our site so they can show you our ads elsewhere. California law calls this sharing for cross-context behavioral advertising; other states call it targeted advertising.',
    ],
    [
        'title' => 'Personal Information We Sell or Share',
        'content' => 'In the past 12 months we have sold or shared the following categories of personal information to the categories of third parties shown:

Category of personal information: Identifiers (name, postal address, email, phone and mobile number, IP address)
Sold or shared with: Schools and education providers; lead marketplaces such as PX Inc.; marketing partners; advertising partners

Category of personal information: Education details (education level, graduation year, degree and program of interest, campus preference, start date, RN license or teaching certificate)
Sold or shared with: Schools and education providers; lead marketplaces; marketing partners

Category of personal information: Demographic details (age or date of birth, military or veteran affiliation)
Sold or shared with: Schools and education providers; lead marketplaces; marketing partners

Category of personal information: Sensitive personal information (U.S. citizenship or residency status), only when a school requires it
Sold or shared with: Schools and education providers; lead marketplaces

Category of personal information: Internet and device activity (pages viewed, form interactions, referring URL, device and browser data)
Sold or shared with: Advertising partners; lead-verification providers (Jornaya, TrustedForm)

Category of personal information: Inferences about your educational interests
Sold or shared with: Schools and education providers; lead marketplaces; marketing partners

We do not knowingly sell or share the personal information of consumers under 16 years of age. We do not offer financial incentives or price differences in exchange for personal information.',
    ],
    [
        'title' => 'How to Opt Out',
        'content' => 'Choose any one of these methods. You do not need an account.

1. Online: submit the form at https://higherlearningmarketers.com/removeme/
2. Email: support@higherlearningmarketers.com with the subject line "Do Not Sell or Share"
3. Phone: 718 655-2573
4. Mail: Higher Learning Marketers, Privacy Requests, 138-07 82nd Drive, Briarwood, NY 11435

Include the name, email address, telephone number and zip code you used on our form so we can locate your record. We will not ask you to verify your identity to opt out, but we may ask for enough information to find you in our systems.

Global Privacy Control. If your browser or extension sends a Global Privacy Control (GPC) signal, we treat it as an opt-out of sale, sharing and targeted advertising for that browser and device. To also stop the sale of information you have already given us, submit an opt-out request using one of the methods above.

Authorized agents. Someone else may opt out for you if they give us written permission signed by you or a copy of a power of attorney.',
    ],
    [
        'title' => 'What Happens After You Opt Out',
        'content' => '- We stop selling or sharing your personal information within 15 business days of receiving your request.
- We notify the third parties to whom we sold or shared your information in the prior 90 days that you have opted out and instruct them not to sell or share it further.
- We will not sell or share your information again unless you later give us clear, affirmative permission. We will wait at least 12 months before asking you to opt back in.
- You keep receiving the same service on the same terms. We will not discriminate against you for exercising your rights.
- Opting out with Higher Learning Marketers does not withdraw consent you gave directly to a school. Contact the school to opt out of its communications.',
    ],
    [
        'title' => 'Limit the Use of My Sensitive Personal Information',
        'content' => 'The only sensitive personal information we collect is U.S. citizenship or residency status, and only when a school requires it to respond to your inquiry. We use it solely to deliver your inquiry to that school and do not use it to infer characteristics about you. You may ask us to limit or stop using it through any of the methods above.',
    ],
    [
        'title' => 'Other Rights You Have',
        'content' => 'You also have the right to know what personal information we hold about you, to correct or delete it, to receive a portable copy, and to appeal a decision we make about your request. These rights, the verification steps, and our response times are described in Section 7 of our Privacy Policy at https://higherlearningmarketers.com/privacy-policy .

California "Shine the Light" requests. California Civil Code Section 1798.83 lets you request a list of the third parties to which we disclosed personal information for their direct marketing purposes in the prior calendar year. Email support@higherlearningmarketers.com with the subject line "CA Privacy Disclosures."

Nevada residents. You may direct us not to sell your covered information under NRS Chapter 603A using any of the methods above.',
    ],
    [
        'title' => 'Do Not Track',
        'content' => 'We do not respond to browser "Do Not Track" signals because no industry standard exists for them. We do honor Global Privacy Control signals as described above.',
    ],
    [
        'title' => 'Contact',
        'content' => 'Questions about this notice or our privacy practices: support@higherlearningmarketers.com, 718 655-2573, or Higher Learning Marketers, 138-07 82nd Drive, Briarwood, NY 11435.

Copyright © 2026 Higher Learning App. View our Privacy Policy (https://higherlearningmarketers.com/privacy-policy ).',
    ],
];

$ccpa_sections = CCPA_SECTIONS;
$GLOBALS['ccpa_sections'] = $ccpa_sections;
