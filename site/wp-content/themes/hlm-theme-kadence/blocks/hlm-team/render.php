<?php
/**
 * Team block render template.
 *
 * @package HLM_Kadence
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks content.
 * @var WP_Block $block      Block instance.
 */

$variant     = hlm_block_attr( $attributes, 'variant', 'profile' );
$overline    = hlm_block_attr( $attributes, 'overline', 'Our Team' );
$headline    = hlm_block_attr( $attributes, 'headline', 'Meet Our Experts' );
$description = hlm_block_attr( $attributes, 'description', '' );
$members     = hlm_block_attr( $attributes, 'members', array() );
$block_id    = hlm_block_id( 'hlm-team', $block );

$section_class = 'hlm-team hlm-section';
if ( 'cards' === $variant ) {
	$section_class .= ' hlm-team--cards';
} elseif ( 'leadership' === $variant ) {
	$section_class .= ' hlm-team--leadership';
}
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $section_class ); ?>">
	<div class="hlm-container">
		<div class="hlm-section-header hlm-animate">
			<?php if ( $overline ) : ?>
				<div class="hlm-overline"><?php echo esc_html( $overline ); ?></div>
			<?php endif; ?>
			<?php hlm_block_headline( $headline ); ?>
			<?php if ( $description ) : ?>
				<p class="hlm-body-lg"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( 'leadership' === $variant ) : ?>
			<div class="hlm-team-profiles">
				<?php foreach ( $members as $index => $member ) : ?>
					<?php
					$name       = $member['name'] ?? '';
					$initials   = '';
					$name_parts = preg_split( '/\s+/', trim( $name ) );
					if ( is_array( $name_parts ) ) {
						foreach ( array_slice( $name_parts, 0, 2 ) as $part ) {
							$initials .= mb_strtoupper( mb_substr( $part, 0, 1 ) );
						}
					}
					?>
					<article class="hlm-team-profile hlm-animate hlm-animate--delay-<?php echo esc_attr( min( $index + 1, 6 ) ); ?>">
						<div class="hlm-team-profile__head">
							<?php if ( ! empty( $member['photoUrl'] ) ) : ?>
								<img class="hlm-team-profile__photo"
									src="<?php echo esc_url( $member['photoUrl'] ); ?>"
									alt="<?php echo esc_attr( $name ); ?>"
									width="112" height="112"
									loading="lazy">
							<?php else : ?>
								<div class="hlm-team-profile__avatar" aria-hidden="true">
									<span><?php echo esc_html( $initials ?: '?' ); ?></span>
								</div>
							<?php endif; ?>
							<div class="hlm-team-profile__identity">
								<h3 class="hlm-team-profile__name"><?php echo esc_html( $name ); ?></h3>
								<?php if ( ! empty( $member['role'] ) ) : ?>
									<p class="hlm-team-profile__role"><?php echo esc_html( $member['role'] ); ?></p>
								<?php endif; ?>
							</div>
						</div>
						<?php if ( ! empty( $member['bio'] ) ) : ?>
							<p class="hlm-team-profile__bio"><?php echo esc_html( $member['bio'] ); ?></p>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php elseif ( 'cards' === $variant ) : ?>
			<div class="hlm-team__grid">
				<?php foreach ( $members as $index => $member ) : ?>
					<article class="hlm-team-card hlm-animate hlm-animate--delay-<?php echo esc_attr( min( $index + 1, 6 ) ); ?>">
						<?php if ( ! empty( $member['photoUrl'] ) ) : ?>
							<img class="hlm-team-card__img"
								src="<?php echo esc_url( $member['photoUrl'] ); ?>"
								alt="<?php echo esc_attr( $member['name'] ?? '' ); ?>"
								width="600" height="400">
						<?php else : ?>
							<div class="hlm-team-card__img hlm-team-card__img--placeholder hlm-bg-navy hlm-flex-center">
								<?php echo esc_html( mb_substr( $member['name'] ?? '?', 0, 1 ) ); ?>
							</div>
						<?php endif; ?>
						<div class="hlm-team-card__body">
							<h3 class="hlm-h3"><?php echo esc_html( $member['name'] ?? '' ); ?></h3>
							<p class="hlm-team-card__role"><?php echo esc_html( $member['role'] ?? '' ); ?></p>
							<p class="hlm-body"><?php echo esc_html( $member['bio'] ?? '' ); ?></p>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="hlm-grid-2">
				<?php foreach ( $members as $index => $member ) : ?>
					<div class="hlm-team__member hlm-animate hlm-animate--delay-<?php echo esc_attr( min( $index + 1, 6 ) ); ?>">
						<?php if ( ! empty( $member['photoUrl'] ) ) : ?>
							<img class="hlm-team__photo"
								src="<?php echo esc_url( $member['photoUrl'] ); ?>"
								alt="<?php echo esc_attr( $member['name'] ?? '' ); ?>"
								width="200" height="200">
						<?php else : ?>
							<div class="hlm-team__photo hlm-bg-navy hlm-flex-center" style="font-size:3rem;color:var(--hlm-gold-400);">
								<?php echo esc_html( mb_substr( $member['name'] ?? '?', 0, 1 ) ); ?>
							</div>
						<?php endif; ?>
						<h3 class="hlm-h3"><?php echo esc_html( $member['name'] ?? '' ); ?></h3>
						<p class="hlm-team__role"><?php echo esc_html( $member['role'] ?? '' ); ?></p>
						<p class="hlm-body"><?php echo esc_html( $member['bio'] ?? '' ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
