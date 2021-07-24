import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Flex,
	Heading,
	Icon,
	Image,
	Link,
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
import { Link as RouterLink, useLocation, useParams } from 'react-router-dom';
import AddCategoryModal from '../../components/common/AddCategoryModal';
import FullScreenLoader from '../../components/layout/FullScreenLoader';
import { Logo } from '../../constants/images';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { CourseDataMap } from '../../types/course';
import API from '../../utils/api';
import { deepMerge } from '../../utils/utils';
import EditCourse from '../courses/EditCourse';
import SectionBuilder from '../sections/SectionBuilder';
import CourseSetting from './component/CourseSetting';

const Builder: React.FC = () => {
	const { courseId }: any = useParams();
	const { search } = useLocation();
	const queryClient = useQueryClient();
	const toast = useToast();
	const methods = useForm();

	const courseAPI = new API(urls.courses);
	const builderAPI = new API(urls.builder);

	const [builderData, setBuilderData] = useState<any>(null);
	const { type, page } = queryString.parse(search);
	const [tabIndex, setTabIndex] = useState<number>(page === 'builder' ? 1 : 0);

	const tabStyles = {
		fontWeight: 'medium',
		fontSize: 'sm',
		py: '6',
		px: 0,
		mx: 4,
		_hover: {
			color: 'blue.500',
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
		}
	);

	const updateCourse = useMutation(
		(data: CourseDataMap) => courseAPI.update(courseId, data),
		{
			onSuccess: (data: CourseDataMap) => {
				toast({
					title: data.name + __(' is updated successfully.', 'masteriyo'),
					description: __('You can keep editing it', 'masteriyo'),
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
		updateBuilder.mutate(builderData);

		const newData: any = {
			...(data.categories && {
				categories: data.categories.map((category: any) => ({
					id: category.value,
				})),
				status: type,
			}),
			regular_price: `${data.regular_price}`,
		};

		updateCourse.mutate(deepMerge(data, newData));
	};

	if (courseQuery.isSuccess && builderQuery.isSuccess) {
		return (
			<>
				<FormProvider {...methods}>
					<Tabs index={tabIndex} onChange={(index) => setTabIndex(index)}>
						<Stack direction="column" spacing="10" align="center">
							<Box bg="white" w="full">
								<Container maxW="container.xl">
									<Flex
										direction="row"
										justifyContent="space-between"
										align="center">
										<Stack direction="row" spacing="12" align="center">
											<Box>
												<Link as={RouterLink} to={routes.courses.list}>
													<Image src={Logo} alt="Masteriyo Logo" w="120px" />
												</Link>
											</Box>
											<TabList borderBottom="none" bg="white">
												<Tab sx={tabStyles}>
													<Icon as={BiBook} sx={iconStyles} />
													{__('Course', 'masteriyo')}
												</Tab>
												<Tab sx={tabStyles}>
													<Icon as={BiEdit} sx={iconStyles} />
													{__('Builder', 'masteriyo')}
												</Tab>
												<Tab sx={tabStyles}>
													<Icon as={BiCog} sx={iconStyles} />
													{__('Settings', 'masteriyo')}
												</Tab>
											</TabList>
										</Stack>
										<ButtonGroup>
											<Link
												href={courseQuery.data?.preview_permalink}
												isExternal>
												<Button variant="outline">Preview</Button>
											</Link>

											{type && type == 'draft' && (
												<Button
													variant="outline"
													onClick={() => {
														methods.handleSubmit((data) =>
															onSave(data, 'draft')
														)();
													}}
													isLoading={
														updateCourse.isLoading || updateBuilder.isLoading
													}>
													{__('Update', 'masteriyo')}
												</Button>
											)}
											<Button
												colorScheme="blue"
												onClick={methods.handleSubmit((data) =>
													onSave(data, 'publish')
												)}
												isLoading={
													updateCourse.isLoading || updateBuilder.isLoading
												}>
												{type && type == 'draft'
													? __('Publish', 'masteriyo')
													: __('Save', 'masteriyo')}
											</Button>
										</ButtonGroup>
									</Flex>
								</Container>
							</Box>
							<Container maxW="container.xl">
								<Stack direction="column" spacing="6">
									<Heading as="h1" fontSize="x-large">
										{__('Edit Course: ', 'masteriyo')} {courseQuery.data.name}
									</Heading>
									<TabPanels>
										<TabPanel sx={tabPanelStyles}>
											<EditCourse courseData={courseQuery.data} />
										</TabPanel>
										<TabPanel sx={tabPanelStyles}>
											<SectionBuilder
												courseId={courseQuery.data.id}
												builderData={
													(builderData && builderData) || builderQuery.data
												}
												setBuilderData={setBuilderData}
											/>
										</TabPanel>
										<TabPanel sx={tabPanelStyles}>
											<CourseSetting courseData={courseQuery.data} />
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
	}
	return <FullScreenLoader />;
};

export default Builder;
