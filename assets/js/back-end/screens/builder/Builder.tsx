import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Box,
	Button,
	ButtonGroup,
	Container,
	Flex,
	Icon,
	Image,
	Link,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	useDisclosure,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import queryString from 'query-string';
import React, { useRef, useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiBook, BiCog, BiEdit } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import {
	Link as RouterLink,
	useHistory,
	useLocation,
	useParams,
} from 'react-router-dom';
import AddCategoryModal from '../../components/common/AddCategoryModal';
import PageNav from '../../components/common/PageNav';
import FullScreenLoader from '../../components/layout/FullScreenLoader';
import { Logo } from '../../constants/images';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { CourseDataMap } from '../../types/course';
import API from '../../utils/api';
import { deepClean, deepMerge } from '../../utils/utils';
import EditCourse from '../courses/EditCourse';
import SectionBuilder from '../sections/SectionBuilder';
import CourseSetting from './component/CourseSetting';

const Builder: React.FC = () => {
	const { courseId }: any = useParams();
	const { search } = useLocation();
	const { onClose, onOpen, isOpen } = useDisclosure();
	const queryClient = useQueryClient();
	const toast = useToast();
	const methods = useForm();
	const history = useHistory();
	const cancelRef = useRef<any>();

	const courseAPI = new API(urls.courses);
	const builderAPI = new API(urls.builder);
	const sectionAPI = new API(urls.sections);

	const [builderData, setBuilderData] = useState<any>(null);
	const [deleteSectionId, setDeleteSectionId] = useState<number>();
	const { type, page } = queryString.parse(search);
	const [tabIndex, setTabIndex] = useState<number>(
		page === 'builder' ? 1 : page === 'settings' ? 2 : 0
	);
	const [currentPageName, setCurrentPageName] = useState<string>(
		page === 'builder' ? 'Builder' : page === 'settings' ? 'Settings' : 'Edit'
	);

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
		};

		updateCourse.mutate(deepClean(deepMerge(data, newData)));
	};

	const deleteMutation = useMutation((id: number) => sectionAPI.delete(id), {
		onSuccess: (data: any) => {
			onClose();
			toast({
				title: __('Section Deleted', 'masteriyo'),
				description:
					data?.name + __(' has been deleted successfully', 'masteriyo'),
				isClosable: true,
				status: 'error',
			});
			queryClient.invalidateQueries(`builder${courseId}`);
		},
		onError: (error: any) => {
			onClose();
			toast({
				title: __('Failed to delete section', 'masteriyo'),
				description: `${error.response?.data?.message}`,
				isClosable: true,
				status: 'error',
			});
		},
	});

	const onDeletePress = (sectionId: number) => {
		onOpen();
		setDeleteSectionId(sectionId);
	};

	const onDeleteConfirm = () => {
		deleteSectionId && deleteMutation.mutate(deleteSectionId);
	};

	if (courseQuery.isSuccess && builderQuery.isSuccess) {
		return (
			<>
				<FormProvider {...methods}>
					<Tabs index={tabIndex} onChange={(index) => setTabIndex(index)}>
						<Stack direction="column" spacing="8" align="center">
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
												<Tab
													sx={tabStyles}
													onClick={() => {
														setCurrentPageName(__('Edit', 'masteriyo'));
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
														setCurrentPageName(__('Builder', 'masteriyo'));
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
														setCurrentPageName(__('Settings', 'masteriyo'));
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
								<Stack direction="column" spacing="2">
									<PageNav
										courseName={courseQuery.data.name}
										currentTitle={currentPageName}
									/>
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
												onDeletePress={onDeletePress}
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
				<AlertDialog
					isOpen={isOpen}
					onClose={onClose}
					isCentered
					leastDestructiveRef={cancelRef}>
					<AlertDialogOverlay>
						<AlertDialogContent>
							<AlertDialogHeader>
								{__('Delete Section')} {name}
							</AlertDialogHeader>
							<AlertDialogBody>
								{__(
									"Are you sure? You can't restore this section",
									'masteriyo'
								)}
							</AlertDialogBody>
							<AlertDialogFooter>
								<ButtonGroup>
									<Button ref={cancelRef} onClick={onClose} variant="outline">
										{__('Cancel', 'masteriyo')}
									</Button>
									<Button
										colorScheme="red"
										onClick={onDeleteConfirm}
										isLoading={deleteMutation.isLoading}>
										{__('Delete', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</AlertDialogFooter>
						</AlertDialogContent>
					</AlertDialogOverlay>
				</AlertDialog>
			</>
		);
	}
	return <FullScreenLoader />;
};

export default Builder;
