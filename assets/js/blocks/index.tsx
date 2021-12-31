import { select, subscribe } from '@wordpress/data';
import { register_course_categories_block } from './course-categories/block';
import { register_courses_block } from './courses/block';
import { updateBlocksCategoryIcon } from './helpers/updateBlocksCategoryIcon';
import { frontedCSS } from './utils/frontedCss';

updateBlocksCategoryIcon();

register_courses_block();
register_course_categories_block();

subscribe(() => {
	if (select('core/editor')) {
		const { isSavingPost, isAutosavingPost } = select('core/editor');

		if (isSavingPost() && !isAutosavingPost()) {
			frontedCSS();
		}
	}
});
