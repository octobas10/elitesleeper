/**
 * Shared editor components for HLM blocks.
 */
import { __ } from '@wordpress/i18n';
import { RichText, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl, ToggleControl, SelectControl } from '@wordpress/components';

export function MediaImageControl( {
	label,
	imageUrl,
	imageId,
	onSelect,
	onRemove,
} ) {
	return (
		<div className="hlm-media-control">
			{ label && <p className="hlm-media-control__label">{ label }</p> }
			{ imageUrl ? (
				<img className="hlm-media-control__preview" src={ imageUrl } alt="" />
			) : (
				<p className="hlm-media-control__empty">{ __( 'No image selected', 'hlm-kadence' ) }</p>
			) }
			<div className="hlm-media-control__actions">
				<MediaUploadCheck>
					<MediaUpload
						onSelect={ onSelect }
						allowedTypes={ [ 'image' ] }
						value={ imageId || 0 }
						render={ ( { open } ) => (
							<Button variant="secondary" onClick={ open }>
								{ imageUrl ? __( 'Replace image', 'hlm-kadence' ) : __( 'Select image', 'hlm-kadence' ) }
							</Button>
						) }
					/>
				</MediaUploadCheck>
				{ imageUrl && (
					<Button variant="link" isDestructive onClick={ onRemove }>
						{ __( 'Remove', 'hlm-kadence' ) }
					</Button>
				) }
			</div>
		</div>
	);
}

export function MediaBackgroundPanel( { attributes, setAttributes, title = __( 'Background media', 'hlm-kadence' ) } ) {
	const storedType = attributes.mediaType ?? 'image';
	const mediaType = storedType === '' ? 'placeholder' : storedType;
	const showOverlay = attributes.mediaShowOverlay !== false;

	const overlayControls = ( mediaType === 'image' || mediaType === 'video' ) && (
		<>
			<ToggleControl
				label={ __( 'Color overlay', 'hlm-kadence' ) }
				help={ __( 'Darkens the photo or video so headline text stays readable.', 'hlm-kadence' ) }
				checked={ showOverlay }
				onChange={ ( value ) => setAttributes( { mediaShowOverlay: value } ) }
			/>
			{ showOverlay && (
				<SelectControl
					label={ __( 'Overlay strength', 'hlm-kadence' ) }
					value={ attributes.mediaOverlayStrength || 'medium' }
					options={ [
						{ label: __( 'Light', 'hlm-kadence' ), value: 'light' },
						{ label: __( 'Medium', 'hlm-kadence' ), value: 'medium' },
						{ label: __( 'Strong', 'hlm-kadence' ), value: 'strong' },
					] }
					onChange={ ( value ) => setAttributes( { mediaOverlayStrength: value } ) }
				/>
			) }
		</>
	);

	return (
		<PanelBody title={ title } initialOpen={ false }>
			<SelectControl
				label={ __( 'Background style', 'hlm-kadence' ) }
				value={ mediaType }
				options={ [
					{ label: __( 'Campus photo with overlay', 'hlm-kadence' ), value: 'image' },
					{ label: __( 'University campus video', 'hlm-kadence' ), value: 'video' },
					{ label: __( 'University video placeholder', 'hlm-kadence' ), value: 'placeholder' },
				] }
				onChange={ ( value ) => {
					if ( value === 'placeholder' ) {
						setAttributes( {
							mediaType: '',
							mediaPlaceholderLabel:
								attributes.mediaPlaceholderLabel || __( 'University Campus Video', 'hlm-kadence' ),
						} );
						return;
					}

					setAttributes( { mediaType: value } );
				} }
			/>
			{ mediaType === 'image' && (
				<MediaImageControl
					label={ __( 'Campus hero image', 'hlm-kadence' ) }
					imageUrl={ attributes.mediaImageUrl }
					imageId={ attributes.mediaImageId }
					onSelect={ ( media ) =>
						setAttributes( {
							mediaImageUrl: media.url,
							mediaImageId: media.id,
							mediaImageAlt: media.alt || attributes.mediaImageAlt || '',
						} )
					}
					onRemove={ () =>
						setAttributes( {
							mediaImageUrl: '',
							mediaImageId: 0,
						} )
					}
				/>
			) }
			{ mediaType === 'image' && (
				<TextControl
					label={ __( 'Image alt text', 'hlm-kadence' ) }
					value={ attributes.mediaImageAlt || '' }
					onChange={ ( value ) => setAttributes( { mediaImageAlt: value } ) }
				/>
			) }
			{ mediaType === 'image' && overlayControls }
			{ mediaType === 'video' && (
				<>
					<TextControl
						label={ __( 'Video URL (MP4 or WebM)', 'hlm-kadence' ) }
						value={ attributes.mediaVideoUrl || '' }
						onChange={ ( value ) => setAttributes( { mediaVideoUrl: value } ) }
						help={ __(
							'Upload a campus b-roll clip to the Media Library, or use the demo button below.',
							'hlm-kadence'
						) }
					/>
					{ window.hlmThemeMedia?.demoHeroVideo && (
						<Button
							variant="secondary"
							onClick={ () =>
								setAttributes( {
									mediaVideoUrl: window.hlmThemeMedia.demoHeroVideo,
									mediaPosterUrl:
										attributes.mediaPosterUrl || window.hlmThemeMedia.demoHeroPosterUrl || '',
									mediaShowOverlay: true,
								} )
							}
						>
							{ window.hlmThemeMedia.demoHeroVideoLabel || __( 'Use demo campus video', 'hlm-kadence' ) }
						</Button>
					) }
					<TextControl
						label={ __( 'Poster image URL (optional)', 'hlm-kadence' ) }
						value={ attributes.mediaPosterUrl || '' }
						onChange={ ( value ) => setAttributes( { mediaPosterUrl: value } ) }
					/>
					{ overlayControls }
				</>
			) }
			{ mediaType === 'placeholder' && (
				<>
					<p className="hlm-media-control__help">
						{ __(
							'Shows an animated gradient with a play badge until you add a real campus video or photo.',
							'hlm-kadence'
						) }
					</p>
					<TextControl
						label={ __( 'Placeholder label', 'hlm-kadence' ) }
						value={ attributes.mediaPlaceholderLabel || __( 'University Campus Video', 'hlm-kadence' ) }
						onChange={ ( value ) => setAttributes( { mediaPlaceholderLabel: value } ) }
					/>
				</>
			) }
		</PanelBody>
	);
}

