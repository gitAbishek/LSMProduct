import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Divider,
	Icon,
	Link,
	List,
	ListItem,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiChevronLeft } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import {
	Link as RouterLink,
	NavLink,
	useHistory,
	useParams,
} from 'react-router-dom';
import Header from '../../../../components/common/Header';
import { navActiveStyles, navLinkStyles } from '../../../../config/styles';
import routes from '../../../../constants/routes';
import urls from '../../../../constants/urls';
import { CourseReviewSchema } from '../../../../schemas';
import API from '../../../../utils/api';
import { deepClean } from '../../../../utils/utils';
import Content from '../Content';
import Courses from '../Courses';
import Rating from '../Rating';
import Status from '../Status';
import Title from '../Title';

interface Props {
	editMode: boolean;
	reviewQueryData?: CourseReviewSchema;
}

const ReviewForm: React.FC<Props> = (props) => {
	const { editMode, reviewQueryData } = props;
	const { reviewId }: any = useParams();
	const history = useHistory();
	const queryClient = useQueryClient();
	const toast = useToast();

	const methods = useForm<CourseReviewSchema>({
		defaultValues: {
			title: reviewQueryData?.title || '',
			content: reviewQueryData?.description || '',
			rating: reviewQueryData?.rating || 0,
			course_id: reviewQueryData?.course?.id,
			status: reviewQueryData?.status || 'approve',
		},
	});

	const { handleSubmit } = methods;
	const reviewAPI = new API(urls.reviews);

	const addReviewMutation = useMutation((data: CourseReviewSchema) =>
		reviewAPI.store(data)
	);

	const updateReviewMutation = useMutation(
		(data: CourseReviewSchema) => reviewAPI.update(reviewId, data),
		{
			onSuccess: () => {
				toast({
					title: __('Review updated', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
				queryClient.invalidateQueries(`review${reviewId}`);
			},
			onError: (error: any) => {
				toast({
					description: `${error?.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const onSubmit = (data: CourseReviewSchema) => {
		if (editMode) {
			updateReviewMutation.mutate(deepClean(data));
		} else {
			addReviewMutation.mutate(deepClean(data), {
				onSuccess: (data: CourseReviewSchema) => {
					history.push({
						pathname: routes.reviews.edit.replace(
							':reviewId',
							data.id.toString()
						),
					});
				},
			});
		}
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header showLinks>
				<List>
					<ListItem>
						<Link
							as={NavLink}
							sx={navLinkStyles}
							_activeLink={navActiveStyles}
							to={routes.reviews.list}>
							{__(`${editMode ? 'Update' : 'Create'} Review`, 'masteriyo')}
						</Link>
					</ListItem>
				</List>
			</Header>
			<Container maxW="container.xl" marginTop="6">
				<Stack direction="column" spacing="6">
					<ButtonGroup>
						<RouterLink to={routes.reviews.list}>
							<Button
								variant="link"
								_hover={{ color: 'blue.500' }}
								leftIcon={<Icon fontSize="xl" as={BiChevronLeft} />}>
								{__('Back to Reviews', 'masteriyo')}
							</Button>
						</RouterLink>
					</ButtonGroup>
					<Stack direction="column" spacing="8">
						<FormProvider {...methods}>
							<form onSubmit={handleSubmit(onSubmit)}>
								<Stack direction="row" spacing="8">
									<Box
										flex="1"
										bg="white"
										p="10"
										shadow="box"
										d="flex"
										flexDirection="column"
										justifyContent="space-between">
										<Stack direction="column" spacing="6">
											<Title />
											<Content />
											<Stack direction="row" spacing="12">
												<Courses
													defaultValue={
														editMode && reviewQueryData
															? {
																	label: reviewQueryData.course.name,
																	value: reviewQueryData.course.id,
															  }
															: null
													}
												/>
												<Status />
											</Stack>
											<Rating />
											<Box py="2">
												<Divider />
											</Box>
											<ButtonGroup>
												<Button
													colorScheme="blue"
													type="submit"
													isLoading={
														addReviewMutation.isLoading ||
														updateReviewMutation.isLoading
													}>
													{__(
														`${editMode ? 'Update' : 'Create'} Review`,
														'masteriyo'
													)}
												</Button>
												<Button
													variant="outline"
													onClick={() => history.push(routes.reviews.list)}>
													{__('Cancel', 'masteriyo')}
												</Button>
											</ButtonGroup>
										</Stack>
									</Box>
								</Stack>
							</form>
						</FormProvider>
					</Stack>
				</Stack>
			</Container>
		</Stack>
	);
};

export default ReviewForm;
