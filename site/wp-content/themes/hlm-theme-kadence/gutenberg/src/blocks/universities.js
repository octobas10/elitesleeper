import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
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
	RichText,
} from '../shared';

const META_TEMPLATE = { icon: 'location', text: '' };
const INSTITUTION_TEMPLATE = {
	badge: '',
	title: '',
	description: '',
	meta: [ { ...META_TEMPLATE } ],
};

export function EditUniversities( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( { className: 'hlm-unis hlm-section' } );
	const institutions = attributes.institutions || [];

	return (
		<section { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Institutions', 'hlm-kadence' ) } initialOpen={ true }>
					<Button
						variant="primary"
						onClick={ () =>
							setAttributes( {
								institutions: addListItem( institutions, INSTITUTION_TEMPLATE ),
							} )
						}
					>
						{ __( 'Add Institution', 'hlm-kadence' ) }
					</Button>
				</PanelBody>
			</InspectorControls>

			<SectionHeader attributes={ attributes } setAttributes={ setAttributes } />

			<div className="hlm-unis-grid">
				{ institutions.map( ( item, index ) => (
					<div key={ index } className="hlm-uni-card hlm-animate">
						<RichText
							tagName="div"
							className="hlm-uni-badge"
							value={ item.badge || '' }
							onChange={ ( value ) =>
								setAttributes( {
									institutions: updateListItem( institutions, index, { badge: value } ),
								} )
							}
							placeholder={ __( 'Badge', 'hlm-kadence' ) }
							allowedFormats={ [] }
						/>
						<RichText
							tagName="h3"
							value={ item.title || '' }
							onChange={ ( value ) =>
								setAttributes( {
									institutions: updateListItem( institutions, index, { title: value } ),
								} )
							}
							placeholder={ __( 'Institution name', 'hlm-kadence' ) }
							allowedFormats={ [] }
						/>
						<RichText
							tagName="p"
							value={ item.description || '' }
							onChange={ ( value ) =>
								setAttributes( {
									institutions: updateListItem( institutions, index, { description: value } ),
								} )
							}
							placeholder={ __( 'Description', 'hlm-kadence' ) }
							allowedFormats={ [] }
						/>
						{ ( item.meta || [] ).map( ( meta, metaIndex ) => (
							<TextControl
								key={ metaIndex }
								label={ __( 'Meta line', 'hlm-kadence' ) + ' ' + ( metaIndex + 1 ) }
								value={ meta.text || '' }
								onChange={ ( value ) => {
									const metaList = [ ...( item.meta || [] ) ];
									metaList[ metaIndex ] = { ...metaList[ metaIndex ], text: value };
									setAttributes( {
										institutions: updateListItem( institutions, index, { meta: metaList } ),
									} );
								} }
							/>
						) ) }
						<Button
							isDestructive
							variant="link"
							onClick={ () =>
								setAttributes( {
									institutions: removeListItem( institutions, index ),
								} )
							}
						>
							{ __( 'Remove institution', 'hlm-kadence' ) }
						</Button>
					</div>
				) ) }
			</div>

			<RepeaterActions
				addLabel={ __( 'Add Institution', 'hlm-kadence' ) }
				onAdd={ () =>
					setAttributes( {
						institutions: addListItem( institutions, INSTITUTION_TEMPLATE ),
					} )
				}
			/>
		</section>
	);
}
