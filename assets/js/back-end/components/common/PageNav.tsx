import React from 'react';

interface Props {
	isCurrentTitle: string;
	courseId: number;
}

const PageNav: React.FC<Props> = () => {
	// const { isCurrentTitle, courseId } = props;
	// const courseAPI = new API(urls.courses);

	// const courseQuery = useQuery<CourseSchema>(`pageNav${courseId}`, () =>
	// 	courseAPI.get(courseId)
	// );

	// if (courseQuery.isSuccess) {
	// 	return (
	// 		<Breadcrumb
	// 			fontWeight="medium"
	// 			fontSize="sm"
	// 			mb="8"
	// 			separator={<Icon as={BiChevronRight} color="gray.500" />}>
	// 			<BreadcrumbItem>
	// 				<BreadcrumbLink
	// 					color="gray.500"
	// 					as={RouterLink}
	// 					to={routes.courses.edit.replace(':courseId', courseId.toString())}>
	// 					{courseQuery?.data?.name}
	// 				</BreadcrumbLink>
	// 			</BreadcrumbItem>
	// 			<BreadcrumbItem isCurrentPage>
	// 				<BreadcrumbLink color="blue.600">{isCurrentTitle}</BreadcrumbLink>
	// 			</BreadcrumbItem>
	// 		</Breadcrumb>
	// 	);
	// }

	return <></>;
};

export default PageNav;
