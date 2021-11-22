import { register_course_categories_block } from './course-categories/block';
import { register_courses_block } from './courses/block';
import { updateBlocksCategoryIcon } from './helpers/updateBlocksCategoryIcon';
import { frontedCSS } from './utils/frontedCss';

updateBlocksCategoryIcon();

register_courses_block();
register_course_categories_block();

wp.data.subscribe(() => {
	const { isSavingPost, isAutosavingPost } = wp.data.select('core/editor');

	if (isSavingPost() && !isAutosavingPost()) {
		frontedCSS();
	}
});
