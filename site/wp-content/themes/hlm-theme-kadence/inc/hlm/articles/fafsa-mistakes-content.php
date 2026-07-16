<?php
/**
 * FAFSA mistakes article — native Gutenberg block markup.
 *
 * Flat top-level blocks only (no nested groups) for a clear List View.
 *
 * @package HLM_Kadence
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build one mistake block with semantic fields.
 *
 * @param string $number Mistake number.
 * @param string $title  Mistake title.
 * @param string $summary Context paragraph.
 * @param string $recommendation Action paragraph.
 * @param string $why_it_matters Impact paragraph.
 * @return string
 */
function hlm_fafsa_mistake_block( string $number, string $title, string $summary, string $recommendation, string $why_it_matters ): string {
	return hlm_article_block(
		'article-mistake',
		array(
			'number'         => $number,
			'title'          => $title,
			'summary'        => $summary,
			'recommendation' => $recommendation,
			'whyItMatters'   => $why_it_matters,
		)
	);
}

/**
 * Block-based post content for "5 FAFSA Mistakes to Avoid".
 *
 * @return string
 */
function hlm_article_fafsa_mistakes_content(): string {
	$blocks = array(
		// Introduction — top-level core blocks.
		hlm_article_heading( 'Why FAFSA Mistakes Cost U.S. Students Real Money' ),
		hlm_article_paragraph( 'The Free Application for Federal Student Aid (FAFSA) is the gateway to federal grants, work-study, and loans for college students across the United States. A single error can delay your aid package, reduce eligibility, or leave thousands of dollars on the table. Every year, millions of students submit the FAFSA - and a significant share make preventable mistakes that slow verification or disqualify them from aid they would otherwise receive.' ),
		hlm_article_paragraph( 'Whether you are a first-time freshman, a working adult returning to school, or a parent helping a dependent student, understanding common FAFSA pitfalls protects your timeline and your budget. Financial aid offices cannot finalize packages until your FAFSA is complete and accurate.' ),
		hlm_article_paragraph( 'This guide covers five mistakes U.S. students make most often - and practical steps to avoid them before deadlines pass.' ),

		// Five mistake cards — each a named block in List View.
		hlm_fafsa_mistake_block(
			'1',
			'Mistake 1: Missing Federal and State Deadlines',
			'The FAFSA opens annually for the upcoming academic year, and federal deadlines are only part of the picture. Many states and individual colleges award aid on a first-come, first-served basis until funds run out. Waiting until the last minute risks losing state grants or institutional scholarships tied to early submission.',
			'Mark three dates on your calendar: the federal FAFSA deadline, your state aid deadline (rules vary by state), and each target college\'s priority financial aid date. Schools publish priority dates on financial aid web pages - treat them as hard targets, not suggestions.',
			'Set reminders to file as soon as tax documentation is available. You can often use prior-prior year income data, so waiting for the current tax season to finish is not always necessary.'
		),
		hlm_fafsa_mistake_block(
			'2',
			'Mistake 2: FSA ID and Login Problems',
			'Every FAFSA filer needs a Federal Student Aid (FSA) ID to sign electronically. Dependent students also need a parent FSA ID. Creating IDs in advance prevents last-minute lockouts when deadlines approach.',
			'Create FSA IDs at least a week before you plan to submit. Store credentials securely and verify that parent and student accounts are linked correctly for dependent filers.',
			'Common errors include using the wrong email, forgetting passwords, or mismatched legal names on Social Security records - all of which cause authentication failures that delay filing.'
		),
		hlm_fafsa_mistake_block(
			'3',
			'Mistake 3: Leaving Schools Off Your List',
			'The FAFSA allows you to list multiple colleges that should receive your Student Aid Report. Some students list only one dream school and omit backups, transfers, or community colleges they are genuinely considering.',
			'Include every college you might attend, up to the form limit, even if you have not applied yet. You can remove schools later; you cannot recover lost time if aid funds are allocated while you amend your list.',
			'If a school is not on your FAFSA list, it cannot package federal aid for you. Adding schools later is possible but delays processing at institutions you omitted initially.'
		),
		hlm_fafsa_mistake_block(
			'4',
			'Mistake 4: Skipping Verification or Income Documentation',
			'After submission, many students are selected for verification - a process where colleges confirm FAFSA data against tax transcripts, W-2s, or other documents. Ignoring verification requests freezes your aid offer.',
			'Respond to verification requests immediately. Use the IRS Data Retrieval Tool when available to reduce mismatch risk and simplify the process.',
			'Errors in reported income, household size, or dependency status often trigger verification. Delays here are one of the top reasons aid arrives after tuition bills are due.'
		),
		hlm_fafsa_mistake_block(
			'5',
			'Mistake 5: Assuming You Will Not Qualify',
			'Students skip the FAFSA because they believe their family earns too much for grants. In reality, many colleges require a FAFSA on file for merit scholarships, institutional aid, and federal student loans - regardless of grant eligibility.',
			'Complete the FAFSA every year you attend college. There is no income cutoff that makes filing pointless - it is the only way to know what aid you may receive.',
			'Working adults often underestimate aid available for part-time or career-focused programs. State programs and college-specific grants sometimes surprise applicants who complete the form.'
		),

		// Checklist.
		hlm_article_block(
			'article-checklist',
			array(
				'headline'   => 'Build a FAFSA Checklist Before You File',
				'paragraphs' => array(
					'Gather Social Security numbers, federal tax returns or income estimates, records of untaxed income, and asset information before logging in. Dependent students should coordinate with parents on timing so both FSA IDs are ready.',
					'Double-check name spelling, citizenship status, and dependency questions. Small data entry errors create weeks of verification.',
				),
				'items'      => array(
					'Social Security numbers for student and parent (if dependent)',
					'Federal tax returns or income estimates',
					'Records of untaxed income',
					'Asset information (savings, investments)',
					'FSA IDs created and verified for all required signers',
					'List of every college you may attend',
				),
				'outro'      => array(
					'After submission, download your confirmation page and monitor email from colleges for next steps.',
				),
			)
		),

		// Help callout.
		hlm_article_block(
			'article-callout',
			array(
				'variant'    => 'help',
				'headline'   => 'Get Help Navigating Financial Aid and Program Options',
				'paragraphs' => array(
					'FAFSA completion is one step in a larger enrollment journey. Choosing the right program, understanding total cost of attendance, and comparing aid packages across schools matters as much as filing correctly.',
					'Higher Learning Marketers helps prospective students across the United States explore accredited nursing, business, and career-focused programs while connecting with advisors who can discuss financial aid timelines alongside admissions. Read our guide on <a href="/berkeley-college-application-tips-for-working-adults/">applying to college as a working adult</a> or <a href="/contact-us/">contact an advisor</a> when you are ready for personalized guidance.',
				),
			)
		),

		// Conclusion — top-level core blocks.
		hlm_article_heading( 'Stay Organized Through Award Season' ),
		hlm_article_paragraph( 'Compare financial aid award letters carefully - cost of attendance, gift aid versus loans, and renewal conditions differ by institution. Accept aid packages only after understanding net price for your situation.' ),
		hlm_article_paragraph( 'Refile the FAFSA each year you attend college. Aid eligibility changes with income, household size, and enrollment status.' ),
		hlm_article_paragraph( 'Avoiding these five FAFSA mistakes puts you in a stronger position to fund your education and focus on choosing the right college pathway in the United States.' ),
	);

	return implode( "\n\n", $blocks );
}
