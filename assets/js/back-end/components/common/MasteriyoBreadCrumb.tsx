import {
	Breadcrumb,
	BreadcrumbItem,
	BreadcrumbLink,
	Icon,
} from '@chakra-ui/react';
import React from 'react';
import { BiChevronRight } from 'react-icons/bi';
import { Link as RouterLink } from 'react-router-dom';
import routes from '../../constants/routes';

interface Props {
	isCurrentTitle: string;
	courseTitle: string;
	courseId: string;
}

const MasteriyoBreadCrumb: React.FC<Props> = (props) => {
	const { isCurrentTitle, courseTitle, courseId } = props;
	return (
		<Breadcrumb
			fontWeight="medium"
			fontSize="sm"
			mb="8"
			separator={<Icon as={BiChevronRight} color="gray.500" />}>
			<BreadcrumbItem>
				<BreadcrumbLink
					color="gray.500"
					as={RouterLink}
					to={routes.courses.edit.replace(':courseId', courseId)}>
					{courseTitle}
				</BreadcrumbLink>
			</BreadcrumbItem>
			<BreadcrumbItem isCurrentPage>
				<BreadcrumbLink color="blue.600">{isCurrentTitle}</BreadcrumbLink>
			</BreadcrumbItem>
		</Breadcrumb>
	);
};

export default MasteriyoBreadCrumb;
