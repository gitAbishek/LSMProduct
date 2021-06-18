import { Button, ButtonGroup } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Link as RouterLink } from 'react-router-dom';

import routes from '../../../constants/routes';
import Header from '../../../components/layout/Header';

interface Props {
	isEmpty?: boolean;
}
const TaxBuilderHeader: React.FC<Props> = (props) => {
	const { isEmpty } = props;
	return (
		<Header hideAddNewCourseBtn>
			{!isEmpty && (
				<ButtonGroup>
					<RouterLink to={routes.course_difficulties.add}>
						<Button colorScheme="blue">
							{__('Add New Difficulty', 'masteriyo')}
						</Button>
					</RouterLink>
				</ButtonGroup>
			)}
		</Header>
	);
};

export default TaxBuilderHeader;
