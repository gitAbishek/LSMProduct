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
import FullScreenLoader from 'Components/layout/FullScreenLoader';
import queryString from 'query-string';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiBook, BiCog, BiEdit } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import {
	Link as RouterLink,
	useHistory,
	useLocation,
	useParams,
} from 'react-router-dom';
import { Logo } from '../../constants/images';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { CourseDataMap } from '../../types/course';
import API from '../../utils/api';
import { mergeDeep } from '../../utils/mergeDeep';
import EditCourse from '../courses/EditCourse';
import SectionBuilder from '../sections/SectionBuilder';
import CourseSetting from './component/CourseSetting';

const Builder: React.FC = () => {
	const { courseId }: any = useParams();
	const { search } = useLocation();
	const history = useHistory();
	const queryClient = useQueryClient();
	const toast = useToast();
	const methods = useForm();

	const courseAPI = new API(urls.courses);
	const builderAPI = new API(urls.builder);

	const [builderData, setBuilderData] = useState<any>(null);

	// what type of post it is
	const { type } = queryString.parse(search);

	const tabStyles = {
		fontWeight: 'medium',
		fontSize: 'sm',
		py: '6',
		px: 0,
		mx: 4,
	};

	const tabPanelStyles = {
		px: '0',
	};

	const iconStyles = {
		mr: '2',
	};
	const courseQuery = useQuery<CourseDataMap>(
		[`courses${courseId}`, courseId],
		() => courseAPI.get(courseId),
		{
			onError: () => {
				history.push(routes.notFound);
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
				queryClient.invalidateQueries(`courses${data.id}`);
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

		updateCourse.mutate(mergeDeep(data, newData));
	};

	if (courseQuery.isLoading) {
		return <FullScreenLoader />;
	}

	return (
		<FormProvider {...methods}>
			<Tabs>
				<Stack direction="column" spacing="10" align="center">
					<Box bg="white" w="full">
						<Container maxW="container.xl">
							<Flex
								direction="row"
								justifyContent="space-between"
								align="center">
								<Stack direction="row" spacing="12" align="center">
									<Box>
										<RouterLink to={routes.courses.list}>
											<Image src={Logo} alt="Masteriyo Logo" w="120px" />
										</RouterLink>
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
									<Link href={courseQuery.data?.preview_permalink} isExternal>
										<Button variant="outline">Preview</Button>
									</Link>

									{type && type == 'draft' && (
										<Button
											variant="outline"
											onClick={() => {
												methods.handleSubmit((data) => onSave(data, 'draft'))();
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
								{__('Edit Course: ', 'masteriyo')} {courseQuery.data?.name}
							</Heading>
							<TabPanels>
								<TabPanel sx={tabPanelStyles}>
									<EditCourse courseData={courseQuery.data} />
								</TabPanel>
								<TabPanel sx={tabPanelStyles}>
									<SectionBuilder
										courseId={courseQuery.data?.id}
										builderData={builderData}
										setBuilderData={setBuilderData}
									/>
								</TabPanel>
								<TabPanel sx={tabPanelStyles}>
									<CourseSetting courseData={courseQuery?.data} />
								</TabPanel>
							</TabPanels>
						</Stack>
					</Container>
				</Stack>
			</Tabs>
		</FormProvider>
	);
};

export default Builder;