export function SectionHeader( {
	attributes,
	setAttributes,
	overlineKey = 'overline',
	headlineKey = 'headline',
	descriptionKey = 'description',
	headlineTag = 'h2',
	headlineClass = 'hlm-h2',
	showDescription = true,
} ) {
	return (
		<div className="hlm-section-header hlm-animate">
			<RichText
				tagName="div"
				className="hlm-overline"
				value={ attributes[ overlineKey ] || '' }
				onChange={ ( value ) => setAttributes( { [ overlineKey ]: value } ) }
				placeholder={ __( 'Overline', 'hlm-kadence' ) }
				allowedFormats={ [] }
			/>
			<RichText
				tagName={ headlineTag }
				className={ headlineClass }
				value={ attributes[ headlineKey ] || '' }
				onChange={ ( value ) => setAttributes( { [ headlineKey ]: value } ) }
				placeholder={ __( 'Headline', 'hlm-kadence' ) }
				allowedFormats={ [ 'core/bold', 'core/italic' ] }
			/>
			{ showDescription && (
				<RichText
					tagName="p"
					className="hlm-body-lg"
					value={ attributes[ descriptionKey ] || '' }
					onChange={ ( value ) => setAttributes( { [ descriptionKey ]: value } ) }
					placeholder={ __( 'Description (optional)', 'hlm-kadence' ) }
					allowedFormats={ [] }
				/>
			) }
		</div>
	);
}

export function RepeaterActions( { onAdd, addLabel } ) {
	return (
		<div className="hlm-block-repeater-actions">
			<Button variant="secondary" onClick={ onAdd }>
				{ addLabel }
			</Button>
		</div>
	);
}

export function updateListItem( list, index, patch ) {
	return list.map( ( item, i ) => ( i === index ? { ...item, ...patch } : item ) );
}

export function removeListItem( list, index ) {
	return list.filter( ( _, i ) => i !== index );
}

export function addListItem( list, template ) {
	return [ ...list, { ...template } ];
}

export { InspectorControls, PanelBody, Button, TextControl, ToggleControl, SelectControl, RichText };
