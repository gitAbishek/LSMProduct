import {
	Breadcrumb,
	BreadcrumbItem,
	BreadcrumbLink,
	Icon,
} from '@chakra-ui/react';
import React from 'react';
import { BiChevronRight } from 'react-icons/bi';
import { useQuery } from 'react-query';
import { Link as RouterLink } from 'react-router-dom';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { CourseSchema } from '../../schemas';
import API from '../../utils/api';

interface Props {
	isCurrentTitle: string;
	courseId: number;
}

const PageNav: React.FC<Props> = (props) => {
	const { isCurrentTitle, courseId } = props;
	const courseAPI = new API(urls.courses);

	const courseQuery = useQuery<CourseSchema>(`pageNav${courseId}`, () =>
		courseAPI.get(courseId)
	);

	if (courseQuery.isSuccess) {
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
						to={routes.courses.edit.replace(':courseId', courseId.toString())}>
						{courseQuery?.data?.name}
					</BreadcrumbLink>
				</BreadcrumbItem>
				<BreadcrumbItem isCurrentPage>
					<BreadcrumbLink color="blue.600">{isCurrentTitle}</BreadcrumbLink>
				</BreadcrumbItem>
			</Breadcrumb>
		);
	}

	return <></>;
};

export default PageNav;
