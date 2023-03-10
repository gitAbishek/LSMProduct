import {
	Container,
	Icon,
	Skeleton,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import queryString from 'query-string';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiBook, BiCog, BiEdit } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useLocation, useParams } from 'react-router-dom';
import AddCategoryModal from '../../components/common/AddCategoryModal';
import Header from '../../components/common/Header';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import CourseSkeleton from '../../skeleton/CourseSkeleton';
import { CourseDataMap } from '../../types/course';
import API from '../../utils/api';
import { deepClean, deepMerge } from '../../utils/utils';
import EditCourse from '../courses/EditCourse';
import SectionBuilder from '../sections/SectionBuilder';
import CourseSetting from './component/CourseSetting';

const Builder: React.FC = () => {
	const { courseId }: any = useParams();
	const { search } = useLocation();
	const queryClient = useQueryClient();
	const toast = useToast();
	const methods = useForm();
	const history = useHistory();

	const courseAPI = new API(urls.courses);
	const builderAPI = new API(urls.builder);

	const [builderData, setBuilderData] = useState<any>(null);
	const { page } = queryString.parse(search);
	const [tabIndex, setTabIndex] = useState<number>(
		page === 'builder' ? 1 : page === 'settings' ? 2 : 0
	);

	const tabStyles = {
		fontWeight: 'medium',
		fontSize: 'sm',
		py: '6',
		px: 0,
		mx: 4,
		_hover: {
			color: 'primary.500',
			borderBottom: [0, 0, '2px solid'],
		},
	};

	const tabPanelStyles = {
		px: '0',
	};

	const iconStyles = {
		mr: '2',
	};

	const courseQuery = useQuery<CourseDataMap>(
		[`course${courseId}`, courseId],
		() => courseAPI.get(courseId)
	);
	const builderQuery = useQuery(
		[`builder${courseId}`, courseId],
		() => builderAPI.get(courseId),
		{
			onSuccess: (data) => {
				setBuilderData(data);
			},
			enabled: tabIndex === 1,
		}
	);

	const updateCourse = useMutation(
		(data: CourseDataMap) => courseAPI.update(courseId, data),
		{
			onSuccess: (data: CourseDataMap) => {
				toast({
					title: __('Course Updated', 'masteriyo'),
					status: 'success',
					isClosable: true,
				});
				queryClient.invalidateQueries(`course${data.id}`);
			},
		}
	);

	const draftCourse = useMutation(
		(data: CourseDataMap) => courseAPI.update(courseId, data),
		{
			onSuccess: (data: CourseDataMap) => {
				toast({
					title: data.name + __(' drafted', 'masteriyo'),
					status: 'success',
					isClosable: true,
				});
				queryClient.invalidateQueries(`course${data.id}`);
			},
		}
	);

	const updateBuilder = useMutation(
		(data: any) => builderAPI.update(courseId, data),
		{
			onSuccess: () => {
				queryClient.invalidateQueries(`builder${courseId}`);
			},
		}
	);

	const onSave = (data: any, type: string) => {
		updateBuilder.mutate(deepClean(builderData));

		const newData: any = {
			...(data.categories && {
				categories: data.categories.map((category: any) => ({
					id: category.value,
				})),
				status: type,
			}),
			duration: (data?.duration_hour || 0) * 60 + +(data?.duration_minute || 0),
			duration_hour: null,
			duration_minute: null,
			regular_price: `${data.regular_price}`,
			featured_image: data.featuredImage,
		};

		if (type === 'draft') {
			draftCourse.mutate(deepClean(deepMerge(data, newData)));
		} else {
			updateCourse.mutate(deepClean(deepMerge(data, newData)));
		}
	};

	const isPublished = () => {
		if (courseQuery.data?.status === 'publish') {
			return true;
		} else {
			return false;
		}
	};

	const isDrafted = () => {
		if (courseQuery.data?.status === 'draft') {
			return true;
		} else {
			return false;
		}
	};

	return (
		<>
			<FormProvider {...methods}>
				<Tabs index={tabIndex} onChange={(index) => setTabIndex(index)}>
					<Stack direction="column" spacing="8" align="center">
						<Header
							showCourseName
							showPreview
							course={
								courseQuery.isSuccess
									? {
											name: courseQuery.data.name,
											id: courseQuery.data.id,
											previewUrl: courseQuery.data.permalink,
									  }
									: {
											name: <Skeleton width="100px" height="25px" />,
											id: 0,
											previewUrl: '',
									  }
							}
							secondBtn={{
								label: isDrafted()
									? __('Save To Draft', 'masteriyo')
									: __('Switch To Draft', 'masteriyo'),
								action: methods.handleSubmit((data) => onSave(data, 'draft')),
								isLoading: draftCourse.isLoading,
							}}
							thirdBtn={{
								label: isPublished()
									? __('Update', 'masteriyo')
									: __('Publish', 'masteriyo'),
								action: methods.handleSubmit((data) => onSave(data, 'publish')),
								isLoading: updateCourse.isLoading,
							}}>
							<TabList
								borderBottom="none"
								bg="white"
								ml={[
									'-13px !important',
									'-13px !important',
									'32px !important',
									'32px !important',
								]}>
								<Tab
									sx={tabStyles}
									onClick={() => {
										history.push(
											routes.courses.edit.replace(':courseId', courseId)
										);
									}}>
									<Icon as={BiBook} sx={iconStyles} />
									{__('Course', 'masteriyo')}
								</Tab>
								<Tab
									sx={tabStyles}
									onClick={() => {
										history.push({
											pathname: routes.courses.edit.replace(
												':courseId',
												courseId
											),
											search: '?page=builder',
										});
									}}>
									<Icon as={BiEdit} sx={iconStyles} />
									{__('Builder', 'masteriyo')}
								</Tab>
								<Tab
									sx={tabStyles}
									onClick={() => {
										history.push({
											pathname: routes.courses.edit.replace(
												':courseId',
												courseId
											),
											search: '?page=settings',
										});
									}}>
									<Icon as={BiCog} sx={iconStyles} />
									{__('Settings', 'masteriyo')}
								</Tab>
							</TabList>
						</Header>
						<Container maxW="container.xl">
							<Stack direction="column" spacing="2">
								<TabPanels>
									<TabPanel sx={tabPanelStyles}>
										{courseQuery.isSuccess ? (
											<EditCourse
												courseData={courseQuery.data}
												tabIndex={tabIndex === 0}
											/>
										) : (
											<CourseSkeleton page={tabIndex} />
										)}
									</TabPanel>
									<TabPanel sx={tabPanelStyles}>
										{courseQuery.isSuccess && builderQuery.isSuccess ? (
											<SectionBuilder
												courseId={courseQuery.data.id}
												builderData={
													(builderData && builderData) || builderQuery.data
												}
												setBuilderData={setBuilderData}
											/>
										) : (
											<CourseSkeleton page={tabIndex} />
										)}
									</TabPanel>
									<TabPanel sx={tabPanelStyles}>
										{courseQuery.isSuccess ? (
											<CourseSetting courseData={courseQuery.data} />
										) : (
											<CourseSkeleton page={tabIndex} />
										)}
									</TabPanel>
								</TabPanels>
							</Stack>
						</Container>
					</Stack>
				</Tabs>
			</FormProvider>
			<AddCategoryModal />
		</>
	);
};

export default Builder;
