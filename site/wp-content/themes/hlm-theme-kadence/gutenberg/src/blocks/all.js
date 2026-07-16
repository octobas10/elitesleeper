import { __ } from '@wordpress/i18n';
import { useBlockProps, InnerBlocks, useInnerBlocksProps } from '@wordpress/block-editor';
import {
	SectionHeader,
	RepeaterActions,
	updateListItem,
	removeListItem,
	addListItem,
	InspectorControls,
	PanelBody,
	Button,
	TextControl,
	ToggleControl,
	SelectControl,
	RichText,
	MediaImageControl,
	MediaBackgroundPanel,
} from '../shared';

const emptySave = () => null;

// -- Hero --
export function EditHero( { attributes, setAttributes } ) {
	const variant = attributes.variant || 'light';
	const blockProps = useBlockProps( { className: variant === 'landing' ? 'hlm-hero hlm-hero--landing' : 'hlm-hero hlm-hero--light' } );

	return (
		<section { ...blockProps }>
			<InspectorControls>
				<MediaBackgroundPanel attributes={ attributes } setAttributes={ setAttributes } />
				<PanelBody title={ __( 'Buttons & form', 'hlm-kadence' ) }>
					<TextControl label={ __( 'Primary CTA URL', 'hlm-kadence' ) } value={ attributes.primaryCtaUrl } onChange={ ( v ) => setAttributes( { primaryCtaUrl: v } ) } />
					<TextControl label={ __( 'Secondary CTA URL', 'hlm-kadence' ) } value={ attributes.secondaryCtaUrl } onChange={ ( v ) => setAttributes( { secondaryCtaUrl: v } ) } />
					<ToggleControl label={ __( 'Show lead form', 'hlm-kadence' ) } checked={ attributes.showLeadForm } onChange={ ( v ) => setAttributes( { showLeadForm: v } ) } />
				</PanelBody>
			</InspectorControls>
			<div className="hlm-hero-inner">
				<div className="hlm-hero-content">
					<RichText tagName="div" className="hlm-overline" value={ attributes.overline } onChange={ ( v ) => setAttributes( { overline: v } ) } placeholder={ __( 'Overline', 'hlm-kadence' ) } allowedFormats={ [] } />
					<RichText tagName="h1" className="hlm-h1" value={ attributes.headline } onChange={ ( v ) => setAttributes( { headline: v } ) } placeholder={ __( 'Headline', 'hlm-kadence' ) } />
					<RichText tagName="p" className="hlm-hero-desc" value={ attributes.description } onChange={ ( v ) => setAttributes( { description: v } ) } placeholder={ __( 'Description', 'hlm-kadence' ) } allowedFormats={ [] } />
					<div className="hlm-hero-btns">
						<RichText tagName="span" className="hlm-btn hlm-btn-primary" value={ attributes.primaryCtaText } onChange={ ( v ) => setAttributes( { primaryCtaText: v } ) } placeholder={ __( 'Primary button', 'hlm-kadence' ) } allowedFormats={ [] } />
						<RichText tagName="span" className="hlm-btn hlm-btn-outline" value={ attributes.secondaryCtaText } onChange={ ( v ) => setAttributes( { secondaryCtaText: v } ) } placeholder={ __( 'Secondary button', 'hlm-kadence' ) } allowedFormats={ [] } />
					</div>
					<p className="hlm-hero-trust-text">
						<RichText tagName="strong" value={ attributes.trustHighlight } onChange={ ( v ) => setAttributes( { trustHighlight: v } ) } placeholder="10,000+" allowedFormats={ [] } />
						<RichText tagName="span" value={ attributes.trustText } onChange={ ( v ) => setAttributes( { trustText: v } ) } placeholder={ __( 'Trust line', 'hlm-kadence' ) } allowedFormats={ [] } />
					</p>
				</div>
				{ attributes.showLeadForm && (
					<div className="hlm-form-card">
						<p className="hlm-form-placeholder">{ __( 'Lead capture form (Student / University / Berkeley tabs) - preview on live site.', 'hlm-kadence' ) }</p>
					</div>
				) }
			</div>
		</section>
	);
}

// -- Stats --
const STAT_TEMPLATE = { number: 0, prefix: '', suffix: '+', label: '' };

