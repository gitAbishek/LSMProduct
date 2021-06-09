import { Button, ButtonGroup } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Link as RouterLink } from 'react-router-dom';

import routes from '../../../constants/routes';
import Header from '../../../components/layout/Header';

interface Props {
	isEmpty?: boolean;
}
const CategoriesBuilderHeader: React.FC<Props> = (props) => {
	const { isEmpty } = props;
	return (
		<Header hideAddNewCourseBtn>
			{!isEmpty && (
				<ButtonGroup>
					<RouterLink to={routes.course_categories.add}>
						<Button colorScheme="blue">
							{__('Add New Category', 'masteriyo')}
						</Button>
					</RouterLink>
				</ButtonGroup>
			)}
		</Header>
	);
};

export default CategoriesBuilderHeader;
