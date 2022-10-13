import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Heading,
	Icon,
	Link,
	List,
	ListIcon,
	ListItem,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiChevronLeft } from 'react-icons/bi';
import { GiStairs } from 'react-icons/gi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router';
import { Link as RouterLink, NavLink } from 'react-router-dom';
import Header from '../../components/common/Header';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { CourseDifficultySchema } from '../../schemas';
import EditDifficultySkeleton from '../../skeleton/DifficultySkeleton';
import API from '../../utils/api';
import { makeSlug } from '../../utils/categories';
import { deepClean } from '../../utils/utils';
import DescriptionInput from './components/DescriptionInput';
import NameInput from './components/NameInput';
import SlugInput from './components/SlugInput';

interface EditFormData {
	name: string;
	slug: string;
	description: string;
}

const EditCourseDifficulty: React.FC = () => {
	const { difficultyId }: any = useParams();
	const history = useHistory();
	const methods = useForm();
	const toast = useToast();
	const difficultyAPI = new API(urls.difficulties);
	const queryClient = useQueryClient();

	const difficultyQuery = useQuery<CourseDifficultySchema>(
		[`courseDifficulty${difficultyId}`, difficultyId],
		() => difficultyAPI.get(difficultyId)
	);

	const updateDifficultyMutation = useMutation(
		(data: object) => difficultyAPI.update(difficultyId, data),
		{
			onSuccess: () => {
				toast({
					title: __('Course Difficulty Updated', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
				difficultyQuery.refetch();
				queryClient.invalidateQueries('courseDifficultiesList');
				history.push(routes.course_difficulties.list);
			},
			onError: (error: any) => {
				toast({
					title: __('Failed to update course difficulty.', 'masteriyo'),
					description: `${error.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const onSubmit = (data: EditFormData) => {
		updateDifficultyMutation.mutate(
			deepClean({
				...data,
				slug: data.slug ? data.slug : makeSlug(data.name),
			})
		);
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
							to={routes.course_difficulties.list}>
							<ListIcon as={GiStairs} />
							{__('Course Difficulty', 'masteriyo')}
						</Link>
					</ListItem>
				</List>
			</Header>
			<Container maxW="container.xl">
				<Stack direction="column" spacing="6">
					<ButtonGroup>
						<RouterLink to={routes.course_difficulties.list}>
							<Button
								variant="link"
								_hover={{ color: 'primary.500' }}
								leftIcon={<Icon fontSize="xl" as={BiChevronLeft} />}>
								{__('Back to Course Difficulties', 'masteriyo')}
							</Button>
						</RouterLink>
					</ButtonGroup>
					<FormProvider {...methods}>
						{difficultyQuery.isLoading ? (
							<EditDifficultySkeleton />
						) : (
							<form onSubmit={methods.handleSubmit(onSubmit)}>
								<Stack direction={['column', null, 'row']} spacing="8">
									<Box
										flex="1"
										bg="white"
										p={['4', null, '10']}
										shadow="box"
										d="flex"
										flexDirection="column"
										justifyContent="space-between">
										<Stack direction="column" spacing="8">
											<Heading as="h1" fontSize="x-large">
												{__('Edit Course Difficulty', 'masteriyo')}
											</Heading>

											<Stack direction="column" spacing="6">
												<NameInput defaultValue={difficultyQuery.data?.name} />

												<DescriptionInput
													defaultValue={difficultyQuery.data?.description}
												/>

												<ButtonGroup>
													<Button
														colorScheme="primary"
														type="submit"
														isLoading={updateDifficultyMutation.isLoading}>
														{__('Update', 'masteriyo')}
													</Button>
													<Button
														variant="outline"
														onClick={() =>
															history.push(routes.course_difficulties.list)
														}>
														{__('Cancel', 'masteriyo')}
													</Button>
												</ButtonGroup>
											</Stack>
										</Stack>
									</Box>
									<Box
										w={['100%', null, '400px']}
										bg="white"
										p={['4', null, '10']}
										shadow="box">
										<Stack direction="column" spacing="6">
											<SlugInput
												defaultValue={difficultyQuery?.data?.slug}
												defaultNameValue={difficultyQuery?.data?.name}
											/>
										</Stack>
									</Box>
								</Stack>
							</form>
						)}
					</FormProvider>
				</Stack>
			</Container>
		</Stack>
	);
};

export default EditCourseDifficulty;