export function EditStats( { attributes, setAttributes } ) {
	const variant = attributes.variant || 'bar';
	const blockProps = useBlockProps( { className: variant === 'network' ? 'hlm-stats hlm-stats--network hlm-section' : 'hlm-stats' } );
	const stats = attributes.stats || [];

	return (
		<section { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Layout', 'hlm-kadence' ) }>
					<SelectControl
						label={ __( 'Style variant', 'hlm-kadence' ) }
						value={ variant }
						options={ [
							{ label: __( 'Homepage bar', 'hlm-kadence' ), value: 'bar' },
							{ label: __( 'Network section', 'hlm-kadence' ), value: 'network' },
						] }
						onChange={ ( v ) => setAttributes( { variant: v } ) }
					/>
				</PanelBody>
			</InspectorControls>
			{ variant === 'network' ? (
				<div className="hlm-container">
					<SectionHeader attributes={ attributes } setAttributes={ setAttributes } />
					<div className="hlm-network-visual">
						<div className="hlm-stats-grid hlm-stats-grid--network">
							{ stats.map( ( stat, index ) => (
								<div key={ index } className="hlm-stat-item">
									<div className="hlm-stat-number">{ String( stat.number ?? 0 ) }{ stat.suffix }</div>
									<RichText tagName="div" className="hlm-stat-label" value={ stat.label || '' } onChange={ ( v ) => setAttributes( { stats: updateListItem( stats, index, { label: v } ) } ) } placeholder={ __( 'Label', 'hlm-kadence' ) } allowedFormats={ [] } />
									<TextControl label={ __( 'Number', 'hlm-kadence' ) } type="number" value={ String( stat.number ?? '' ) } onChange={ ( v ) => setAttributes( { stats: updateListItem( stats, index, { number: parseInt( v, 10 ) || 0 } ) } ) } />
									<TextControl label={ __( 'Suffix', 'hlm-kadence' ) } value={ stat.suffix || '' } onChange={ ( v ) => setAttributes( { stats: updateListItem( stats, index, { suffix: v } ) } ) } />
									<Button isDestructive variant="link" onClick={ () => setAttributes( { stats: removeListItem( stats, index ) } ) }>{ __( 'Remove', 'hlm-kadence' ) }</Button>
								</div>
							) ) }
						</div>
					</div>
					<RepeaterActions addLabel={ __( 'Add Stat', 'hlm-kadence' ) } onAdd={ () => setAttributes( { stats: addListItem( stats, STAT_TEMPLATE ) } ) } />
				</div>
			) : (
				<>
					<div className="hlm-stats-grid">
						{ stats.map( ( stat, index ) => (
							<div key={ index } className="hlm-stat-item">
								<div className="hlm-stat-number">{ String( stat.number ?? 0 ) }{ stat.suffix }</div>
								<RichText tagName="div" className="hlm-stat-label" value={ stat.label || '' } onChange={ ( v ) => setAttributes( { stats: updateListItem( stats, index, { label: v } ) } ) } placeholder={ __( 'Label', 'hlm-kadence' ) } allowedFormats={ [] } />
								<TextControl label={ __( 'Number', 'hlm-kadence' ) } type="number" value={ String( stat.number ?? '' ) } onChange={ ( v ) => setAttributes( { stats: updateListItem( stats, index, { number: parseInt( v, 10 ) || 0 } ) } ) } />
								<TextControl label={ __( 'Suffix', 'hlm-kadence' ) } value={ stat.suffix || '' } onChange={ ( v ) => setAttributes( { stats: updateListItem( stats, index, { suffix: v } ) } ) } />
								<Button isDestructive variant="link" onClick={ () => setAttributes( { stats: removeListItem( stats, index ) } ) }>{ __( 'Remove', 'hlm-kadence' ) }</Button>
							</div>
						) ) }
					</div>
					<RepeaterActions addLabel={ __( 'Add Stat', 'hlm-kadence' ) } onAdd={ () => setAttributes( { stats: addListItem( stats, STAT_TEMPLATE ) } ) } />
				</>
			) }
		</section>
	);
}

// -- Programs --
const PROGRAM_TEMPLATE = { icon: 'activity', name: '' };

export function EditPrograms( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( { className: 'hlm-programs hlm-section' } );
	const programs = attributes.programs || [];

	return (
		<section { ...blockProps }>
			<SectionHeader attributes={ attributes } setAttributes={ setAttributes } />
			<div className="hlm-programs-grid">
				{ programs.map( ( program, index ) => (
					<div key={ index } className="hlm-program-tag">
						<RichText tagName="span" value={ program.name || '' } onChange={ ( v ) => setAttributes( { programs: updateListItem( programs, index, { name: v } ) } ) } placeholder={ __( 'Program name', 'hlm-kadence' ) } allowedFormats={ [] } />
						<Button isDestructive variant="link" onClick={ () => setAttributes( { programs: removeListItem( programs, index ) } ) }>{ __( 'Remove', 'hlm-kadence' ) }</Button>
					</div>
				) ) }
			</div>
			<RepeaterActions addLabel={ __( 'Add Program', 'hlm-kadence' ) } onAdd={ () => setAttributes( { programs: addListItem( programs, PROGRAM_TEMPLATE ) } ) } />
		</section>
	);
}

// -- Process --
const STEP_TEMPLATE = { number: '', icon: 'document', title: '', description: '', ctaText: '', ctaUrl: '' };

const PROCESS_INNER_TEMPLATE = [
		[
		'core/columns',
		{},
		[
			[
				'core/column',
				{},
				[
					[
						'core/group',
						{ className: 'hlm-process-card', layout: { type: 'constrained' } },
						[
							[ 'core/paragraph', { content: '01', className: 'hlm-process-num' } ],
							[ 'core/heading', { level: 4, content: __( 'Step title', 'hlm-kadence' ) } ],
							[ 'core/paragraph', { content: __( 'Step description.', 'hlm-kadence' ) } ],
						],
					],
				],
			],
			[
				'core/column',
				{},
				[
					[
						'core/group',
						{ className: 'hlm-process-card', layout: { type: 'constrained' } },
						[
							[ 'core/paragraph', { content: '02', className: 'hlm-process-num' } ],
							[ 'core/heading', { level: 4, content: __( 'Step title', 'hlm-kadence' ) } ],
							[ 'core/paragraph', { content: __( 'Step description.', 'hlm-kadence' ) } ],
						],
					],
				],
			],
			[
				'core/column',
				{},
				[
					[
						'core/group',
						{ className: 'hlm-process-card', layout: { type: 'constrained' } },
						[
							[ 'core/paragraph', { content: '03', className: 'hlm-process-num' } ],
							[ 'core/heading', { level: 4, content: __( 'Step title', 'hlm-kadence' ) } ],
							[ 'core/paragraph', { content: __( 'Step description.', 'hlm-kadence' ) } ],
						],
					],
				],
			],
		],
	],
];

const PROCESS_ALLOWED_BLOCKS = [
	'core/group',
	'core/columns',
	'core/column',
	'core/heading',
	'core/paragraph',
	'core/buttons',
	'core/button',
];

export function saveProcess() {
	return <InnerBlocks.Content />;
}

export function EditProcess( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( { className: 'hlm-process hlm-section' } );
	const steps = attributes.steps || [];
	const layoutMode = attributes.layoutMode || 'steps';
	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'hlm-process-inner' },
		{
			allowedBlocks: PROCESS_ALLOWED_BLOCKS,
			template: PROCESS_INNER_TEMPLATE,
			templateLock: false,
		}
	);

	return (
		<section { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Layout', 'hlm-kadence' ) }>
					<SelectControl
						label={ __( 'Step content', 'hlm-kadence' ) }
						value={ layoutMode }
						options={ [
							{ label: __( 'Structured steps (default)', 'hlm-kadence' ), value: 'steps' },
							{ label: __( 'Core blocks (Group / Columns)', 'hlm-kadence' ), value: 'blocks' },
						] }
						onChange={ ( v ) => setAttributes( { layoutMode: v } ) }
						help={ __( 'Use core blocks for editable Group and Columns in List View.', 'hlm-kadence' ) }
					/>
					<SelectControl
						label={ __( 'Style variant', 'hlm-kadence' ) }
						value={ attributes.variant }
						options={ [
							{ label: 'Home (dark cards)', value: 'home' },
							{ label: 'Dark strip', value: 'strip' },
							{ label: 'Cream background', value: 'cream' },
						] }
						onChange={ ( v ) => setAttributes( { variant: v } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Section CTA', 'hlm-kadence' ) }>
					<TextControl label={ __( 'Footer button text', 'hlm-kadence' ) } value={ attributes.footerCtaText || '' } onChange={ ( v ) => setAttributes( { footerCtaText: v } ) } />
					<TextControl label={ __( 'Footer button URL', 'hlm-kadence' ) } value={ attributes.footerCtaUrl || '' } onChange={ ( v ) => setAttributes( { footerCtaUrl: v } ) } />
				</PanelBody>
			</InspectorControls>
			<SectionHeader attributes={ attributes } setAttributes={ setAttributes } />
			{ layoutMode === 'blocks' ? (
				<div { ...innerBlocksProps } />
			) : (
				<>
					<div
						className={ `hlm-process-grid${ steps.length >= 4 ? ' hlm-process-grid--4' : '' }` }
						style={ { '--hlm-process-cols': String( Math.min( steps.length || 3, 4 ) ) } }
					>
						{ steps.map( ( step, index ) => (
							<div key={ index } className="hlm-process-grid__step">
								<div className="hlm-process-card">
									<div className="hlm-process-card__head">
										<RichText tagName="div" className="hlm-process-num" value={ step.number || '' } onChange={ ( v ) => setAttributes( { steps: updateListItem( steps, index, { number: v } ) } ) } placeholder="01" allowedFormats={ [] } />
									</div>
									<RichText tagName="h4" value={ step.title || '' } onChange={ ( v ) => setAttributes( { steps: updateListItem( steps, index, { title: v } ) } ) } placeholder={ __( 'Step title', 'hlm-kadence' ) } allowedFormats={ [] } />
									<RichText tagName="p" value={ step.description || '' } onChange={ ( v ) => setAttributes( { steps: updateListItem( steps, index, { description: v } ) } ) } placeholder={ __( 'Step description', 'hlm-kadence' ) } allowedFormats={ [] } />
									<TextControl label={ __( 'Step link text', 'hlm-kadence' ) } value={ step.ctaText || '' } onChange={ ( v ) => setAttributes( { steps: updateListItem( steps, index, { ctaText: v } ) } ) } />
									<TextControl label={ __( 'Step link URL', 'hlm-kadence' ) } value={ step.ctaUrl || '' } onChange={ ( v ) => setAttributes( { steps: updateListItem( steps, index, { ctaUrl: v } ) } ) } help={ __( 'Use #form or #programs for on-page scroll.', 'hlm-kadence' ) } />
									{ step.ctaText && step.ctaUrl ? (
										<a className="hlm-process-card__cta hlm-btn-arrow" href={ step.ctaUrl }>{ step.ctaText }</a>
									) : null }
									<Button isDestructive variant="link" onClick={ () => setAttributes( { steps: removeListItem( steps, index ) } ) }>{ __( 'Remove step', 'hlm-kadence' ) }</Button>
								</div>
							</div>
						) ) }
					</div>
					<RepeaterActions addLabel={ __( 'Add Step', 'hlm-kadence' ) } onAdd={ () => setAttributes( { steps: addListItem( steps, STEP_TEMPLATE ) } ) } />
				</>
			) }
		</section>
	);
}

// -- Testimonials --
const TESTIMONIAL_TEMPLATE = { quote: '', author: '', meta: '', photoUrl: '', stars: 5 };

export function EditTestimonials( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( { className: 'hlm-testimonials hlm-section' } );
	const testimonials = attributes.testimonials || [];

	return (
		<section { ...blockProps }>
			<SectionHeader attributes={ attributes } setAttributes={ setAttributes } />
			<div className="hlm-testimonials-grid">
				{ testimonials.map( ( item, index ) => (
					<div key={ index } className="hlm-testimonial-card">
						<RichText tagName="p" className="hlm-testimonial-quote" value={ item.quote || '' } onChange={ ( v ) => setAttributes( { testimonials: updateListItem( testimonials, index, { quote: v } ) } ) } placeholder={ __( 'Quote', 'hlm-kadence' ) } allowedFormats={ [] } />
						<RichText tagName="h5" value={ item.author || '' } onChange={ ( v ) => setAttributes( { testimonials: updateListItem( testimonials, index, { author: v } ) } ) } placeholder={ __( 'Author', 'hlm-kadence' ) } allowedFormats={ [] } />
						<RichText tagName="span" value={ item.meta || '' } onChange={ ( v ) => setAttributes( { testimonials: updateListItem( testimonials, index, { meta: v } ) } ) } placeholder={ __( 'Role / company', 'hlm-kadence' ) } allowedFormats={ [] } />
						<TextControl label={ __( 'Photo URL', 'hlm-kadence' ) } value={ item.photoUrl || '' } onChange={ ( v ) => setAttributes( { testimonials: updateListItem( testimonials, index, { photoUrl: v } ) } ) } />
						<Button isDestructive variant="link" onClick={ () => setAttributes( { testimonials: removeListItem( testimonials, index ) } ) }>{ __( 'Remove', 'hlm-kadence' ) }</Button>
					</div>
				) ) }
			</div>
			<RepeaterActions addLabel={ __( 'Add Testimonial', 'hlm-kadence' ) } onAdd={ () => setAttributes( { testimonials: addListItem( testimonials, TESTIMONIAL_TEMPLATE ) } ) } />
		</section>
	);
}

// -- CTA --
export function EditCta( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( { className: 'hlm-cta hlm-section' } );

	return (
		<section { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Button links', 'hlm-kadence' ) }>
					<TextControl label={ __( 'Primary URL', 'hlm-kadence' ) } value={ attributes.primaryUrl } onChange={ ( v ) => setAttributes( { primaryUrl: v } ) } />
					<TextControl label={ __( 'Secondary URL', 'hlm-kadence' ) } value={ attributes.secondaryUrl } onChange={ ( v ) => setAttributes( { secondaryUrl: v } ) } />
				</PanelBody>
			</InspectorControls>
			<div className="hlm-cta-inner">
				<RichText tagName="div" className="hlm-overline" value={ attributes.overline } onChange={ ( v ) => setAttributes( { overline: v } ) } placeholder={ __( 'Overline', 'hlm-kadence' ) } allowedFormats={ [] } />
				<RichText tagName="h2" className="hlm-h2" value={ attributes.headline } onChange={ ( v ) => setAttributes( { headline: v } ) } placeholder={ __( 'Headline', 'hlm-kadence' ) } />
				<RichText tagName="p" value={ attributes.description } onChange={ ( v ) => setAttributes( { description: v } ) } placeholder={ __( 'Description', 'hlm-kadence' ) } allowedFormats={ [] } />
				<div className="hlm-cta-btns">
					<RichText tagName="span" className="hlm-btn hlm-btn-primary" value={ attributes.primaryText } onChange={ ( v ) => setAttributes( { primaryText: v } ) } placeholder={ __( 'Primary button', 'hlm-kadence' ) } allowedFormats={ [] } />
					<RichText tagName="span" className="hlm-btn hlm-btn-outline" value={ attributes.secondaryText } onChange={ ( v ) => setAttributes( { secondaryText: v } ) } placeholder={ __( 'Secondary button', 'hlm-kadence' ) } allowedFormats={ [] } />
				</div>
			</div>
		</section>
	);
}

// -- Generic repeater card block helper --
function EditCardGrid( { attributes, setAttributes, blockClass, itemsKey, itemTemplate, addLabel, renderCard } ) {
	const blockProps = useBlockProps( { className: blockClass } );
	const items = attributes[ itemsKey ] || [];

	return (
		<section { ...blockProps }>
			<SectionHeader attributes={ attributes } setAttributes={ setAttributes } showDescription={ attributes.description !== undefined } />
			<div className="hlm-unis-grid">
				{ items.map( ( item, index ) => (
					<div key={ index } className="hlm-uni-card">
						{ renderCard( item, index, items ) }
						<Button isDestructive variant="link" onClick={ () => setAttributes( { [ itemsKey ]: removeListItem( items, index ) } ) }>{ __( 'Remove', 'hlm-kadence' ) }</Button>
					</div>
				) ) }
			</div>
			<RepeaterActions addLabel={ addLabel } onAdd={ () => setAttributes( { [ itemsKey ]: addListItem( items, itemTemplate ) } ) } />
		</section>
	);
}

export function EditServices( { attributes, setAttributes } ) {
	const services = attributes.services || [];
	return EditCardGrid( {
		attributes,
		setAttributes,
		blockClass: 'hlm-services hlm-section hlm-bg-cream',
		itemsKey: 'services',
		itemTemplate: { icon: '✨', title: '', description: '', tags: '', link: '' },
		addLabel: __( 'Add Service', 'hlm-kadence' ),
		renderCard: ( item, index, items ) => (
			<>
				<RichText tagName="div" className="hlm-card__icon" value={ item.icon || '' } onChange={ ( v ) => setAttributes( { services: updateListItem( services, index, { icon: v } ) } ) } allowedFormats={ [] } />
				<RichText tagName="h3" className="hlm-h3" value={ item.title || '' } onChange={ ( v ) => setAttributes( { services: updateListItem( services, index, { title: v } ) } ) } allowedFormats={ [] } />
				<RichText tagName="p" className="hlm-body" value={ item.description || '' } onChange={ ( v ) => setAttributes( { services: updateListItem( services, index, { description: v } ) } ) } allowedFormats={ [] } />
			</>
		),
	} );
}

export function EditTeam( { attributes, setAttributes } ) {
	const variant = attributes.variant || 'profile';
	const sectionClass = `hlm-team hlm-section${ variant === 'cards' ? ' hlm-team--cards' : '' }${ variant === 'leadership' ? ' hlm-team--leadership' : '' }`;
	const blockProps = useBlockProps( { className: sectionClass } );
	const members = attributes.members || [];

	return (
		<section { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Layout', 'hlm-kadence' ) }>
					<SelectControl
						label={ __( 'Style variant', 'hlm-kadence' ) }
						value={ variant }
						options={ [
							{ label: __( 'Profile circles', 'hlm-kadence' ), value: 'profile' },
							{ label: __( 'Card grid', 'hlm-kadence' ), value: 'cards' },
							{ label: __( 'Leadership editorial', 'hlm-kadence' ), value: 'leadership' },
						] }
						onChange={ ( v ) => setAttributes( { variant: v } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div className="hlm-container">
				<SectionHeader attributes={ attributes } setAttributes={ setAttributes } />
				<div className={ variant === 'leadership' ? 'hlm-team-profiles' : variant === 'cards' ? 'hlm-team__grid' : 'hlm-grid-2' }>
					{ members.map( ( member, index ) => (
						<div key={ index } className={ variant === 'leadership' ? 'hlm-team-profile' : variant === 'cards' ? 'hlm-team-card' : 'hlm-team__member' }>
							<TextControl label={ __( 'Photo URL', 'hlm-kadence' ) } value={ member.photoUrl || '' } onChange={ ( v ) => setAttributes( { members: updateListItem( members, index, { photoUrl: v } ) } ) } />
							<RichText tagName="h3" className={ variant === 'leadership' ? 'hlm-team-profile__name' : 'hlm-h3' } value={ member.name || '' } onChange={ ( v ) => setAttributes( { members: updateListItem( members, index, { name: v } ) } ) } placeholder={ __( 'Name', 'hlm-kadence' ) } allowedFormats={ [] } />
							<RichText tagName="p" className={ variant === 'leadership' ? 'hlm-team-profile__role' : variant === 'cards' ? 'hlm-team-card__role' : 'hlm-team__role' } value={ member.role || '' } onChange={ ( v ) => setAttributes( { members: updateListItem( members, index, { role: v } ) } ) } placeholder={ __( 'Role', 'hlm-kadence' ) } allowedFormats={ [] } />
							<RichText tagName="p" className={ variant === 'leadership' ? 'hlm-team-profile__bio' : 'hlm-body' } value={ member.bio || '' } onChange={ ( v ) => setAttributes( { members: updateListItem( members, index, { bio: v } ) } ) } placeholder={ __( 'Bio', 'hlm-kadence' ) } allowedFormats={ [] } />
							<Button isDestructive variant="link" onClick={ () => setAttributes( { members: removeListItem( members, index ) } ) }>{ __( 'Remove', 'hlm-kadence' ) }</Button>
						</div>
					) ) }
				</div>
				<RepeaterActions addLabel={ __( 'Add Team Member', 'hlm-kadence' ) } onAdd={ () => setAttributes( { members: addListItem( members, { name: '', role: '', bio: '', photoUrl: '' } ) } ) } />
			</div>
		</section>
	);
}

export function EditOverview( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( { className: 'hlm-overview hlm-section' } );
	return (
		<section { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Image', 'hlm-kadence' ) }>
					<TextControl label={ __( 'Image URL', 'hlm-kadence' ) } value={ attributes.imageUrl || '' } onChange={ ( v ) => setAttributes( { imageUrl: v } ) } />
					<TextControl label={ __( 'Image alt', 'hlm-kadence' ) } value={ attributes.imageAlt || '' } onChange={ ( v ) => setAttributes( { imageAlt: v } ) } />
				</PanelBody>
			</InspectorControls>
			<div className="hlm-grid-2">
				<div>
					<RichText tagName="p" className="hlm-overline" value={ attributes.overline } onChange={ ( v ) => setAttributes( { overline: v } ) } allowedFormats={ [] } />
					<RichText tagName="h2" className="hlm-h2" value={ attributes.headline } onChange={ ( v ) => setAttributes( { headline: v } ) } allowedFormats={ [] } />
					<RichText tagName="div" className="hlm-body-lg" value={ attributes.content } onChange={ ( v ) => setAttributes( { content: v } ) } allowedFormats={ [] } />
				</div>
			</div>
		</section>
	);
}

export function EditWhyUs( { attributes, setAttributes } ) {
	const variant = attributes.variant || 'default';
	const pillars = attributes.pillars || [];
	const blockProps = useBlockProps( { className: variant === 'values' ? 'hlm-why-us hlm-why-us--values hlm-section' : 'hlm-why-us hlm-section hlm-bg-white' } );
	return (
		<section { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Layout', 'hlm-kadence' ) }>
					<SelectControl
						label={ __( 'Style variant', 'hlm-kadence' ) }
						value={ variant }
						options={ [
							{ label: __( 'Light pillars', 'hlm-kadence' ), value: 'default' },
							{ label: __( 'Dark value cards', 'hlm-kadence' ), value: 'values' },
						] }
						onChange={ ( v ) => setAttributes( { variant: v } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div className="hlm-container">
				<SectionHeader attributes={ attributes } setAttributes={ setAttributes } />
				<div className={ variant === 'values' ? 'hlm-why-us__values-grid' : 'hlm-grid-4' }>
					{ pillars.map( ( pillar, index ) => (
						<div key={ index } className={ variant === 'values' ? 'hlm-value-card' : 'hlm-pillar' }>
							<RichText tagName="div" className={ variant === 'values' ? 'hlm-value-card__icon' : 'hlm-pillar__icon' } value={ pillar.icon || '' } onChange={ ( v ) => setAttributes( { pillars: updateListItem( pillars, index, { icon: v } ) } ) } allowedFormats={ [] } />
							<RichText tagName="h3" className="hlm-h3" value={ pillar.title || '' } onChange={ ( v ) => setAttributes( { pillars: updateListItem( pillars, index, { title: v } ) } ) } allowedFormats={ [] } />
							<RichText tagName="p" className="hlm-body" value={ pillar.description || '' } onChange={ ( v ) => setAttributes( { pillars: updateListItem( pillars, index, { description: v } ) } ) } allowedFormats={ [] } />
						</div>
					) ) }
				</div>
				<RepeaterActions addLabel={ __( 'Add Pillar', 'hlm-kadence' ) } onAdd={ () => setAttributes( { pillars: addListItem( pillars, { icon: '', title: '', description: '' } ) } ) } />
			</div>
		</section>
	);
}

export function EditTimeline( { attributes, setAttributes } ) {
	const variant = attributes.variant || 'dark';
	const milestones = attributes.milestones || [];
	const blockProps = useBlockProps( { className: variant === 'light' ? 'hlm-timeline hlm-timeline--light hlm-section' : 'hlm-timeline hlm-section' } );

	return (
		<section { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Layout', 'hlm-kadence' ) }>
					<SelectControl
						label={ __( 'Background', 'hlm-kadence' ) }
						value={ variant }
						options={ [
							{ label: __( 'Dark', 'hlm-kadence' ), value: 'dark' },
							{ label: __( 'Light', 'hlm-kadence' ), value: 'light' },
						] }
						onChange={ ( v ) => setAttributes( { variant: v } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div className="hlm-container">
				<SectionHeader attributes={ attributes } setAttributes={ setAttributes } />
				<div className="hlm-timeline__inner">
					{ milestones.map( ( item, index ) => (
						<div key={ index } className="hlm-timeline__item">
							{ index % 2 === 1 ? (
								<>
									<div className="hlm-timeline__content">
										<RichText tagName="h3" className="hlm-h4" value={ item.title || '' } onChange={ ( v ) => setAttributes( { milestones: updateListItem( milestones, index, { title: v } ) } ) } placeholder={ __( 'Title', 'hlm-kadence' ) } allowedFormats={ [] } />
										<RichText tagName="p" className="hlm-body" value={ item.description || '' } onChange={ ( v ) => setAttributes( { milestones: updateListItem( milestones, index, { description: v } ) } ) } placeholder={ __( 'Description', 'hlm-kadence' ) } allowedFormats={ [] } />
									</div>
									<RichText tagName="div" className="hlm-timeline__year" value={ item.year || '' } onChange={ ( v ) => setAttributes( { milestones: updateListItem( milestones, index, { year: v } ) } ) } placeholder={ __( 'Year', 'hlm-kadence' ) } allowedFormats={ [] } />
								</>
							) : (
								<>
									<RichText tagName="div" className="hlm-timeline__year" value={ item.year || '' } onChange={ ( v ) => setAttributes( { milestones: updateListItem( milestones, index, { year: v } ) } ) } placeholder={ __( 'Year', 'hlm-kadence' ) } allowedFormats={ [] } />
									<div className="hlm-timeline__content">
										<RichText tagName="h3" className="hlm-h4" value={ item.title || '' } onChange={ ( v ) => setAttributes( { milestones: updateListItem( milestones, index, { title: v } ) } ) } placeholder={ __( 'Title', 'hlm-kadence' ) } allowedFormats={ [] } />
										<RichText tagName="p" className="hlm-body" value={ item.description || '' } onChange={ ( v ) => setAttributes( { milestones: updateListItem( milestones, index, { description: v } ) } ) } placeholder={ __( 'Description', 'hlm-kadence' ) } allowedFormats={ [] } />
									</div>
								</>
							) }
							<Button isDestructive variant="link" onClick={ () => setAttributes( { milestones: removeListItem( milestones, index ) } ) }>{ __( 'Remove', 'hlm-kadence' ) }</Button>
						</div>
					) ) }
				</div>
				<RepeaterActions addLabel={ __( 'Add Milestone', 'hlm-kadence' ) } onAdd={ () => setAttributes( { milestones: addListItem( milestones, { year: '', title: '', description: '' } ) } ) } />
			</div>
		</section>
	);
}

export function EditContact( { attributes, setAttributes } ) {
	const variant = attributes.variant || 'cards';
	const blockProps = useBlockProps( {
		className: `hlm-contact hlm-section${ 'inquiry' === variant ? ' hlm-contact--inquiry' : '' }`,
	} );

	return (
		<section { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Layout', 'hlm-kadence' ) }>
					<SelectControl
						label={ __( 'Variant', 'hlm-kadence' ) }
						value={ variant }
						options={ [
							{ label: __( 'Contact cards', 'hlm-kadence' ), value: 'cards' },
							{ label: __( 'Inquiry + form', 'hlm-kadence' ), value: 'inquiry' },
						] }
						onChange={ ( v ) => setAttributes( { variant: v } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div className="hlm-container">
				{ 'inquiry' === variant ? (
					<div className="hlm-contact-inquiry">
						<div className="hlm-contact-inquiry__aside">
							<SectionHeader attributes={ attributes } setAttributes={ setAttributes } />
							<RichText
								tagName="p"
								className="hlm-body-lg hlm-contact-inquiry__intro"
								value={ attributes.description || '' }
								onChange={ ( v ) => setAttributes( { description: v } ) }
								placeholder={ __( 'Intro copy', 'hlm-kadence' ) }
								allowedFormats={ [] }
							/>
							<ul className="hlm-contact-inquiry__channels">
								<li className="hlm-contact-inquiry__channel">
									<RichText tagName="p" className="hlm-body" value={ attributes.email } onChange={ ( v ) => setAttributes( { email: v } ) } placeholder={ __( 'Email', 'hlm-kadence' ) } allowedFormats={ [] } />
								</li>
								<li className="hlm-contact-inquiry__channel">
									<RichText tagName="p" className="hlm-body" value={ attributes.phone } onChange={ ( v ) => setAttributes( { phone: v } ) } placeholder={ __( 'Phone', 'hlm-kadence' ) } allowedFormats={ [] } />
								</li>
								<li className="hlm-contact-inquiry__channel">
									<RichText tagName="p" className="hlm-body" value={ attributes.address } onChange={ ( v ) => setAttributes( { address: v } ) } placeholder={ __( 'Address', 'hlm-kadence' ) } allowedFormats={ [] } />
								</li>
							</ul>
						</div>
						<div className="hlm-contact-inquiry__form hlm-form-card">
							<RichText
								tagName="h3"
								className="hlm-h3"
								value={ attributes.formHeadline || '' }
								onChange={ ( v ) => setAttributes( { formHeadline: v } ) }
								placeholder={ __( 'Form headline', 'hlm-kadence' ) }
								allowedFormats={ [] }
							/>
							<p className="hlm-form-placeholder">{ __( 'Lead form renders on the front end.', 'hlm-kadence' ) }</p>
						</div>
					</div>
				) : (
					<>
						<SectionHeader attributes={ attributes } setAttributes={ setAttributes } showDescription={ false } />
						<div className="hlm-contact__grid">
							<div className="hlm-contact__item hlm-card">
								<RichText tagName="p" className="hlm-body" value={ attributes.email } onChange={ ( v ) => setAttributes( { email: v } ) } placeholder={ __( 'Email', 'hlm-kadence' ) } allowedFormats={ [] } />
							</div>
							<div className="hlm-contact__item hlm-card">
								<RichText tagName="p" className="hlm-body" value={ attributes.phone } onChange={ ( v ) => setAttributes( { phone: v } ) } placeholder={ __( 'Phone', 'hlm-kadence' ) } allowedFormats={ [] } />
							</div>
							<div className="hlm-contact__item hlm-card">
								<RichText tagName="p" className="hlm-body" value={ attributes.address } onChange={ ( v ) => setAttributes( { address: v } ) } placeholder={ __( 'Address', 'hlm-kadence' ) } allowedFormats={ [] } />
							</div>
						</div>
					</>
				) }
			</div>
		</section>
	);
}

export function EditAffiliation( { attributes, setAttributes } ) {
	const benefits = attributes.benefits || [];
	const blockProps = useBlockProps( { className: 'hlm-affiliation hlm-section hlm-affiliation--benefits' } );
	return (
		<section { ...blockProps }>
			<div className="hlm-container">
				<SectionHeader attributes={ attributes } setAttributes={ setAttributes } />
				<div className="hlm-affiliation-benefits-grid">
					{ benefits.map( ( benefit, index ) => (
						<div key={ index } className="hlm-affiliation-benefit">
							<RichText tagName="div" className="hlm-affiliation-benefit__icon" value={ benefit.icon || '' } onChange={ ( v ) => setAttributes( { benefits: updateListItem( benefits, index, { icon: v } ) } ) } allowedFormats={ [] } />
							<div className="hlm-affiliation-benefit__content">
								<RichText tagName="h3" className="hlm-h4" value={ benefit.title || '' } onChange={ ( v ) => setAttributes( { benefits: updateListItem( benefits, index, { title: v } ) } ) } placeholder={ __( 'Title', 'hlm-kadence' ) } allowedFormats={ [] } />
								<RichText tagName="p" className="hlm-body" value={ benefit.description || '' } onChange={ ( v ) => setAttributes( { benefits: updateListItem( benefits, index, { description: v } ) } ) } placeholder={ __( 'Description', 'hlm-kadence' ) } allowedFormats={ [] } />
							</div>
							<Button isDestructive variant="link" onClick={ () => setAttributes( { benefits: removeListItem( benefits, index ) } ) }>{ __( 'Remove', 'hlm-kadence' ) }</Button>
						</div>
					) ) }
				</div>
				<RepeaterActions addLabel={ __( 'Add Benefit', 'hlm-kadence' ) } onAdd={ () => setAttributes( { benefits: addListItem( benefits, { icon: '', title: '', description: '' } ) } ) } />
			</div>
		</section>
	);
}

export function EditPageHero( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( { className: 'hlm-page-hero' } );
	const mediaPreview = attributes.mediaImageUrl ? (
		<div className="hlm-page-hero__media hlm-page-hero__media--editor">
			<img src={ attributes.mediaImageUrl } alt={ attributes.mediaImageAlt || '' } />
		</div>
	) : null;

	return (
		<section { ...blockProps }>
			<InspectorControls>
				<MediaBackgroundPanel attributes={ attributes } setAttributes={ setAttributes } />
			</InspectorControls>
			{ mediaPreview }
			<div className="hlm-container hlm-page-hero__inner">
				<RichText tagName="div" className="hlm-overline" value={ attributes.overline } onChange={ ( v ) => setAttributes( { overline: v } ) } placeholder={ __( 'Overline', 'hlm-kadence' ) } allowedFormats={ [] } />
				<RichText tagName="h1" className="hlm-h1" value={ attributes.headline } onChange={ ( v ) => setAttributes( { headline: v } ) } placeholder={ __( 'Headline', 'hlm-kadence' ) } />
				<RichText tagName="p" className="hlm-body-lg" value={ attributes.description } onChange={ ( v ) => setAttributes( { description: v } ) } placeholder={ __( 'Description', 'hlm-kadence' ) } allowedFormats={ [] } />
			</div>
		</section>
	);
}

const PARTNER_STAT_TEMPLATE = { number: '', label: '' };

export function EditPartnerShowcase( { attributes, setAttributes } ) {
	const variant = attributes.variant || 'partner';
	const imagePosition = attributes.imagePosition || 'right';
	const stats = attributes.stats || [];
	const sectionClass = variant === 'story'
		? 'hlm-partner-showcase hlm-partner-showcase--story hlm-section'
		: 'hlm-partner-showcase hlm-section';
	const blockProps = useBlockProps( { className: sectionClass } );
	const statsClass = variant === 'story' || stats.length >= 4
		? 'hlm-partner-showcase__stats hlm-partner-showcase__stats--row'
		: 'hlm-partner-showcase__stats';

	const contentCol = (
		<div className="hlm-partner-showcase__content">
			<RichText tagName="div" className="hlm-overline" value={ attributes.overline } onChange={ ( v ) => setAttributes( { overline: v } ) } allowedFormats={ [] } />
			<RichText tagName="h2" className="hlm-h2" value={ attributes.headline } onChange={ ( v ) => setAttributes( { headline: v } ) } allowedFormats={ [] } />
			<RichText tagName="div" className="hlm-partner-showcase__copy" value={ attributes.content } onChange={ ( v ) => setAttributes( { content: v } ) } allowedFormats={ [] } />
			<div className={ statsClass }>
				{ stats.map( ( stat, index ) => (
					<div key={ index } className="hlm-partner-showcase__stat">
						<TextControl label={ __( 'Number', 'hlm-kadence' ) } value={ stat.number || '' } onChange={ ( v ) => setAttributes( { stats: updateListItem( stats, index, { number: v } ) } ) } />
						<TextControl label={ __( 'Label', 'hlm-kadence' ) } value={ stat.label || '' } onChange={ ( v ) => setAttributes( { stats: updateListItem( stats, index, { label: v } ) } ) } />
					</div>
				) ) }
			</div>
			<RepeaterActions addLabel={ __( 'Add Stat', 'hlm-kadence' ) } onAdd={ () => setAttributes( { stats: addListItem( stats, PARTNER_STAT_TEMPLATE ) } ) } />
		</div>
	);

	const visualCol = attributes.imageUrl ? (
		<div className="hlm-partner-showcase__visual">
			<img className="hlm-partner-showcase__image" src={ attributes.imageUrl } alt={ attributes.imageAlt || '' } />
			{ attributes.badge && <div className="hlm-partner-showcase__badge">{ attributes.badge }</div> }
		</div>
	) : null;

	return (
		<section { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Layout', 'hlm-kadence' ) }>
					<SelectControl
						label={ __( 'Style variant', 'hlm-kadence' ) }
						value={ variant }
						options={ [
							{ label: __( 'Partner feature', 'hlm-kadence' ), value: 'partner' },
							{ label: __( 'Company story', 'hlm-kadence' ), value: 'story' },
						] }
						onChange={ ( v ) => setAttributes( { variant: v } ) }
					/>
					<SelectControl
						label={ __( 'Image position', 'hlm-kadence' ) }
						value={ imagePosition }
						options={ [
							{ label: __( 'Right', 'hlm-kadence' ), value: 'right' },
							{ label: __( 'Left', 'hlm-kadence' ), value: 'left' },
						] }
						onChange={ ( v ) => setAttributes( { imagePosition: v } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Media', 'hlm-kadence' ) }>
					<MediaImageControl
						label={ __( 'Section photo', 'hlm-kadence' ) }
						imageUrl={ attributes.imageUrl }
						imageId={ attributes.imageId }
						onSelect={ ( media ) =>
							setAttributes( {
								imageUrl: media.url,
								imageId: media.id,
								imageAlt: media.alt || attributes.imageAlt || '',
							} )
						}
						onRemove={ () => setAttributes( { imageUrl: '', imageId: 0 } ) }
					/>
					<TextControl label={ __( 'Image alt', 'hlm-kadence' ) } value={ attributes.imageAlt || '' } onChange={ ( v ) => setAttributes( { imageAlt: v } ) } />
					<TextControl label={ __( 'Badge text', 'hlm-kadence' ) } value={ attributes.badge || '' } onChange={ ( v ) => setAttributes( { badge: v } ) } />
				</PanelBody>
			</InspectorControls>
			<div className="hlm-container">
				<div className={ `hlm-partner-showcase__grid${ imagePosition === 'left' ? ' hlm-partner-showcase__grid--image-left' : '' }` }>
					{ imagePosition === 'left' ? ( <> { visualCol } { contentCol } </> ) : ( <> { contentCol } { visualCol } </> ) }
				</div>
			</div>
		</section>
	);
}

const PARTNER_CARD_TEMPLATE = { initials: '', title: '', description: '', meta: '' };

export function EditPartners( { attributes, setAttributes } ) {
	const partners = attributes.partners || [];
	const blockProps = useBlockProps( { className: 'hlm-partners hlm-section' } );
	return (
		<section { ...blockProps }>
			<div className="hlm-container">
				<SectionHeader attributes={ attributes } setAttributes={ setAttributes } />
				<div className="hlm-partners-grid">
					{ partners.map( ( partner, index ) => (
						<div key={ index } className="hlm-partner-card">
							<RichText tagName="div" className="hlm-partner-card__logo" value={ partner.initials || '' } onChange={ ( v ) => setAttributes( { partners: updateListItem( partners, index, { initials: v } ) } ) } allowedFormats={ [] } />
							<RichText tagName="h3" className="hlm-h4" value={ partner.title || '' } onChange={ ( v ) => setAttributes( { partners: updateListItem( partners, index, { title: v } ) } ) } allowedFormats={ [] } />
							<RichText tagName="p" className="hlm-body" value={ partner.description || '' } onChange={ ( v ) => setAttributes( { partners: updateListItem( partners, index, { description: v } ) } ) } allowedFormats={ [] } />
							<RichText tagName="div" className="hlm-partner-card__meta" value={ partner.meta || '' } onChange={ ( v ) => setAttributes( { partners: updateListItem( partners, index, { meta: v } ) } ) } allowedFormats={ [] } />
							<Button isDestructive variant="link" onClick={ () => setAttributes( { partners: removeListItem( partners, index ) } ) }>{ __( 'Remove', 'hlm-kadence' ) }</Button>
						</div>
					) ) }
				</div>
				<RepeaterActions addLabel={ __( 'Add Partner', 'hlm-kadence' ) } onAdd={ () => setAttributes( { partners: addListItem( partners, PARTNER_CARD_TEMPLATE ) } ) } />
			</div>
		</section>
	);
}

// -- Blog --
export function EditBlog( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( { className: 'hlm-blog hlm-section' } );

	return (
		<section { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Posts', 'hlm-kadence' ) }>
					<TextControl
						label={ __( 'Number of posts', 'hlm-kadence' ) }
						type="number"
						min={ 1 }
						max={ 6 }
						value={ String( attributes.postCount ?? 3 ) }
						onChange={ ( v ) => setAttributes( { postCount: Math.max( 1, Math.min( 6, parseInt( v, 10 ) || 3 ) ) } ) }
					/>
					<TextControl label={ __( 'View all button text', 'hlm-kadence' ) } value={ attributes.viewAllText || '' } onChange={ ( v ) => setAttributes( { viewAllText: v } ) } />
					<TextControl label={ __( 'View all URL', 'hlm-kadence' ) } value={ attributes.viewAllUrl || '' } onChange={ ( v ) => setAttributes( { viewAllUrl: v } ) } />
				</PanelBody>
			</InspectorControls>
			<div className="hlm-container">
				<SectionHeader attributes={ attributes } setAttributes={ setAttributes } />
				<div className="hlm-blog-grid">
					<div className="hlm-blog-card">
						<div className="hlm-blog-card__body">
							<p className="hlm-blog-card__date">{ __( 'Sample date', 'hlm-kadence' ) }</p>
							<h3 className="hlm-h4">{ __( 'Sample blog post title', 'hlm-kadence' ) }</h3>
							<p className="hlm-body hlm-blog-card__excerpt">{ __( 'Latest posts from WordPress appear here on the live site.', 'hlm-kadence' ) }</p>
						</div>
					</div>
				</div>
				{ attributes.viewAllText && (
					<div className="hlm-blog__actions">
						<span className="hlm-btn hlm-btn-outline hlm-btn-arrow">{ attributes.viewAllText }</span>
					</div>
				) }
			</div>
		</section>
	);
}

// -- Article blocks (SEO blog posts) --
export function EditArticleCompare( { attributes, setAttributes } ) {
	const leftParagraphs = attributes.leftParagraphs || [];
	const rightParagraphs = attributes.rightParagraphs || [];
	const footnote = attributes.footnote || [];
	const blockProps = useBlockProps( { className: 'hlm-article-compare' } );

	const renderParagraphs = ( paragraphs, key, onUpdate ) => paragraphs.map( ( paragraph, index ) => (
		<div key={ `${ key }-${ index }` }>
			<RichText
				tagName="p"
				className="hlm-article-compare__text"
				value={ paragraph || '' }
				onChange={ ( v ) => onUpdate( paragraphs.map( ( item, i ) => ( i === index ? v : item ) ) ) }
				placeholder={ __( 'Paragraph', 'hlm-kadence' ) }
				allowedFormats={ [] }
			/>
			<Button isDestructive variant="link" onClick={ () => onUpdate( removeListItem( paragraphs, index ) ) }>
				{ __( 'Remove paragraph', 'hlm-kadence' ) }
			</Button>
		</div>
	) );

	return (
		<section { ...blockProps }>
			<RichText
				tagName="h2"
				className="hlm-article-compare__headline"
				value={ attributes.headline || '' }
				onChange={ ( v ) => setAttributes( { headline: v } ) }
				placeholder={ __( 'Section headline', 'hlm-kadence' ) }
				allowedFormats={ [] }
			/>
			<div className="hlm-article-compare__grid">
				<div className="hlm-article-compare__column hlm-article-compare__column--left">
					<RichText
						tagName="h3"
						className="hlm-article-compare__title"
						value={ attributes.leftTitle || '' }
						onChange={ ( v ) => setAttributes( { leftTitle: v } ) }
						placeholder={ __( 'Left column title', 'hlm-kadence' ) }
						allowedFormats={ [] }
					/>
					{ renderParagraphs( leftParagraphs, 'left', ( v ) => setAttributes( { leftParagraphs: v } ) ) }
					<RepeaterActions addLabel={ __( 'Add left paragraph', 'hlm-kadence' ) } onAdd={ () => setAttributes( { leftParagraphs: addListItem( leftParagraphs, '' ) } ) } />
				</div>
				<div className="hlm-article-compare__column hlm-article-compare__column--right">
					<RichText
						tagName="h3"
						className="hlm-article-compare__title"
						value={ attributes.rightTitle || '' }
						onChange={ ( v ) => setAttributes( { rightTitle: v } ) }
						placeholder={ __( 'Right column title', 'hlm-kadence' ) }
						allowedFormats={ [] }
					/>
					{ renderParagraphs( rightParagraphs, 'right', ( v ) => setAttributes( { rightParagraphs: v } ) ) }
					<RepeaterActions addLabel={ __( 'Add right paragraph', 'hlm-kadence' ) } onAdd={ () => setAttributes( { rightParagraphs: addListItem( rightParagraphs, '' ) } ) } />
				</div>
			</div>
			{ renderParagraphs( footnote, 'footnote', ( v ) => setAttributes( { footnote: v } ) ) }
			<RepeaterActions addLabel={ __( 'Add footnote paragraph', 'hlm-kadence' ) } onAdd={ () => setAttributes( { footnote: addListItem( footnote, '' ) } ) } />
		</section>
	);
}

export function EditArticleMistake( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( { className: 'hlm-article-mistake' } );

	const summary = attributes.summary || attributes.paragraphs?.[0] || '';
	const recommendation = attributes.recommendation || attributes.paragraphs?.[1] || '';
	const whyItMatters = attributes.whyItMatters || attributes.paragraphs?.[2] || '';

	return (
		<article { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Mistake card', 'hlm-kadence' ) }>
					<TextControl
						label={ __( 'Mistake number', 'hlm-kadence' ) }
						value={ attributes.number || '1' }
						onChange={ ( v ) => setAttributes( { number: v } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div className="hlm-article-mistake__badge" aria-hidden="true">
				<span className="hlm-article-mistake__number">{ attributes.number || '1' }</span>
			</div>
			<div className="hlm-article-mistake__body">
				<RichText
					tagName="h3"
					className="hlm-article-mistake__title"
					value={ attributes.title || '' }
					onChange={ ( v ) => setAttributes( { title: v } ) }
					placeholder={ __( 'Mistake title', 'hlm-kadence' ) }
					allowedFormats={ [] }
				/>
				<div className="hlm-article-field">
					<span className="hlm-article-field__label">{ __( 'Summary', 'hlm-kadence' ) }</span>
					<RichText
						tagName="p"
						className="hlm-article-mistake__text"
						value={ summary }
						onChange={ ( v ) => setAttributes( { summary: v, paragraphs: [] } ) }
						placeholder={ __( 'What goes wrong and why', 'hlm-kadence' ) }
						allowedFormats={ [] }
					/>
				</div>
				<div className="hlm-article-field">
					<span className="hlm-article-field__label">{ __( 'Recommendation', 'hlm-kadence' ) }</span>
					<RichText
						tagName="p"
						className="hlm-article-mistake__text"
						value={ recommendation }
						onChange={ ( v ) => setAttributes( { recommendation: v, paragraphs: [] } ) }
						placeholder={ __( 'What to do instead', 'hlm-kadence' ) }
						allowedFormats={ [] }
					/>
				</div>
				<div className="hlm-article-field">
					<span className="hlm-article-field__label">{ __( 'Why it matters', 'hlm-kadence' ) }</span>
					<RichText
						tagName="p"
						className="hlm-article-mistake__text"
						value={ whyItMatters }
						onChange={ ( v ) => setAttributes( { whyItMatters: v, paragraphs: [] } ) }
						placeholder={ __( 'Impact on your aid or timeline', 'hlm-kadence' ) }
						allowedFormats={ [] }
					/>
				</div>
			</div>
		</article>
	);
}

export function EditArticleChecklist( { attributes, setAttributes } ) {
	const paragraphs = attributes.paragraphs || [];
	const items = attributes.items || [];
	const outro = attributes.outro || [];
	const blockProps = useBlockProps( { className: 'hlm-article-checklist' } );

	return (
		<section { ...blockProps }>
			<RichText
				tagName="h2"
				className="hlm-article-checklist__headline"
				value={ attributes.headline || '' }
				onChange={ ( v ) => setAttributes( { headline: v } ) }
				placeholder={ __( 'Checklist headline', 'hlm-kadence' ) }
				allowedFormats={ [] }
			/>
			{ paragraphs.map( ( paragraph, index ) => (
				<RichText
					key={ `intro-${ index }` }
					tagName="p"
					className="hlm-article-checklist__intro"
					value={ paragraph || '' }
					onChange={ ( v ) => setAttributes( { paragraphs: paragraphs.map( ( item, i ) => ( i === index ? v : item ) ) } ) }
					placeholder={ __( 'Intro paragraph', 'hlm-kadence' ) }
					allowedFormats={ [] }
				/>
			) ) }
			<ul className="hlm-article-checklist__list">
				{ items.map( ( item, index ) => (
					<li key={ index } className="hlm-article-checklist__item">
						<RichText
							tagName="span"
							value={ item || '' }
							onChange={ ( v ) => setAttributes( { items: items.map( ( row, i ) => ( i === index ? v : row ) ) } ) }
							placeholder={ __( 'Checklist item', 'hlm-kadence' ) }
							allowedFormats={ [] }
						/>
					</li>
				) ) }
			</ul>
			{ outro.map( ( paragraph, index ) => (
				<RichText
					key={ `outro-${ index }` }
					tagName="p"
					className="hlm-article-checklist__outro"
					value={ paragraph || '' }
					onChange={ ( v ) => setAttributes( { outro: outro.map( ( item, i ) => ( i === index ? v : item ) ) } ) }
					placeholder={ __( 'Closing paragraph', 'hlm-kadence' ) }
					allowedFormats={ [] }
				/>
			) ) }
		</section>
	);
}

export function EditArticleCallout( { attributes, setAttributes } ) {
	const variant = attributes?.variant || 'help';
	const paragraphs = attributes?.paragraphs?.length
		? attributes.paragraphs
		: ( attributes?.body ? [ attributes.body ] : [ '' ] );
	const blockProps = useBlockProps( { className: `hlm-article-callout hlm-article-callout--${ variant }` } );

	return (
		<aside { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Callout style', 'hlm-kadence' ) }>
					<SelectControl
						label={ __( 'Variant', 'hlm-kadence' ) }
						value={ variant }
						options={ [
							{ label: __( 'Help', 'hlm-kadence' ), value: 'help' },
							{ label: __( 'CTA', 'hlm-kadence' ), value: 'cta' },
							{ label: __( 'Tip', 'hlm-kadence' ), value: 'tip' },
						] }
						onChange={ ( v ) => setAttributes( { variant: v } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<RichText
				tagName="h2"
				className="hlm-article-callout__headline"
				value={ attributes?.headline || '' }
				onChange={ ( v ) => setAttributes( { headline: v } ) }
				placeholder={ __( 'Callout headline', 'hlm-kadence' ) }
				allowedFormats={ [] }
			/>
			<div className="hlm-article-callout__body">
				{ paragraphs.map( ( paragraph, index ) => (
					<RichText
						key={ index }
						tagName="p"
						value={ paragraph || '' }
						onChange={ ( v ) => {
							const next = paragraphs.map( ( item, i ) => ( i === index ? v : item ) );
							setAttributes( { paragraphs: next, body: '' } );
						} }
						placeholder={ __( 'Callout paragraph', 'hlm-kadence' ) }
						allowedFormats={ [ 'core/link', 'core/bold', 'core/italic' ] }
					/>
				) ) }
			</div>
		</aside>
	);
}

export function EditPolicy( { attributes, setAttributes } ) {
	const sections = attributes.sections || [];
	const blockProps = useBlockProps( { className: 'hlm-policy hlm-section' } );
	return (
		<section { ...blockProps }>
			<RichText tagName="h1" className="hlm-h1" value={ attributes.headline } onChange={ ( v ) => setAttributes( { headline: v } ) } allowedFormats={ [] } />
			<TextControl label={ __( 'Last updated', 'hlm-kadence' ) } value={ attributes.lastUpdated || '' } onChange={ ( v ) => setAttributes( { lastUpdated: v } ) } />
			<RichText tagName="div" className="hlm-body-lg" value={ attributes.intro } onChange={ ( v ) => setAttributes( { intro: v } ) } placeholder={ __( 'Intro', 'hlm-kadence' ) } allowedFormats={ [] } />
			{ sections.map( ( section, index ) => (
				<div key={ index } className="hlm-policy__section">
					<RichText tagName="h3" value={ section.title || '' } onChange={ ( v ) => setAttributes( { sections: updateListItem( sections, index, { title: v } ) } ) } allowedFormats={ [] } />
					<RichText tagName="div" className="hlm-body" value={ section.content || '' } onChange={ ( v ) => setAttributes( { sections: updateListItem( sections, index, { content: v } ) } ) } allowedFormats={ [] } />
					<Button isDestructive variant="link" onClick={ () => setAttributes( { sections: removeListItem( sections, index ) } ) }>{ __( 'Remove section', 'hlm-kadence' ) }</Button>
				</div>
			) ) }
			<RepeaterActions addLabel={ __( 'Add Section', 'hlm-kadence' ) } onAdd={ () => setAttributes( { sections: addListItem( sections, { title: '', content: '' } ) } ) } />
		</section>
	);
}

export { emptySave };
