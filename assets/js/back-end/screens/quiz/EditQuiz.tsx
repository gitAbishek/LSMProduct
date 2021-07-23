import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Divider,
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
import queryString from 'query-string';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation, useQuery } from 'react-query';
import { useHistory, useLocation, useParams } from 'react-router-dom';
import PageNav from '../../components/common/PageNav';
import FullScreenLoader from '../../components/layout/FullScreenLoader';
import HeaderBuilder from '../../components/layout/HeaderBuilder';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { QuizSchema } from '../../schemas';
import API from '../../utils/api';
import Description from './components/Description';
import Name from './components/Name';
import Questions from './components/question/Questions';
import QuizSettings from './components/QuizSettings';

const EditQuiz: React.FC = () => {
	const { quizId, courseId }: any = useParams();
	const { search } = useLocation();
	const { page } = queryString.parse(search);

	const methods = useForm();
	const history = useHistory();
	const toast = useToast();
	const quizAPI = new API(urls.quizes);
	const [tabIndex, setTabIndex] = useState<number>(
		page === 'questions' ? 1 : 0
	);

	const tabStyles = {
		fontWeight: 'medium',
		py: '4',
	};

	const tabPanelStyles = {
		p: '0',
	};

	// gets total number of content on section
	const quizQuery = useQuery<QuizSchema>([`quiz${quizId}`, quizId], () =>
		quizAPI.get(quizId)
	);

	const updateQuiz = useMutation(
		(data: object) => quizAPI.update(quizId, data),
		{
			onSuccess: (data: QuizSchema) => {
				toast({
					title: __('Quiz Added', 'masteriyo'),
					description: data?.name + __(' is successfully added.', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
			},
		}
	);

	const onSubmit = (data: object) => {
		updateQuiz.mutate(data);
	};

	if (quizQuery.isSuccess) {
		return (
			<Stack direction="column" spacing="8" alignItems="center">
				<HeaderBuilder courseId={courseId} />
				<Container maxW="container.xl">
					<PageNav isCurrentTitle={quizQuery?.data?.name} courseId={courseId} />
					<FormProvider {...methods}>
						<Box bg="white" p="10" shadow="box">
							<Stack direction="column" spacing="8">
								<Flex aling="center" justify="space-between">
									<Heading as="h1" fontSize="x-large">
										{__('Edit Quiz:', 'masteriyo')} {quizQuery?.data?.name}
									</Heading>
								</Flex>

								<Tabs index={tabIndex} onChange={(index) => setTabIndex(index)}>
									<TabList justifyContent="center" borderBottom="1px">
										<Tab sx={tabStyles}>{__('Info', 'masteriyo')}</Tab>
										<Tab sx={tabStyles}>{__('Questions', 'masteriyo')}</Tab>
										<Tab sx={tabStyles}>{__('Settings', 'masteriyo')}</Tab>
									</TabList>
									<TabPanels>
										<TabPanel sx={tabPanelStyles}></TabPanel>
										<TabPanel sx={tabPanelStyles}>
											<Questions
												courseId={quizQuery?.data?.course_id}
												quizId={quizId}
											/>
										</TabPanel>
										<TabPanel sx={tabPanelStyles}></TabPanel>
									</TabPanels>
								</Tabs>
								<form onSubmit={methods.handleSubmit(onSubmit)}>
									<Stack direction="column" spacing="6">
										{tabIndex === 0 && (
											<>
												<Name defaultValue={quizQuery?.data?.name} />
												<Description
													defaultValue={quizQuery?.data?.description}
												/>
											</>
										)}

										{tabIndex === 2 && (
											<QuizSettings quizData={quizQuery?.data} />
										)}

										{tabIndex !== 1 && (
											<>
												<Box py="3">
													<Divider />
												</Box>
												<ButtonGroup>
													<Button
														colorScheme="blue"
														type="submit"
														isLoading={updateQuiz.isLoading}>
														{__('Update', 'masteriyo')}
													</Button>
													<Button
														variant="outline"
														onClick={() =>
															history.push(
																routes.courses.edit.replace(
																	':courseId',
																	courseId
																)
															)
														}>
														{__('Cancel', 'masteriyo')}
													</Button>
												</ButtonGroup>
											</>
										)}
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

export default EditQuiz;
