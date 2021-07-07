import {
	Alert,
	AlertDescription,
	AlertIcon,
	AlertTitle,
	Box,
	Button,
	ButtonGroup,
	Container,
	Flex,
	Heading,
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
import HeaderBuilder from 'Components/layout/HeaderBuilder';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation, useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { QuizSchema } from '../../schemas';
import API from '../../utils/api';
import { mergeDeep } from '../../utils/mergeDeep';
import Description from './components/Description';
import Name from './components/Name';
import QuizSettings from './components/QuizSettings';

const AddNewQuiz: React.FC = () => {
	const { sectionId, courseId }: any = useParams();
	const methods = useForm<QuizSchema>();
	const history = useHistory();
	const toast = useToast();
	const contentAPI = new API(urls.contents);
	const quizAPI = new API(urls.quizes);
	const tabStyles = {
		fontWeight: 'medium',
		py: '4',
	};

	// gets total number of content on section
	const contentQuery = useQuery([`content${sectionId}`, sectionId], () =>
		contentAPI.list({ section: sectionId })
	);

	const addQuiz = useMutation((data: QuizSchema) => quizAPI.store(data), {
		onSuccess: (data: QuizSchema) => {
			toast({
				title: __('Quiz Added', 'masteriyo'),
				description: data.name + __(' is successfully added.', 'masteriyo'),
				isClosable: true,
				status: 'success',
			});
			history.push({
				pathname: routes.quiz.edit
					.replace(':quizId', `${data.id}`)
					.replace(':courseId', `${data.course_id}`),
				search: '?page=questions',
			});
		},
	});

	const onSubmit = (data: QuizSchema) => {
		const newData = {
			course_id: courseId,
			parent_id: sectionId,
		};

		addQuiz.mutate(mergeDeep(data, newData));
	};

	if (contentQuery.isSuccess) {
		return (
			<Stack direction="column" spacing="8" alignItems="center">
				<HeaderBuilder courseId={courseId} />
				<Container maxW="container.xl">
					<FormProvider {...methods}>
						<Box bg="white" p="10" shadow="box">
							<Stack direction="column" spacing="8">
								<Flex aling="center" justify="space-between">
									<Heading as="h1" fontSize="x-large">
										{__('Add New Quiz', 'masteriyo')}
									</Heading>
								</Flex>

								<form onSubmit={methods.handleSubmit(onSubmit)}>
									<Stack direction="column" spacing="6">
										<Tabs>
											<TabList justifyContent="center" borderBottom="1px">
												<Tab sx={tabStyles}>{__('Info', 'masteriyo')}</Tab>
												<Tab sx={tabStyles}>{__('Questions', 'masteriyo')}</Tab>
												<Tab sx={tabStyles}>{__('Settings', 'masteriyo')}</Tab>
											</TabList>
											<TabPanels>
												<TabPanel px="0">
													<Stack direction="column" spacing="6">
														<Name />
														<Description />
													</Stack>
												</TabPanel>
												<TabPanel px="0">
													<Alert status="error">
														<AlertIcon />
														<AlertTitle mr={2}>
															{__('Add course first', 'masteriyo')}
														</AlertTitle>
														<AlertDescription>
															{__(
																'In order to add questions. You need to add quiz first',
																'masteriyo'
															)}
														</AlertDescription>
													</Alert>
												</TabPanel>
												<TabPanel px="0">
													<QuizSettings />
												</TabPanel>
											</TabPanels>
										</Tabs>

										<ButtonGroup>
											<Button
												colorScheme="blue"
												type="submit"
												isLoading={addQuiz.isLoading}>
												{__('Add New Quiz', 'masteriyo')}
											</Button>
											<Button
												variant="outline"
												onClick={() =>
													history.push(
														routes.courses.edit.replace(':courseId', courseId)
													)
												}>
												{__('Cancel', 'masteriyo')}
											</Button>
										</ButtonGroup>
									</Stack>
								</form>
							</Stack>
						</Box>
					</FormProvider>
				</Container>
			</Stack>
		);
	}

	return <FullScreenLoader />;
};

export default AddNewQuiz;
