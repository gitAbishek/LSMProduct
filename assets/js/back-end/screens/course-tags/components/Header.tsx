import { Button, ButtonGroup } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Link as RouterLink } from 'react-router-dom';
import Header from '../../../components/layout/Header';
import routes from '../../../constants/routes';

interface Props {
	isEmpty?: boolean;
}
const TaxBuilderHeader: React.FC<Props> = (props) => {
	const { isEmpty } = props;
	return (
		<Header hideAddNewCourseBtn hideCoursesMenu>
			{!isEmpty && (
				<ButtonGroup>
					<RouterLink to={routes.course_tags.add}>
						<Button colorScheme="blue">{__('Add New Tag', 'masteriyo')}</Button>
					</RouterLink>
				</ButtonGroup>
			)}
		</Header>
	);
};

export default TaxBuilderHeader;
