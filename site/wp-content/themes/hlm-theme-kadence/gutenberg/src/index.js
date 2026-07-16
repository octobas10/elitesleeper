import { registerBlockType } from '@wordpress/blocks';
import { EditUniversities } from './blocks/universities';
import articleMistakeMetadata from '../../blocks/hlm-article-mistake/block.json';
import articleChecklistMetadata from '../../blocks/hlm-article-checklist/block.json';
import articleCalloutMetadata from '../../blocks/hlm-article-callout/block.json';
import articleCompareMetadata from '../../blocks/hlm-article-compare/block.json';
import {
	EditHero,
	EditStats,
	EditPrograms,
	EditProcess,
	saveProcess,
	EditTestimonials,
	EditCta,
	EditServices,
	EditTeam,
	EditOverview,
	EditWhyUs,
	EditTimeline,
	EditContact,
	EditAffiliation,
	EditPageHero,
	EditPartnerShowcase,
	EditPartners,
	EditBlog,
	EditPolicy,
	EditArticleMistake,
	EditArticleChecklist,
	EditArticleCallout,
	EditArticleCompare,
	emptySave,
} from './blocks/all';

const blocks = [
	[ 'hlm/hero', EditHero ],
	[ 'hlm/page-hero', EditPageHero ],
	[ 'hlm/stats', EditStats ],
	[ 'hlm/universities', EditUniversities ],
	[ 'hlm/programs', EditPrograms ],
	[ 'hlm/process', EditProcess, saveProcess ],
	[ 'hlm/testimonials', EditTestimonials ],
	[ 'hlm/blog', EditBlog ],
	[ 'hlm/cta', EditCta ],
	[ 'hlm/services', EditServices ],
	[ 'hlm/team', EditTeam ],
	[ 'hlm/overview', EditOverview ],
	[ 'hlm/why-us', EditWhyUs ],
	[ 'hlm/timeline', EditTimeline ],
	[ 'hlm/contact', EditContact ],
	[ 'hlm/affiliation', EditAffiliation ],
	[ 'hlm/partner-showcase', EditPartnerShowcase ],
	[ 'hlm/partners', EditPartners ],
	[ 'hlm/policy', EditPolicy ],
	[ articleMistakeMetadata, EditArticleMistake ],
	[ articleChecklistMetadata, EditArticleChecklist ],
	[ articleCalloutMetadata, EditArticleCallout ],
	[ articleCompareMetadata, EditArticleCompare ],
];

/**
 * __experimentalLabel receives block attributes directly (not { attributes }).
 *
 * @param {Record<string, unknown>|{ attributes?: Record<string, unknown> }} blockOrAttrs Block or attrs.
 * @return {Record<string, unknown>}
 */
function getBlockAttributes( blockOrAttrs ) {
	if ( blockOrAttrs && typeof blockOrAttrs === 'object' && blockOrAttrs.attributes ) {
		return blockOrAttrs.attributes;
	}
	return blockOrAttrs && typeof blockOrAttrs === 'object' ? blockOrAttrs : {};
}

const listViewLabels = {
	'hlm/article-mistake': ( blockOrAttrs ) => {
		const attributes = getBlockAttributes( blockOrAttrs );
		if ( attributes.title ) {
			return attributes.title;
		}
		return attributes.number ? `Mistake ${ attributes.number }` : undefined;
	},
	'hlm/article-checklist': ( blockOrAttrs ) => getBlockAttributes( blockOrAttrs ).headline || undefined,
	'hlm/article-callout': ( blockOrAttrs ) => getBlockAttributes( blockOrAttrs ).headline || undefined,
	'hlm/article-compare': ( blockOrAttrs ) => getBlockAttributes( blockOrAttrs ).headline || undefined,
};

blocks.forEach( ( entry ) => {
	const [ nameOrMetadata, edit, save ] = entry;
	const name = typeof nameOrMetadata === 'string' ? nameOrMetadata : nameOrMetadata.name;
	const metadata = typeof nameOrMetadata === 'string' ? { name } : nameOrMetadata;

	registerBlockType( metadata, {
		edit,
		save: save || emptySave,
		...( listViewLabels[ name ] ? { __experimentalLabel: listViewLabels[ name ] } : {} ),
	} );
} );
